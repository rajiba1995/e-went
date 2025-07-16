<div class="row mb-4">
  <style>
    .avatar {
        position: relative;
        width: 1.5rem !important;
        height: 1.5rem !important;
        cursor: pointer;
    }
  </style>
    <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
        <div class="row gx-4 mb-4">
            <div class="col-auto my-auto">
              <div class="h-100">
                <h5 class="mb-1">Customer Details</h5>
              </div>
            </div>
            <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
              <div class="nav-wrapper position-relative end text-end">
                <!-- Back Button -->
                <a class="btn btn-dark btn-sm" href="{{route('admin.customer.engagement.list')}}" role="button">
                  <i class="ri-arrow-go-back-line ri-16px me-0 me-sm-2 align-baseline"></i>
                  Back
                </a>
                  @if($activeTab=="cancel_history")
                    <button wire:click="exportCancelHistory" class="btn btn-primary btn-sm">
                      <i class="ri-download-line"></i> Export
                    </button>
                  @elseif($activeTab=="ride_history")
                    <button wire:click="exportAll" class="btn btn-primary btn-sm">
                      <i class="ri-download-line"></i> Export
                    </button>
                  @else
                    <button wire:click="exportJourney" class="btn btn-primary btn-sm">
                      <i class="ri-download-line"></i> Export
                    </button>
                  @endif
              </div>
            </div>
        </div>
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
        <div class="row" style="font-size: 11px !important;">
            <div class="col-3">
                <div class="card">
                    <div class="card-body">
                        <div class="customer-avatar-section">
                            <div class="d-flex align-items-center flex-column">
                              <img class="img-fluid rounded mb-4"
                                src="{{ $user->profile_image?asset($user->profile_image):asset('assets/img/profile-image.webp') }}"
                                height="85" width="85" alt="User avatar">
                              <div class="customer-info text-center mb-6">
                                <h6 class="mb-0">{{$user->name}}</h6>
                                 <span class="text-primary">{{$user->email}}</span> <br>
                                {{-- <span>Customer ID #{{$user->customer_id??'TEST00001'}}</span> --}}
                              </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-around flex-wrap mb-6 gap-0 gap-md-3 gap-lg-4">
                            <div class="d-flex align-items-center gap-4 me-5">
                              <div class="avatar">
                                <div class="avatar-initial rounded bg-label-primary"><i class="ri-shopping-cart-line"></i>
                                </div>
                              </div>
                              <div>
                                <h5 class="mb-0">{{$customer_total_order}}</h5>
                                <span>Rides</span>
                              </div>
                            </div>
                            <div class="d-flex align-items-center gap-4">
                              <div class="avatar">
                                <div class="avatar-initial rounded bg-label-primary"><i class="ri-money-rupee-circle-line ri-24px"></i>
                                </div>
                              </div>
                              <div>
                                <h5 class="mb-0"> {{ env('APP_CURRENCY', '₹') }}{{ number_format($total_payment_amount) }}</h5>
                                <span>Spent</span>
                              </div>
                            </div>
                        </div>

                        <div class="info-container">
                            <h5 class="border-bottom text-capitalize pb-4 mt-6 mb-4"></h5>
                            <ul class="list-unstyled mb-6">
                              <li class="mb-2">
                                <span class="h6 me-1">Reg. Date:</span>
                                <span>{{ date('d M y h:i A', strtotime($user->created_at)) }}</span>
                              </li>
                              <li class="mb-2">
                                <span class="h6 me-1">Mobile:</span>
                                <span>{{env('APP_COUNTRY_CODE', 91)}} {{$user->mobile}}</span>
                              </li>

                              <li class="mb-2">
                                <span class="h6 me-1">Current Location:</span>
                                <span>{{$user->address}}</span>
                              </li>
                            </ul>
                            <div class="d-flex justify-content-center">
                               @if($user->status==1)
                                <button class="btn btn-success w-100 waves-effect waves-light">Active</button>
                              @else
                                <button class="btn btn-danger w-100 waves-effect waves-light">Inactive</button>
                              @endif

                            </div>


                            <!-- Edit User Modal -->
                            @if ($showEditModal)
                                  <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background: rgba(0, 0, 0, 0.5);">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit User Details</h5>
                                                <button type="button" class="btn-close" wire:click="closeModal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Name</label>
                                                    <input type="text" id="name" class="form-control" wire:model.defer="user.name">
                                                    @error('user.name') <small class="text-danger">{{ $message }}</small> @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" id="email" class="form-control" wire:model.defer="user.email">
                                                    @error('user.email') <small class="text-danger">{{ $message }}</small> @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="mobile" class="form-label">Mobile</label>
                                                    <input type="text" id="mobile" class="form-control" wire:model.defer="user.mobile">
                                                    @error('user.mobile') <small class="text-danger">{{ $message }}</small> @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="address" class="form-label">Current Location(City)</label>
                                                    <input type="text" id="address" class="form-control" wire:model.defer="user.address">
                                                    @error('user.address') <small class="text-danger">{{ $message }}</small> @enderror
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" wire:click="closeModal">Cancel</button>
                                                <button class="btn btn-primary" wire:click="updateUser">Save Changes</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-9">
                <div class="row text-nowrap">
                    <div class="nav-align-top">
                      <ul class="nav nav-pills flex-column flex-md-row flex-wrap mb-6 row-gap-2">
                        <li class="nav-item">
                          <a class="nav-link waves-effect waves-light {{$activeTab=="account"?"active":""}}" href="javascript:void(0)" wire:click="changeTab('account')">
                            <i class="icon-base ri ri-user-3-line icon-sm me-1_5"></i>Account Details
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link waves-effect waves-light {{$activeTab=="ride_history"?"active":""}}" href="javascript:void(0)" wire:click="changeTab('ride_history')">
                            <i class="icon-base ri ri-taxi-line icon-sm me-1_5"></i>Ride History
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link waves-effect waves-light {{$activeTab=="cancel_history"?"active":""}}" href="javascript:void(0);" wire:click="changeTab('cancel_history')">
                            <i class="icon-base ri ri-close-circle-line icon-sm me-1_5"></i>Cancel Request History
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link waves-effect waves-light {{$activeTab=="location_history"?"active":""}}" href="javascript:void(0);" wire:click="changeTab('location_history')">
                           <i class="icon-base ri ri-map-pin-line icon-sm me-1_5"></i>Location History

                          </a>
                        </li>
                      </ul>

                    </div>
                    @if($activeTab=="ride_history")
                      <!--/ DataTable with Buttons -->
                      <div class="card card-action mb-6">
                          <div class="card-header align-items-center flex-wrap gap-2">
                            <h5 class="card-action-title mb-0">Ride History</h5>
                          </div>
                          <div class="card-body">
                            <div class="accordion accordion-arrow-left">
                              <div class="accordion-item">
                                <div class="accordion-collapse">
                                  <div class="accordion-body table-responsive text-nowrap p-0">
                                    <table class="table table-striped">
                                      <thead>
                                          <tr>
                                            <th class="h6">Order No</th>
                                            <th class="h6">Vehicle</th>
                                            <th class="h6">Amount</th>
                                            <th class="h6">Duration</th>
                                            <th class="h6">Status</th>
                                            <th class="h6">Actions</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                        @foreach ($orders as $key=>$order_item)
                                          <tr class="{{in_array($key, $expandedRows)?"active_ride_table_tr":""}}">
                                            <td class="">
                                                <small class="">{{ $order_item->order_number }}</small>
                                                {{-- <small class="text-muted">{{ date('d M y h:i A', strtotime($order_item->start_date)) }}</small> --}}
                                            </td>
                                            <td class="">
                                                <span> {{$order_item->product?$order_item->product->title:"N/A"}}</span>
                                            </td>
                                            <td class="">
                                                  Deposit Amount: <small class="">{{ ENV('APP_CURRENCY')}}{{number_format($order_item->deposit_amount)??0.00 }}</small> <br>
                                                  Rent Amount: <small class="">{{ ENV('APP_CURRENCY')}}{{number_format($order_item->rental_amount)??0.00 }}</small>
                                            </td>
                                            <td class="">
                                              Start Date:  <small class="">{{ date('d M y h:i A', strtotime($order_item->rent_start_date)) }}</small> <br>
                                              End Date:  <small class="">{{ date('d M y h:i A', strtotime($order_item->rent_end_date)) }}</small>
                                            </td>
                                            <td class="">
                                                <span class="badge bg-label-primary me-1 rounded-pill">{{ucwords($order_item->rent_status)}}</span>
                                            </td>
                                            <td class="text-center">
                                               <a href="javascript:void(0)" wire:click="fetchRideData({{$order_item->id}},{{$key}})">
                                                  <span class="control"></span>
                                                </a>
                                            </td>
                                          </tr>
                                          @if(in_array($key, $expandedRows))
                                            <tr>
                                                <td colspan="6" class="active_table_td">
                                                  <table class="table">
                                                    <thead>
                                                      <tr>
                                                        <th class="h6">Vehicle</th>
                                                        <th class="h6">Date</th>
                                                        <th class="h6">Rent</th>
                                                        <th class="h6">Rent Status</th>
                                                        <th class="h6">Action By</th>
                                                      </tr>
                                                    </thead>
                                                    <tbody>
                                                      @foreach ($ride_history as $ride_item)
                                                        <tr>
                                                            <td>
                                                                <small class="text-dark">
                                                                    {{ optional($ride_item->stock)->vehicle_number ?? 'N/A' }}
                                                                </small><br>
                                                                <small>
                                                                    <code>{{ optional(optional($ride_item->stock)->product)->title ?? 'N/A' }}</code>
                                                                </small>
                                                            </td>

                                                            <td>
                                                                @if($ride_item->status == 'exchanged')
                                                                    <small class="text-muted">
                                                                        Exchanged Date : {{ date('d M y h:i A', strtotime($ride_item->exchanged_at)) }}
                                                                    </small>
                                                                @elseif($ride_item->status == 'returned')
                                                                    <small class="text-muted">
                                                                        Returned Date : {{ date('d M y h:i A', strtotime($ride_item->exchanged_at)) }}
                                                                    </small>
                                                                @else
                                                                    <small class="text-muted">

                                                                        Assigned Date : @if (!$ride_item->assigned_at || $ride_item->assigned_at == '1970-01-01 00:00:00')
    N/A
@else
    {{ date('d M y h:i A', strtotime($ride_item->assigned_at)) }}
@endif
                                                                    </small>
                                                                @endif
                                                            </td>

                                                            <td>
                                                                <small class="text-muted">
                                                                    {{ optional($ride_item->order)->rental_amount
                                                                        ? env('APP_CURRENCY') . number_format($ride_item->order->rental_amount)
                                                                        : env('APP_CURRENCY') . '0.00' }}
                                                                </small>
                                                            </td>

                                                            <td>
                                                                <small class="text-muted">{{ ucwords($ride_item->status) }}</small>
                                                            </td>

                                                            <td>
                                                                @if(!empty($ride_item->exchanged_by) || !empty($ride_item->assigned_by))
                                                                    <small class="text-primary">
                                                                        {{ optional($ride_item->admin)->email ?? '....' }}
                                                                    </small>
                                                                @else
                                                                    <small class="text-success">
                                                                        {{ optional($ride_item->user)->email ?? 'N/A' }}
                                                                    </small>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                    </tbody>
                                                  </table>
                                                </td>
                                            </tr>
                                          @endif
                                        @endforeach
                                      </tbody>
                                    </table>
                                    <div class="d-flex justify-content-end mt-2">
                                      {{ $orders->links() }}
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                      </div>
                    @endif
                   @if($activeTab == "account")
                    <div class="card mb-6">
                      <div class="card-header">
                        <h5 class="card-title m-0">User Journey Timeline</h5>
                      </div>
                      <div class="card-body mt-3">
                        <ul class="timeline pb-0 mb-0">
                          @foreach($userJourney as $step)
                            <li class="timeline-item timeline-item-transparent border-primary">
                              <span class="timeline-point timeline-point-primary"></span>
                              <div class="timeline-event">
                                <div class="timeline-header mb-1">
                                  <h6 class="mb-0">{{ $step['title'] }}</h6>
                                  @if(!empty($step['date']))
                                    <small class="text-body-secondary">{{ \Carbon\Carbon::parse($step['date'])->format('d M Y h:i A') }}</small>
                                  @endif
                                </div>
                                <p class="mt-1 mb-3">
                                  {!! $step['description'] !!}
                                </p>
                                 {{-- Check if T&C info exists --}}
                                @if(!empty($step['terms_and_conditions']))
                                  <div class="mt-1">
                                    <span class="badge bg-{{$step['terms_and_conditions']->status=='success'?"success":"danger"}} text-white">T&C Status: {{ ucwords($step['terms_and_conditions']->status) }}</span>
                                    <br>
                                    @if($step['terms_and_conditions']->status=='success')
                                      <a href="{{ $step['terms_and_conditions']->signed_url }}" target="_blank" class="btn btn-sm btn-outline-primary mt-1">
                                        Download Terms & Conditions
                                      </a>
                                    @endif
                                  </div>
                                @endif
                              </div>
                            </li>
                          @endforeach
                            <li class="timeline-item timeline-item-transparent border-transparent pb-0">
                              <span class="timeline-point timeline-point-secondary"></span>
                              <div class="timeline-event pb-0">
                                <div class="timeline-header mb-1">
                                  <h6 class="mb-0">Journey Ongoing</h6>
                                </div>
                                <p class="mt-1 mb-3">The user is actively using the service.</p>
                              </div>
                            </li>
                        </ul>
                      </div>
                    </div>
                  @endif
                  @if($activeTab == "cancel_history")
                    <div class="card mb-6">
                        <div class="card-body mt-3">
                          <div class="table-responsive">

                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Order ID</th>
                                            <th>Vehicle ID</th>
                                            <th>Requested On</th>
                                            <th>Accepted On</th>
                                            <th>Status</th>
                                            <th>Accepted By</th>
                                            <th>Rejected Reason</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      @forelse($cancel_request_histories as $request)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>#{{ $request->order->order_number }}</td>
                                            <td>{{ $request->stock->vehicle_number }}</td>
                                            <td>{{ \Carbon\Carbon::parse($request->request_date)->format('d M Y h:i A') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($request->accepted_date)->format('d M Y h:i A') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $request->type == 'accepted' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($request->type) }}
                                                </span>
                                            </td>
                                            <td>{{ $request->admin->email }}</td>
                                            <td>{{ $request->rejected_reason ?? '-' }}</td>
                                        </tr>
                                        @empty
                                           <tr>
                                            <td colspan="8">
                                               <div class="alert alert-info">No cancel request history found.</div>
                                            </td>
                                           </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                          </div>
                        </div>
                    </div>
                  @endif
                  @if($activeTab == "location_history")
                    <div class="card mb-6">
                        <div class="card-body mt-3">
                          <div class="table-responsive">

                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Address</th>
                                            <th>Latitude & Longitude </th>
                                            <th>Date & Time </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      @forelse($location_history as $location_data)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $location_data->address }}</td>
                                            <td>{{ $location_data->latitude }} & {{ $location_data->longitude }}</td>
                                            <td>{{ $location_data->created_at }}</td>
                                        </tr>
                                         @empty
                                           <tr>
                                            <td colspan="3">
                                               <div class="alert alert-info">No location history found.</div>
                                            </td>
                                           </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end mt-3 paginator">
                                    {{ $location_history->links() }} <!-- Pagination links -->
                                </div>
                          </div>
                        </div>
                    </div>
                  @endif


                </div>
            </div>
        </div>
    </div>
    <div class="loader-container" wire:loading>
      <div class="loader"></div>
    </div>
</div>

