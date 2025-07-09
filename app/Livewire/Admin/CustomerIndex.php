<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\UserKycLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;


class CustomerIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $remarks,$field,$document_type,$id;
    public $active_tab = 1;
    public $customers = [];
    public $selectedCustomer = null; // Stores the selected customer data
    public $isModalOpen = false; // Track modal visibility
    public $isRejectModal = false; // Track modal visibility
    public $isPreviewimageModal = false;
    public $preview_front_image, $preview_back_image;

    /**
     * Search button click handler to reset pagination.
     */
    public function btn_search()
    {
        $this->resetPage(); // Reset to the first page
    }
    public function updateLog($status,$field,$document_type,$id){
        $user = User::find($id);
        if (!$user) {
            session()->flash('modal_message', 'User not found.');
            return false;
        }

        // Check if the provided field exists in the User model
        if (!Schema::hasColumn('users', $field)) {
            session()->flash('modal_message', 'Invalid field name.');
            return false;
        }
        $remarks = null;
        $message = $document_type." is successfully verified for KYC.";
        if($status==3){
            $user->date_of_rejection = date('Y-m-d h:i:s');
            $user->rejected_by = Auth::guard('admin')->user()->id;
            $user->is_verified = "rejected";
            $user->save();

            if(empty($this->remarks)){
                session()->flash('remarks', 'Please enter a remark for the rejection reason.');
                return false;
            }
            $remarks = $this->remarks;
            $message = $document_type." has been rejected. Please upload a valid document.";
        }

        $log = UserKycLog::create([
            'user_id' => $user->id,
            'document_type' => $document_type,
            'status' => $status,
            'remarks' => $remarks,
            'message' => $message,
            'created_by' => Auth::guard('admin')->user()->id, // Corrected Auth syntax
        ]);
        // Update the field value and save
        $user->$field = $status;
        $user->save();
        $this->showCustomerDetails($user->id);
        $this->closeRejectModal();
        session()->flash('modal_message', 'Status updated successfully.');
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

    public function VerifyKyc($status, $id){
        $user = User::find($id);
        if($user){

            // if($user->driving_licence_status!=2){
                
            // }
            if($status=="verified"){
                if($user->aadhar_card_status!=2){
                    session()->flash('error_kyc_message', 'Aadhar card is not verified. Please verify the Aadhar card.');
                    return false;
                }
                if($user->pan_card_status!=2){
                    session()->flash('error_kyc_message', 'Pan card is not verified. Please verify the Pan card.');
                    return false;
                }
                if($user->current_address_proof_status!=2){
                    session()->flash('error_kyc_message', 'Address proof is not verified. Please verify the current address proof.');
                    return false;
                }
                if($user->passbook_status!=2){
                    session()->flash('error_kyc_message', 'Passbook/Cancelled cheque is not verified. Please verify the passbook/cancelled cheque.');
                    return false;
                }
                if($user->profile_image_status!=2){
                    session()->flash('error_kyc_message', 'Rider image is not verified. Please verify the rider image.');
                    return false;
                }
                
                    
                $user->kyc_uploaded_at = date('Y-m-d h:i:s');
                $user->kyc_verified_by = Auth::guard('admin')->user()->id;
                $user->is_verified = "verified";
                $user->date_of_rejection = NULL;
                $user->rejected_by = NULL;
            }elseif($status=="rejected"){
                $user->date_of_rejection = date('Y-m-d h:i:s');
                $user->rejected_by = Auth::guard('admin')->user()->id;
                $user->is_verified = "rejected";
            }else{
                $user->kyc_uploaded_at = date('Y-m-d h:i:s');
                $user->kyc_verified_by = Auth::guard('admin')->user()->id;
                $user->is_verified = "unverified";
                 $user->date_of_rejection = NULL;
                $user->rejected_by = NULL;
            }
            $user->save();
            $this->showCustomerDetails($id);
            // Optionally, show a confirmation message
            session()->flash('modal_message', 'KYC status updated successfully.');
        }
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

    public function showCustomerDetails($customerId)
    {
        $this->selectedCustomer = User::with('doc_logs')->find($customerId);
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function updateStatus($id, $document_type, $status)
    {
        $update = User::where('id', $id)->first();
        $update->$document_type = $status;
        $update->save();
        $this->showCustomerDetails($id);
        // Optionally, show a confirmation message
        session()->flash('modal_message', 'Status updated successfully.');
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

    public function tab_change($value){
        $this->active_tab = $value;
        $this->search = "";
    }
    public function render()
    {
        // Query users based on the search term
        $unverified_users = User::when($this->search, function ($query) {
                $searchTerm = '%' . $this->search . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'like', $searchTerm)
                    ->orWhere('mobile', 'like', $searchTerm)
                    ->orWhere('email', 'like', $searchTerm)
                    ->orWhere('customer_id', 'like', $searchTerm);
                });
            })->with('doc_logs')
            ->where('is_verified', 'unverified')
            ->orderBy('id', 'DESC')
            ->paginate(20);
        $verified_users = User::with('doc_logs','latest_order','active_vehicle')
            ->when($this->search, function ($query) {
                $searchTerm = '%' . $this->search . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'like', $searchTerm)
                    ->orWhere('mobile', 'like', $searchTerm)
                    ->orWhere('email', 'like', $searchTerm)
                    ->orWhere('customer_id', 'like', $searchTerm);
                });
            })
            ->where('is_verified', 'verified')
            ->orderBy('id', 'DESC')
            ->paginate(20);
        $rejected_users = User::with('doc_logs')
            ->when($this->search, function ($query) {
                $searchTerm = '%' . $this->search . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'like', $searchTerm)
                      ->orWhere('mobile', 'like', $searchTerm)
                      ->orWhere('email', 'like', $searchTerm)
                      ->orWhere('customer_id', 'like', $searchTerm);
                });
            })
            ->where('is_verified', 'rejected')
            ->orderBy('id', 'DESC')
            ->paginate(20);
        return view('livewire.admin.customer-index', [
            'unverified_users' => $unverified_users,
            'verified_users' => $verified_users,
            'rejected_users' => $rejected_users
        ]);
    }
}
