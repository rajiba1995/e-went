<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\UserKycLog;
use App\Models\Order;
use App\Models\AsignedVehicle;
use App\Models\UserTermsConditions;
use App\Models\ExchangeVehicle;
use App\Models\CancelRequestHistory;
use App\Models\UserLocationLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\Paginator;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserRideSummaryExport;
use App\Exports\UserJourneyExport;
use App\Exports\CancelRequestExport;

class CustomerDetails extends Component
{
    use WithPagination;
    public $user;
    public $activeTab = 'account'; // Default active tab
    public $newPassword; // Correct naming convention
    public $confirmPassword;
    public $userId;
    public $showEditModal = false;
    public $ride_history = [];
    public $documents = [];
    public $data = [];
    public $customer_total_order = 0;
    public $expandedRows = [];
    public $total_payment_amount = 0;

    // Rules for updating the password
    protected $rules = [
        'newPassword' => 'required|min:6|regex:/[A-Z]/|regex:/[\W_]/', // Minimum 6 characters, at least one uppercase and one special character
        'confirmPassword' => 'required|same:newPassword',
    ];

    public $search = '';
    protected $paginationTheme = 'bootstrap';
    // public function boot()
    // {
    //     Paginator::useBootstrap();
    // }

    public function mount($id)
    {
        // Fetch the user by ID or fail
        $this->user = User::findOrFail($id);
        $this->userId = $id;
        $this->getKYC($this->user->email);
        $this->GetDocumentStatus();
        $this->customer_total_order = Order::where('user_id', $id)->count();
        // Get all completed payments with their items
        $payments = Order::with([
                'payments' => function ($query) {
                    $query->where('payment_status', 'completed')
                        ->with(['paymentItem' => function ($q) {
                            $q->where('type', 'rental'); // Only rental items
                        }]);
                }
            ])
            ->where('user_id', $id)
            ->get()
            ->pluck('payments')
            ->flatten();

        // Now extract and sum all rental items
        $this->total_payment_amount = $payments->flatMap(function ($payment) {
            return $payment->paymentItem;
        })->sum('amount');
    }
    public function getKYC($user_email){
         $data = UserTermsConditions::where('email',$user_email)->first();
        
        if (!$data || !$data->request_id) {
            return response()->json(['error' => 'Request ID not found.'], 404);
        }

        $requestId = $data->request_id;
        $url = 'https://live.zoop.one/contract/esign/v5/fetch/request?request_id=' . $requestId;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'app-id: ' . env('ZOOP_APP_ID'),
            'api-key: ' . env('ZOOP_APP_KEY'),
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);

        curl_close($ch);

        if ($curlError) {
            return response()->json(['error' => $curlError], 500);
        }

        $responseData = json_decode($response, true);
       if ($data->status === 'pending' && $data->signed_url === null) {
            if (isset($responseData['transaction_status']) && $responseData['transaction_status'] === 'SUCCESS') {
                $data->status = 'success';
                $data->request_timestamp = $responseData['request_timestamp'] ?? null;
                $data->response_timestamp = $responseData['response_timestamp'] ?? null;
                $data->signer_name = $responseData['signer_name'] ?? null;
                $data->signer_city = $responseData['signer_city'] ?? null;
                $data->signer_state = $responseData['signer_aadhaar_details']['state_or_province'] ?? null;
                $data->signer_postal_code = $responseData['signer_aadhaar_details']['postal_code'] ?? null;
                $data->signed_at = $responseData['document']['signedAt'] ?? null;
                $data->signed_url = $responseData['document']['signed_url'] ?? null;

                // Save the whole payload for auditing/debugging
                $data->response_payload = json_encode($responseData);

                $data->save();
            }
        }
        
    }

    public function searchButtonClicked()
    {
        $this->resetPage(); // Reset to the first page
    }

   
    public function resetSearch()
    {
        $this->reset('search'); // Reset the search term
        $this->resetPage();     // Reset pagination
    }
    
    public function GetDocumentStatus()
    {
        $this->documents = [
            [
                'name' => 'Driving Licence',
                'tag' => 'driving_licence_status',
                'icon' => 'ri-roadster-line',
                'doc' => $this->user->driving_licence,
                'status' => $this->user->driving_licence_status,
            ],
            [
                'name' => 'Govt. ID Card',
                'tag' => 'govt_id_card_status',
                'icon' => 'ri-passport-line',
                'doc' => $this->user->govt_id_card,
                'status' => $this->user->govt_id_card_status,
            ],
            [
                'name' => 'Cancelled Cheque',
                'tag' => 'cancelled_cheque_status',
                'icon' => 'ri-bank-line',
                'doc' => $this->user->cancelled_cheque,
                'status' => $this->user->cancelled_cheque_status,
            ],
            [
                'name' => 'Current Address Proof',
                'tag' => 'current_address_proof_status',
                'icon' => 'ri-home-line',
                'doc' => $this->user->current_address_proof,
                'status' => $this->user->current_address_proof_status,
            ],
        ];
    }

    public function updateStatus($document_type, $status)
    {
        $update = User::where('id', $this->userId)->first();
        $update->$document_type = $status;
        $update->save();
        $this->mount($this->userId);
        // Optionally, show a confirmation message
        session()->flash('message', 'Status updated successfully.');
    }


    /**
     * Show the edit modal for user details.
     */
    public function activeEditModal()
    {
        $this->showEditModal = true;
    }

    /**
     * Close the edit modal.
     */
    public function closeModal()
    {
        $this->showEditModal = false;
    }

    /**
     * Update user details.
     */
    public function updateUser()
    {
        $this->validate([
            'user.email' => 'required|email|unique:users,email,' . $this->userId,
            'user.mobile' => 'required|numeric',
            'user.address' => 'nullable|string|max:255',
        ]);

        $this->user->save();

        $this->showEditModal = false;
        session()->flash('message', 'User details updated successfully!');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword()
    {
        $this->validate();

        $user = User::find($this->userId);

        if (!$user) {
            session()->flash('error', 'User not found!');
            return;
        }

        // Update password
        $user->password = Hash::make($this->newPassword);
        $user->save();

        session()->flash('message', 'Password updated successfully!');

        // Reset password fields after update
        $this->reset(['newPassword', 'confirmPassword']);
    }

    /**
     * Set the active tab.
     */
    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function exportAll()
    {
        return Excel::download(new UserRideSummaryExport($this->userId), 'user_ride_history.xlsx');
    }
    /**
     * Render the Livewire component.
     */
   public function fetchRideData($order_id, $key)
    {
        $this->reset(['ride_history', 'expandedRows']);

        if (in_array($key, $this->expandedRows)) {
            $this->expandedRows = array_diff($this->expandedRows, [$key]);
        } else {
            $this->expandedRows[] = $key;
        }

        $exchangeVehicles = ExchangeVehicle::with('stock')
            ->where('user_id', $this->userId)
            ->where('order_id', $order_id)
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function ($item) {
                $item->model_type = 'exchange';
                return $item;
            });

        $assignedVehicle = AsignedVehicle::whereIn('status', ['assigned', 'overdue'])
            ->where('user_id', $this->userId)
            ->where('order_id', $order_id)
            ->first();

        $collection = collect();

        if ($assignedVehicle) {
            $assignedVehicle->exchanged_by = $assignedVehicle->assigned_by;
            $assignedVehicle->model_type = 'assigned';
            $collection->push($assignedVehicle);
        }

        $this->ride_history = $collection->merge($exchangeVehicles);

    }
    public function changeTab($value){
        $this->activeTab = $value;
    }
    public function exportJourney()
    {
        $userJourney = $this->getUserJourney(); // Make sure this returns the formatted array

        return Excel::download(new UserJourneyExport($userJourney), 'user-journey.xlsx');
    }

    public function getUserJourney(){
        $user = User::find($this->userId);

        $getTermsAndCondision = UserTermsConditions::where('email',$user->email)->first();

        $register = $user->created_at ?? null;
        $kyc_uploaded = UserKycLog::where('user_id', $this->userId)->latest()->value('created_at');
        $kyc_verified_at = $user->kyc_verified_at ?? null;

        $firstOrder = Order::where('user_id', $this->userId)->orderBy('id', 'ASC')->first();
        $lastOrder = Order::where('user_id', $this->userId)->orderBy('id', 'DESC')->first();

        $totalOrders = Order::where('user_id', $this->userId)->count();
        $totalPayment = $this->total_payment_amount;

        $lastAssigned = AsignedVehicle::where('user_id', $this->userId)
            ->whereIn('status', ['assigned', 'overdue'])
            ->orderBy('id', 'DESC')
            ->with('stock')
            ->first();

        $userJourney = [];

        if ($register) {
            $userJourney[] = [
                'title' => 'User Registered',
                'description' => 'User account created successfully.',
                'terms_and_conditions' => $getTermsAndCondision,
                'date' => $register,
            ];
        }

        if ($kyc_uploaded) {
            $userJourney[] = [
                'title' => 'KYC Uploaded',
                'description' => 'User submitted KYC documents.',
                'date' => $kyc_uploaded,
            ];
        }

        if ($kyc_verified_at) {
            $userJourney[] = [
                'title' => 'KYC Verified',
                'description' => 'KYC has been verified.',
                'date' => $kyc_verified_at,
            ];
        }

        if ($firstOrder) {
             $vehicle_number = 'N/A';

            // Check assigned vehicle
            if ($firstOrder->vehicle && $firstOrder->vehicle->stock) {
                $vehicle_number = $firstOrder->vehicle->stock->vehicle_number;
            }

            // If not found in assigned, check in exchange vehicle (assuming one-to-one or latest returned)
            if (!$firstOrder->vehicle && $firstOrder->exchange_vehicle) {
                $returnedExchange = $firstOrder->exchange_vehicle->where('status', 'returned')->first();
                if ($returnedExchange && $returnedExchange->stock) {
                    $vehicle_number = $returnedExchange->stock->vehicle_number;
                }
            }
            $firstOrder->vehicle?$firstOrder->vehicle->vehicle_id:$firstOrder->exchange_vehicle;
            $userJourney[] = [
                'title' => 'First Ride Placed',
                'description' => 'Vehicle  Number: <span class="badge bg-label-success">#' . $vehicle_number . '</span>',
                'date' => $firstOrder->created_at,
            ];
        }

        

        if ($lastOrder) {
            $vehicle_number = 'N/A';
            // Check assigned vehicle
            if ($lastOrder->vehicle && $lastOrder->vehicle->stock) {
                $vehicle_number = $lastOrder->vehicle->stock->vehicle_number;
            }

            // If not found in assigned, check in exchange vehicle (assuming one-to-one or latest returned)
            if (!$lastOrder->vehicle && $lastOrder->exchange_vehicle) {
                $returnedExchange = $lastOrder->exchange_vehicle->where('status', 'returned')->first();
                if ($returnedExchange && $returnedExchange->stock) {
                    $vehicle_number = $returnedExchange->stock->vehicle_number;
                }
            }
            $userJourney[] = [
                'title' => 'Last Ride',
                'description' => 'Vehicle Number: <span class="badge bg-label-success">#' . $vehicle_number . '</span>',
                'date' => $lastOrder->created_at,
            ];
        }
        if ($totalOrders > 0) {
            $userJourney[] = [
                'title' => 'Ride Summary',
                'description' => "Total Rides: <span class='text-primary fw-bold'>{$totalOrders}</span>, Total Rent Paid: <code>₹" . number_format($totalPayment, 2) . "</code>",
                'date' => now(), // or leave null
            ];
        }
        return $userJourney;
    }
    public function getCancelRequestHistory(){
        return CancelRequestHistory::where('user_id', $this->userId)->get();
    }
    public function getLocationHistory(){
        return UserLocationLog::where('user_id', $this->userId)->orderBy('id','DESC')->paginate(10);
    }
    
    public function exportCancelHistory()
    {
        $cancelRequests = $this->getCancelRequestHistory();

        return Excel::download(new CancelRequestExport($cancelRequests), 'cancel_request_history.xlsx');
    }
    public function render(){
        $cancel_request_histories = $this->getCancelRequestHistory();
        $location_history = $this->getLocationHistory();
        $userJourney = $this->getUserJourney();
        $orders = Order::where('user_id',$this->userId)->whereIn('rent_status',['active','returned'])->orderBy('id','DESC')->paginate(18);
        return view('livewire.admin.customer-details',[
            'orders'=>$orders,
            'userJourney' => $userJourney,
            'cancel_request_histories' => $cancel_request_histories,
            'location_history' => $location_history,
        ]);
    }
}
