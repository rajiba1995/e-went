<div>
    
  <style>
    .card-icon {
      font-size: 2rem;
      padding: 10px;
      border-radius: 50%;
      display: inline-block;
      margin-right: 10px;
    }

    .deposit-icon {
      background-color: #ffeeba;
      color: #856404;
    }

    .rental-icon {
      background-color: #f8d7da;
      color: #721c24;
    }

    .refund-icon {
      background-color: #d1ecf1;
      color: #0c5460;
    }

    .summary-card {
      min-height: 100px;
    }
  </style>

  <!-- Summary Cards -->
    <div class="row text-white mb-4">
        <div class="col-auto my-auto">
            <div class="h-100">
              <h5 class="mb-0">Payment Summary</h5>
              <div>
                   <small class="text-dark fw-medium">Dashboard </small>
                   <small class="text-light fw-medium arrow"><a href="{{route('admin.vehicle.list')}}">Vehicles</a></small>
                   @if($model)
                      <small class="text-light fw-medium arrow"><a href="{{route('admin.payment.summary',[$model->id])}}">{{$model->title}}</a> </small>
                   @endif
                   @if($vehicle)
                      <small class="text-light fw-medium arrow">{{$vehicle->vehicle_number}}</small>
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
    </div>
  <div class="row text-white mb-4">
    <div class="col-md-4">
      <div class="card summary-card">
        <div class="card-body d-flex align-items-center">
          <div class="card-icon deposit-icon">📁</div>
          <div>
            <h6 class="mb-0">Deposit Amount</h6>
            <h5>{{ENV('APP_CURRENCY')}} {{$deposit_amount}}</h5>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card summary-card">
        <div class="card-body d-flex align-items-center">
          <div class="card-icon rental-icon">📉</div>
          <div>
            <h6 class="mb-0">Rental Amount</h6>
            <h5>{{ENV('APP_CURRENCY')}} {{$rental_amount}}</h5>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card summary-card">
        <div class="card-body d-flex align-items-center">
          <div class="card-icon refund-icon">💸</div>
          <div>
            <h6 class="mb-0">Refund Amount</h6>
            <h5>{{ENV('APP_CURRENCY')}} 0</h5>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Data Table -->
  <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
    <div class="row">
      <div class="col-12">
        <div class="card my-4">
          <div class="card-header pb-0">
            <div class="row">
                <div class="col-lg-2 col-2"></div>
                
                <div class="col-lg-10 col-10 my-auto text-end">
                    <div class="d-flex align-items-center justify-content-end flex-wrap gap-2">
                      <div style="max-width: 250px;
                            margin-bottom: 20px;" class="text-start text-uppercase">
                                 <label for="startDate" class="form-label small mb-1">Models</label>
                            <select
                                class="form-select border border-2 p-2 custom-input-sm" wire:model="model" wire:change="FilterModel($event.target.value)">
                                <option value="" selected hidden>Select model</option>
                                @foreach($models as $model_item)
                                <option value="{{ $model_item->id }}">{{$model_item->category->title}}|{{ $model_item->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Start Date -->
                        <div style="max-width: 250px;
                            margin-bottom: 20px;" class="text-start text-uppercase">
                            <label for="startDate" class="form-label small mb-1">Start Date</label>
                            <input type="date" id="startDate" wire:model="start_date" class="form-control border-2 p-2 custom-input-sm" wire:change="updateDate('start_date', $event.target.value)">
                        </div>
                            
                        <div style="max-width: 250px;
                            margin-bottom: 20px;" class="text-start text-uppercase">
                            <label for="endDate" class="form-label small mb-1">End Date</label>
                            <input type="date" id="endDate" wire:model="end_date" class="form-control border-2 p-2 custom-input-sm" wire:change="updateDate('end_date', $event.target.value)">
                        </div>
                        <!-- Search Button -->
                        {{-- <button type="button" wire:click="searchButtonClicked"
                            class="btn btn-dark text-white custom-input-sm">
                            <i class="ri-search-line"></i>
                        </button>
                     --}}
                        <!-- Reset Button -->
                        <a href="javascript:void(0)"
                            class="btn btn-danger text-white custom-input-sm" wire:click="resetPageField">
                            <i class="ri-restart-line"></i>
                        </a>
                    
                        <!-- Export Button -->
                        <button type="button" wire:click="exportAll"
                            class="btn btn-primary text-white custom-input-sm">
                            <i class="ri-download-2-line me-1"></i> Export All
                        </button>
                
                    </div>
                </div>
            </div>
              
          <div class="card-body px-0 pb-2 mt-2">
            <div class="table-responsive p-0">
              <table class="table align-items-center mb-0 product-list">
                <thead>
                  <tr>
                    <th
                      class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                      SL
                    </th>
                    <th
                      class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle"
                      width="25%">
                      Model
                    </th>
                    <th
                      class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                      Deposit Amount
                    </th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                      Rental Amount
                    </th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                     Total Amount
                    </th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                      Refund Amount
                    </th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                      Action
                    </th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($data as $key=> $item)
                    <tr>
                      <td class="align-middle text-center">{{$key+1}}</td>
                      <td class="sorting_1" width="25%">
                        <div class="d-flex justify-content-start align-items-center product-name">
                          <div class="avatar-wrapper me-4">
                            <div class="avatar rounded-2 bg-label-secondary">
                              <img src="{{asset($item['image'])}}" alt="Product-9"
                                class="rounded-2">
                            </div>

                          </div>
                          <div class="d-flex flex-column">
                            <span class="text-heading fw-medium">{{$item['title']}}</span>
                            {{-- <small class="text-truncate d-none d-sm-block">{{$item['types']}}</small> --}}
                          </div>
                        </div>
                      </td>
                      <td class="text-center">{{ENV('APP_CURRENCY')}}{{$item['deposit_amount']}}</td>
                      <td class="text-center">
                        {{ENV('APP_CURRENCY')}}{{$item['rental_amount']}}
                      </td>
                      <td class="text-center">
                        {{ENV('APP_CURRENCY')}}{{$item['total_amount']}}
                      </td>
                      <td class="text-center">
                        {{ENV('APP_CURRENCY')}}0
                      </td>
                      <td class="text-center">
                        <a href="javascript:void(0)" wire:click="toggleRow({{ $key }})">
                          <span class="control"></span>
                        </a>
                      </td>
                    </tr>
                    @if(in_array($key, $expandedRows))
                      @foreach ($item['vehicles'] as $vehicle)
                          <tr style="font-weight: 100;font-size:12px;">
                            <td colspan="2" class="text-end text-primary">{{$vehicle['vehicle_number']}}</td>
                            <td class="text-center text-primary">{{ENV('APP_CURRENCY')}}{{$vehicle['deposit_amount']}}</td>
                            <td class="text-center text-primary">
                              {{ENV('APP_CURRENCY')}}{{$vehicle['rental_amount']}}
                            </td>
                            <td class="text-center text-primary">
                              {{ENV('APP_CURRENCY')}}{{$vehicle['total_amount']}}
                            </td>
                            <td class="text-center text-primary">
                              {{ENV('APP_CURRENCY')}}0
                            </td>
                          </tr>
                      @endforeach
                    @endif
                    
                  @empty
                      <tr>
                          <td colspan="7">
                            <div class="alert alert-secondary">
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
  <div class="loader-container" wire:loading>
    <div class="loader"></div>
  </div>
</div>
