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
                   <small class="text-light fw-medium arrow">{{$vehicle->vehicle_number}}</small>
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
            <h5>₹ 51108</h5>
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
            <h5>₹ 393176</h5>
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
            <h5>₹ 0</h5>
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
                    
                        <!-- Start Date -->
                        <div style="max-width: 250px;
                            margin-bottom: 20px;" class="text-start text-uppercase">
                            <label for="startDate" class="form-label small mb-1">Start Date</label>
                            <input type="date" id="startDate" class="form-control border-2 p-2 custom-input-sm">
                        </div>
                            
                        <div style="max-width: 250px;
                            margin-bottom: 20px;" class="text-start text-uppercase">
                            <label for="endDate" class="form-label small mb-1">End Date</label>
                            <input type="date" id="endDate" class="form-control border-2 p-2 custom-input-sm">
                        </div>
                        <!-- Search Button -->
                        <button type="button" wire:click="searchButtonClicked"
                            class="btn btn-dark text-white custom-input-sm">
                            <i class="ri-search-line"></i>
                        </button>
                    
                        <!-- Reset Button -->
                        <button type="button" wire:click="resetSearch"
                            class="btn btn-danger text-white custom-input-sm">
                            <i class="ri-restart-line"></i>
                        </button>
                    
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
                      Total Quantity
                    </th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                      Assigned Quantity
                    </th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                      No of Sold
                    </th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                      Available Quantity
                    </th>

                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                      Action
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <!--[if BLOCK]><![endif]-->
                  <!--[if BLOCK]><![endif]-->
                  <tr>
                    <td class="align-middle text-center">1</td>
                    <td class="sorting_1" width="25%">
                      <div class="d-flex justify-content-start align-items-center product-name">
                        <div class="avatar-wrapper me-4">
                          <div class="avatar rounded-2 bg-label-secondary">
                            <img src="http://127.0.0.1:8000/storage/uploads/product/5510_1743129690.png" alt="Product-9"
                              class="rounded-2">
                          </div>

                        </div>
                        <div class="d-flex flex-column">
                          <span class="text-heading fw-medium"> Lightning</span>
                          <small class="text-truncate d-none d-sm-block"> LTNG</small></div>
                      </div>
                    </td>
                    <td class="text-center">3</td>
                    <td class="text-center">
                      2
                    </td>
                    <td class="text-center">
                      0
                    </td>
                    <td class="text-center">
                      1
                    </td>
                    <td class="text-center">
                      <a href="http://127.0.0.1:8000/admin/stock/vehicle/1">
                        <span class="control"></span>
                      </a>
                    </td>
                  </tr>
                  <!--[if ENDBLOCK]><![endif]-->
                  <!--[if ENDBLOCK]><![endif]-->
                </tbody>
              </table>

              <div class="d-flex justify-content-end mt-2">
                <div>
                  <!--[if BLOCK]><![endif]-->
                  <!--[if ENDBLOCK]><![endif]-->
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
