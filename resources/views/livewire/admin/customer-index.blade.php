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
    </style>
    <div class="col-lg-12 justify-content-left">
       <h5 class="mb-0">Rider Management</h5>
       <div>
            <small class="text-dark fw-medium">Riders</small>
            <small class="text-light fw-medium arrow">Verification</small>
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
                              data-bs-target="#navs-justified-home" aria-controls="navs-justified-home" aria-selected="false"
                              tabindex="-1">
                              <span class="d-none d-sm-block">
                                <i class="tf-icons ri-user-3-line me-1_5"></i>
                                </i> Unverified <span
                                  class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-secondary ms-1_5 pt-50">{{count($unverified_users)}}</span>
                                </span>
                                <i class="ri-user-3-line ri-20px d-sm-none"></i>
                          </li>
                          <li class="nav-item" role="presentation" wire:click="tab_change(2)">
                            <button type="button" class="nav-link waves-effect {{$active_tab==2?"active":""}}" role="tab" data-bs-toggle="tab"
                              data-bs-target="#navs-justified-profile" aria-controls="navs-justified-profile" aria-selected="false"
                              tabindex="-1">
                              <span class="d-none d-sm-block">
                                <i class="tf-icons ri-user-3-line me-1_5"></i>
                                </i> Verified <span
                                  class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-success ms-1_5 pt-50">{{count($verified_users)}}</span>
                                </span>
                                <i class="ri-user-3-line ri-20px d-sm-none"></i>
                            </button>
                          </li>
                          <li class="nav-item" role="presentation" wire:click="tab_change(3)">
                            <button type="button" class="nav-link waves-effect {{$active_tab==3?"active":""}}" role="tab" data-bs-toggle="tab"
                              data-bs-target="#navs-justified-messages" aria-controls="navs-justified-messages" aria-selected="true">
                              <span class="d-none d-sm-block">
                                <i class="tf-icons ri-user-3-line me-1_5"></i>
                                </i> Rejected <span
                                  class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-danger ms-1_5 pt-50">{{count($rejected_users)}}</span>
                                </span>
                                <i class="ri-user-3-line ri-20px d-sm-none"></i>
                            </button>
                          </li>
                          {{-- <span class="tab-slider" style="left: 681.312px; width: 354.688px; bottom: 0px;"></span> --}}
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
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Rider ID</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">KYC Uploaded Date/Time</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Status</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Dashboard</th>
                                            <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">Documents</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                        @foreach($unverified_users as $k => $un_user)
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
                                                                @if ($un_user->profile_image)
                                                                    <img src="{{ asset($un_user->profile_image) }}" alt="Avatar" class="rounded-circle">
                                                                @else
                                                                    <div class="avatar-initial rounded-circle {{$colorClass}}">
                                                                        {{ strtoupper(substr($un_user->name, 0, 1)) }}{{ strtoupper(substr(strrchr($un_user->name, ' '), 1, 1)) }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <a href="{{ route('admin.customer.details', $un_user->id) }}"
                                                                class="text-heading"><span class="fw-medium text-truncate">{{ ucwords($un_user->name) }}</span>
                                                            </a>
                                                            <small class="text-truncate">{{ $un_user->email }} | {{$un_user->country_code}} {{ $un_user->mobile }}</small>
                                                        <div>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-start">{{$un_user->customer_id?$un_user->customer_id:"...."}}</td>
                                                <td class="align-middle text-start">
                                                    @php
                                                        $kyc_data = App\Models\UserKycLog::where('user_id', $un_user->id)->orderBy('id', 'ASC')->first();
                                                    @endphp
                                                    {{ $kyc_data?date('d M y h:i A', strtotime($kyc_data->created_at)):"N/A" }}
                                                </td>
                                                <td class="align-middle text-sm text-center">
                                                    <div class="form-check form-switch">
                                                        <input 
                                                            class="form-check-input ms-auto" 
                                                            type="checkbox" 
                                                            id="flexSwitchCheckDefault{{ $un_user->id }}" 
                                                            wire:click="toggleStatus({{ $un_user->id }})"
                                                            @if($un_user->status) checked @endif>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-sm text-center">
                                                    <div class="dropdown cursor-pointer">
                                                        <span class="badge px-2 rounded-pill bg-label-secondary dropdown-toggle" id="exploreDropdown_await_{{$un_user->id}}" data-bs-toggle="dropdown" aria-expanded="false">Explore</span>
                                                        <ul class="dropdown-menu" aria-labelledby="exploreDropdown_await_{{$un_user->id}}">
                                                             <li><a class="dropdown-item" href="{{ route('admin.customer.details', $un_user->id) }}">Rider Details</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-end px-4">
                                                    <button class="btn btn-outline-success waves-effect mb-0 custom-input-sm ms-2"
                                                            wire:click="showCustomerDetails({{ $un_user->id}})">
                                                        View
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end mt-3 paginator">
                                    {{ $unverified_users->links() }} <!-- Pagination links -->
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
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">KYC Verified Date/Time</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Vehicle Model</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Deposit Status</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Rental Status</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Dashboard</th>
                                            <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">Documents</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                        @foreach($verified_users as $k => $v_user)
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
                                                                @if ($v_user->image)
                                                                    <img src="{{ asset($v_user->image) }}" alt="Avatar" class="rounded-circle">
                                                                @else
                                                                    <div class="avatar-initial rounded-circle {{$colorClass}}">
                                                                        {{ strtoupper(substr($v_user->name, 0, 1)) }}{{ strtoupper(substr(strrchr($v_user->name, ' '), 1, 1)) }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <a href="{{ route('admin.customer.details', $v_user->id) }}"
                                                                class="text-heading"><span class="fw-medium text-truncate">{{ ucwords($v_user->name) }}</span>
                                                            </a>
                                                            <small class="text-truncate">{{ $v_user->email }} </small>
                                                            {{-- | {{$v_user->country_code}} {{ $v_user->mobile }} --}}
                                                        <div>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-start">
                                                    {{$v_user->kyc_uploaded_at?date('d M y h:i A', strtotime($v_user->kyc_uploaded_at)):"N/A"}}</td>
                                                <td class="align-middle text-start">
                                                    @if($v_user->active_vehicle)
                                                        {{$v_user->latest_order?$v_user->latest_order->product->title:"N/A"}}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td class="align-middle text-sm text-center">
                                                    @if($v_user->active_vehicle)
                                                        @if($v_user->latest_order)
                                                            @if($v_user->latest_order->payment_status=="completed")
                                                                <span class="badge bg-label-success mb-0 cursor-pointer text-uppercase">{{$v_user->latest_order->payment_status}}</span>
                                                            @else
                                                                <span class="badge bg-label-warning mb-0 cursor-pointer text-uppercase">{{$v_user->latest_order->payment_status}}</span>
                                                            @endif
                                                        @else
                                                            <span class="badge bg-label-danger mb-0 cursor-pointer">NOT PAID</span>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-label-danger mb-0 cursor-pointer">NOT PAID</span>
                                                    @endif
                                                </td>
                                                <td class="align-middle text-sm text-center">
                                                    @if($v_user->active_vehicle)
                                                        @if($v_user->latest_order)
                                                            @if($v_user->latest_order->payment_status=="completed")
                                                                <span class="badge bg-label-success mb-0 cursor-pointer text-uppercase">{{$v_user->latest_order->payment_status}}</span>
                                                            @else
                                                                <span class="badge bg-label-warning mb-0 cursor-pointer text-uppercase">{{$v_user->latest_order->payment_status}}</span>
                                                            @endif
                                                        @else
                                                            <span class="badge bg-label-danger mb-0 cursor-pointer">NOT PAID</span>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-label-danger mb-0 cursor-pointer">NOT PAID</span>
                                                    @endif
                                                </td>
                                                <td class="align-middle text-sm text-center">
                                                    <div class="dropdown cursor-pointer">
                                                        <span class="badge px-2 rounded-pill bg-label-secondary dropdown-toggle" id="exploreDropdown_await_{{$v_user->id}}" data-bs-toggle="dropdown" aria-expanded="false">Explore</span>
                                                        <ul class="dropdown-menu" aria-labelledby="exploreDropdown_await_{{$v_user->id}}">
                                                             <li><a class="dropdown-item" href="{{ route('admin.customer.details', $v_user->id) }}">Rider Details</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-end px-4">
                                                    <button class="btn btn-outline-success waves-effect mb-0 custom-input-sm ms-2"
                                                        wire:click="showCustomerDetails({{ $v_user->id}})">
                                                    View
                                                </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end mt-3 paginator">
                                    {{ $verified_users->links() }} <!-- Pagination links -->
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade {{$active_tab==3?"active show":""}}" id="navs-justified-messages" role="tabpanel">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Customer</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Date Of Rejection</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Reason For Rejection</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Rejected By</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Re-Uploaded Status</th>
                                            <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                        @foreach($rejected_users as $k => $r_user)
                                        @php
                                            $UserKycLog = App\Models\UserKycLog::where('user_id', $r_user->id)->where('status', 'Rejected')->orderBy('id', 'DESC')->whereDate('created_at', '>=', date('Y-m-d', strtotime($r_user->date_of_rejection)))->get();

                                            $UploadedStatus = App\Models\UserKycLog::where('user_id', $r_user->id)
                                                ->where('status', 'Re-uploaded')
                                                ->where('created_at', '>=', $r_user->date_of_rejection)
                                                ->latest('id')  // More readable than orderBy('id', 'DESC')
                                                ->exists();
                                            $colors = ['bg-label-primary', 'bg-label-success', 'bg-label-info', 'bg-label-secondary', 'bg-label-danger', 'bg-label-warning'];
                                            $colorClass = $colors[$k % count($colors)]; // Rotate colors based on index
                                        @endphp
                                            <tr>
                                                <td class="sorting_1">
                                                    <div class="d-flex justify-content-start align-items-center customer-name">
                                                        <div class="avatar-wrapper me-3">
                                                            <div class="avatar avatar-sm">
                                                                @if ($r_user->image)
                                                                    <img src="{{ asset($r_user->image) }}" alt="Avatar" class="rounded-circle">
                                                                @else
                                                                    <div class="avatar-initial rounded-circle {{$colorClass}}">
                                                                        {{ strtoupper(substr($r_user->name, 0, 1)) }}{{ strtoupper(substr(strrchr($r_user->name, ' '), 1, 1)) }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <a href="{{ route('admin.customer.details', $r_user->id) }}"
                                                                class="text-heading"><span class="fw-medium text-truncate">{{ ucwords($r_user->name) }}</span>
                                                            </a>
                                                            <small class="text-truncate">{{$r_user->country_code}} {{ $r_user->mobile }}</small>
                                                        <div>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-start">{{$r_user->date_of_rejection?date('d M y h:i A', strtotime($r_user->date_of_rejection)):"N/A"}}</td>
                                                <td class="align-middle text-start p-3">
                                                    <div class="bg-white rounded-lg shadow-md p-4 space-y-2 max-w-md">
                                                        <ul class="list-disc list-inside text-sm text-gray-700">
                                                            @forelse ($UserKycLog as $reason)
                                                                <li class="px-2 py-1 rounded-md bg-gray-50 hover:bg-blue-50 transition">{{ $reason->remarks }}</li>
                                                            @empty
                                                                <li class="text-gray-500 italic">No remarks available.</li>
                                                            @endforelse
                                                        </ul>
                                                    </div>
                                                </td>

                                                <td class="align-middle text-start">
                                                    {{$r_user->rejectedBy?$r_user->rejectedBy->email:"N/A"}}
                                                </td>
                                                <td class="align-middle text-start">
                                                   @if($UploadedStatus)
                                                        <span class="badge bg-label-success mb-0 cursor-pointer">Recently Uploaded</span>
                                                    @else
                                                        <span class="badge bg-label-danger mb-0 cursor-pointer">Pending</span>
                                                    @endif
                                                </td>
                                               <td class="align-middle text-end px-4">
                                                    <button class="btn btn-outline-success waves-effect mb-0 custom-input-sm ms-2"
                                                        wire:click="showCustomerDetails({{ $r_user->id}})">
                                                    View
                                                </button>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end mt-3 paginator">
                                    {{ $rejected_users->links() }} <!-- Pagination links -->
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
                                    <div class="col-6 text-center cursor-pointer">
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
@endsection
