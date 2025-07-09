<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Offer;

class OfferIndex extends Component
{
    public $search = '';
    public $offers = [];
    public $offerId;
    public $couponCode;
    public $discountType;
    public $discountValue;
    public $minOrderAmount;
    public $maximum_discount;
    public $startDate;
    public $endDate;
    public $usageLimit;
    public $usagePerUser;
    public $active_tab = 1;
    public $active_dis_value = '';

    protected $rules = [
        'couponCode' => 'required|string|max:255',
        'discountType' => 'required|in:flat,percentage',
        'discountValue' => 'required|numeric|min:1',
        'minOrderAmount' => 'required|numeric|min:1',
        'startDate' => 'required|date|before:endDate',
        'endDate' => 'required|date|after:startDate',
        'maximum_discount' => 'nullable|integer|min:0',
        'usageLimit' => 'nullable|integer|min:0',
        'usagePerUser' => 'nullable|integer|min:0',
    ];

    public function searchButtonClicked()
    {
        $this->mount(); // Reset to the first page
    }
    public function mount()
    {
        $this->resetErrorBag();
        $this->offers = Offer::query()
        ->when($this->search, function ($query) {
            $query->where(function ($query) {
                $query->where('coupon_code', 'like', '%' . $this->search . '%')
                    ->orWhere('discount_type', 'like', '%' . $this->search . '%')
                    ->orWhere('discount_value', 'like', '%' . $this->search . '%')
                    ->orWhere('minimum_order_amount', 'like', '%' . $this->search . '%')
                    ->orWhere('maximum_discount', 'like', '%' . $this->search . '%')
                    ->orWhere('start_date', 'like', '%' . $this->search . '%')
                    ->orWhere('end_date', 'like', '%' . $this->search . '%')
                    ->orWhere('usage_limit', 'like', '%' . $this->search . '%')
                    ->orWhere('usage_per_user', 'like', '%' . $this->search . '%');
            });
        })
        ->orderBy('id', 'DESC')->get();
    }

    public function newSubmit()
    {
        $this->rules['couponCode'] = 'required|string|max:255|unique:offers,coupon_code';
        $this->validate();
        // Save the offer (if needed, you can add saving logic here)
        Offer::create([
            'coupon_code' => $this->couponCode,
            'discount_type' => $this->discountType,
            'discount_value' => $this->discountValue,
            'minimum_order_amount' => $this->minOrderAmount,
            'maximum_discount' => $this->maximum_discount,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'usage_limit' => $this->usageLimit,
            'usage_per_user' => $this->usagePerUser,
        ]);
        session()->flash('success', 'Offer created successfully!');
        $this->resetForm();
    }
    public function editOffer($id){

        
        $offer = Offer::findOrFail($id);
        $this->offerId = $offer->id;
        $this->couponCode = $offer->coupon_code;
        $this->discountType = $offer->discount_type;
        $this->discountValue = $offer->discount_value;
        $this->minOrderAmount = $offer->minimum_order_amount;
        $this->maximum_discount = $offer->maximum_discount?$offer->maximum_discount:NULL;
        $this->startDate = $offer->start_date;
        $this->endDate = $offer->end_date;
        $this->usageLimit = $offer->usage_limit;
        $this->usagePerUser = $offer->usage_per_user;
        $this->active_tab = 3;
    }

    public function updateOffer()
    {
        $this->validate();
        $this->rules['couponCode'] = 'required|string|max:255|unique:offers,coupon_code,' . $this->offerId;

        $offer = Offer::findOrFail($this->offerId);
        $offer->update([
            'coupon_code' => $this->couponCode,
            'discount_type' => $this->discountType,
            'discount_value' => $this->discountValue,
            'minimum_order_amount' => $this->minOrderAmount,
            'maximum_discount' => $this->maximum_discount?$this->maximum_discount:NULL,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'usage_limit' => $this->usageLimit,
            'usage_per_user' => $this->usagePerUser,
        ]);

        session()->flash('success', 'Offer updated successfully!');
        $this->resetForm();
    }

    public function ChangeDiscountType($value){
        $this->active_dis_value = $value;
    }
    public function ActiveCreateTab($value)
    {
        $this->resetForm();
        $this->active_tab = $value;
        
    }
    public function DeleteOffer($id){
        try {
            Offer::findOrFail($id)->delete();
            session()->flash('success', 'Offer deleted successfully!');
            $this->resetForm();
        }catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->offerId = null;
        $this->search = '';
        $this->couponCode = '';
        $this->discountType = '';
        $this->discountValue = '';
        $this->minOrderAmount = '';
        $this->maximum_discount = '';
        $this->startDate = '';
        $this->endDate = '';
        $this->usageLimit = '';
        $this->usagePerUser = '';
        $this->reset(); // Reset form fields
        $this->mount();
    }
    public function resetSearch()
    {
        $this->reset('search'); // Reset the search term
        $this->mount();     // Reset pagination
    }
    public function render()
    {
        return view('livewire.admin.offer-index', [
            'activeTab' => $this->active_tab,
            'offers' => $this->offers,
        ]);
    }
}
