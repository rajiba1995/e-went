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
                                  class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-secondary ms-1_5 pt-50">{{$eligible_refunds->total()}}</span>
                                </span>
                                <i class="ri-user-3-line ri-20px d-sm-none"></i>
                          </li>
                          <li class="nav-item" role="presentation" wire:click="tab_change(2)">
                            <button type="button" class="nav-link waves-effect {{$active_tab==2?"active":""}}" role="tab" data-bs-toggle="tab"
                              data-bs-target="#navs-justified-profile" aria-controls="navs-justified-profile" aria-selected="false"
                              tabindex="-1">
                              <span class="d-none d-sm-block">
                                In Progress <span
                                  class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-success ms-1_5 pt-50">{{$in_progress_data->total()}}</span>
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
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Vehicle Model</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Refund Amount</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Refund Initiated By</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Refund Category</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach($in_progress_data as $in_progress_index => $in_progress)
                                        {{-- {{dd($in_progress)}} --}}
                                        @php
                                            $colors = ['bg-label-primary', 'bg-label-success', 'bg-label-info', 'bg-label-secondary', 'bg-label-danger', 'bg-label-warning'];
                                            $colorClass = $colors[$in_progress_index % count($colors)]; // Rotate colors based on index
                                        @endphp
                                            <tr>
                                                <td class="align-middle text-center">{{ $in_progress_index + 1 }}</td>
                                                <td class="sorting_1">
                                                    <div class="d-flex justify-content-start align-items-center customer-name">
                                                        <div class="avatar-wrapper me-3">
                                                            <div class="avatar avatar-sm">
                                                                @if ($in_progress->user->image)
                                                                    <img src="{{ asset($in_progress->user->image) }}" alt="Avatar" class="rounded-circle">
                                                                @else
                                                                    <div class="avatar-initial rounded-circle {{$colorClass}}">
                                                                        {{ strtoupper(substr($in_progress->user->name, 0, 1)) }}{{ strtoupper(substr(strrchr($in_progress->user->name, ' '), 1, 1)) }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <a href="{{ route('admin.customer.details', $in_progress->user->id) }}"
                                                                class="text-heading"><span class="fw-medium text-truncate">{{ ucwords($in_progress->user->name) }}</span>
                                                            </a>
                                                            <small class="text-truncate">{{ $in_progress->user->email }} </small>
                                                        <div>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-start">
                                                   {{ $in_progress->order_item?->product?->title ?? 'N/A' }}
                                                </td>
                                                <td class="align-middle text-start">
                                                   {{env('APP_CURRENCY')}}{{ $in_progress->refund_amount }}
                                                </td>
                                                <td class="align-middle text-sm text-center">
                                                    <div class="d-flex flex-column cursor-pointer">
                                                        <small class="text-truncate text-success" title="{{ ucwords($in_progress->initiated_by?->name ?? 'N/A') }}">{{ $in_progress->initiated_by->email }} </small>
                                                        <small class="text-truncate">{{ date('d M y h:i A', strtotime($in_progress->refund_initiated_at)) }}</small>
                                                    <div>
                                                </td>
                                                <td class="align-middle text-sm text-center">
                                                    <span class="badge bg-label-{{ $in_progress->refund_category == 'deposit_partial_refund' ? 'warning' : ($in_progress->refund_category == 'deposit_full_refund' ? 'success' : 'danger') }} mb-0 text-uppercase">
                                                        {{ strtoupper(str_replace('_', ' ', $in_progress->refund_category)) }}
                                                    </span>

                                                </td>
                                                <td class="align-middle text-end px-4">
                                                    <button class="btn btn-sm btn-primary text-white mb-0 custom-input-sm ms-2"
                                                    wire:click="ProgressModal({{ $in_progress->id }})"
                                                    >
                                                    Update
                                                </button>
                                                <button class="btn btn-sm btn-success waves-effect mb-0 custom-input-sm ms-2"
 wire:click="viewReturnModal({{ $in_progress->order_item_id }},{{ $in_progress->id}},{{ $in_progress->user->id }})"
                                                    >
                                                    View
                                                </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end mt-3 paginator">
                                    {{ $in_progress_data->links() }} <!-- Pagination links -->
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
        <form wire:submit.prevent="submit" enctype="multipart/form-data">
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
                        <div class="col-12 mb-3" wire:ignore>
                            <label for="product_id" class="form-label">BOM Parts <span class="text-danger">*</span></label>
                            <select
                                class="form-control"
                                id="bom_part"
                                wire:model="bom_part" data-placeholder="Please select..." multiple
                                >
                                <option value="" hidden>Select product</option>
                                @foreach($BomParts as $bom_part)
                                    <option value="{{ $bom_part->id }}">{{ $bom_part->part_name }} |  {{env('APP_CURRENCY')}}{{round($bom_part->part_price)}}</option> <!-- Adjust field name if needed -->
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                          <label for="product_id" class="form-label">Overdue Days <span class="text-danger">*</span></label>
                          <select
                              class="form-select"
                              id="over_due_days"
                              wire:model="over_due_days" wire:change="setOverdueDays($event.target.value)">
                              <option value="" >Select Overdue</option>
                              @for ($i = 1; $i <= 20; $i++)
                              <option value="{{ $i }}">{{ $i }}</option>
                              @endfor
                          </select>
                      </div>
                      <div class="col-12 mb-3">
                        <label for="product_id" class="form-label">Port Charge </label>
                        <input type="text" class="form-control" id="port_charges"
                       wire:model="port_charges" oninput="debounceUpdate()">

                    </div>
                    <div class="col-12 mb-3">
                        <label for="product_id" class="form-label">Overdue Amount </label>
                        <input type="text" class="form-control"  readonly wire:model="over_due_amnts">

                    </div>
                      <div class="col-12 mb-3">
                        <label for="product_id" class="form-label">Total Deduct Amount </label>
                        <input type="text" class="form-control"  wire:model="deduct_amounts" readonly>

                    </div>
                    <div class="col-12 mb-3">
                      <label for="product_id" class="form-label">Balance Amount </label>
                      <input type="text" class="form-control  @error('balance_amnt') is-invalid @enderror"  wire:model="balance_amnt" readonly >
                      @error('balance_amnt') <span class="text-danger">{{ $message }}</span> @enderror


                  </div>
                  <div class="col-12 mb-3">
                    <label for="product_id" class="form-label">Return Condition </label>
                   <textarea class="form-control" wire:model="return_condition"></textarea>

                </div>
                  <div class="col-12 mb-3">
                    <label for="product_id" class="form-label">Damaged Part Image </label>
                    <input type="file" class="form-control"  wire:model="damaged_part_image" multiple accept="image/*">

                </div>
                <div class="col-12 mb-3 text-end">
                  <button type="submit" class="btn btn-primary" >
                    Submit
                </button>
              </div>
                    </div>
                </div>
            </div>
        </form>
        @endif
    </div>
    @endif

 <!-- Side Modal (Drawer) -->
    @if($isReturnModal)
    <div class="side-modal {{ $isReturnModal ? 'open' : '' }}">
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
                            <a href="javascript:void(0)" wire:click="closeReturnModal"
                                class="template-customizer-close-btn fw-light text-body" tabindex="-1">
                                <i class="ri-close-line ri-24px"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="side-modal-content">
              @if (!empty($damaged_part_logs))
              @foreach ($damaged_part_logs as $damaged_part)
              <div style="border-bottom: 1px solid #8d58ff;" class="mb-3">

                                <div class="d-flex align-items-center mb-3">
                                    <!-- Icon -->

                                    <!-- Document Name -->
                                    <div>
                                        <span class="fw-medium text-truncate text-dark">{{ $damaged_part->bom_part->part_name }}</span>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="col-6">
                                        <div class="card academy-content shadow-none border mx-2" style="width:150px">
                                            <div class="p-2">
                                                <div class="cursor-pointer">
                                                   {{env('APP_CURRENCY')}} {{$damaged_part->price}}
                                                </div>
                                            </div>
                                        </div>

                                </div>


                        </div>

                  </div>
              @endforeach

              @endif


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

     @if ($isProgressModal)
        <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background: rgba(0, 0, 0, 0.5);z-index: 99999;">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">In Progress</h5>
                        <button type="button" class="btn-close" wire:click="closeProgressModal"></button>
                    </div>
                    <div class="modal-body">
                       <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" wire:model="status">
                              <option value="">Select Status</option>
                              <option value="processed">Processed</option>
                              <option value="rejected">Rejected</option>
                            </select>
                          @error('status') <span class="text-danger">{{ $message }}</span> @enderror

                        </div>
                        <div class="mb-3">
                            <label class="form-label">Remark</label>
                            <textarea class="form-control @error('reason') is-invalid @enderror" wire:model="reason"></textarea>
                          @error('reason') <span class="text-danger">{{ $message }}</span> @enderror

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger"   wire:click="ChangeReturnStatus()">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>

@section('page-script')
<link rel="stylesheet" href="{{ asset('assets/custom_css/component-chosen.css') }}">
<script src="{{ asset('assets/js/chosen.jquery.js') }}"></script>



<script>
  let timeout;
    function debounceUpdate() {
        clearTimeout(timeout);
        timeout = setTimeout(function() {
            // Trigger Livewire method after 500ms delay
            @this.call('setPortCharges', document.getElementById('port_charges').value);
        }, 500);
    }
  var jq = $.noConflict();

function initChosen() {
    // Re-initialize Chosen
    jq("#bom_part").chosen({ width: "100%" });

    // Attach change event
    jq("#bom_part").on('change', function () {
        const selected = jq(this).val(); // Array of selected values
        console.log(selected);
          //this@.cll('bomPartChanged', selected);
          @this.call('bomPartChanged', selected)
    });
}

// Bind after Livewire updates the DOM
window.addEventListener('bind-chosen', () => {
    setTimeout(() => {
        initChosen();
    }, 100); // Slight delay to ensure DOM is ready
});

// Optional: trigger from Livewire component like:
// $this->dispatchBrowserEvent('bind-chosen');



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
