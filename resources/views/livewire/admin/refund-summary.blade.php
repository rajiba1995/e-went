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
        .full_payment{
            color: #ff4c51;
            background-color: #ffffff;
            border-color: #ff4c51;
        }
        .zero_payment{
            color: #000;
            background-color: #ffffff;
            border-color: #000;
        }
    </style>
    <div class="col-lg-12 justify-content-left">
       <h5 class="mb-0">Rider Refund Summary</h5>
       <div>
            <small class="text-dark fw-medium">Payment</small>
            <small class="text-light fw-medium arrow">Refund Summary</small>
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
                                 Eligible Refunds <span
                                  class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-secondary ms-1_5 pt-50">{{count($eligible_refunds)}}</span>
                                </span>
                                <i class="ri-user-3-line ri-20px d-sm-none"></i>
                          </li>
                          <li class="nav-item" role="presentation" wire:click="tab_change(2)">
                            <button type="button" class="nav-link waves-effect {{$active_tab==2?"active":""}}" role="tab" data-bs-toggle="tab"
                              data-bs-target="#navs-justified-profile" aria-controls="navs-justified-profile" aria-selected="false"
                              tabindex="-1">
                              <span class="d-none d-sm-block">
                                In Progress <span
                                  class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-success ms-1_5 pt-50">{{count($verified_users)}}</span>
                                </span>
                                <i class="ri-user-3-line ri-20px d-sm-none"></i>
                            </button>
                          </li>
                          <li class="nav-item" role="presentation" wire:click="tab_change(3)">
                            <button type="button" class="nav-link waves-effect {{$active_tab==3?"active":""}}" role="tab" data-bs-toggle="tab"
                              data-bs-target="#navs-justified-messages" aria-controls="navs-justified-messages" aria-selected="true">
                              <span class="d-none d-sm-block">
                                Processed <span
                                  class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-danger ms-1_5 pt-50">{{count($rejected_users)}}</span>
                                </span>
                                <i class="ri-user-3-line ri-20px d-sm-none"></i>
                            </button>
                          </li>
                          <li class="nav-item" role="presentation" wire:click="tab_change(4)">
                            <button type="button" class="nav-link waves-effect {{$active_tab==3?"active":""}}" role="tab" data-bs-toggle="tab"
                              data-bs-target="#navs-justified-messages" aria-controls="navs-justified-messages" aria-selected="true">
                              <span class="d-none d-sm-block">
                                Rejected <span
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
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Vehicle Model</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Deposit Amount</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Deposit Paid Date/Time</th>
                                            <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                        @foreach($eligible_refunds as $k => $un_user)
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
                                                                @if ($un_user->user->profile_image)
                                                                    <img src="{{ asset($un_user->user->profile_image) }}" alt="Avatar" class="rounded-circle">
                                                                @else
                                                                    <div class="avatar-initial rounded-circle {{$colorClass}}">
                                                                        {{ strtoupper(substr($un_user->user->name, 0, 1)) }}{{ strtoupper(substr(strrchr($un_user->user->name, ' '), 1, 1)) }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <a href="{{ route('admin.customer.details', $un_user->user->id) }}"
                                                                class="text-heading"><span class="fw-medium text-truncate">{{ ucwords($un_user->user->name) }}</span>
                                                            </a>
                                                            <small class="text-truncate">{{ $un_user->user->email }} <br> {{$un_user->user->country_code}} {{ $un_user->user->mobile }}</small>
                                                        <div>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-start">{{$un_user->product?$un_user->product->title:"...."}}</td>
                                                <td class="align-middle text-sm text-center">
                                                    {{env('APP_CURRENCY')}}{{$un_user->deposit_amount}}
                                                </td>
                                                <td class="align-middle text-start">
                                                    {{ date('d M y h:i A', strtotime($un_user->created_at)) }}
                                                </td>
                                                <td class="align-middle text-end px-4">
                                                   <button class="btn btn-xs btn-danger waves-effect waves-light full_payment">Full</button>
                                                   <button class="btn btn-xs btn-dark waves-effect waves-light zero_payment">Zero</button>
                                                   <button class="btn btn-xs btn-primary waves-effect waves-light" wire:click="PartialPayment({{$un_user->id}},{{ $un_user->user->id}})">Partial</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end mt-3 paginator">
                                    {{ $eligible_refunds->links() }} <!-- Pagination links -->
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
                        <div class="tab-pane fade {{$active_tab==4?"active show":""}}" id="navs-justified-messages" role="tabpanel">
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
                        <small class="text-truncate">{{ $selected_order->product->title }} |
                        Deposit Amount: <strong>{{env('APP_CURRENCY')}}{{ $selected_order->deposit_amount }}</strong></small>
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
                        <button type="button" class="nav-link waves-effect modal-nav active" role="tab">
                          <span class="d-none d-sm-block">Partial Refund 
                            </span>
                      </li>
                    </ul>
                </div>
                <div class="tab-content p-0 mt-6">
                    <div class="tab-pane fade active show" id="navs-justified-overview" role="tabpanel">
                        <div class="col-12 mb-3">
                            <label for="product_id" class="form-label">BOM Parts <span class="text-danger">*</span></label>
                            <select 
                                class="form-select @error('bom_part') is-invalid @enderror" 
                                id="bom_part" 
                                wire:model="bom_part">
                                <option value="" hidden>Select product</option>
                                @foreach($BomParts as $bom_part)
                                    <option value="{{ $bom_part->id }}">{{ $bom_part->part_name }} |  {{env('APP_CURRENCY')}}{{round($bom_part->part_price)}}</option> <!-- Adjust field name if needed -->
                                @endforeach
                            </select>
                            @error('bom_part') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
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
