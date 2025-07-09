
<div>
<div class="row mb-4">
    <div class="col-lg-12 d-flex justify-content-end">
        <a href="#"  class="btn btn-primary">
            <i class="ri-add-line ri-16px me-0 me-sm-2 align-baseline"></i>
            New Order
        </a>
    </div>
    <div class="col-lg-12 mt-2">
        <div class="card mb-6">
            <div class="card-widget-separator-wrapper">
                <div class="card-body card-widget-separator">
                    <div class="row gy-4 gy-sm-1">
                        <div class="col-sm-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-4 pb-sm-0">
                                <div>
                                    <h4 class="mb-0">{{ $data['pending_orders'] }}</h4>
                                    <p class="mb-0">Pending</p>
                                </div>
                                <div class="avatar me-sm-6">
                                    <span class="avatar-initial rounded bg-label-secondary text-heading">
                                        <i class="ri-time-line ri-24px"></i> <!-- Pending icon -->
                                    </span>
                                </div>
                            </div>
                            <hr class="d-none d-sm-block d-lg-none me-6">
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-4 pb-sm-0">
                                <div>
                                    <h4 class="mb-0">{{ $data['ready_to_pickup_orders'] }}</h4>
                                    <p class="mb-0">Ready To Pickup</p>
                                </div>
                                <div class="avatar me-sm-6">
                                    <span class="avatar-initial rounded bg-label-secondary text-heading">
                                        <i class="ri-time-line ri-24px"></i> <!-- Pending icon -->
                                    </span>
                                </div>
                            </div>
                            <hr class="d-none d-sm-block d-lg-none me-6">
                        </div>
                        
                        <div class="col-sm-6 col-lg-2">
                            <div class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-4 pb-sm-0">
                                <div>
                                    <h4 class="mb-0">{{ $data['completed_orders'] }}</h4>
                                    <p class="mb-0">Completed</p>
                                </div>
                                <div class="avatar me-lg-6">
                                    <span class="avatar-initial rounded bg-label-secondary text-heading">
                                        <i class="ri-check-line ri-24px"></i> <!-- Completed icon -->
                                    </span>
                                </div>
                            </div>
                            <hr class="d-none d-sm-block d-lg-none">
                        </div>
                        <div class="col-sm-6 col-lg-2">
                            <div class="d-flex justify-content-between align-items-start border-end pb-4 pb-sm-0 card-widget-3">
                                <div>
                                    <h4 class="mb-0">{{ $data['rented_orders'] }}</h4>
                                    <p class="mb-0">Rented</p>
                                </div>
                                <div class="avatar me-sm-6">
                                    <span class="avatar-initial rounded bg-label-secondary text-heading">
                                        <i class="ri-bike-line ri-24px"></i> <!-- Rented bike icon -->
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-2">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h4 class="mb-0">{{ $data['sell_orders'] }}</h4>
                                    <p class="mb-0">Sell</p>
                                </div>
                                <div class="avatar">
                                    <span class="avatar-initial rounded bg-label-secondary text-heading">
                                        <i class="ri-shopping-cart-line ri-24px"></i> <!-- Sell icon -->
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
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
                            
                            @if(session()->has('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-7">
                                <h6>Orders</h6>
                            </div>
                            <div class="col-lg-6 col-5 my-auto text-end">
                                <div class="d-flex align-items-center">
                                    <input type="text" wire:model.debounce.300ms="search" 
                                           class="form-control border border-2 p-2 custom-input-sm" 
                                           placeholder="Search here...">
                                    <button type="button" 
                                            class="btn btn-dark text-white mb-0 custom-input-sm ms-2">
                                        <span class="material-icons">search</span>
                                    </button>
                                    <!-- Refresh Button -->
                                    <button type="button" 
                                        class="btn btn-danger text-white mb-0 custom-input-sm ms-2">
                                        <i class="ri-restart-line"></i>
                                </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2 mt-2">
                        <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0 product-list">
                            <thead>
                                <tr>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                                        SL No
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                                        Order ID
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                                        Order Type
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle" width="25%">
                                        Customers
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                                        Amount
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">
                                        Payment
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">
                                        Status
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">
                                        Rent Status
                                    </th>
                                    <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orderData as $k => $item)
                                @php
                               
                                    if($item->status==="Ready to pickup"){
                                        $status_color = "warning";
                                    }elseif($item->status==="pending"){
                                        $status_color = "warning";
                                    }elseif($item->status==="completed"){
                                        $status_color = "success";
                                    }elseif($item->status==="cancelled"){
                                        $status_color = "danger";
                                    }elseif($item->status==="returned"){
                                        $status_color = "primary";
                                    }
                                    if($item->rent_status==="Ready to pickup"){
                                        $rent_status_color = "warning";
                                    }elseif($item->rent_status==="pending"){
                                        $rent_status_color = "warning";
                                    }elseif($item->rent_status==="completed"){
                                        $rent_status_color = "success";
                                    }elseif($item->rent_status==="late"){
                                        $rent_status_color = "danger";
                                    }elseif($item->rent_status==="ongoing"){
                                        $rent_status_color = "primary";
                                    }

                                    if($item->payment_status==="pending"){
                                        $status_payment_color = "warning";
                                    }elseif($item->status==="failed"){
                                        $status_payment_color = "danger";
                                    }elseif($item->status==="cancelled"){
                                        $status_payment_color = "secondary";
                                    }
                                   

                                @endphp
                                    <tr>
                                        <td class="align-middle text-center">{{ $k + 1 }}</td>
                                        <td class="align-middle text-center">
                                            <a href="{{route('admin.order.detail', $item->id)}}"><span>#{{$item->order_number}}</span></a>
                                        </td>
                                        <td class="align-middle text-center"> 
                                            <span class="badge bg-label-{{$item->order_type=="Rent"?"danger":"success"}} price-title">{{$item->order_type}}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-start align-items-center customer-name">
                                                <div class="avatar-wrapper me-3">
                                                    <div class="avatar avatar-sm"><img
                                                            src="{{ $item->user->image ? asset($item->user->image) : asset('assets/img/profile-image.webp') }}"
                                                            alt="Avatar" class="rounded-circle"></div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <a href="{{ route('admin.customer.details', $item->user->id) }}"
                                                        class="text-heading"><span class="fw-medium text-truncate">{{ ucwords($item->user->name) }}</span>
                                                    </a>
                                                    <small class="text-truncate">{{ $item->user->email }} | {{ $item->user->mobile }}</small>
                                                <div>
                                            </div>
                                        </td>
                                        
                                        <td class="align-middle text-center">
                                           <span>{{env('APP_CURRENCY')}}{{$item->final_amount}}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <h6 class="mb-0 w-px-100 d-flex align-items-center text-{{$status_payment_color}}"><i class="ri-circle-fill ri-10px me-1"></i>{{ucwords($item->payment_status)}}</h6>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="badge px-2 rounded-pill bg-label-{{$status_color}}" text-capitalized="">{{ucwords($item->status)}}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            @if($item->rent_status)
                                            <span class="badge px-2 rounded-pill bg-label-{{$rent_status_color}}" text-capitalized="">{{ucwords($item->rent_status)}}</span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-end px-4">
                                            <a href="{{route('admin.order.detail', $item->id)}}">
                                                <span class="control"></span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-end mt-2">
                            {{ $orderData->links() }}
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="loader-container" wire:loading>
        <div class="loader"></div>
      </div>
</div>
</div>
