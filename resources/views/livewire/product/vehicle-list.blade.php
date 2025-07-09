<div class="row mb-4">
    <div class="col-auto my-auto">
        <div class="h-100">
          <h5 class="mb-0">Vehicle Management</h5>
          <div>
               <small class="text-dark fw-medium">Dashboard </small>
               <small class="text-light fw-medium arrow">Vehicles</small>
          </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
        <div class="nav-wrapper position-relative end text-end">
          <!-- Back Button -->
          <a class="btn btn-secondary btn-sm add-new btn-primary waves-effect waves-light" href="{{route('admin.vehicle.create')}}">
            New Vehicle
          </a>
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
                        <div class="col-lg-3 col-6 my-auto mb-2">
                            <select
                                class="form-select border border-2 p-2 custom-input-sm" wire:model="model" wire:change="FilterModel($event.target.value)">
                                <option value="" selected hidden>Select vehicle type</option>
                                @foreach($models as $model_item)
                                <option value="{{ $model_item->id }}">{{$model_item->category->title}}|{{ $model_item->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6 col-6 my-auto mb-2">
                            <div class="d-flex align-items-center justify-content-end">
                                <input type="text" wire:model="search" 
                                       class="form-control border border-2 p-2 custom-input-sm" 
                                       placeholder="Search by Reg. No, lot IMEI, chassis number">
                                <button type="button" wire:click="btn_search" 
                                        class="btn btn-success text-white mb-0 custom-input-sm ms-2">
                                    <span class="material-icons">search</span>
                                </button>
                                <!-- Refresh Button -->
                                <button type="button" wire:click="reset_search" 
                                        class="btn btn-outline-danger waves-effect mb-0 custom-input-sm ms-2">
                                    <span class="material-icons">refresh</span>
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
                                </i> All <span
                                  class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-secondary ms-1_5 pt-50">{{count($all_vehicles)}}</span>
                                </span>
                                <i class="ri-user-3-line ri-20px d-sm-none"></i>
                          </li>
                          <li class="nav-item" role="presentation" wire:click="tab_change(2)">
                            <button type="button" class="nav-link waves-effect {{$active_tab==2?"active":""}}" role="tab" data-bs-toggle="tab"
                              data-bs-target="#navs-justified-profile" aria-controls="navs-justified-profile" aria-selected="false"
                              tabindex="-1">
                              <span class="d-none d-sm-block">
                                <i class="tf-icons ri-user-3-line me-1_5"></i>
                                </i> Unassigned <span
                                  class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-success ms-1_5 pt-50">{{count($unassigned_vehicles)}}</span>
                                </span>
                                <i class="ri-user-3-line ri-20px d-sm-none"></i>
                            </button>
                          </li>
                          <li class="nav-item" role="presentation" wire:click="tab_change(3)">
                            <button type="button" class="nav-link waves-effect {{$active_tab==3?"active":""}}" role="tab" data-bs-toggle="tab"
                              data-bs-target="#navs-justified-messages" aria-controls="navs-justified-messages" aria-selected="true">
                              <span class="d-none d-sm-block">
                                <i class="tf-icons ri-user-3-line me-1_5"></i>
                                </i> Assigned <span
                                  class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-danger ms-1_5 pt-50">{{count($assigned_vehicles)}}</span>
                                </span>
                                <i class="ri-user-3-line ri-20px d-sm-none"></i>
                            </button>
                          </li>
                          {{-- <li class="nav-item" role="presentation" wire:click="tab_change(4)">
                            <button type="button" class="nav-link waves-effect {{$active_tab==4?"active":""}}" role="tab" data-bs-toggle="tab"
                              data-bs-target="#navs-justified-maintenance" aria-controls="navs-justified-maintenance" aria-selected="true">
                              <span class="d-none d-sm-block">
                                <i class="tf-icons ri-user-3-line me-1_5"></i>
                                </i> Maintenance <span
                                  class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-danger ms-1_5 pt-50">{{count($all_vehicles)}}</span>
                                </span>
                                <i class="ri-user-3-line ri-20px d-sm-none"></i>
                            </button>
                          </li> --}}
                          <li class="nav-item" role="presentation" wire:click="tab_change(4)">
                            <button type="button" class="nav-link waves-effect {{$active_tab==4?"active":""}}" role="tab" data-bs-toggle="tab"
                              data-bs-target="#navs-justified-overdue" aria-controls="navs-justified-overdue" aria-selected="true">
                              <span class="d-none d-sm-block">
                                <i class="tf-icons ri-user-3-line me-1_5"></i>
                                </i> Overdue <span
                                  class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-danger ms-1_5 pt-50">{{count($overdue_vehicles)}}</span>
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
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Vehicle Model/Number/lot IMEI</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Rider/Subscription</th>
                                            {{-- <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">SOC</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Ignition State</th> --}}
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">Vehicle Status</th>
                                            {{-- <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">Ignition Status</th> --}}
                                            <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($all_vehicles as $all_index => $all_item)
                                            <tr>
                                                <td class="align-middle text-center">{{ $all_index + 1 }}</td>
                                                <td class="sorting_1">
                                                    <div class="d-flex justify-content-start align-items-center product-name">
                                                        <div class="d-flex flex-column">
                                                            <span class="badge bg-label-primary">{{$all_item->product?$all_item->product->title:"N/A"}}</span>
                                                            <span class="text-heading fw-medium"> {{ $all_item->vehicle_number }}</span>
                                                            <small class="text-truncate d-none d-sm-block"> {{ $all_item->imei_number }}</small></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($all_item->assignedVehicle)
                                                        <p class="m-0"><strong>{{$all_item->assignedVehicle->user->name}}</strong></p>
                                                        <p class="m-0 text-sm text-success"><small>{{$all_item->assignedVehicle->user->country_code}} {{$all_item->assignedVehicle->user->mobile}}</small></p>
                                                        <p class="m-0 text-sm text-success"><small>{{$all_item->assignedVehicle->user->email}}</small></p>
                                                    <p class="text-sm m-0"><small>Start Date: {{ date('d M y h:i A', strtotime($all_item->assignedVehicle->start_date)) }}</small></p>
                                                        <p class="text-sm m-0"><small>End Date: {{ date('d M y h:i A', strtotime($all_item->assignedVehicle->end_date)) }}</small>
                                                        </p>
                                                    @else
                                                        <p class="m-0">N/A</p>
                                                        <p class="text-sm m-0"><small>Start Date: N/A</small></p>
                                                        <p class="text-sm m-0"><small>End Date: N/A</small>
                                                        </p>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($all_item->assignedVehicle)
                                                    <span class="badge px-2 rounded-pill bg-label-success text-sm">Assigned Vehicle</span>
                                                    @else
                                                        <span class="badge px-2 rounded-pill bg-label-warning text-sm">Unassigned Vehicle</span>
                                                    @endif
                                                </td>
                                                {{-- <td>
                                                    @php
                                                        $ignition_status = GetIgnitionStatus($all_item->vehicle_track_id);
                                                    @endphp
                                                     @if($ignition_status=="ON")
                                                     <span class="badge px-2 rounded-pill bg-label-success text-sm">{{$ignition_status}}</span>
                                                     @else
                                                         <span class="badge px-2 rounded-pill bg-label-danger text-sm">OFF</span>
                                                     @endif
                                                </td> --}}
                                                <td class="text-end">
                                                    <div class="dropdown cursor-pointer">
                                                        <span class="badge px-2 rounded-pill bg-label-secondary dropdown-toggle" id="exploreDropdown_all_item_item_{{$all_item->id}}" data-bs-toggle="dropdown" aria-expanded="false">Explore</span>
                                                        <ul class="dropdown-menu" aria-labelledby="exploreDropdown_all_item_item_{{$all_item->id}}">
                                                            <li><a class="dropdown-item" href="{{route('admin.vehicle.detail', $all_item->vehicle_track_id)}}">Dashboard</a></li>
                                                            <li><a class="dropdown-item" href="{{route('admin.payment.summary',[$all_item->product_id,$all_item->id])}}">Payment Summary</a></li>
                                                            <li><a class="dropdown-item" href="{{route('admin.payment.vehicle.summary',[$all_item->product_id,$all_item->id])}}">Payment History</a></li>
                                                        </ul>
                                                        <span>
                                                            <a href="{{route('admin.vehicle.update', $all_item->id)}}" class="btn btn-sm btn-icon edit-record btn-text-secondary rounded-pill waves-effect btn-sm" title="Edit">
                                                                <i class="ri-edit-box-line ri-20px text-info"></i>
                                                            </a>
                                                        </span>
                                                    </div>
                                                   
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end mt-3 paginator">
                                    {{ $all_vehicles->links() }} <!-- Pagination links -->
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade {{$active_tab==2?"active show":""}}" id="navs-justified-profile" role="tabpanel">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">SL</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Vehicle Model/Number/lot IMEI</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Rider/Subscription</th>
                                            {{-- <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">SOC</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Ignition State</th> --}}
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">Vehicle Status</th>
                                            {{-- <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">Dashboard</th> --}}
                                            <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($unassigned_vehicles as $unassigned_index => $unassigned_item)
                                            <tr>
                                                <td class="align-middle text-center">{{ $unassigned_index + 1 }}</td>
                                                <td class="sorting_1">
                                                    <div class="d-flex justify-content-start align-items-center product-name">
                                                        <div class="d-flex flex-column">
                                                            <span class="badge bg-label-primary">{{$unassigned_item->product?$unassigned_item->product->title:"N/A"}}</span>
                                                            <span class="text-heading fw-medium"> {{ $unassigned_item->vehicle_number }}</span>
                                                            <small class="text-truncate d-none d-sm-block"> {{ $unassigned_item->imei_number }}</small></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($unassigned_item->assignedVehicle)
                                                        <p class="m-0"><strong>{{$unassigned_item->assignedVehicle->user->name}}</strong></p>
                                                    <p class="text-sm m-0"><small>Start Date: {{ date('d M y h:i A', strtotime($unassigned_item->assignedVehicle->start_date)) }}</small></p>
                                                        <p class="text-sm m-0"><small>End Date: {{ date('d M y h:i A', strtotime($unassigned_item->assignedVehicle->end_date)) }}</small>
                                                        </p>
                                                    @else
                                                        <p class="m-0">N/A</p>
                                                        <p class="text-sm m-0"><small>Start Date: N/A</small></p>
                                                        <p class="text-sm m-0"><small>End Date: N/A</small>
                                                        </p>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($unassigned_item->assignedVehicle)
                                                    <span class="badge px-2 rounded-pill bg-label-success text-sm">Assigned Vehicle</span>
                                                    @else
                                                        <span class="badge px-2 rounded-pill bg-label-warning text-sm">Unassigned Vehicle</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <div class="dropdown cursor-pointer">
                                                        <span class="badge px-2 rounded-pill bg-label-secondary dropdown-toggle" id="exploreDropdown_unassigned_item_{{$unassigned_item->id}}" data-bs-toggle="dropdown" aria-expanded="false">Explore</span>
                                                        <ul class="dropdown-menu" aria-labelledby="exploreDropdown_unassigned_item_{{$unassigned_item->id}}">
                                                            <li><a class="dropdown-item" href="{{route('admin.vehicle.detail', $unassigned_item->vehicle_track_id)}}">Dashboard</a></li>
                                                            <li><a class="dropdown-item" href="{{route('admin.payment.summary',[$unassigned_item->product_id,$unassigned_item->id])}}">Payment Summary</a></li>
                                                            <li><a class="dropdown-item" href="{{route('admin.payment.vehicle.summary',[$unassigned_item->product_id,$unassigned_item->id])}}">Payment History</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end mt-3 paginator fffff">
                                    {{ $unassigned_vehicles->links() }} <!-- Pagination links -->
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade {{$active_tab==3?"active show":""}}" id="navs-justified-messages" role="tabpanel">
                            
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">SL</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Vehicle Model/Number/lot IMEI</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Rider/Subscription</th>
                                            {{-- <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">SOC</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Ignition State</th> --}}
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">Vehicle Status</th>
                                            {{-- <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">Dashboard</th> --}}
                                            <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($assigned_vehicles as $assigned_index => $assigned_item)
                                            <tr>
                                                <td class="align-middle text-center">{{ $assigned_index + 1 }}</td>
                                                <td class="sorting_1">
                                                    <div class="d-flex justify-content-start align-items-center product-name">
                                                        <div class="d-flex flex-column">
                                                            <span class="badge bg-label-primary">{{$assigned_item->product?$assigned_item->product->title:"N/A"}}</span>
                                                            <span class="text-heading fw-medium"> {{ $assigned_item->vehicle_number }}</span>
                                                            <small class="text-truncate d-none d-sm-block"> {{ $assigned_item->imei_number }}</small></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($assigned_item->assignedVehicle)
                                                        <p class="m-0"><strong>{{$assigned_item->assignedVehicle->user->name}}</strong></p>
                                                        <p class="m-0 text-sm text-success"><small>{{$assigned_item->assignedVehicle->user->country_code}} {{$assigned_item->assignedVehicle->user->mobile}}</small></p>
                                                        <p class="m-0 text-sm text-success"><small>{{$assigned_item->assignedVehicle->user->email}}</small></p>
                                                    <p class="text-sm m-0"><small>Start Date: {{ date('d M y h:i A', strtotime($assigned_item->assignedVehicle->start_date)) }}</small></p>
                                                        <p class="text-sm m-0"><small>End Date: {{ date('d M y h:i A', strtotime($assigned_item->assignedVehicle->end_date)) }}</small>
                                                        </p>
                                                    @else
                                                        <p class="m-0">N/A</p>
                                                        <p class="text-sm m-0"><small>Start Date: N/A</small></p>
                                                        <p class="text-sm m-0"><small>End Date: N/A</small>
                                                        </p>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($assigned_item->assignedVehicle)
                                                    <span class="badge px-2 rounded-pill bg-label-success text-sm">Assigned Vehicle</span>
                                                    @else
                                                        <span class="badge px-2 rounded-pill bg-label-warning text-sm">Unassigned Vehicle</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <div class="dropdown cursor-pointer">
                                                        <span class="badge px-2 rounded-pill bg-label-secondary dropdown-toggle" id="exploreDropdown_assigned_item_{{$assigned_item->id}}" data-bs-toggle="dropdown" aria-expanded="false">Explore</span>
                                                        <ul class="dropdown-menu" aria-labelledby="exploreDropdown_assigned_item_{{$assigned_item->id}}">
                                                            <li><a class="dropdown-item" href="{{route('admin.vehicle.detail', $assigned_item->vehicle_track_id)}}">Dashboard</a></li>
                                                            <li><a class="dropdown-item" href="{{route('admin.payment.summary',[$assigned_item->product_id,$assigned_item->id])}}">Payment Summary</a></li>
                                                            <li><a class="dropdown-item" href="{{route('admin.payment.vehicle.summary',[$assigned_item->product_id,$assigned_item->id])}}">Payment History</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end mt-3 paginator">
                                    {{ $assigned_vehicles->links() }} <!-- Pagination links -->
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade {{$active_tab==4?"active show":""}}" id="navs-justified-messages" role="tabpanel">
                            
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">SL</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Vehicle Model/Number/lot IMEI</th>
                                            <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Rider/Subscription</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">Vehicle Status</th>
                                            {{-- <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">Dashboard</th> --}}
                                            <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($overdue_vehicles as $overdue_index => $overdue_item)
                                            <tr>
                                                <td class="align-middle text-center">{{ $overdue_index + 1 }}</td>
                                                <td class="sorting_1">
                                                    <div class="d-flex justify-content-start align-items-center product-name">
                                                        <div class="d-flex flex-column">
                                                            <span class="badge bg-label-primary">{{$overdue_item->product?$overdue_item->product->title:"N/A"}}</span>
                                                            <span class="text-heading fw-medium"> {{ $overdue_item->vehicle_number }}</span>
                                                            <small class="text-truncate d-none d-sm-block"> {{ $overdue_item->imei_number }}</small></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($overdue_item->overdueVehicle)
                                                        <p class="m-0"><strong>{{$overdue_item->overdueVehicle->user->name}}</strong></p>
                                                        <p class="m-0 text-sm text-success"><small>{{$overdue_item->overdueVehicle->user->country_code}} {{$overdue_item->overdueVehicle->user->mobile}}</small></p>
                                                        <p class="m-0 text-sm text-success"><small>{{$overdue_item->overdueVehicle->user->email}}</small></p>
                                                    <p class="text-sm m-0"><small>Start Date: {{ date('d M y h:i A', strtotime($overdue_item->overdueVehicle->start_date)) }}</small></p>
                                                        <p class="text-sm m-0"><small>End Date: {{ date('d M y h:i A', strtotime($overdue_item->overdueVehicle->end_date)) }}</small>
                                                        </p>
                                                    @else
                                                        <p class="m-0">N/A</p>
                                                        <p class="text-sm m-0"><small>Start Date: N/A</small></p>
                                                        <p class="text-sm m-0"><small>End Date: N/A</small>
                                                        </p>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($overdue_item->overdueVehicle)
                                                    <span class="badge px-2 rounded-pill bg-label-danger text-sm">Overdue</span>
                                                    @else
                                                        <span class="badge px-2 rounded-pill bg-label-warning text-sm">Unassigned Vehicle</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <div class="dropdown cursor-pointer">
                                                        <span class="badge px-2 rounded-pill bg-label-secondary dropdown-toggle" id="exploreDropdown_overdue_item_item_{{$overdue_item->id}}" data-bs-toggle="dropdown" aria-expanded="false">Explore</span>
                                                        <ul class="dropdown-menu" aria-labelledby="exploreDropdown_overdue_item_item_{{$overdue_item->id}}">
                                                            <li><a class="dropdown-item" href="{{route('admin.vehicle.detail', $overdue_item->vehicle_track_id)}}">Dashboard</a></li>
                                                            <li><a class="dropdown-item" href="{{route('admin.payment.summary')}}">Payment Summary</a></li>
                                                            <li><a class="dropdown-item" href="{{route('admin.payment.vehicle.summary',[$overdue_item->product_id,$overdue_item->id])}}">Payment History</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end mt-3 paginator">
                                    {{ $overdue_vehicles->links() }} <!-- Pagination links -->
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

