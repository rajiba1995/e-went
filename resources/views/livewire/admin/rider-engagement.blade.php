<div class="row mb-4">
    <style>
        .side-modal {
            position: fixed;
            top: 0;
            right: -400px; /* Initially hidden */
            width: 500px;
            height: 690px;
            background: #fff;
            box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: right 0.3s ease-in-out;
            z-index: 10000;
        }

        .side-modal.open {
            right: 0;
        }

        .side-modal-content {
            display: flex;
            flex-direction: column;
            max-height: -webkit-fill-available;
            overflow-y: auto;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            border: none;
            background: none;
            cursor: pointer;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }
        
        /* 17-03-2025 */
        .side-modal {
            height: 100vh;
        }
        .side-modal-content {
            height: calc(100vh - 110px);
        }
        .table{
            font-size: 12px;
        }
    </style>
    <div class="col-lg-12 justify-content-left">
       <h5 class="mb-0">Rider Management</h5>
       <div>
            <small class="text-dark fw-medium">Riders</small>
            <small class="text-light fw-medium arrow">Engagement</small>
       </div>
    </div>
    <div class="col-lg-12 justify-content-left">
        <div class="row">
            @if(session()->has('message'))
                <div class="alert alert-success" id="flashMessage">
                    {{ session('message') }}
                </div>
            @endif
            @if(session()->has('error'))
                <div class="alert alert-danger" id="flashMessage">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </div>
    
    <div class="col-lg-12 col-md-6 mb-md-0 my-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-2 py-4 px-2">
                    <div class="row justify-content-end">
                        <div class="col-lg-6 col-6 my-auto mb-2">
                            <div class="d-flex align-items-center justify-content-end">
                                <input type="text" wire:model="search" 
                                       class="form-control border border-2 p-2 custom-input-sm" 
                                       placeholder="Search by Rider's Name, Email, or Mobile Number">
                                <button type="button" wire:click="btn_search" 
                                        class="btn btn-primary text-white mb-0 custom-input-sm ms-2">
                                    <span class="material-icons">Search</span>
                                </button>
                                <!-- Refresh Button -->
                                <button type="button" wire:click="reset_search" 
                                        class="btn btn-outline-danger waves-effect mb-0 custom-input-sm ms-2">
                                    <span class="material-icons">Refresh</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-6">
                    <div class="card-header px-0 pt-0">
                      <div class="nav-align-top">
                        <ul class="nav nav-tabs nav-fill" role="tablist">
                          <li class="nav-item" role="presentation" wire:click="tab_change(1)">
                            <button type="button" class="nav-link waves-effect {{$active_tab==1?"active":""}}" role="tab" data-bs-toggle="tab"
                              data-bs-target="#navs-justified-1" aria-controls="navs-justified-1" aria-selected="false"
                              tabindex="-1">
                              <span class="d-none d-sm-block engagement_header">
                                <i class="tf-icons ri-user-3-line me-1_5"></i>
                                </i> All <span
                                  class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-secondary ms-1_5 pt-50">{{ $all_users->total() }}</span>
                                </span>
                                <i class="ri-user-3-line ri-20px d-sm-none"></i>
                          </li>
                          <li class="nav-item" role="presentation" wire:click="tab_change(2)">
                            <button type="button" class="nav-link waves-effect {{$active_tab==2?"active":""}}" role="tab" data-bs-toggle="tab"
                              data-bs-target="#navs-justified-2" aria-controls="navs-justified-2" aria-selected="false"
                              tabindex="-1">
                              <span class="d-none d-sm-block engagement_header">
                                <i class="tf-icons ri-user-3-line me-1_5"></i>
                                </i> Await <span
                                  class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-dark ms-1_5 pt-50">{{$await_users->total()}}</span>
                                </span>
                                <i class="ri-user-3-line ri-20px d-sm-none"></i>
                            </button>
                          </li>
                          {{-- <li class="nav-item" role="presentation" wire:click="tab_change(8)">
                            <button type="button" class="nav-link waves-effect {{$active_tab==8?"active":""}}" role="tab" data-bs-toggle="tab"
                              data-bs-target="#navs-justified-8" aria-controls="navs-justified-8" aria-selected="false"
                              tabindex="-1">
                              <span class="d-none d-sm-block engagement_header">
                                <i class="tf-icons ri-user-3-line me-1_5"></i>
                                </i> Payment Pending <span
                                  class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-dark ms-1_5 pt-50">{{count($pending_orders)}}</span>
                                </span>
                                <i class="ri-user-3-line ri-20px d-sm-none"></i>
                            </button>
                          </li> --}}
                          <li class="nav-item" role="presentation" wire:click="tab_change(3)">
                            <button type="button" class="nav-link waves-effect {{$active_tab==3?"active":""}}" role="tab" data-bs-toggle="tab"
                              data-bs-target="#navs-justified-3" aria-controls="navs-justified-3" aria-selected="true">
                              <span class="d-none d-sm-block engagement_header">
                                <i class="tf-icons ri-user-3-line me-1_5"></i>
                                </i> Ready To Assign <span
                                  class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-success ms-1_5 pt-50">{{$ready_to_assigns->total()}}</span>
                                </span>
                                <i class="ri-user-3-line ri-20px d-sm-none"></i>
                            </button>
                          </li>
                          <li class="nav-item" role="presentation" wire:click="tab_change(4)">
                            <button type="button" class="nav-link waves-effect {{$active_tab==4?"active":""}}" role="tab" data-bs-toggle="tab"
                              data-bs-target="#navs-justified-4" aria-controls="navs-justified-4" aria-selected="true">
                              <span class="d-none d-sm-block engagement_header">
                                <i class="tf-icons ri-user-3-line me-1_5"></i>
                                </i> Active <span
                                  class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-success ms-1_5 pt-50">{{count($active_users)}}</span>
                                </span>
                                <i class="ri-user-3-line ri-20px d-sm-none"></i>
                            </button>
                          </li>
                          <li class="nav-item" role="presentation" wire:click="tab_change(5)">
                            <button type="button" class="nav-link waves-effect {{$active_tab==5?"active":""}}" role="tab" data-bs-toggle="tab"
                              data-bs-target="#navs-justified-5" aria-controls="navs-justified-5" aria-selected="true">
                              <span class="d-none d-sm-block engagement_header">
                                <i class="tf-icons ri-user-3-line me-1_5"></i>
                                </i> Inactive <span
                                  class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-danger ms-1_5 pt-50">{{$inactive_users->total()}}</span>
                                </span>
                                <i class="ri-user-3-line ri-20px d-sm-none"></i>
                            </button>
                          </li>
                          <li class="nav-item" role="presentation" wire:click="tab_change(6)">
                            <button type="button" class="nav-link waves-effect {{$active_tab==6?"active":""}}" role="tab" data-bs-toggle="tab"
                              data-bs-target="#navs-justified-6" aria-controls="navs-justified-6" aria-selected="true">
                              <span class="d-none d-sm-block engagement_header">
                                <i class="tf-icons ri-user-3-line me-1_5"></i>
                                </i> Suspended <span
                                  class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-danger ms-1_5 pt-50">{{$suspended_users->total()}}</span>
                                </span>
                                <i class="ri-user-3-line ri-20px d-sm-none"></i>
                            </button>
                          </li>
                          <li class="nav-item" role="presentation" wire:click="tab_change(7)">
                            <button type="button" class="nav-link waves-effect {{$active_tab==7?"active":""}}" role="tab" data-bs-toggle="tab"
                              data-bs-target="#navs-justified-7" aria-controls="navs-justified-7" aria-selected="true">
                              <span class="d-none d-sm-block engagement_header">
                                <i class="tf-icons ri-user-3-line me-1_5"></i>
                                </i> Cancel Subscription Request <span
                                  class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-danger ms-1_5 pt-50">{{count($cancel_requested_users)}}</span>
                                </span>
                                <i class="ri-user-3-line ri-20px d-sm-none"></i>
                            </button>
                          </li>
                        </ul>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="tab-content p-0">
                        <div class="tab-pane fade {{$active_tab==1?"active show":""}}" id="navs-justified-home" role="tabpanel">
                            
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">SL</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Riders</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Payment Status</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Vehicle Model</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Subscription</th>
                                            <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">DashBoard</th>
                                            <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">Documents</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                        @foreach($all_users as $k => $al_user)
                                        @php
                                            $colors = ['bg-label-primary', 'bg-label-success', 'bg-label-info', 'bg-label-secondary', 'bg-label-danger', 'bg-label-warning'];
                                            $colorClass = $colors[$k % count($colors)]; // Rotate colors based on index
                                        @endphp
                                            <tr>
                                                <td class="align-middle text-center">{{ $k + 1 }}</td>
                                                <td class="sorting_1">
                                                    <div class="d-flex justify-content-start align-items-center customer-name">
                                                        <div class="avatar-wrapper me-3">
                                                            <div class="avatar avatar-sm">
                                                                @if ($al_user->profile_image)
                                                                    <img src="{{ asset($al_user->profile_image) }}" alt="Avatar" class="rounded-circle">
                                                                @else
                                                                    <div class="avatar-initial rounded-circle {{$colorClass}}">
                                                                        {{ strtoupper(substr($al_user->name, 0, 1)) }}{{ strtoupper(substr(strrchr($al_user->name, ' '), 1, 1)) }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <a href="{{ route('admin.customer.details', $al_user->id) }}"
                                                                class="text-heading"><span class="fw-medium text-truncate">{{ ucwords($al_user->name) }}</span>
                                                            </a>
                                                            <small class="text-truncate">{{ $al_user->email }} | {{$al_user->country_code}} {{ $al_user->mobile }}</small>
                                                        <div>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-start">
                                                    
                                                    @if($al_user->ready_to_assign_order)
                                                        @if($al_user->latest_order)
                                                            @if($al_user->latest_order->payment_status=="completed")
                                                                <span class="badge bg-label-success mb-0 cursor-pointer text-uppercase">{{$al_user->latest_order->payment_status}}</span>
                                                            @else
                                                                <span class="badge bg-label-warning mb-0 cursor-pointer text-uppercase">{{$al_user->latest_order->payment_status}}</span>
                                                            @endif
                                                        @else
                                                            <span class="badge bg-label-danger mb-0 cursor-pointer">NOT PAID</span>
                                                        @endif
                                                    @else
                                                        @if($al_user->active_order)
                                                            @if($al_user->latest_order)
                                                                @if($al_user->latest_order->payment_status=="completed")
                                                                    <span class="badge bg-label-success mb-0 cursor-pointer text-uppercase">{{$al_user->latest_order->payment_status}}</span>
                                                                @else
                                                                    <span class="badge bg-label-warning mb-0 cursor-pointer text-uppercase">{{$al_user->latest_order->payment_status}}</span>
                                                                @endif
                                                            @else
                                                                <span class="badge bg-label-danger mb-0 cursor-pointer">NOT PAID</span>
                                                            @endif
                                                        @else
                                                            <span class="badge bg-label-danger mb-0 cursor-pointer">NOT PAID</span>
                                                        @endif
                                                    @endif
                                                        
                                                </td>
                                                <td class="align-middle text-start">
                                                    @if($al_user->ready_to_assign_order)
                                                        {{$al_user->latest_order?$al_user->latest_order->product->title:"N/A"}}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td class="align-middle text-start">
                                                    @if($al_user->active_order && optional($al_user->latest_order)->subscription)
                                                        {{ ucwords($al_user->latest_order->subscription->subscription_type) }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td class="align-middle text-sm text-center">
                                                    <div class="dropdown cursor-pointer">
                                                        <span class="badge px-2 rounded-pill bg-label-secondary dropdown-toggle" id="exploreDropdown_all_{{$al_user->id}}" data-bs-toggle="dropdown" aria-expanded="false">Explore</span>
                                                        <ul class="dropdown-menu" aria-labelledby="exploreDropdown_all_{{$al_user->id}}">
                                                            <li><a class="dropdown-item" href="{{ route('admin.customer.details', $al_user->id) }}">Rider Details</a></li>
                                                            {{-- <li><a class="dropdown-item" href="{{route('admin.payment.user_history', $al_user->id)}}">History</a></li> --}}
                                                        </ul>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-end px-4">
                                                    <button class="btn btn-outline-success waves-effect mb-0 custom-input-sm ms-2"
                                                            wire:click="showCustomerDetails({{ $al_user->id}})">
                                                        View
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                        {{-- @php

                                            $inc_index = count($all_users);
                                            
                                        @endphp
                                        @foreach($inactive_users as $all_inact_user)
                                            @php
                                                $colors = ['bg-label-primary', 'bg-label-success', 'bg-label-info', 'bg-label-secondary', 'bg-label-danger', 'bg-label-warning'];
                                                $colorClass = $colors[$inc_index % count($colors)]; // Rotate colors based on index
                                              
                                            @endphp
                                            <tr>
                                                <td class="align-middle text-center">{{ $inc_index + 1 }}</td>
                                                <td class="sorting_1">
                                                    <div class="d-flex justify-content-start align-items-center customer-name">
                                                        <div class="avatar-wrapper me-3">
                                                            <div class="avatar avatar-sm">
                                                                @if ($all_inact_user->profile_image)
                                                                    <img src="{{ asset($all_inact_user->profile_image) }}" alt="Avatar" class="rounded-circle">
                                                                @else
                                                                    <div class="avatar-initial rounded-circle {{$colorClass}}">
                                                                        {{ strtoupper(substr($all_inact_user->name, 0, 1)) }}{{ strtoupper(substr(strrchr($all_inact_user->name, ' '), 1, 1)) }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <a href="{{ route('admin.customer.details', $all_inact_user->id) }}"
                                                                class="text-heading"><span class="fw-medium text-truncate">{{ ucwords($all_inact_user->name) }}</span>
                                                            </a>
                                                            <small class="text-truncate">{{ $all_inact_user->email }} | {{$all_inact_user->country_code}} {{ $all_inact_user->mobile }}</small>
                                                        <div>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-start">
                                                    @if($all_inact_user->active_order)
                                                        @if($all_inact_user->await_order)
                                                            @if($all_inact_user->await_order->payment_status=="completed")
                                                                <span class="badge bg-label-success mb-0 cursor-pointer text-uppercase">{{$all_inact_user->await_order->payment_status}}</span>
                                                            @else
                                                                <span class="badge bg-label-warning mb-0 cursor-pointer text-uppercase">{{$all_inact_user->await_order->payment_status}}</span>
                                                            @endif
                                                        @else
                                                            <span class="badge bg-label-danger mb-0 cursor-pointer">NOT PAID</span>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-label-danger mb-0 cursor-pointer">NOT PAID</span>
                                                    @endif
                                                </td>
                                                <td class="align-middle text-start">N/A</td>
                                                <td class="align-middle text-start">N/A</td>
                                                <td class="align-middle text-sm text-center">
                                                    <div class="dropdown cursor-pointer">
                                                        <span class="badge px-2 rounded-pill bg-label-secondary dropdown-toggle" id="exploreDropdown_await_{{$all_inact_user->id}}" data-bs-toggle="dropdown" aria-expanded="false">Explore</span>
                                                        <ul class="dropdown-menu" aria-labelledby="exploreDropdown_await_{{$all_inact_user->id}}">
                                                             <li><a class="dropdown-item" href="{{ route('admin.customer.details', $all_inact_user->id) }}">Rider Details</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-end px-4">
                                                    <button class="btn btn-outline-success waves-effect mb-0 custom-input-sm ms-2"
                                                        wire:click="showCustomerDetails({{ $all_inact_user->id}})">
                                                    View
                                                </button>
                                                </td>
                                            </tr>
                                            @php
                                                $inc_index++;
                                            @endphp
                                        @endforeach --}}
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end mt-3 paginator">
                                    {{ $all_users->links() }} <!-- Pagination links -->
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade {{$active_tab==2?"active show":""}}" id="navs-justified-profile" role="tabpanel">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">SL</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Riders</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Payment Status</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Vehicle Model</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Dashboard</th>
                                            <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">Documents</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                        @foreach($await_users as $k => $aw_user)
                                        @php
                                            $colors = ['bg-label-primary', 'bg-label-success', 'bg-label-info', 'bg-label-secondary', 'bg-label-danger', 'bg-label-warning'];
                                            $colorClass = $colors[$k % count($colors)]; // Rotate colors based on index
                                        @endphp
                                            <tr>
                                                <td class="align-middle text-center">{{ $k + 1 }}</td>
                                                <td class="sorting_1">
                                                    <div class="d-flex justify-content-start align-items-center customer-name">
                                                        <div class="avatar-wrapper me-3">
                                                            <div class="avatar avatar-sm">
                                                                @if ($aw_user->image)
                                                                    <img src="{{ asset($aw_user->image) }}" alt="Avatar" class="rounded-circle">
                                                                @else
                                                                    <div class="avatar-initial rounded-circle {{$colorClass}}">
                                                                        {{ strtoupper(substr($aw_user->name, 0, 1)) }}{{ strtoupper(substr(strrchr($aw_user->name, ' '), 1, 1)) }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <a href="{{ route('admin.customer.details', $aw_user->id) }}"
                                                                class="text-heading"><span class="fw-medium text-truncate">{{ ucwords($aw_user->name) }}</span>
                                                            </a>
                                                            <small class="text-truncate">{{ $aw_user->email }} </small>
                                                            {{-- | {{$aw_user->country_code}} {{ $aw_user->mobile }} --}}
                                                        <div>
                                                    </div>
                                                    <td class="align-middle text-start">
                                                        @if($aw_user->await_order)
                                                            @if($aw_user->await_order->payment_status=="completed")
                                                                <span class="badge bg-label-success mb-0 cursor-pointer text-uppercase">{{$aw_user->await_order->payment_status}}</span>
                                                            @else
                                                                <span class="badge bg-label-warning mb-0 cursor-pointer text-uppercase">{{$aw_user->await_order->payment_status}}</span>
                                                            @endif
                                                        @else
                                                            <span class="badge bg-label-danger mb-0 cursor-pointer">NOT PAID</span>
                                                        @endif
                                                    </td>
                                                    <td class="align-middle text-start">{{$aw_user->await_order?$aw_user->await_order->product->title:"N/A"}}</td>
                                                    <td class="align-middle text-sm text-center">
                                                        <div class="dropdown cursor-pointer">
                                                            <span class="badge px-2 rounded-pill bg-label-secondary dropdown-toggle" id="exploreDropdown_await_{{$aw_user->id}}" data-bs-toggle="dropdown" aria-expanded="false">Explore</span>
                                                            <ul class="dropdown-menu" aria-labelledby="exploreDropdown_await_{{$aw_user->id}}">
                                                                <li><a class="dropdown-item" href="{{ route('admin.customer.details', $aw_user->id) }}">Rider Details</a></li>
                                                                {{-- <li><a class="dropdown-item" href="{{route('admin.payment.user_history', $aw_user->id)}}">History</a></li> --}}
                                                            </ul>
                                                        </div>
                                                    </td>
                                                    <td class="align-middle text-end px-4">
                                                        <button class="btn btn-warning text-white mb-0 mx-1 action_btn_padding" wire:click="suspendRiderWarning({{$aw_user->id}})">
                                                            Suspend
                                                        </button>
                                                        <button class="btn btn-outline-success waves-effect mb-0 custom-input-sm ms-2"
                                                            wire:click="showCustomerDetails({{ $aw_user->id}})">
                                                        View
                                                    </button>
                                                    </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end mt-3 paginator">
                                    {{ $await_users->links() }} <!-- Pagination links -->
                                </div>
                            </div>
                        </div>
                        {{-- <div class="tab-pane fade {{$active_tab==8?"active show":""}}" id="navs-justified-profile" role="tabpanel">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">SL</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Riders</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Payment Status</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Vehicle Model</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Dashboard</th>
                                            <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">Documents</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                        @foreach($pending_orders as $k => $pend_order)
                                        @php
                                            $colors = ['bg-label-primary', 'bg-label-success', 'bg-label-info', 'bg-label-secondary', 'bg-label-danger', 'bg-label-warning'];
                                            $colorClass = $colors[$k % count($colors)]; // Rotate colors based on index
                                        @endphp
                                            <tr>
                                                <td class="align-middle text-center">{{ $k + 1 }}</td>
                                                <td class="sorting_1">
                                                    <div class="d-flex justify-content-start align-items-center customer-name">
                                                        <div class="avatar-wrapper me-3">
                                                            <div class="avatar avatar-sm">
                                                                @if ($pend_order->image)
                                                                    <img src="{{ asset($pend_order->image) }}" alt="Avatar" class="rounded-circle">
                                                                @else
                                                                    <div class="avatar-initial rounded-circle {{$colorClass}}">
                                                                        {{ strtoupper(substr($pend_order->name, 0, 1)) }}{{ strtoupper(substr(strrchr($pend_order->name, ' '), 1, 1)) }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <a href="{{ route('admin.customer.details', $pend_order->id) }}"
                                                                class="text-heading"><span class="fw-medium text-truncate">{{ ucwords($pend_order->name) }}</span>
                                                            </a>
                                                            <small class="text-truncate">{{ $pend_order->email }} </small>
                                                        <div>
                                                    </div>
                                                    <td class="align-middle text-start">
                                                        @if($pend_order->pending_order)
                                                            @if($pend_order->pending_order->payment_status=="completed")
                                                                <span class="badge bg-label-success mb-0 cursor-pointer text-uppercase">{{$pend_order->pending_order->payment_status}}</span>
                                                            @else
                                                                <span class="badge bg-label-warning mb-0 cursor-pointer text-uppercase">{{$pend_order->pending_order->payment_status}}</span>
                                                            @endif
                                                        @else
                                                            <span class="badge bg-label-danger mb-0 cursor-pointer">NOT PAID</span>
                                                        @endif
                                                    </td>
                                                    <td class="align-middle text-start">{{$pend_order->pending_order?$pend_order->pending_order->product->title:"N/A"}}</td>
                                                    <td class="align-middle text-sm text-center">
                                                        <div class="dropdown cursor-pointer">
                                                            <span class="badge px-2 rounded-pill bg-label-secondary dropdown-toggle" id="exploreDropdown_await_{{$pend_order->id}}" data-bs-toggle="dropdown" aria-expanded="false">Explore</span>
                                                            <ul class="dropdown-menu" aria-labelledby="exploreDropdown_await_{{$pend_order->id}}">
                                                                <li><a class="dropdown-item" href="{{route('admin.payment.user_history', $pend_order->id)}}">History</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                    <td class="align-middle text-end px-4">
                                                        <button class="btn btn-outline-success waves-effect mb-0 custom-input-sm ms-2"
                                                            wire:click="showCustomerDetails({{ $pend_order->id}})">
                                                        View
                                                    </button>
                                                    </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end mt-3 paginator">
                                    {{ $pending_orders->links() }} <!-- Pagination links -->
                                </div>
                            </div>
                        </div> --}}
                        <div class="tab-pane fade {{$active_tab==3?"active show":""}}" id="navs-justified-messages" role="tabpanel">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">SL</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Riders</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Payment Status</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Vehicle Model</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Subscription</th>
                                            <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">Documents</th>
                                            <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                        @foreach($ready_to_assigns as $k => $rta_user)
                                        @php
                                            $colors = ['bg-label-primary', 'bg-label-success', 'bg-label-info', 'bg-label-secondary', 'bg-label-danger', 'bg-label-warning'];
                                            $colorClass = $colors[$k % count($colors)]; // Rotate colors based on index
                                        @endphp
                                            <tr>
                                                <td class="align-middle text-center">{{ $k + 1 }}</td>
                                                <td class="sorting_1">
                                                    <div class="d-flex justify-content-start align-items-center customer-name">
                                                        <div class="avatar-wrapper me-3">
                                                            <div class="avatar avatar-sm">
                                                                @if ($rta_user->image)
                                                                    <img src="{{ asset($rta_user->image) }}" alt="Avatar" class="rounded-circle">
                                                                @else
                                                                    <div class="avatar-initial rounded-circle {{$colorClass}}">
                                                                        {{ strtoupper(substr($rta_user->name, 0, 1)) }}{{ strtoupper(substr(strrchr($rta_user->name, ' '), 1, 1)) }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <a href="{{ route('admin.customer.details', $rta_user->id) }}"
                                                                class="text-heading"><span class="fw-medium text-truncate">{{ ucwords($rta_user->name) }}</span>
                                                            </a>
                                                            <small class="text-truncate">{{ $rta_user->email }} | {{$rta_user->country_code}} {{ $rta_user->mobile }}</small>
                                                        <div>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-start">
                                                    @if($rta_user->ready_to_assign_order)
                                                        @if($rta_user->ready_to_assign_order->payment_status=="completed")
                                                            <span class="badge bg-label-success mb-0 cursor-pointer text-uppercase">{{$rta_user->ready_to_assign_order->payment_status}}</span>
                                                        @else
                                                            <span class="badge bg-label-warning mb-0 cursor-pointer text-uppercase">{{$rta_user->ready_to_assign_order->payment_status}}</span>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-label-danger mb-0 cursor-pointer">NOT PAID</span>
                                                    @endif
                                                </td>
                                                <td class="align-middle text-start">{{$rta_user->ready_to_assign_order?$rta_user->ready_to_assign_order->product->title:"N/A"}}</td>
                                                <td class="align-middle text-start">
                                                    @if(optional($rta_user->ready_to_assign_order)->subscription)
                                                        {{ ucwords($rta_user->ready_to_assign_order->subscription->subscription_type) }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td class="align-middle text-end px-4">
                                                    <button class="btn btn-outline-success waves-effect mb-0 custom-input-sm ms-2"
                                                            wire:click="showCustomerDetails({{ $rta_user->id}})">
                                                        View
                                                    </button>
                                                </td>
                                                <td class="align-middle text-end px-4">
                                                    <button class="btn btn-success text-white mb-0 custom-input-sm ms-2" wire:click="OpenAssignedForm({{$rta_user->id}},{{ optional($rta_user->ready_to_assign_order)->product->id ?? 'N/A' }},{{$rta_user->ready_to_assign_order->id}})">
                                                        Assign
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end mt-3 paginator">
                                    {{ $ready_to_assigns->links() }} <!-- Pagination links -->
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade {{$active_tab==4?"active show":""}}" id="navs-justified-messages" role="tabpanel">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">SL</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Riders</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Vehicle Info</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Status</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Allocated <br> Date/Time</th>
                                            <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">Expected End <br> Date/Time</th>
                                            <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">Dashboard</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                        @foreach($active_users as $k => $ac_user)
                                        @php
                                            $colors = ['bg-label-primary', 'bg-label-success', 'bg-label-info', 'bg-label-secondary', 'bg-label-danger', 'bg-label-warning'];
                                            $colorClass = $colors[$k % count($colors)]; // Rotate colors based on index
                                        @endphp
                                            <tr>
                                                <td class="align-middle text-center">{{ $k + 1 }}</td>
                                                <td class="sorting_1">
                                                    <div class="d-flex justify-content-start align-items-center customer-name">
                                                        <div class="avatar-wrapper me-3">
                                                            <div class="avatar avatar-sm">
                                                                @if ($ac_user->image)
                                                                    <img src="{{ asset($ac_user->image) }}" alt="Avatar" class="rounded-circle">
                                                                @else
                                                                    <div class="avatar-initial rounded-circle {{$colorClass}}">
                                                                        {{ strtoupper(substr($ac_user->name, 0, 1)) }}{{ strtoupper(substr(strrchr($ac_user->name, ' '), 1, 1)) }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <a href="{{ route('admin.customer.details', $ac_user->id) }}"
                                                                class="text-heading"><span class="fw-medium text-truncate">{{ ucwords($ac_user->name) }}</span>
                                                            </a>
                                                            <small class="text-truncate">{{ $ac_user->email }} | {{$ac_user->country_code}} {{ $ac_user->mobile }}</small>
                                                        <div>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-start">
                                                    @if(optional($ac_user->active_vehicle)->stock && optional($ac_user->active_order)->product)
                                                        {{ ucwords(optional($ac_user->active_vehicle->stock)->vehicle_number) }} <br>
                                                        {{ ucwords(optional($ac_user->active_order->product)->title) }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td class="align-middle text-start">
                                                    @if($ac_user->active_order)
                                                        @if($ac_user->active_order->payment_status=="completed")
                                                            <span class="badge bg-label-success mb-0 cursor-pointer text-uppercase">PAID</span>
                                                        @else
                                                            <span class="badge bg-label-warning mb-0 cursor-pointer text-uppercase">{{$ac_user->active_order->payment_status}}</span>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-label-danger mb-0 cursor-pointer">NOT PAID</span>
                                                    @endif
                                                </td>
                                                <td class="align-middle text-start">
                                                    @if($ac_user->active_order->rent_start_date)
                                                        <small class="text-muted">{{ date('d M y h:i A', strtotime($ac_user->active_order->rent_start_date)) }}</small>
                                                    @else
                                                        ........
                                                    @endif
                                                    
                                                </td>
                                                <td class="align-middle text-start">
                                                    @if($ac_user->active_order->rent_end_date)
                                                        <small class="text-muted">{{ date('d M y h:i A', strtotime($ac_user->active_order->rent_end_date)) }}</small>
                                                    @else
                                                        ........
                                                    @endif
                                                </td>
                                                <td class="align-middle text-sm text-center">
                                                    <div class="dropdown cursor-pointer">
                                                        <span class="badge px-2 rounded-pill bg-label-secondary dropdown-toggle" id="exploreDropdown_active_{{$ac_user->id}}" data-bs-toggle="dropdown" aria-expanded="false">Explore</span>
                                                        <ul class="dropdown-menu" aria-labelledby="exploreDropdown_active_{{$ac_user->id}}">
                                                            <li><a class="dropdown-item" href="{{route('admin.vehicle.detail', optional($ac_user->active_vehicle->stock)->vehicle_track_id)}}">Dashboard</a></li>
                                                            <li><a class="dropdown-item" href="{{ route('admin.customer.details', $ac_user->id) }}">Rider Details</a></li>
                                                            {{-- <li><a class="dropdown-item" href="{{route('admin.payment.user_history', $ac_user->id)}}">History</a></li> --}}
                                                        </ul>
                                                    </div>
                                                 </td>
                                                <td class="align-middle text-end px-4">
                                                    <div class="d-flex">
                                                        @if(optional($ac_user->active_vehicle)->status=='overdue')
                                                            <button class="btn btn-danger text-white mb-0 mx-1 action_btn_padding">
                                                                Overdue
                                                            </button>
                                                        @else
                                                            {{-- @if($ac_user->vehicle_assign_status=="deallocate")
                                                                <button class="btn btn-primary text-white mb-0 mx-1 action_btn_padding" wire:click="updateUserData({{$ac_user->id}})">
                                                                    Reallocate
                                                                </button>
                                                            @endif --}}
                                                            @if($ac_user->vehicle_assign_status==null)
                                                                <button class="btn btn-success text-white mb-0 mx-1 action_btn_padding" wire:click="confirmDeallocate({{$ac_user->active_order->id}})">
                                                                    Deallocate
                                                                </button>
                                                            @endif
                                                            <button class="btn btn-outline-success waves-effect mb-0 mx-1 action_btn_padding"  wire:click="OpenExchangeForm({{$ac_user->id}},{{ optional($ac_user->active_order)->product->id ?? 'N/A' }},{{$ac_user->active_order->id}},'{{ ucwords(optional($ac_user->active_vehicle->stock)->vehicle_number) }}')">
                                                                Exchange
                                                            </button>
                                                        @endif
                                                        
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end mt-3 paginator">
                                    {{ $active_users->links() }} <!-- Pagination links -->
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade {{$active_tab==5?"active show":""}}" id="navs-justified-inactive" role="tabpanel">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">SL</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Riders</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Payment Status</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Vehicle Model</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Dashboard</th>
                                            <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">Documents</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                        @foreach($inactive_users as $k => $inact_user)
                                        @php
                                            $colors = ['bg-label-primary', 'bg-label-success', 'bg-label-info', 'bg-label-secondary', 'bg-label-danger', 'bg-label-warning'];
                                            $colorClass = $colors[$k % count($colors)]; // Rotate colors based on index
                                        @endphp
                                            <tr>
                                                <td class="align-middle text-center">{{ $k + 1 }}</td>
                                                <td class="sorting_1">
                                                    <div class="d-flex justify-content-start align-items-center customer-name">
                                                        <div class="avatar-wrapper me-3">
                                                            <div class="avatar avatar-sm">
                                                                @if ($inact_user->image)
                                                                    <img src="{{ asset($inact_user->image) }}" alt="Avatar" class="rounded-circle">
                                                                @else
                                                                    <div class="avatar-initial rounded-circle {{$colorClass}}">
                                                                        {{ strtoupper(substr($inact_user->name, 0, 1)) }}{{ strtoupper(substr(strrchr($inact_user->name, ' '), 1, 1)) }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <a href="{{ route('admin.customer.details', $inact_user->id) }}"
                                                                class="text-heading"><span class="fw-medium text-truncate">{{ ucwords($inact_user->name) }}</span>
                                                            </a>
                                                            <small class="text-truncate">{{ $inact_user->email }} </small>
                                                            {{-- | {{$inact_user->country_code}} {{ $inact_user->mobile }} --}}
                                                        <div>
                                                    </div>
                                                    <td class="align-middle text-start">
                                                        @if($inact_user->await_order)
                                                            @if($inact_user->await_order->payment_status=="completed")
                                                                <span class="badge bg-label-success mb-0 cursor-pointer text-uppercase">{{$inact_user->await_order->payment_status}}</span>
                                                            @else
                                                                <span class="badge bg-label-warning mb-0 cursor-pointer text-uppercase">{{$inact_user->await_order->payment_status}}</span>
                                                            @endif
                                                        @else
                                                            <span class="badge bg-label-danger mb-0 cursor-pointer">NOT PAID</span>
                                                        @endif
                                                    </td>
                                                    <td class="align-middle text-start">{{$inact_user->await_order?$inact_user->await_order->product->title:"N/A"}}</td>
                                                    <td class="align-middle text-sm text-center">
                                                        <div class="dropdown cursor-pointer">
                                                            <span class="badge px-2 rounded-pill bg-label-secondary dropdown-toggle" id="exploreDropdown_await_{{$inact_user->id}}" data-bs-toggle="dropdown" aria-expanded="false">Explore</span>
                                                            <ul class="dropdown-menu" aria-labelledby="exploreDropdown_await_{{$inact_user->id}}">
                                                                 <li><a class="dropdown-item" href="{{ route('admin.customer.details', $inact_user->id) }}">Rider Details</a></li>
                                                                {{-- <li><a class="dropdown-item" href="{{route('admin.payment.user_history', $inact_user->id)}}">History</a></li> --}}
                                                            </ul>
                                                        </div>
                                                    </td>
                                                    <td class="align-middle text-end px-4">
                                                        <button class="btn btn-outline-success waves-effect mb-0 custom-input-sm ms-2"
                                                            wire:click="showCustomerDetails({{ $inact_user->id}})">
                                                        View
                                                    </button>
                                                    </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end mt-3 paginator">
                                    {{ $inactive_users->links() }} <!-- Pagination links -->
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade {{$active_tab==6?"active show":""}}" id="navs-justified-inactive" role="tabpanel">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">SL</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Riders</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Payment Status</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Vehicle Model</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Dashboard</th>
                                            <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">Documents</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                        @foreach($suspended_users as $k => $susp_user)
                                        @php
                                            $colors = ['bg-label-primary', 'bg-label-success', 'bg-label-info', 'bg-label-secondary', 'bg-label-danger', 'bg-label-warning'];
                                            $colorClass = $colors[$k % count($colors)]; // Rotate colors based on index
                                        @endphp
                                            <tr>
                                                <td class="align-middle text-center">{{ $k + 1 }}</td>
                                                <td class="sorting_1">
                                                    <div class="d-flex justify-content-start align-items-center customer-name">
                                                        <div class="avatar-wrapper me-3">
                                                            <div class="avatar avatar-sm">
                                                                @if ($susp_user->image)
                                                                    <img src="{{ asset($susp_user->image) }}" alt="Avatar" class="rounded-circle">
                                                                @else
                                                                    <div class="avatar-initial rounded-circle {{$colorClass}}">
                                                                        {{ strtoupper(substr($susp_user->name, 0, 1)) }}{{ strtoupper(substr(strrchr($susp_user->name, ' '), 1, 1)) }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <a href="{{ route('admin.customer.details', $susp_user->id) }}"
                                                                class="text-heading"><span class="fw-medium text-truncate">{{ ucwords($susp_user->name) }}</span>
                                                            </a>
                                                            <small class="text-truncate">{{ $susp_user->email }} </small>
                                                            {{-- | {{$susp_user->country_code}} {{ $susp_user->mobile }} --}}
                                                        <div>
                                                    </div>
                                                <td class="align-middle text-start">
                                                    @if($susp_user->await_order)
                                                        @if($susp_user->await_order->payment_status=="completed")
                                                            <span class="badge bg-label-success mb-0 cursor-pointer text-uppercase">{{$susp_user->await_order->payment_status}}</span>
                                                        @else
                                                            <span class="badge bg-label-warning mb-0 cursor-pointer text-uppercase">{{$susp_user->await_order->payment_status}}</span>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-label-danger mb-0 cursor-pointer">NOT PAID</span>
                                                    @endif
                                                </td>
                                                <td class="align-middle text-start">
                                                    {{$susp_user->await_order?$susp_user->await_order->product->title:"N/A"}}
                                                </td>
                                                <td class="align-middle text-sm text-center">
                                                    <div class="dropdown cursor-pointer">
                                                        <span class="badge px-2 rounded-pill bg-label-secondary dropdown-toggle" id="exploreDropdown_await_{{$susp_user->id}}" data-bs-toggle="dropdown" aria-expanded="false">Explore</span>
                                                        <ul class="dropdown-menu" aria-labelledby="exploreDropdown_await_{{$susp_user->id}}">
                                                            <li><a class="dropdown-item" href="{{ route('admin.customer.details', $susp_user->id) }}">Rider Details</a></li>
                                                            {{-- <li><a class="dropdown-item" href="{{route('admin.payment.user_history', $susp_user->id)}}">History</a></li> --}}
                                                        </ul>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-end px-4">
                                                    <button class="btn btn-success text-white mb-0 mx-1" wire:click="activeRiderWarning({{$susp_user->id}})">
                                                        Active
                                                    </button>
                                                    <button class="btn btn-outline-success waves-effect mb-0 custom-input-sm ms-2"
                                                        wire:click="showCustomerDetails({{ $susp_user->id}})">
                                                    View
                                                </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end mt-3 paginator">
                                    {{ $suspended_users->links() }} <!-- Pagination links -->
                                </div>
                            </div>
                        </div>
                         <div class="tab-pane fade {{$active_tab==7?"active show":""}}" id="navs-justified-messages" role="tabpanel">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">SL</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Riders</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Vehicle Info</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Allocated <br> Date/Time</th>
                                            <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">Expected End <br> Date/Time</th>
                                            <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">Requested At</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                        @foreach($cancel_requested_users as $k => $cr_user)
                                        @php
                                            $colors = ['bg-label-primary', 'bg-label-success', 'bg-label-info', 'bg-label-secondary', 'bg-label-danger', 'bg-label-warning'];
                                            $colorClass = $colors[$k % count($colors)]; // Rotate colors based on index
                                        @endphp
                                            <tr>
                                                <td class="align-middle text-center">{{ $k + 1 }}</td>
                                                <td class="sorting_1">
                                                    <div class="d-flex justify-content-start align-items-center customer-name">
                                                        <div class="avatar-wrapper me-3">
                                                            <div class="avatar avatar-sm">
                                                                @if ($cr_user->image)
                                                                    <img src="{{ asset($cr_user->image) }}" alt="Avatar" class="rounded-circle">
                                                                @else
                                                                    <div class="avatar-initial rounded-circle {{$colorClass}}">
                                                                        {{ strtoupper(substr($cr_user->name, 0, 1)) }}{{ strtoupper(substr(strrchr($cr_user->name, ' '), 1, 1)) }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <a href="{{ route('admin.customer.details', $cr_user->id) }}"
                                                                class="text-heading"><span class="fw-medium text-truncate">{{ ucwords($cr_user->name) }}</span>
                                                            </a>
                                                            <small class="text-truncate">{{$cr_user->country_code}} {{ $cr_user->mobile }}</small>
                                                        <div>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-start">
                                                    @if(optional($cr_user->active_vehicle)->stock && optional($cr_user->active_order)->product)
                                                        {{ ucwords(optional($cr_user->active_vehicle->stock)->vehicle_number) }} <br>
                                                        {{ ucwords(optional($cr_user->active_order->product)->title) }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td class="align-middle text-start">
                                                    @if($cr_user->active_order->rent_start_date)
                                                        <small class="text-muted">{{ date('d M y h:i A', strtotime($cr_user->active_order->rent_start_date)) }}</small>
                                                    @else
                                                        ........
                                                    @endif
                                                    
                                                </td>
                                                <td class="align-middle text-start">
                                                    @if($cr_user->active_order->rent_end_date)
                                                        <small class="text-muted">{{ date('d M y h:i A', strtotime($cr_user->active_order->rent_end_date)) }}</small>
                                                    @else
                                                        ........
                                                    @endif
                                                </td>
                                                <td class="align-middle text-sm text-center">
                                                    <small class="text-danger">{{ date('d M y h:i A', strtotime($cr_user->active_order->cancel_request_at)) }}</small>
                                                 </td>
                                                <td class="align-middle text-end px-4">
                                                    <div class="d-flex">
                                                        @if(optional($cr_user->active_vehicle)->status=='overdue')
                                                            <button class="btn btn-danger text-white mb-0 mx-1 action_btn_padding">
                                                                Overdue
                                                            </button>
                                                        @else
                                                            {{-- @if($ac_user->vehicle_assign_status=="deallocate")
                                                                <button class="btn btn-primary text-white mb-0 mx-1 action_btn_padding" wire:click="updateUserData({{$ac_user->id}})">
                                                                    Reallocate
                                                                </button>
                                                            @endif --}}
                                                            @if($cr_user->vehicle_assign_status==null)
                                                                <button class="btn btn-success text-white mb-0 mx-1 action_btn_padding" wire:click="confirmDeallocate({{$cr_user->active_order->id}})">
                                                                    Deallocate
                                                                </button>
                                                            @endif
                                                        @endif
                                                        
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end mt-3 paginator">
                                    {{ $active_users->links() }} <!-- Pagination links -->
                                </div>
                            </div>
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
    <!-- Side Modal (Drawer) -->
    @if($isModalOpen)
    <div class="side-modal {{ $isModalOpen ? 'open' : '' }}">
        @if($selectedCustomer)
            <div class="m-0 lh-1 border-bottom template-customizer-header position-relative py-4">
                <div class="d-flex justify-content-start align-items-center customer-name">
                    <div class="avatar-wrapper me-3">
                        <div class="avatar avatar-sm">
                        @if ($selectedCustomer->image)
                        <img src="{{ asset($selectedCustomer->image) }}" alt="Avatar" class="rounded-circle">
                        @else
                        <div class="avatar-initial rounded-circle {{$colorClass}}">
                            {{ strtoupper(substr($selectedCustomer->name, 0, 1)) }}{{ strtoupper(substr(strrchr($selectedCustomer->name, ' '), 1, 1)) }}
                        </div>
                        @endif
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <a href="javascript:vid(0)" class="text-heading"><span
                            class="fw-medium text-truncate">{{ ucwords($selectedCustomer->name) }}</span>
                        </a>
                        <small class="text-truncate">{{ $selectedCustomer->email }} | {{$selectedCustomer->country_code}}
                        {{ $selectedCustomer->mobile }}</small>
                        <div>
                        </div>
                        <div class="d-flex align-items-center gap-2 position-absolute end-0 top-0 mt-6 me-5">
                        <a href="javascript:void(0)" wire:click="closeModal"
                            class="template-customizer-close-btn fw-light text-body" tabindex="-1">
                            <i class="ri-close-line ri-24px"></i>
                        </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="side-modal-content">
                @if(session()->has('modal_message'))
                    <div class="alert alert-success" id="modalflashMessage">
                        {{ session('modal_message') }}
                    </div>
                @endif
                <div class="nav-align-top">
                    <ul class="nav nav-tabs nav-fill" role="tablist">
                      <li class="nav-item" role="presentation"> 
                        <button type="button" class="nav-link waves-effect modal-nav active" role="tab" data-bs-toggle="tab"
                          data-bs-target="#navs-justified-overview" aria-controls="navs-justified-overview" aria-selected="false"
                          tabindex="-1">
                          <span class="d-none d-sm-block">Overview 
                            </span>
                      </li>
                      <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab"
                          data-bs-target="#navs-justified-history" aria-controls="navs-justified-history" aria-selected="false"
                          tabindex="-1">
                          <span class="d-none d-sm-block">
                            History 
                            </span>
                        </button>
                      </li>
                    </ul>
                </div>
                <div class="tab-content p-0 mt-6">
                    <div class="tab-pane fade active show" id="navs-justified-overview" role="tabpanel">

                        {{-- Driving Licence --}}
                        <div style="border-bottom: 1px solid #8d58ff;" class="mb-3">
                            <div class="d-flex align-items-center mb-3">
                                <!-- Icon -->
                                <div class="avatar me-3" style=" width:1.5rem; height: 1.5rem;">
                                  <div class="avatar-initial rounded 
                                        bg-label-dark document_type">
                                    <i class="ri-roadster-line ri-15px"></i>
                                  </div>
                                </div>
                                <!-- Document Name -->
                                <div>
                                    <span class="fw-medium text-truncate text-dark">Driving Licence</span>
                                </div>
                            </div>
                            @if($selectedCustomer->driving_licence_status>0)
                                <div class="d-flex">
                                <div class="col-6">
                                    <div class="card academy-content shadow-none border mx-2" style="width:150px">
                                        <div class="p-2">
                                        <div class="cursor-pointer">
                                        <img src="{{asset($selectedCustomer->driving_licence_front)}}" alt="" style="max-width: 150px;max-height: 130px; width: 100%;">
                                        </div>
                                        <div class="text-center fw-medium text-truncate">Front</div>
                                        </div>
                                    </div>
                                </div>
                                    <div class="col-6">
                                        <div class="card academy-content shadow-none border mx-2" style="width:150px">
                                            <div class="p-2">
                                            <div class="cursor-pointer">
                                            <img src="{{asset($selectedCustomer->driving_licence_back)}}" alt="" style="max-width: 150px;max-height: 130px; width: 100%;">
                                            </div>
                                            <div class="text-center fw-medium text-truncate">Back</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex my-4">
                                    <div class="col-4 text-center cursor-pointer">
                                        <span class="badge rounded-pill bg-label-secondary" wire:click="OpenPreviewImage('{{asset($selectedCustomer->driving_licence_front)}}','{{asset($selectedCustomer->driving_licence_back)}}','Driving Licence')">Preview</span>
                                    </div>
                                    <div class="col-4 text-center cursor-pointer">
                                        @if($selectedCustomer->driving_licence_status==2)
                                            <span class="badge rounded-pill bg-label-success">
                                                <i class="ri-check-line"></i> Approved
                                            </span>
                                        @else
                                            <span class="badge rounded-pill bg-label-secondary" wire:click="updateLog('2','driving_licence_status','Driving Licence',{{$selectedCustomer->id}})">
                                                 Approve
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-4 text-center cursor-pointer">
                                        @if($selectedCustomer->driving_licence_status==3)
                                            <span class="badge rounded-pill bg-label-danger"><i class="ri-close-line"></i> Rejected</span>
                                        @else
                                            <span class="badge rounded-pill bg-label-secondary" wire:click="OpenRejectForm('driving_licence_status','Driving Licence',{{$selectedCustomer->id}})">Reject</span>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-danger">
                                    Driving licence not uploaded
                                </div>
                            @endif
                        </div>

                        {{-- Aadhar Card --}}
                        <div style="border-bottom: 1px solid #8d58ff;" class="mb-3">
                            @if($selectedCustomer->aadhar_card_status>0)
                                <div class="d-flex align-items-center mb-3">
                                    <!-- Icon -->
                                    <div class="avatar me-3" style=" width:1.5rem; height: 1.5rem;">
                                    <div class="avatar-initial rounded 
                                            bg-label-dark document_type">
                                        <i class="ri-passport-line ri-15px"></i>
                                    </div>
                                    </div>
                                    <!-- Document Name -->
                                    <div>
                                        <span class="fw-medium text-truncate text-dark">Aadhar Card</span>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="col-6">
                                        <div class="card academy-content shadow-none border mx-2" style="width:150px">
                                            <div class="p-2">
                                                <div class="cursor-pointer">
                                                    {{-- <img src="{{asset('storage/uploads/aadhar_card/9397_1747917560.webp')}}" alt="" style="max-width: 150px;max-height: 130px; width: 100%;"> --}}
                                                    {{$selectedCustomer->aadhar_number?$selectedCustomer->aadhar_number:"N/A"}}
                                                </div>
                                                {{-- <div class="text-center fw-medium text-truncate">Front</div> --}}
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="col-6">
                                        <div class="card academy-content shadow-none border mx-2" style="width:150px">
                                            <div class="p-2">
                                                <div class="cursor-pointer">
                                                <img src="{{asset($selectedCustomer->aadhar_card_back)}}" alt="" style="max-width: 150px;max-height: 130px; width: 100%;">
                                                </div>
                                                <div class="text-center fw-medium text-truncate">Back</div>
                                            </div>
                                        </div>
                                    </div> --}}
                                </div>
                                <div class="d-flex my-4">
                                    {{-- <div class="col-4 text-center cursor-pointer">
                                        <span class="badge rounded-pill bg-label-secondary" wire:click="OpenPreviewImage('{{asset($selectedCustomer->aadhar_card_front)}}','{{asset($selectedCustomer->aadhar_card_back)}}','Aadhar Card')"> Preview</span>
                                    </div> --}}
                                    <div class="col-12 text-center cursor-pointer">
                                        @if($selectedCustomer->aadhar_card_status==2)
                                            <span class="badge rounded-pill bg-label-success">
                                                <i class="ri-check-line"></i> Approved
                                            </span>
                                        @else
                                            <span class="badge rounded-pill bg-label-warning">
                                                 Pending
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-6 text-center cursor-pointer">
                                        @if($selectedCustomer->aadhar_card_status==2)
                                            <a href="{{route('digilocker.aadhar.download',$selectedCustomer->id)}}" target="_blank">
                                                <span class="badge rounded-pill bg-label-success">
                                                    <i class="ri-check-line"></i> Download PDF
                                                </span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-danger">
                                    Aadhar card not verified.
                                </div>
                            @endif
                        </div>

                        {{-- Pan Card --}}
                        <div style="border-bottom: 1px solid #8d58ff;" class="mb-3">
                            @if($selectedCustomer->pan_card_status>0)
                                <div class="d-flex align-items-center mb-3">
                                    <!-- Icon -->
                                    <div class="avatar me-3" style=" width:1.5rem; height: 1.5rem;">
                                    <div class="avatar-initial rounded 
                                            bg-label-dark document_type">
                                        <i class="ri-passport-line ri-15px"></i>
                                    </div>
                                    </div>
                                    <!-- Document Name -->
                                    <div>
                                        <span class="fw-medium text-truncate text-dark">Pan Card</span>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="col-6">
                                        <div class="card academy-content shadow-none border mx-2" style="width:150px">
                                            <div class="p-2">
                                            <div class="cursor-pointer">
                                            <img src="{{asset($selectedCustomer->pan_card_front)}}" alt="" style="max-width: 150px;max-height: 130px; width: 100%;">
                                            </div>
                                            <div class="text-center fw-medium text-truncate">Front</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card academy-content shadow-none border mx-2" style="width:150px">
                                            <div class="p-2">
                                                <div class="cursor-pointer">
                                                <img src="{{asset($selectedCustomer->pan_card_back)}}" alt="" style="max-width: 150px;max-height: 130px; width: 100%;">
                                                </div>
                                                <div class="text-center fw-medium text-truncate">Back</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex my-4">
                                    <div class="col-4 text-center cursor-pointer">
                                        <span class="badge rounded-pill bg-label-secondary" wire:click="OpenPreviewImage('{{asset($selectedCustomer->pan_card_front)}}','{{asset($selectedCustomer->pan_card_back)}}','Pan Card')"> Preview</span>
                                    </div>
                                    <div class="col-4 text-center cursor-pointer">
                                        @if($selectedCustomer->pan_card_status==2)
                                            <span class="badge rounded-pill bg-label-success">
                                                <i class="ri-check-line"></i> Approved
                                            </span>
                                        @else
                                            <span class="badge rounded-pill bg-label-secondary" wire:click="updateLog('2','pan_card_status','Pan Card',{{$selectedCustomer->id}})">
                                                 Approve
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-4 text-center cursor-pointer">
                                        @if($selectedCustomer->pan_card_status==3)
                                            <span class="badge rounded-pill bg-label-danger"><i class="ri-close-line"></i> Rejected</span>
                                        @else
                                            <span class="badge rounded-pill bg-label-secondary" wire:click="OpenRejectForm('pan_card_status','Pan Card',{{$selectedCustomer->id}})">Reject</span>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-danger">
                                    Pan card not uploaded
                                </div>
                            @endif
                        </div>

                        {{-- Address Proff --}}
                        <div style="border-bottom: 1px solid #8d58ff;" class="mb-3">
                            @if($selectedCustomer->current_address_proof_status>0)
                                <div class="d-flex align-items-center mb-3">
                                <!-- Icon -->
                                 <!-- Icon -->
                                <div class="avatar me-3" style=" width:1.5rem; height: 1.5rem;">
                                    <div class="avatar-initial rounded 
                                            bg-label-dark document_type">
                                    <i class="ri-bank-line ri-15px"></i>
                                    </div>
                                </div>
                                <!-- Document Name -->
                                <div>
                                    <span class="fw-medium text-truncate text-dark">Current Address Proof</span>
                                </div>
                                </div>
                                <div class="d-flex">
                                <div class="col-6">
                                    <div class="card academy-content shadow-none border mx-2" style="width:150px">
                                    <div class="p-2">
                                        <div class="cursor-pointer">
                                        <img src="{{asset($selectedCustomer->current_address_proof_front)}}" alt=""
                                            style="max-width: 150px;max-height: 130px; width: 100%;">
                                        </div>
                                        <div class="text-center fw-medium text-truncate">Front</div>
                                    </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card academy-content shadow-none border mx-2" style="width:150px">
                                    <div class="p-2">
                                        <div class="cursor-pointer">
                                        <img src="{{asset($selectedCustomer->current_address_proof_back)}}" alt=""
                                            style="max-width: 150px;max-height: 130px; width: 100%;">
                                        </div>
                                        <div class="text-center fw-medium text-truncate">Back</div>
                                    </div>
                                    </div>
                                </div>
                                </div>
                                <div class="d-flex my-4">
                                <div class="col-4 text-center cursor-pointer">
                                    <span class="badge rounded-pill bg-label-secondary" wire:click="OpenPreviewImage('{{asset($selectedCustomer->current_address_proof_front)}}','{{asset($selectedCustomer->current_address_proof_back)}}','Current Address Proof')"> Preview</span>
                                </div>
                                <div class="col-4 text-center cursor-pointer">
                                    @if($selectedCustomer->current_address_proof_status==2)
                                    <span class="badge rounded-pill bg-label-success">
                                    <i class="ri-check-line"></i> Approved
                                    </span>
                                    @else
                                    <span class="badge rounded-pill bg-label-secondary"
                                    wire:click="updateLog('2','current_address_proof_status','Current Address Proof',{{$selectedCustomer->id}})">
                                    Approve
                                    </span>
                                    @endif
                                </div>
                                <div class="col-4 text-center cursor-pointer">
                                    @if($selectedCustomer->current_address_proof_status==3)
                                    <span class="badge rounded-pill bg-label-danger"><i class="ri-close-line"></i>
                                    Rejected</span>
                                    @else
                                    <span class="badge rounded-pill bg-label-secondary"
                                    wire:click="OpenRejectForm('current_address_proof_status','Current Address Proof',{{$selectedCustomer->id}})">Reject</span>
                                    @endif
                                </div>
                                </div>
                            @else
                                <div class="alert alert-danger">
                                    Current address proof not uploaded
                                </div>
                            @endif
                        </div>

                        {{-- Passbook --}}
                        <div style="border-bottom: 1px solid #8d58ff;" class="mb-3">
                            @if($selectedCustomer->passbook_status>0)
                                <div class="d-flex align-items-center mb-3">
                                <div class="avatar me-3" style=" width:1.5rem; height: 1.5rem;">
                                        <div class="avatar-initial rounded 
                                                bg-label-dark document_type">
                                        <i class="ri-home-line ri-15px"></i>
                                        </div>
                                    </div>
                                <!-- Document Name -->
                                <div>
                                    <span class="fw-medium text-truncate text-dark">Passbook/Cancelled Cheque</span>
                                </div>
                                </div>
                                <div class="d-flex">
                                    <div class="col-12">
                                        <div class="card academy-content shadow-none border mx-2" style="width:150px">
                                        <div class="p-2">
                                            <div class="cursor-pointer">
                                            <img src="{{asset($selectedCustomer->passbook_front)}}" alt=""
                                                style="max-width: 150px;max-height: 130px; width: 100%;">
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex my-4">
                                <div class="col-4 text-center cursor-pointer">
                                    <span class="badge rounded-pill bg-label-secondary" wire:click="OpenPreviewImage('{{asset($selectedCustomer->passbook_front)}}','','Passbook')"> Preview</span>
                                </div>
                                <div class="col-4 text-center cursor-pointer">
                                    @if($selectedCustomer->passbook_status==2)
                                    <span class="badge rounded-pill bg-label-success">
                                    <i class="ri-check-line"></i> Approved
                                    </span>
                                    @else
                                    <span class="badge rounded-pill bg-label-secondary"
                                    wire:click="updateLog('2','passbook_status','Passbook',{{$selectedCustomer->id}})">
                                    Approve
                                    </span>
                                    @endif
                                </div>
                                <div class="col-4 text-center cursor-pointer">
                                    @if($selectedCustomer->passbook_status==3)
                                    <span class="badge rounded-pill bg-label-danger"><i class="ri-close-line"></i>
                                    Rejected</span>
                                    @else
                                    <span class="badge rounded-pill bg-label-secondary"
                                    wire:click="OpenRejectForm('passbook_status','Passbook',{{$selectedCustomer->id}})">Reject</span>
                                    @endif
                                </div>
                                </div>
                            @else
                                <div class="alert alert-danger">
                                    Passbook/Cancelled cheque not uploaded
                                </div>
                            @endif
                        </div>

                        {{-- Rider Profile --}}
                        <div style="border-bottom: 1px solid #8d58ff;" class="mb-3">
                            @if($selectedCustomer->profile_image_status>0)
                                <div class="d-flex align-items-center mb-3">
                                <!-- Icon -->
                                <div class="avatar me-3" style=" width:1.5rem; height: 1.5rem;">
                                    <div class="avatar-initial rounded 
                                            bg-label-dark document_type">
                                     <i class="ri-user-line ri-16px text-dark"></i>
                                    </div>
                                </div>
                                <!-- Document Name -->
                                <div>
                                    <span class="fw-medium text-truncate text-dark">Rider Profle Image</span>
                                </div>
                                </div>
                                <div class="d-flex">
                                    <div class="col-12">
                                        <div class="card academy-content shadow-none border mx-2" style="width:150px">
                                            <div class="p-2">
                                                <div class="cursor-pointer">
                                                <img src="{{asset($selectedCustomer->profile_image)}}" alt=""
                                                    style="max-width: 150px;max-height: 130px; width: 100%;">
                                                </div>
                                                {{-- <div class="text-center fw-medium text-truncate">Front</div> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex my-4">
                                <div class="col-4 text-center cursor-pointer">
                                    <span class="badge rounded-pill bg-label-secondary" wire:click="OpenPreviewImage('{{asset($selectedCustomer->profile_image)}}','','Profile Image')"> Preview</span>
                                </div>
                                <div class="col-4 text-center cursor-pointer">
                                    @if($selectedCustomer->profile_image_status==2)
                                    <span class="badge rounded-pill bg-label-success">
                                    <i class="ri-check-line"></i> Approved
                                    </span>
                                    @else
                                    <span class="badge rounded-pill bg-label-secondary"
                                    wire:click="updateLog('2','profile_image_status','Profile Image',{{$selectedCustomer->id}})">
                                    Approve
                                    </span>
                                    @endif
                                </div>
                                <div class="col-4 text-center cursor-pointer">
                                    @if($selectedCustomer->profile_image_status==3)
                                    <span class="badge rounded-pill bg-label-danger"><i class="ri-close-line"></i>
                                    Rejected</span>
                                    @else
                                    <span class="badge rounded-pill bg-label-secondary"
                                    wire:click="OpenRejectForm('profile_image_status','Profile Image',{{$selectedCustomer->id}})">Reject</span>
                                    @endif
                                </div>
                                </div>
                            @else
                                <div class="alert alert-danger">
                                    Profile Image not uploaded
                                </div>
                            @endif
                        </div>

                        <div class="text-center">
                            @if($selectedCustomer->is_verified=="verified")
                            <button type="button" class="btn btn-success text-white mb-0 custom-input-sm ms-2">
                                KYC VERIFIED
                            </button>
                            @endif
                            @if($selectedCustomer->is_verified=="unverified")
                                <button type="button" class="btn btn-warning text-white mb-0 custom-input-sm ms-2">
                                   KYC UNVERIFIED
                                </button>
                            @endif
                            @if($selectedCustomer->is_verified=="rejected")
                                <button type="button" class="btn btn-danger text-white mb-0 custom-input-sm ms-2">
                                    KYC REJECTED
                                </button>
                            @endif
                        </div>
                        @if(session()->has('error_kyc_message'))
                            <div class="alert alert-danger">
                                {{ session('error_kyc_message') }}
                            </div>
                        @endif
                        <div style="margin-bottom: 20px;" class="text-start text-uppercase">
                                <label for="startDate" class="form-label small mb-1">Update KYC Status</label>
                            <select
                                class="form-select border border-2 p-2 custom-input-sm" wire:model="model" wire:change="VerifyKyc($event.target.value, {{$selectedCustomer->id}})">
                                <option value="" selected hidden>Select one</option>
                                <option value="verified" {{$selectedCustomer->is_verified=="verified"?"selected":""}}>KYC Verified</option>
                                <option value="unverified" {{$selectedCustomer->is_verified=="unverified"?"selected":""}}>KYC Unverified</option>
                                <option value="rejected" {{$selectedCustomer->is_verified=="rejected"?"selected":""}}>KYC Rejected</option>
                            </select>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="navs-justified-history" role="tabpanel">
                        <ul class="timeline pb-0 mb-0">
                            @if(count($selectedCustomer->doc_logs)>0)
                                @foreach ($selectedCustomer->doc_logs->sortByDesc('id') as $logs)
                                <li class="timeline-item timeline-item-transparent border-primary">
                                    <span class="timeline-point timeline-point-primary"></span>
                                    <div class="timeline-event">
                                        <div class="timeline-header mb-1">
                                        <h6 class="mb-0">{{ucwords($logs->document_type)}} | {{ucwords($logs->status)}}</h6>
                                        <small class="text-muted">{{ date('d M y h:i A', strtotime($logs->created_at)) }}</small>
                                        </div>
                                        @if($logs->remarks)
                                            <code>Remarks</code>
                                            <p class="mt-1 mb-3"><small>{{$logs->remarks}}</small></p>
                                        @endif
                                    </div>
                                    </li>
                                @endforeach
                            @else
                                <div class="alert alert-danger">
                                   Sorry! data not found!
                                </div>
                            @endif
                          </ul>
                    </div>
                </div>
            </div>
        @endif
    </div>
    @endif
    <!-- Overlay -->
    @if($isModalOpen)
        <div class="overlay" wire:click="closeModal"></div>
    @endif

    @if ($isRejectModal)
        <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background: rgba(0, 0, 0, 0.5);z-index: 99999;">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $document_type }}</h5>
                        <button type="button" class="btn-close" wire:click="closeRejectModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Remark</label>
                            <textarea class="form-control" wire:model="remarks"></textarea>
                            @if(session()->has('remarks'))
                            <div class="alert alert-danger">
                                {{ session('remarks') }}
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" wire:click="updateLog('3','{{$field}}','{{$document_type}}',{{$id}})">Reject</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if ($isAssignedModal)
        <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background: rgba(0, 0, 0, 0.5);z-index: 99999;">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Assign Vehicle</h5>
                        <button type="button" class="btn-close" wire:click="closeAssignedModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Vehicle Model</label>
                            <div>
                                <select class="form-control border border-2 p-2" wire:model="vehicle_model">
                                    <option value="" selected hidden>Select vehicle</option>
                                    @foreach ($vehicles as $vehicle_index=>$vehicle_item)
                                        <option value="{{$vehicle_item->id}}">{{$vehicle_item->vehicle_number}} | {{ optional($vehicle_item->product)->title ?? 'N/A' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-2">
                                @if(session()->has('assign_error'))
                                <div class="alert alert-danger">
                                    {{ session('assign_error') }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" wire:click="updateAssignRider()">Assign</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if ($isExchangeModal)
        <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background: rgba(0, 0, 0, 0.5);z-index: 99999;">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Exchange Vehicle</h5>
                        <button type="button" class="btn-close" wire:click="closeExchangeModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Vehicle Model</label>
                            <div>
                                <select class="form-control border border-2 p-2" wire:model="vehicle_model">
                                    <option value="" selected hidden>Select vehicle</option>
                                    @foreach ($vehicles as $vehicle_index=>$vehicle_item)
                                        <option value="{{$vehicle_item->id}}">{{$vehicle_item->vehicle_number}} | {{ optional($vehicle_item->product)->title ?? 'N/A' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-2">
                                @if(session()->has('exchange_error'))
                                <div class="alert alert-danger">
                                    {{ session('exchange_error') }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" wire:click="updateExchangeModel()">Exchange Model</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if ($isPreviewimageModal)
        <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background: rgba(0, 0, 0, 0.5);z-index: 99999;">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $document_type }}</h5>
                        <button type="button" class="btn-close" wire:click="closePreviewImage"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <div class="card academy-content shadow-none border mx-2">
                                <div class="p-2">
                                    <div class="cursor-pointer">
                                        <img src="{{$preview_front_image}}" alt="" width="100%">
                                    </div>
                                </div>
                            </div>
                            <div class="card academy-content shadow-none border mx-2 my-2">
                                <div class="p-2">
                                    <div class="cursor-pointer">
                                        <img src="{{$preview_back_image}}" alt="" width="100%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
@section('page-script')
<script>
    setTimeout(() => {
        const flashMessage = document.getElementById('modalflashMessage');
        if (flashMessage) flashMessage.remove();
    }, 3000); // Auto-hide flash message after 3 seconds
    setTimeout(() => {
        const flashMessage = document.getElementById('flashMessage');
        if (flashMessage) flashMessage.remove();
    }, 3000); // Auto-hide flash message after 3 seconds
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
         window.addEventListener('showConfirm', function (event) {
            let itemId = event.detail[0].itemId;
            Swal.fire({
                title: "Deallocate Vehicle?",
                text: "Are you sure you want to deallocate the vehicle?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, deallocate it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('updateUserData', itemId); // Livewire method
                    // Swal.fire("Deallocated!", "The vehicle has been deallocated for this user.", "success");
                }
            });
        });
        window.addEventListener('showWarningConfirm', function (event) {
            let itemId = event.detail[0].itemId;
            // Warning confirmation dialog for suspending a rider
            Swal.fire({
                title: "Suspend Rider?",
                text: "Are you sure you want to suspend this rider? This action can be reversed later.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Suspend"
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('suspendRider', itemId); // Livewire method
                }
            });
        });
        window.addEventListener('showactiveRiderWarning', function (event) {
            let itemId = event.detail[0].itemId;
            // Warning confirmation dialog for suspending a rider
            Swal.fire({
                title: "Active Rider?",
                text: "Are you sure you want to active this rider?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Active"
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('activeRider', itemId); // Livewire method
                }
            });
        });
    </script>
@endsection
