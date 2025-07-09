<div>
    <div class="row gx-4 mb-4">
        <div class="col-auto my-auto">
          <div class="h-100">
            <h5 class="mb-0">Vehicle Management</h5>
            <div>
                 <small class="text-dark fw-medium">Dashboard </small>
                 <small class="text-light fw-medium arrow">New Vehicle</small>
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
              <div class="alert alert-danger">
                  {{ session('error') }}
              </div>
          @endif
      </div>
      <div class="row">
        <!-- Left Card -->
        <div class="col-lg-12">
          <div class="card card-plain p-4">
            <div class="card-body p-3">
                <form wire:submit.prevent="saveVehicle">
                    <div class="row">
                        <!-- Product Title -->
                        <div class="col-4">
                            <div class="mb-2 mt-2 form-floating form-floating-outline">
                                <select wire:model="model"
                                    class="form-select border border-2 p-2">
                                    <option value="" selected hidden>Select model</option>
                                    @foreach($models as $model_item)
                                    <option value="{{ $model_item->id }}">{{$model_item->category->title}}|{{ $model_item->title }}</option>
                                    @endforeach
                                </select>
                                <label class="form-label">Model <span class="text-danger">*</span></label>
                            </div>
                            @error('model')
                            <p class="text-danger inputerror">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-4">
                            <div class="mb-2 mt-2 form-floating form-floating-outline">
                                <select wire:model="vehicle_number"
                                    class="form-select border border-2 p-2" wire:change="selectVehicle($event.target.value)">
                                    <option value="" selected hidden>Select vehicle number</option>
                                    @foreach($vehicles as $vehicle_item)
                                        @if(!in_array($vehicle_item['number'], $existing_stock))
                                            <option value="{{ $vehicle_item['number'] }}">{{ $vehicle_item['number'] }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <label class="form-label">Vehicle Number <span class="text-danger">*</span></label>
                            </div>
                            @error('vehicle_number')
                            <p class="text-danger inputerror">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-4">
                            <div class="form-floating form-floating-outline mb-3 mt-2">
                            <input type="text" wire:model="vehicle_track_id" class="form-control border border-2 p-2"
                                placeholder="T256356">
                            <label>Vehicle Track ID <span class="text-danger">*</span></label>
                            </div>
                            @error('vehicle_track_id')
                            <p class="text-danger inputerror">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-4">
                            <div class="form-floating form-floating-outline mb-3 mt-2">
                            <input type="text" wire:model="friendly_name" class="form-control border border-2 p-2"
                                placeholder="Enter friendly name">
                            <label>Friendly Name</label>
                            </div>
                            @error('friendly_name')
                            <p class="text-danger inputerror">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="col-4">
                            <div class="form-floating form-floating-outline mb-3 mt-2">
                            <input type="text" wire:model="imei_number" class="form-control border border-2 p-2"
                                placeholder="Enter imei number">
                            <label>LOT IMEI Number</label>
                            </div>
                            @error('imei_number')
                            <p class="text-danger inputerror">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-4">
                            <div class="form-floating form-floating-outline mb-3 mt-2">
                            <input type="text" wire:model="chassis_number" class="form-control border border-2 p-2"
                                placeholder="Enter chassis number">
                            <label>Chassis Number<span class="text-danger">*</span></label>
                            </div>
                            @error('chassis_number')
                            <p class="text-danger inputerror">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="text-end">
                            <button type="submit" class="btn btn-secondary btn-sm add-new btn-primary waves-effect waves-light"
                            wire:loading.attr="disabled">
                            <span> Create Vehicle</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
          </div>
        </div>
    </div>
    <div class="loader-container" wire:loading>
        <div class="loader"></div>
    </div>
</div>
