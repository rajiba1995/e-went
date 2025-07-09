<div class="row mb-4">
    @if($active_tab==1)
        <div class="col-lg-12 d-flex justify-content-end">
            <button type="button"  class="btn btn-primary" wire:click="ActiveCreateTab(2)">
                <i class="ri-add-line ri-16px me-0 me-sm-2 align-baseline"></i>
                Create New Offer
            </button>
        </div>
    @else
        <div class="col-lg-12 d-flex justify-content-end">
            <button type="button" class="btn btn-dark btn-sm waves-effect waves-light" wire:click="ActiveCreateTab(1)" role="button">
                <i class="ri-arrow-go-back-line"></i> Back
            </button>
        </div>
    @endif
    @if($active_tab==2)
    <div class="col-lg-12 col-md-6 mb-md-0 my-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5>New Offer</h5>
                        <form wire:submit.prevent="newSubmit">
                            <!-- Coupon Code -->
                            <div class="row">
                                <div class="col-3 mb-3">
                                    <label for="couponCode" class="form-label">Coupon Code <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('couponCode') is-invalid @enderror" 
                                        id="couponCode" 
                                        placeholder="Enter coupon code" 
                                        wire:model="couponCode"
                                    >
                                    @error('couponCode') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <!-- Start Date -->
                                <div class="col-3 mb-3">
                                    <label for="startDate" class="form-label">Start Date<span class="text-danger">*</span></label>
                                    <input 
                                        type="datetime-local" 
                                        class="form-control @error('startDate') is-invalid @enderror" 
                                        id="startDate" 
                                        wire:model="startDate"
                                    >
                                    @error('startDate') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                        
                                <!-- End Date -->
                                <div class="col-3 mb-3">
                                    <label for="endDate" class="form-label">End Date<span class="text-danger">*</span></label>
                                    <input 
                                        type="datetime-local" 
                                        class="form-control @error('endDate') is-invalid @enderror" 
                                        id="endDate" 
                                        wire:model="endDate"
                                    >
                                    @error('endDate') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <!-- Discount Type -->
                                <div class="col-3 mb-3">
                                    <label for="discountType" class="form-label">Discount Type<span class="text-danger">*</span></label>
                                    <select 
                                        class="form-select @error('discountType') is-invalid @enderror" 
                                        id="discountType" 
                                        wire:model="discountType" wire:change="ChangeDiscountType($event.target.value)">
                                        <option value="" hidden>Select discount type</option>
                                        <option value="flat">Flat Discount</option>
                                        <option value="percentage">Percentage Discount</option>
                                    </select>
                                    @error('discountType') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        
                            <div class="row">
                                <!-- Discount Value -->
                                <div class="col-3 mb-3">
                                    <label for="discountValue" class="form-label">Discount Value 
                                      <span class="text-danger">  
                                            @if ($active_dis_value === 'flat')
                                                (Flat)
                                            @elseif ($active_dis_value === 'percentage')
                                                (%)
                                            @endif
                                        </span><span class="text-danger">*</span>
                                    </label>
                                    <input 
                                        type="number" 
                                        class="form-control @error('discountValue') is-invalid @enderror" 
                                        id="discountValue" 
                                        placeholder="Enter discount value" 
                                        wire:model="discountValue"
                                    >
                                    @error('discountValue') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                        
                                <!-- Minimum Order Amount -->
                                <div class="col-3 mb-3">
                                    <label for="minOrderAmount" class="form-label">Minimum Order Amount<span class="text-danger">*</span></label>
                                    <input 
                                        type="number" 
                                        class="form-control @error('minOrderAmount') is-invalid @enderror" 
                                        id="minOrderAmount" 
                                        placeholder="Enter minimum order amount" 
                                        wire:model="minOrderAmount"
                                    >
                                    @error('minOrderAmount') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <!-- Minimum Order Amount -->
                                <div class="col-3 mb-3">
                                    <label for="maximum_discount" class="form-label">Maximum Discount Amount</label>
                                    <input 
                                        type="number" 
                                        class="form-control @error('maximum_discount') is-invalid @enderror" 
                                        id="maximum_discount" 
                                        placeholder="Enter maximum discount" 
                                        wire:model="maximum_discount"
                                    >
                                    @error('maximum_discount') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <!-- Usage Limit -->
                                <div class="col-3 mb-3">
                                    <label for="usageLimit" class="form-label">Global Usage Limit</label>
                                    <input 
                                        type="number" 
                                        class="form-control @error('usageLimit') is-invalid @enderror" 
                                        id="usageLimit" 
                                        placeholder="Enter global usage limit" 
                                        wire:model="usageLimit"
                                    >
                                    @error('usageLimit') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                        
                                <!-- Usage Per User -->
                                <div class="col-3 mb-3">
                                    <label for="usagePerUser" class="form-label">Usage Per User</label>
                                    <input 
                                        type="number" 
                                        class="form-control @error('usagePerUser') is-invalid @enderror" 
                                        id="usagePerUser" 
                                        placeholder="Enter usage limit per user" 
                                        wire:model="usagePerUser"
                                    >
                                    @error('usagePerUser') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        
                            <!-- Submit Button -->
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    @elseif($active_tab==3)
        <div class="col-lg-12 col-md-6 mb-md-0 my-4">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5>Edit Offer</h5>
                            <form wire:submit.prevent="updateOffer">
                                <!-- Coupon Code -->
                                <div class="row">
                                    <div class="col-3 mb-3">
                                        <label for="couponCode" class="form-label">Coupon Code<span class="text-danger">*</span></label>
                                        <input 
                                            type="text" 
                                            class="form-control @error('couponCode') is-invalid @enderror" 
                                            id="couponCode" 
                                            placeholder="Enter coupon code" 
                                            wire:model="couponCode"
                                        >
                                        @error('couponCode') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <!-- Start Date -->
                                    <div class="col-3 mb-3">
                                        <label for="startDate" class="form-label">Start Date<span class="text-danger">*</span></label>
                                        <input 
                                            type="datetime-local" 
                                            class="form-control @error('startDate') is-invalid @enderror" 
                                            id="startDate" 
                                            wire:model="startDate"
                                        >
                                        @error('startDate') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                            
                                    <!-- End Date -->
                                    <div class="col-3 mb-3">
                                        <label for="endDate" class="form-label">End Date<span class="text-danger">*</span></label>
                                        <input 
                                            type="datetime-local" 
                                            class="form-control @error('endDate') is-invalid @enderror" 
                                            id="endDate" 
                                            wire:model="endDate"
                                        >
                                        @error('endDate') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <!-- Discount Type -->
                                    <div class="col-3 mb-3">
                                        <label for="editDiscountType" class="form-label">Discount Type<span class="text-danger">*</span></label>
                                        <select 
                                            class="form-select @error('discountType') is-invalid @enderror" 
                                            id="editDiscountType" 
                                            wire:model="discountType" wire:change="ChangeDiscountType($event.target.value)">
                                            <option value="" hidden>Select discount type</option>
                                            <option value="flat">Flat Discount</option>
                                            <option value="percentage">Percentage Discount</option>
                                        </select>
                                        @error('discountType') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- Discount Value -->
                                    <div class="col-3 mb-3">
                                        <label for="discountValue" class="form-label">Discount Value 
                                        <span class="text-danger">  
                                                @if ($active_dis_value === 'flat')
                                                    (Flat)
                                                @elseif ($active_dis_value === 'percentage')
                                                    (%)
                                                @endif
                                            </span><span class="text-danger">*</span>
                                        </label>
                                        <input 
                                            type="number" 
                                            class="form-control @error('discountValue') is-invalid @enderror" 
                                            id="discountValue" 
                                            placeholder="Enter discount value" 
                                            wire:model="discountValue"
                                        >
                                        @error('discountValue') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <!-- Minimum Order Amount -->
                                    <div class="col-3 mb-3">
                                        <label for="minOrderAmount" class="form-label">Minimum Order Amount<span class="text-danger">*</span></label>
                                        <input 
                                            type="number" 
                                            class="form-control @error('minOrderAmount') is-invalid @enderror" 
                                            id="minOrderAmount" 
                                            placeholder="Enter minimum order amount" 
                                            wire:model="minOrderAmount"
                                        >
                                        @error('minOrderAmount') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <!-- Minimum Order Amount -->
                                    <div class="col-3 mb-3">
                                        <label for="maximum_discount" class="form-label">Maximum Discount Amount</label>
                                        <input 
                                            type="number" 
                                            class="form-control @error('maximum_discount') is-invalid @enderror" 
                                            id="maximum_discount" 
                                            placeholder="Enter maximum discount" 
                                            wire:model="maximum_discount"
                                        >
                                        @error('maximum_discount') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <!-- Usage Limit -->
                                    <div class="col-3 mb-3">
                                        <label for="usageLimit" class="form-label">Global Usage Limit (optional)</label>
                                        <input 
                                            type="number" 
                                            class="form-control @error('usageLimit') is-invalid @enderror" 
                                            id="usageLimit" 
                                            placeholder="Enter global usage limit" 
                                            wire:model="usageLimit"
                                        >
                                        @error('usageLimit') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                            
                                    <!-- Usage Per User -->
                                    <div class="col-3 mb-3">
                                        <label for="usagePerUser" class="form-label">Usage Per User (optional)</label>
                                        <input 
                                            type="number" 
                                            class="form-control @error('usagePerUser') is-invalid @enderror" 
                                            id="usagePerUser" 
                                            placeholder="Enter usage limit per user" 
                                            wire:model="usagePerUser"
                                        >
                                        @error('usagePerUser') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            
                                <!-- Submit Button -->
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                    <button type="button" class="btn btn-dark btn-sm waves-effect waves-light" wire:click="resetForm">Cancel</button>
                                </div>
                            </form>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="col-lg-12 col-md-6 mb-md-0 my-4">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="row">
                                @if(session()->has('success'))
                                    <div class="alert alert-success" id="flashMessage">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                
                                @if(session()->has('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-7">
                                    <h6>Offers</h6>
                                </div>
                                <div class="col-lg-6 col-5 my-auto text-end">
                                    <div class="d-flex align-items-center">
                                        <input type="text" wire:model.debounce.300ms="search" 
                                               class="form-control border border-2 p-2 custom-input-sm" 
                                               placeholder="Search here...">
                                        <button type="button" wire:click="searchButtonClicked" 
                                                class="btn btn-dark text-white mb-0 custom-input-sm ms-2">
                                            <span class="material-icons">search</span>
                                        </button>
                                        <!-- Refresh Button -->
                                        <button type="button" wire:click="resetSearch" 
                                                class="btn btn-danger text-white mb-0 custom-input-sm ms-2">
                                                <i class="ri-restart-line"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pb-2 mt-2">
                            <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0 offer-list">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Coupon Code</th>
                                        <th>Discount</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($offers as $k=> $item)
                                    @php
                                        $type_class= $item->discount_type==="flat"?"success":"primary";
                                    @endphp
                                    <tr>
                                        <td>{{$k+1}}</td>
                                        <td><strong>{{strtoupper($item->coupon_code )}}</strong></td>
                                        <td>
                                            {{-- Percentage --}}
                                            <div class="d-flex discount-details mt-1">
                                                <table>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center me-3 gap-2">
                                                                <div class="avatar">
                                                                      @if($item->discount_type==="flat")
                                                                      <div class="avatar-initial bg-label-{{$type_class}} rounded">
                                                                          {{env('APP_CURRENCY')}}
                                                                      </div>
                                                                      @else
                                                                      <div class="avatar-initial bg-label-{{$type_class}} rounded">
                                                                          <i class="ri-percent-line"></i>
                                                                      </div>
                                                                      @endif
                                                                </div>
                                                                <div class="grid">
                                                                        <h6 class="badge bg-label-{{$type_class}} mb-0">{{ucwords($item->discount_type)}}</h6>
                                                                  <span>Discount Type</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center gap-2">
                                                                <div class="avatar">
                                                                  @if($item->discount_type==="flat")
                                                                      <div class="avatar-initial bg-label-{{$type_class}} rounded">
                                                                          {{env('APP_CURRENCY')}}
                                                                      </div>
                                                                  @else
                                                                      <div class="avatar-initial bg-label-{{$type_class}} rounded">
                                                                          <i class="ri-percent-line"></i>
                                                                      </div>
                                                                  @endif
                                                                </div>
                                                                <div>
                                                                  @if($item->discount_type==="flat")
                                                                      <h6 class="mb-0">{{env('APP_CURRENCY')}}{{$item->discount_value}}</h6>
                                                                  @else
                                                                      <h6 class="mb-0">{{number_format($item->discount_value, 0)}}%</h6>
                                                                  @endif
                                                                  <span>Discount Value</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <div class="d-flex align-items-center gap-2 mt-3">
                                                                <div class="avatar">
                                                                    <div class="avatar-initial bg-label-{{$type_class}} rounded">
                                                                        {{env('APP_CURRENCY')}}
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                      <h6 class="mb-0">{{env('APP_CURRENCY')}}{{$item->minimum_order_amount}}</h6>
                                                                  <span>Maximum Order Amount</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @if($item->maximum_discount)
                                                        <tr>
                                                            <td colspan="2">
                                                                <div class="d-flex align-items-center gap-2 mt-3">
                                                                    <div class="avatar">
                                                                        <div class="avatar-initial bg-label-{{$type_class}} rounded">
                                                                            {{env('APP_CURRENCY')}}
                                                                        </div>
                                                                    </div>
                                                                    <div>
                                                                        <h6 class="mb-0">{{env('APP_CURRENCY')}}{{$item->maximum_discount}}</h6>
                                                                    <span>Maximum Discount Amount</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    <tr>
                                                        @if($item->usage_limit)
                                                        <td>
                                                            <div class="d-flex align-items-center me-3 mt-3 gap-2">
                                                                <div class="avatar">
                                                                    <div class="avatar-initial bg-label-{{$type_class}} rounded">
                                                                        <i class="ri-group-line"></i>
                                                                      </div>
                                                                </div>
                                                                <div class="grid">
                                                                    <h6 class="mb-0">{{ucwords($item->usage_limit)}}</h6>
                                                                  <span>Usage Limit</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        @endif
                                                        @if($item->usage_per_user)
                                                        <td>
                                                            <div class="d-flex align-items-center gap-2 mt-3">
                                                                <div class="avatar">
                                                                      <div class="avatar-initial bg-label-{{$type_class}} rounded">
                                                                        <i class="ri-user-add-fill"></i>
                                                                      </div>
                                                                </div>
                                                                <div class="grid">
                                                                    <h6 class="mb-0">{{ucwords($item->usage_per_user)}}</h6>
                                                                    <span>Usage Per User</span>
                                                                  </div>
                                                            </div>
                                                        </td>
                                                        @endif
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                        <td>
                                            {{-- Date --}}
                                            <div class="d-flex discount-details mt-1">
                                                <div class="d-flex align-items-center gap-2 mt-3">
                                                    <div class="avatar">
                                                        <div class="avatar-initial bg-label-primary rounded">
                                                            <i class="ri-calendar-schedule-line"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{date('d-m-Y h:i:A', strtotime($item->start_date))}}</h6>
                                                        <span>Start Date</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex discount-details mt-1">
                                                <div class="d-flex align-items-center gap-2 mt-3">
                                                    <div class="avatar">
                                                        <div class="avatar-initial bg-label-primary rounded">
                                                            <i class="ri-calendar-schedule-line"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{date('d-m-Y h:i:A', strtotime($item->end_date))}}</h6>
                                                        <span>End Date</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-icon edit-record btn-text-secondary rounded-pill waves-effect btn-sm" title="Edit" wire:click="editOffer({{$item->id}})">
                                                <i class="ri-edit-box-line ri-20px text-info"></i>
                                            </button>
                                            <button wire:click="DeleteOffer({{$item->id}})" class="btn btn-sm btn-icon delete-record btn-text-secondary rounded-pill waves-effect btn-sm" title="Delete">
                                                <i class="ri-delete-bin-7-line ri-20px text-danger"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5"> 
                                                <div class="alert alert-danger">
                                                    Data not found!
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                    
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="loader-container" wire:loading>
        <div class="loader"></div>
      </div>
</div>
