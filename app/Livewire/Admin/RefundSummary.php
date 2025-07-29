<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Order;
use App\Models\BomPart;
use App\Models\Payment;
use App\Models\PaymentLog;
use App\Models\OrderItemReturn;
use App\Models\DamagedPartLog;
use App\Models\UserKycLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
class RefundSummary extends Component
{
    use WithPagination;
    use WithFileUploads; // ✅ REQUIRED for file uploads
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $auto_early_fill = false;
    public $remarks,$field,$document_type,$id,$over_due_days,$early_return_days = 0,$early_return_amount = 0,$bom_parts=[],$balance_amnt=0,$parts_amnt,$order_id,
    $over_due_amnts=0,$deduct_amounts=0,$per_day_amnt,$port_charges = 0,$reason,$damaged_part_image=[],$damage_parts=[],
    $return_condition,$isProgressModal=0,$status,$order_item_return_id,$isReturnModal=0,$damaged_part_logs=[],$damaged_part_images=[],$bom_part=[];
    public $active_tab = 1;
    public $customers = [];
    public $selectedCustomer = null; // Stores the selected customer data
    public $isModalOpen = false; // Track modal visibility
    public $isRejectModal = false; // Track modal visibility
    public $isPreviewimageModal = false;
    public $selected_order;
    public $BomParts = [];
    public $expandedRows = [];
    public $transaction_details = [];

    /**
     * Search button click handler to reset pagination.
     */
    public function btn_search()
    {
        $this->resetPage(); // Reset to the first page
    }

    public function OpenRejectForm($field, $document_type, $id)
    {
        $this->field = $field;
        $this->document_type = $document_type;
        $this->id = $id; // Changed from $this->id to avoid conflicts
        $this->isRejectModal = true;
    }
    public function OpenPreviewImage($front_image, $back_image,$document_type)
    {
        $this->preview_front_image = $front_image;
        $this->preview_back_image = $back_image;
        $this->document_type = $document_type;
        $this->isPreviewimageModal = true;
    }


    public function closePreviewImage()
    {
        $this->isPreviewimageModal = false;
        $this->reset(['preview_front_image', 'preview_back_image','document_type']);
    }
    public function closeRejectModal()
    {
        $this->isRejectModal = false;
        $this->reset(['remarks', 'field','document_type', 'id']);
    }

    public function PartialPayment($order_id,$customerId)
    {
        $this->reset(['BomParts','selected_order','selectedCustomer','order_item_return_id','bom_part',
        'over_due_days','port_charges','over_due_amnts','deduct_amounts','balance_amnt','early_return_days','early_return_amount','auto_early_fill']);

        $this->selected_order = Order::find($order_id);

        $this->BomParts = BomPart::where('product_id', $this->selected_order->product_id)->orderBy('part_name','ASC')->get();
        $this->selectedCustomer = User::find($customerId);
        $this->isModalOpen = true;
        $this->calculateAmount();
        $this->dispatch('bind-chosen', []);
    }
    public function ConfirmFullPayment($id){
        $this->dispatch('showConfirmFullPayment',['itemId' => $id]);
    }
    public function ConfirmZeroPayment($id){
        $this->dispatch('showConfirmZeroPayment',['itemId' => $id]);
    }
    public function ConfirmCancelRequest($id){
        $this->dispatch('showConfirmCancelRequest',['itemId' => $id]);
    }
    public function FullPayment($order_id){
        $Order = Order::find($order_id);
        OrderItemReturn::create([
            'order_item_id' => $order_id,
            'refund_amount' => $Order->deposit_amount,
            'actual_amount' => $Order->deposit_amount,
            'refund_category' => 'deposit_full_refund',
            'refund_initiated_by' => Auth::guard('admin')->user()->id,
            'refund_initiated_at' => now()->toDateTimeString(),
            'user_id' => $Order->user_id,
            'return_status' => 'good_condition',
            'status' => 'in_progress'
        ]);

        $this->active_tab = 2;
        $this->resetPage();
        session()->flash('message', 'request submitted successfully!');
    }
    public function ZeroPayment($order_id){
        $Order = Order::find($order_id);
        OrderItemReturn::create([
            'order_item_id' => $order_id,
            'refund_amount' => 0,
            'actual_amount' => $Order->deposit_amount,
            'refund_category' => 'deposit_no_refund',
            'refund_initiated_by' => Auth::guard('admin')->user()->id,
            'refund_initiated_at' => now()->toDateTimeString(),
            'user_id' => $Order->user_id,
            'return_status' => 'damaged',
            'status' => 'in_progress'
        ]);

        $this->active_tab = 2;
        $this->resetPage();
        session()->flash('message', 'request submitted successfully!');
    }

   public function CancelRequest($id)
    {
        $OrderItemReturn = OrderItemReturn::find($id);

        if ($OrderItemReturn) {
            // Delete related damage parts
            $OrderItemReturn->damageParts()->delete();

            // Delete the return record
            $OrderItemReturn->delete();

            $this->active_tab = 1;
            $this->resetPage();
            session()->flash('message', 'Request cancelled successfully!');
        } else {
            session()->flash('error', 'Request not found or already deleted.');
        }
    }
    public function ProgressModal($id)
    {
        $this->reset(['reason','status']);
        $this->order_item_return_id=$id;
        $this->isProgressModal = 1;

    }
    public function setEarlyReturnDays()
    {
        $this->reset(['early_return_days','early_return_amount']);

        if ($this->auto_early_fill) {
            $rentEnd = \Carbon\Carbon::parse($this->selected_order->rent_end_date);
            $returnDate = \Carbon\Carbon::parse($this->selected_order->return_date);

            $days = round(abs($rentEnd->diffInDays($returnDate, false))); // can be 0 if same day
            $this->early_return_days = $days;

            if ($days == 0) {
                $this->early_return_amount = 0;
            } else {
                $this->per_day_amnt = $this->selected_order->rent_duration > 0
                    ? $this->selected_order->rental_amount / $this->selected_order->rent_duration
                    : 0;

                $this->early_return_amount = round($this->per_day_amnt * $days, 2);
            }

        } else {
            // Uncheck = reset both
            $this->early_return_days = 0;
            $this->early_return_amount = 0;
        }

        $this->calculateAmount();
    }

     public function closeProgressModal()
    {
        $this->reset(['reason','status']);

        $this->isProgressModal = 0;

    }
    public function PaymentConfimed($id){
        $this->dispatch('showConfirmPayment',['itemId' => $id]);
    }
    public function updatePaymentData($order_return_id){
        $OrderItemReturn = OrderItemReturn::find($order_return_id);
        if($OrderItemReturn->refund_category==="deposit_no_refund"){
            $OrderItemReturn->return_date = now()->toDateTimeString();
            $OrderItemReturn->status = 'confimed';
            $OrderItemReturn->txnStatus = 'SUC';
            $OrderItemReturn->save();

            $this->dispatch('paymentUpdateSuccess', [
                'message' => 'The refund payment has been marked as confirmed.'
            ]);
            $this->active_tab = 4;//Confimed Tab
            $this->resetPage();
            return;
        }
        
        if (!$OrderItemReturn) {
            $this->dispatch('paymentUpdateFailed', [
                'message' => 'Refund record not found.'
            ]);
            return;
        }
        if (!$OrderItemReturn->order_item) {
            $this->dispatch('paymentUpdateFailed', [
                'message' => 'Refund payment record not found.'
            ]);
            return;
        }
        if ($OrderItemReturn->order_item->rent_status !== "returned") {
            $this->dispatch('paymentUpdateFailed', [
                'message' => 'Refund cannot be confirmed. Vehicle is not yet returned.'
            ]);
            return;
        }

        $fetchPaymentData = Payment::where('order_id', $OrderItemReturn->order_item_id)
        ->whereHas('paymentItem', function ($query) {
            $query->where('type', 'deposit');
        })
        ->with(['paymentItem' => function ($query) {
            $query->where('type', 'deposit')->select('id', 'payment_id', 'type', 'amount');
        }])
        ->select('id', 'order_id', 'icici_txnID') // 'icici_txnID' is assumed to be the txn ID
        ->first();

        if (
            empty($fetchPaymentData->icici_txnID) || 
            empty($fetchPaymentData->paymentItem) || 
            empty($fetchPaymentData->paymentItem[0])
        ) {
            $this->dispatch('paymentUpdateFailed', [
                'message' => 'Refund payment item or transaction ID not found.'
            ]);
            return;
        }

        if ($OrderItemReturn->refund_amount > $fetchPaymentData->paymentItem[0]->amount) {
            $this->dispatch('paymentUpdateFailed', [
                'message' => 'Refund cannot be confirmed. It exceeds the deposit amount.'
            ]);
            return;
        }

        $merchantTxnNo = 'RTN'.'_'.$order_return_id.'_'.now()->format('dmyHis');
        $merchantID = env('ICICI_MARCHANT_ID');
        $transactionType = 'REFUND';
        $amount = $OrderItemReturn->refund_amount;

        // Retrieve these from DB if needed
        $originalTxnNo = $fetchPaymentData->icici_txnID; // Ideally, fetch actual amount from your DB using this txn no
        // Optional: Only include if the transaction was aggregator-initiated
        // $aggregatorID = env('ICICI_AGGREGATOR_ID');
        $aggregatorSecretKey = env('ICICI_MARCHANT_SECRET_KEY');

        // Create secureHash (optional but usually required)
        $hashString = $amount . $merchantID . $merchantTxnNo . $originalTxnNo . $transactionType;
        $secureHash = hash_hmac('sha256', $hashString, $aggregatorSecretKey);

        $postData = [
            'merchantID'       => $merchantID,
            'merchantTxnNo'    => $merchantTxnNo,
            'originalTxnNo'    => $originalTxnNo,
            'transactionType'  => $transactionType,
            'amount'           => $amount,
            'secureHash'       => $secureHash,
        ];
        // Make cURL request
        $ch = curl_init(env('ICICI_PAYMENT_CHECK_STATUS_BASH_URL'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Capture HTTP response code
        curl_close($ch);
        if ($httpCode == 200) {
            $responseData = json_decode($response, true); // Convert JSON string to associative array
            // dd($responseData);
            if($responseData['responseCode']=='P1000'){
                PaymentLog::create([
                    'gateway' => 'ICICI',
                    'type' => 'refund',
                    'transaction_id' => $responseData['txnID'] ?? null,
                    'merchant_txn_no' => $responseData['merchantTxnNo'] ?? null,
                    'response_payload' => $response, // raw JSON string
                    'status' => $responseData['responseCode'] ?? null,
                    'message' => $responseData['respDescription'] ?? null,
                ]);
                $OrderItemReturn->transaction_id = $responseData['txnID'] ?? null;
                $OrderItemReturn->return_date = now()->toDateTimeString();
                $OrderItemReturn->status = 'confimed';
                $OrderItemReturn->save();

                $this->dispatch('paymentUpdateSuccess', [
                    'message' => 'The refund payment has been marked as confirmed.'
                ]);
                $this->active_tab = 4;//Confimed Tab
                $this->resetPage();
            }else{
                  $this->dispatch('paymentUpdateFailed', [
                    'message' => $responseData['respDescription']
                ]);
            }
        } else {
            $this->dispatch('paymentUpdateFailed', [
                'message' => json_decode($response, true)
            ]);
        }
    }

    public function toggleRow($key, $merchantTxnNo,$amount)
    {
        $this->transaction_details[$key] = $this->paymentFetch($merchantTxnNo,$amount);
        if(!isset($this->transaction_details[$key]['status'])){
            // if($this->transaction_details[$key]['txnStatus']==="SUC"){
            $OrderItemReturn = OrderItemReturn::where('transaction_id',$merchantTxnNo)->first();
            $OrderItemReturn->txnStatus = $this->transaction_details[$key]['txnStatus'];
            $OrderItemReturn->save();
            // }
        }
        if (in_array($key, $this->expandedRows)) {
            $this->expandedRows = array_diff($this->expandedRows, [$key]);
        } else {
            $this->expandedRows[] = $key;
        }
    }
    public function paymentFetch($merchantTxnNo,$amount)
    { 
        $merchantID = env('ICICI_MARCHANT_ID');
        $transactionType = 'STATUS';

        // Retrieve these from DB if needed
        $originalTxnNo = $merchantTxnNo; // Ideally, fetch actual amount from your DB using this txn no
        // Optional: Only include if the transaction was aggregator-initiated
        $aggregatorID = env('ICICI_AGGREGATOR_ID');
        $aggregatorSecretKey = env('ICICI_MARCHANT_SECRET_KEY');

        // Create secureHash (optional but usually required)
        $hashString = $amount . $merchantID . $merchantTxnNo . $originalTxnNo . $transactionType;
        $secureHash = hash_hmac('sha256', $hashString, $aggregatorSecretKey);

        $postData = [
            'merchantID'       => $merchantID,
            'merchantTxnNo'    => $merchantTxnNo,
            'originalTxnNo'    => $originalTxnNo,
            'transactionType'  => $transactionType,
            'amount'           => $amount,
            'secureHash'       => $secureHash,
            // Only include aggregatorID if needed
            // 'aggregatorID'     => $aggregatorID,
        ];
        // Make cURL request
        $ch = curl_init(env('ICICI_PAYMENT_CHECK_STATUS_BASH_URL'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));

       $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Capture HTTP response code
        curl_close($ch);
        if ($httpCode == 200) {
            return json_decode($response, true);
        } else {
            return [
                'status' => false,
                'message' => "Failed to capture payment.",
                'error' => json_decode($response, true)
            ];
        }
    }
    public function ResetEligibleFromField(){
         $this->reset(['over_due_days','bom_parts','balance_amnt','parts_amnt']);
    }

    public function closeModal()
    {
        $this->ResetEligibleFromField();
        $this->isModalOpen = false;
    }

    /**
     * Refresh button click handler to reset the search input and reload data.
     */
    public function reset_search(){
        $this->reset('search'); // Reset the search term
        $this->resetPage();     // Reset pagination
    }
    public function toggleStatus($id){
        $user = User::findOrFail($id);
        $user->status = !$user->status;
        $user->save();
        session()->flash('message', 'Customer status updated successfully.');
    }
    public function rules()
    {
        return [
            // Only validate if attachments are present
            'damaged_part_image.*' => 'nullable|image|max:5120', // each file can be null or a valid image
            'balance_amnt'   => 'required|numeric|min:0.01', // must be greater than zero
        ];
    }
    public function changeReturnStatusRules()
    {
        return [
            'status' => 'required|string|in:processed,confimed,rejected', // status required
        ];
    }
    public function messages()
{
    return [

        'reason.required' => 'The remark field is required.',
    ];
}
    public function tab_change($value){
        $this->active_tab = $value;
        $this->search = "";
        $this->resetPage();
    }
    public function render()
    {
        // Query users based on the search term
        $eligible_refunds = Order::with('user')
            ->when($this->search, function ($query) {
                $searchTerm = '%' . $this->search . '%';
                $query->whereHas('user', function ($q) use ($searchTerm) {
                    $q->where('name', 'like', $searchTerm)
                    ->orWhere('mobile', 'like', $searchTerm)
                    ->orWhere('email', 'like', $searchTerm)
                    ->orWhere('customer_id', 'like', $searchTerm);
                });
            })->doesntHave('refund_payment')
            ->where('subscription_type', 'like', 'new_subscription_%')
            ->where('payment_status', 'completed')
            ->where('rent_status', 'returned')
            ->orderByDesc('id')
            ->paginate(20);
       $in_progress_data = OrderItemReturn::with('order_item')
        ->when($this->search, function ($query) {
            $searchTerm = '%' . $this->search . '%';

            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('order_item.product', function ($productQuery) use ($searchTerm) {
                    $productQuery->where('title', 'like', $searchTerm);
                });

                $q->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                    $userQuery->where('name', 'like', $searchTerm)
                        ->orWhere('mobile', 'like', $searchTerm)
                        ->orWhere('email', 'like', $searchTerm)
                        ->orWhere('customer_id', 'like', $searchTerm);
                });
            });
        })
        ->orderBy('id', 'DESC')
        ->where('status', 'in_progress')
        ->paginate(20);

        $in_processed_data = OrderItemReturn::with('order_item')
        ->when($this->search, function ($query) {
            $searchTerm = '%' . $this->search . '%';

            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('order_item.product', function ($productQuery) use ($searchTerm) {
                    $productQuery->where('title', 'like', $searchTerm);
                });

                $q->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                    $userQuery->where('name', 'like', $searchTerm)
                        ->orWhere('mobile', 'like', $searchTerm)
                        ->orWhere('email', 'like', $searchTerm)
                        ->orWhere('customer_id', 'like', $searchTerm);
                });
            });
        })
        ->orderBy('id', 'DESC')
        ->where('status', 'processed')
        ->paginate(20);

        $in_confirmed_data = OrderItemReturn::with('order_item')
        ->when($this->search, function ($query) {
            $searchTerm = '%' . $this->search . '%';

            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('order_item.product', function ($productQuery) use ($searchTerm) {
                    $productQuery->where('title', 'like', $searchTerm);
                });

                $q->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                    $userQuery->where('name', 'like', $searchTerm)
                        ->orWhere('mobile', 'like', $searchTerm)
                        ->orWhere('email', 'like', $searchTerm)
                        ->orWhere('customer_id', 'like', $searchTerm);
                });
            });
        })
        ->orderBy('id', 'DESC')
        ->where('status', 'confimed')
        ->paginate(20);

        $in_rejected_data = OrderItemReturn::with('order_item')
        ->when($this->search, function ($query) {
            $searchTerm = '%' . $this->search . '%';

            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('order_item.product', function ($productQuery) use ($searchTerm) {
                    $productQuery->where('title', 'like', $searchTerm);
                });

                $q->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                    $userQuery->where('name', 'like', $searchTerm)
                        ->orWhere('mobile', 'like', $searchTerm)
                        ->orWhere('email', 'like', $searchTerm)
                        ->orWhere('customer_id', 'like', $searchTerm);
                });
            });
        })
        ->orderBy('id', 'DESC')
        ->where('status', 'rejected')
        ->paginate(20);

        return view('livewire.admin.refund-summary', [
            'eligible_refunds' => $eligible_refunds,
            'in_progress_data' => $in_progress_data,
            'in_processed_data' => $in_processed_data,
            'in_confirmed_data' => $in_confirmed_data,
            'in_rejected_data' => $in_rejected_data,


        ]);
    }
    public function setOverdueDays($days){
       if ($days == 0) {
            $this->over_due_amnts = 0;
            $this->over_due_days = 0;
        } else {
            $this->per_day_amnt = ($this->selected_order->rental_amount / $this->selected_order->rent_duration);
            $this->over_due_amnts = $this->per_day_amnt * $days;
            $this->over_due_days = $days;
        }

        $this->calculateAmount();
    }
    public function bomPartChanged($parts)
    {
      $totalAmnt=0;
      $bom_parts=BomPart::whereIn('id', $parts)->get();
      $this->damage_parts=$parts;
      foreach($bom_parts as $part)
      {
        $totalAmnt+=$part->part_price;
      }
      $this->parts_amnt=$totalAmnt;
      $this->calculateAmount();
    }
    public function calculateAmount()
    {
        $parts_amnt = (float) $this->parts_amnt;
        $over_due_amnts = (float) $this->over_due_amnts;
        $port_charges = (float) $this->port_charges;

        $this->deduct_amounts = ceil($parts_amnt + $over_due_amnts + $port_charges);

        $deposit_amount = (float) $this->selected_order->deposit_amount;
        $early_return_amount = (float) $this->early_return_amount;

         $this->balance_amnt = $deposit_amount + $early_return_amount - $this->deduct_amounts;

        //   $this->deduct_amounts=ceil($this->parts_amnt+$this->over_due_amnts+$this->port_charges);
        //   $this->balance_amnt=($this->selected_order->deposit_amount-$this->deduct_amounts);
    }

    public function submit()
    {
        DB::transaction(function () {
            $this->validate();

            $damaged_part_image = [];
            foreach ($this->damaged_part_image as $file) {
                $image = storeFileWithCustomName($file, 'uploads/damaged_part_image');
                $damaged_part_image[] = $image;
            }
            $damaged_part_image = array_merge($damaged_part_image, $this->damaged_part_images);

            $admin = Auth::guard('admin')->user();
            $adminId = $admin->id;
            if (!empty($this->order_item_return_id)) {
                OrderItemReturn::where('id', $this->order_item_return_id)->update([
                    'damaged_part_image' => implode(",", $damaged_part_image),
                    'refund_amount' => $this->balance_amnt,
                    'refund_category' => 'deposit_partial_refund',
                    'return_condition' => $this->return_condition,
                    'refund_initiated_by' => $adminId,
                    'over_due_days' => $this->over_due_days,
                    'over_due_amnt' => $this->over_due_amnts,
                    'user_id' => $this->selected_order->user_id,
                    'port_charges' => $this->port_charges,
                    'early_return_days' => $this->early_return_days,
                    'early_return_amount' => $this->early_return_amount,
                ]);
            } else {
                $order = Order::where('id',$this->selected_order->id)->first();
                OrderItemReturn::create([
                    'damaged_part_image' => implode(",", $damaged_part_image),
                    'order_item_id' => $this->selected_order->id,
                    'refund_amount' => $this->balance_amnt,
                    'actual_amount' => $order->deposit_amount,
                    'refund_category' => 'deposit_partial_refund',
                    'return_condition' => $this->return_condition,
                    'refund_initiated_by' => $adminId,
                    'over_due_days' => $this->over_due_days,
                    'over_due_amnt' => $this->over_due_amnts,
                    'user_id' => $this->selected_order->user_id,
                    'port_charges' => $this->port_charges,
                    'early_return_days' => $this->early_return_days,
                    'early_return_amount' => $this->early_return_amount,
                ]);
            }

            $damaged_part_logs = [];
            if (!empty($this->order_item_return_id)) {
                $existing_damages = DamagedPartLog::where('order_item_id', $this->order_id)->pluck('bom_part_id')->toArray();
                if (!empty($this->damage_parts)) {
                    $isSame = (count($existing_damages) === count($this->damage_parts)) && empty(array_diff($existing_damages, $this->damage_parts));
                    if (!$isSame) {
                        DamagedPartLog::where('order_item_id', $this->order_id)->delete();

                        foreach ($this->damage_parts as $bom_part) {
                            $parts = BomPart::findOrFail($bom_part);

                            $damaged_part_logs[] = [
                                'order_item_id' => $this->selected_order->id,
                                'bom_part_id' => $bom_part,
                                'price' => $parts->part_price,
                                'log_by' => $adminId
                            ];
                        }
                        DamagedPartLog::insert($damaged_part_logs);
                    }
                }
            } else {
                if (!empty($this->damage_parts)) {
                    foreach ($this->damage_parts as $bom_part) {
                        $parts = BomPart::findOrFail($bom_part);

                        $damaged_part_logs[] = [
                            'order_item_id' => $this->selected_order->id,
                            'bom_part_id' => $bom_part,
                            'price' => $parts->part_price,
                            'log_by' => $adminId
                        ];
                    }
                    DamagedPartLog::insert($damaged_part_logs);
                }
            }

            $this->closeModal();
            $this->active_tab = 2;
            $this->resetPage();
            session()->flash('message', 'Balance submitted successfully!');
        });
    }
    public function ChangeReturnStatus()
    {
        $this->validate($this->changeReturnStatusRules());
        $return = OrderItemReturn::findOrFail($this->order_item_return_id);

        // Update the status and remarks (if provided)
        $return->status = $this->status;
        $return->reason = $this->reason ?? null; // Set to null if remarks are not provided

        // Save the record
        $return->save();
        $this->active_tab = 3; //Processed Tab
           $this->resetPage();
        $this->closeProgressModal();
    session()->flash('message', 'Status has been changed Successfully !');

    }
    public function setPortCharges()
        {

        \Log::info('Port Charges updated:', ['value' => $this->port_charges]);

            // Your calculation or database logic goes here
            $this->calculateAmount();

        }
        public function updated($propertyName)
        {
            // Run validation whenever any property is updated
            $this->validateOnly($propertyName);
        }
        public function viewReturnModal($order_id,$order_item_id,$customerId)
        {
            $this->selected_order = Order::find($order_id);
            $this->selectedCustomer = User::find($customerId);
            $return = OrderItemReturn::findOrFail($order_item_id);

            $this->damaged_part_logs=DamagedPartLog::with('bom_part')->where('order_item_id',$order_id)->get();
            if(!empty($return->damaged_part_image))
            {
            $this->damaged_part_images=explode(",",$return->damaged_part_image);

            }
            $this->isReturnModal=1;
        }
        public function closeReturnModal()
        {
        $this->isReturnModal=0;
        }
        public function editReturnModal($return_id)
        {
            $this->reset(['BomParts','selected_order','selectedCustomer','order_item_return_id','bom_part','over_due_days',
            'port_charges','over_due_amnts','deduct_amounts','return_condition','damaged_part_images']);

            $this->order_item_return_id=$return_id;
            $return = OrderItemReturn::findOrFail($this->order_item_return_id);
            $order_id=$return->order_item_id;
            $customerId=$return->user_id;
            $this->selected_order = Order::find($order_id);
            $this->order_id=$order_id;
            $this->BomParts = BomPart::where('product_id', $this->selected_order->product_id)->orderBy('part_name','ASC')->get();
            $this->selectedCustomer = User::find($customerId);

            $this->damaged_part_logs=DamagedPartLog::where('order_item_id',$order_id)->get();
            foreach($this->damaged_part_logs as $damaged_part)
            {
                $this->bom_part[]=$damaged_part->bom_part_id;
            }
            $this->bomPartChanged($this->bom_part);

            if(!empty($return->damaged_part_image))
            {
                $this->damaged_part_images=explode(",",$return->damaged_part_image);
            }
            $this->over_due_days=$return->over_due_days;
            $this->over_due_amnts=$return->over_due_amnt;
            $this->early_return_days = $return->early_return_days;
            $this->early_return_amount = $return->early_return_amount;
            $this->port_charges=$return->port_charges;
            $this->return_condition=$return->return_condition;
            $this->isModalOpen = true;
            $this->auto_early_fill = $this->early_return_days>0?true:false;
            
            $this->calculateAmount();
            $this->dispatch('bind-chosen',[]);


        }
    public function openFullRefundConfirm($order_id)
        {
        $this->order_id=$order_id;
        $this->selected_order = Order::find($order_id);
        $this->calculateAmount();
        $this->dispatch('openfullrefund',[]);
        }
    }
