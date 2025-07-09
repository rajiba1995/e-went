<div class="row mb-4">
    <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
        <div class="row gx-4 mb-4">
          <div class="col-auto my-auto">
              <div class="h-100">
                <h5 class="mb-0">Rider History</h5>
                <div>
                    <small class="text-dark fw-medium">Dashboard</small>
                    <small class="text-light fw-medium arrow"><a href="{{route('admin.customer.engagement.list')}}">Riders</a></small>
                    @if($user)
                        <small class="text-light fw-medium arrow"><a href="{{route('admin.payment.user_history',[$user->id])}}">{{$user->name}}</a> </small>
                    @endif
                </div>
              </div>
          </div>
            <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
              <div class="nav-wrapper position-relative end text-end">
                <!-- Back Button -->
                <a class="btn btn-dark btn-sm" href="javascript:history.back();" role="button">
                  <i class="ri-arrow-go-back-line ri-16px me-0 me-sm-2 align-baseline"></i>
                  Back
                </a>
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
        <div class="row">
            <div class="col-12">
                <div class="row text-nowrap">
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
                                            <th class="h6">Vehicle</th>
                                            <th class="h6">Start Date</th>
                                            <th class="h6">End Date</th>
                                            <th class="h6">Rent</th>
                                            <th class="h6">Rent Status</th>
                                            <th class="h6">Action By</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                        @forelse ($history as $item)
                                            <tr>
                                              <td class="">
                                                  <small class="text-dark"> {{$item->stock?$item->stock->vehicle_number:"N/A"}}</small><br>
                                                  <small><code>{{ $item->stock && $item->stock->product ? $item->stock->product->title : "N/A" }}</code></small>

                                              </td>
                                              <td class="">
                                                  <small class="text-muted">{{ date('d M y h:i A', strtotime($item->start_date)) }}</small>
                                              </td>
                                              <td class="">
                                                <small class="text-muted">{{ date('d M y h:i A', strtotime($item->end_date)) }}</small>
                                              </td>
                                              <td class="">
                                                <small class="text-muted">{{ $item->order?ENV('APP_CURRENCY').''.number_format($item->order->rental_amount):0.00 }}</small>
                                              </td>
                                              <td class="">
                                                <small class="text-muted">{{ ucwords($item->status)}}</small>
                                              </td>
                                              <td class="">
                                                @if($item->exchanged_by)
                                                  <small class="text-primary">{{$item->admin?$item->admin->email:'....'}}</small>
                                                @elseif($item->assigned_by)
                                                  <small class="text-primary">{{$item->admin?$item->admin->email:'....'}}</small>
                                                @else
                                                  <small class="text-success">{{$item->user?$item->user->email:"N/A"}}</small>
                                                @endif
                                                
                                              </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6">
                                                  <div class="alert alert-danger">
                                                    Data not found!
                                                  </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                      </tbody>
                                    </table>
                                    <div class="d-flex justify-content-end mt-2">
                                      {{ $history->links('pagination::bootstrap-4') }}
                                  </div>
                                  </div>
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
</div>

