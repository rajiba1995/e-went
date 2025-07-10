<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Order;
use App\Models\BomPart;
use App\Models\UserKycLog;
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
    public $remarks,$field,$document_type,$id,$over_due_days,$bom_parts=[],$balance_amnt=0,$parts_amnt,$order_id,
    $over_due_amnts=0,$deduct_amounts=0,$port_charges=0,$reason,$damaged_part_image=[];
    public $active_tab = 1;
    public $customers = [];
    public $selectedCustomer = null; // Stores the selected customer data
    public $isModalOpen = false; // Track modal visibility
    public $isRejectModal = false; // Track modal visibility
    public $isPreviewimageModal = false;
    public $selected_order;
    public $BomParts = [];

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
        $this->reset(['BomParts','selected_order','selectedCustomer']);
        $this->selected_order = Order::find($order_id);
        $this->BomParts = BomPart::where('product_id', $this->selected_order->product_id)->orderBy('part_name','ASC')->get();
        $this->selectedCustomer = User::find($customerId);
        $this->isModalOpen = true;
        $this->calculateAmount();
        $this->dispatch('bind-chosen', [

      ]);

    }

    public function closeModal()
    {
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
    public function tab_change($value){
        $this->active_tab = $value;
        $this->search = "";
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
        return view('livewire.admin.refund-summary', [
            'eligible_refunds' => $eligible_refunds,
            'verified_users' => $verified_users,
            'rejected_users' => $rejected_users
        ]);
    }
    public function setOverdueDays($days){
    $per_day_amnt=($this->selected_order->deposit_amount/$this->selected_order->rent_duration );
    $this->over_due_amnts=$per_day_amnt*$days;
    $this->calculateAmount();
    }
    public function bomPartChanged($parts)
    {
      $totalAmnt=0;
      $bom_parts=BomPart::whereIn('id', $parts)->get();
      foreach($bom_parts as $part)
      {
        $totalAmnt+=$part->part_price;
      }
      $this->parts_amnt=$totalAmnt;
      $this->calculateAmount();

    }
    public function calculateAmount()
    {
      $this->deduct_amounts=ceil($this->parts_amnt+$this->over_due_amnts+$this->port_charges);
      $this->balance_amnt=($this->selected_order->deposit_amount-$this->deduct_amounts);
    }
    public function setPortChareges($amnt)
    {
      $this->port_charges=$amnt;
      $this->calculateAmount();
    }
    public function submit()
    {
      $this->validate();
        $damaged_part_image=[];
        foreach ($this->damaged_part_image as $file) {
          $image = storeFileWithCustomName($file, 'uploads/damaged_part_image');
          $damaged_part_image[]=$image;

      }
      $admin = Auth::guard('admin')->user();
    $adminId = $admin->id;
      \App\Models\OrderItemReturn::create([
        'damaged_part_image' => implode(",",$damaged_part_image),
        'order_item_id' => $this->selected_order->id,
        'refund_amount' => $this->balance_amnt,
        'refund_category' => 'deposit_partial_refund',
        'reason' => $this->reason,
        'refund_initiated_by' =>  $adminId,

    ]);
    $this->closeModal();
    session()->flash('message', 'Balance submitted successfully!');
    }

}
