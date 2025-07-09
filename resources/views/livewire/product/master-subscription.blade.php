<div class="row mb-4">
    <div class="col-lg-12 d-flex justify-content-between">
        <div>
            <h5 class="mb-0">Model Management</h5>
            <div>
                 <small class="text-dark fw-medium">Dashboard</small>
                 <small class="text-light fw-medium arrow">Subscriptions</small>
            </div>
         </div>
    </div>
    <div class="col-lg-8 col-md-6 mb-md-0 mb-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            @if(session()->has('message'))
                                <div class="alert alert-success" id="flashMessage">
                                    {{ session('message') }}
                                </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-7">
                                {{-- <h6>All Subscription Plans</h6> --}}
                            </div>
                            <div class="col-lg-6 col-5 my-auto text-end">
                                <div class="form-floating form-floating-outline mb-5 fv-plugins-icon-container mt-4">
                                    <select wire:model="asset" class="form-control border border-2 p-2" wire:change="filter($event.target.value)">
                                        <option value="" selected>Select asset</option>
                                        @foreach($models as $item)
                                            <option value="{{ $item->id }}">{{$item->category?$item->category->title.' | ':""}}{{ $item->title }}</option>
                                        @endforeach
                                    </select>
                                    <label>Assets </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                                            SL</th>
                                        <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                                            Type</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                                            Validity In Days</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                                            Deposit Amount</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                                            Rental Amount</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subscriptions as $k => $sub_item)
                                        <tr>
                                            <td class="align-middle text-center">{{ $k + 1 }}</td>
                                            <td class="align-middle text-left">
                                                <div class="d-flex flex-column">
                                                    <a href="javascript:void(0)" class="text-heading"><span
                                                            class="fw-medium text-truncate">{{ ucwords($sub_item->subscription_type) }}</span>
                                                    </a>
                                                    <small class="text-truncate">{{optional($sub_item->product)->title}}</small>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">{{ $sub_item->duration }} Days</td>
                                            <td class="align-middle text-center">{{env('APP_CURRENCY')}}{{ $sub_item->deposit_amount }} </td>
                                            <td class="align-middle text-center">{{env('APP_CURRENCY')}}{{ $sub_item->rental_amount }} </td>
                                           
                                          
                                            <td class="align-middle text-end px-4">
                                                <div class="form-check form-switch">
                                                    <input 
                                                        class="form-check-input ms-auto" 
                                                        type="checkbox" 
                                                        id="flexSwitchCheckDefault{{ $sub_item->id }}" 
                                                        wire:click="toggleStatus({{ $sub_item->id }})"
                                                        @if($sub_item->status) checked @endif>
                                                </div>
                                                <button wire:click="edit({{ $sub_item->id }})" class="btn btn-sm btn-icon edit-record btn-text-secondary rounded-pill waves-effect btn-sm" title="Edit"><i class="ri-edit-box-line ri-20px text-info"></i></button>
                                                @php
                                                    $orders = App\Models\Order::where('subscription_id', $sub_item->id)->get()->count();
                                                @endphp
                                                @if($orders==0)
                                                <button wire:click="destroy({{ $sub_item->id }})" class="btn btn-sm btn-icon delete-record btn-text-secondary rounded-pill waves-effect btn-sm" title="Delete"> <i class="ri-delete-bin-7-line ri-20px text-danger"></i> </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-md-0 mb-4">
        <div class="row">
            <div class="col-12">             
                <div class="card my-4">       
                    <div class="card-body px-0 pb-2 mx-4">
                            <div class="d-flex justify-content-between mb-3">
                                <h5>{{ $subscriptionId ? 'Update' : 'New' }} Subscription</h5>  
                            </div>
                         <form wire:submit.prevent="{{ $subscriptionId ? 'update' : 'store' }}">
                            <div class="form-floating form-floating-outline mb-5 fv-plugins-icon-container mt-4">
                                <select wire:model="subscription_type" class="form-control border border-2 p-2" 
                                        wire:change="GetDuration($event.target.options[$event.target.selectedIndex].dataset.duration)">
                                    <option value="">Type</option>
                                    <option value="daily" data-duration="1">Daily</option>
                                    <option value="weekly" data-duration="7">Weekly</option>
                                    <option value="monthly" data-duration="30">Monthly</option>
                                    <option value="quarterly" data-duration="90">Quarterly</option> <!-- Approx. 90 days -->
                                    <option value="yearly" data-duration="365">Yearly</option> <!-- 365 days (non-leap year) -->
                                </select>
                                <label>Subscription Type </label>
                                @error('subscription_type') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-floating form-floating-outline mb-5 fv-plugins-icon-container mt-4">
                                <select wire:model="model" class="form-control border border-2 p-2">
                                    <option value="" selected hidden>Select model</option>
                                    @foreach($models as $item)
                                        <option value="{{ $item->id }}">{{$item->category?$item->category->title.' | ':""}}{{ $item->title }}</option>
                                    @endforeach
                                </select>
                                <label>Model </label>
                                @error('model') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-floating form-floating-outline mb-5 fv-plugins-icon-container mt-4">
                                <input type="number" wire:model="deposit_amount" class="form-control border border-2 p-2" placeholder="{{env('APP_CURRENCY')}}1000.00">
                                <label>Deposit Amount</label>
                                @error('deposit_amount') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-floating form-floating-outline mb-5 fv-plugins-icon-container mt-4">
                                <input type="number" wire:model="rental_amount" class="form-control border border-2 p-2" placeholder="{{env('APP_CURRENCY')}}100.00">
                                <label>Rental Amount</label>
                                @error('rental_amount') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="text-end">
                                <button type="button" wire:click="refresh" 
                                        class="btn btn-danger text-white mb-0 custom-input-sm ms-2 btn-sm">
                                        <i class="ri-restart-line"></i>
                                </button>
                                <button type="submit" class="btn btn-secondary btn-sm add-new btn-primary waves-effect waves-light">
                                    {{ $subscriptionId ? 'Update Subscription' : 'Create Subscription' }}
                                </button>
                            </div>
                         </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="loader-container" wire:loading>
        <div class="loader"></div>
    </div>
</div>

