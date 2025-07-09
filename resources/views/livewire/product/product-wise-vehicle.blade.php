<div>
    <div class="row mb-2">
        <div class="row gx-4">
            <div class="col-auto my-auto">
              <div class="h-100">
                <h5 class="mb-1"> {{$product_name}}</h5>
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
      <div class="col-lg-12 col-md-12 mb-md-0 mb-4">
        <div class="row">
          <div class="col-12">
            <div class="card my-4">
              <div class="card-header pb-2">
                <div class="row">
                  @if(session()->has('message'))
                  <div class="alert alert-success" id="flashMessage">
                    {{ session('message') }}
                  </div>
                  @endif
                </div>
                <div class="row">
                  <div class="col-lg-6 col-7">
                    <h6>Vehicle List</h6>
                  </div>
                  <div class="col-lg-6 col-5 my-auto text-end">
                    <div class="ms-md-auto d-flex align-items-center">
                      <input type="text" wire:model.debounce.500ms="search"
                        class="form-control border border-2 p-2 custom-input-sm" placeholder="Enter Vehicle Number">
                      <button type="button" wire:click="searchButtonClicked" class="btn btn-dark text-white mb-0 custom-input-sm">
                        <span class="material-icons">search</span>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body px-0 pb-2 mt-2 vehicle_body">
                <div class="row mx-2 mt-2">
                    @foreach ($data as $item)
                    <div class="col-12 col-sm-6 col-md-3 col-lg-2 mb-3 px-1">
                        <div class="vehicle-card">
                            <img src="{{ asset($item->product->image) }}" alt="Vehicle Image" class="vehicle-image">
                            <h3 class="vehicle-number">{{ $item->vehicle_number }}</h3>
                                @php
                                    $vehicleData = VehicleStatus($item->id);
                                @endphp
                                @if (!empty($vehicleData) && !empty($vehicleData['order_id']))
                                    <!-- This vehicle is assigned to an order -->
                                    <div class="text-center">
                                        <a href="{{route('admin.order.detail',$vehicleData['order_id'])}}"><span class="badge bg-label-{{$vehicleData['class']}} mb-0 cursor-pointer" title="">{{$vehicleData['message']}}</span></a>
                                    </div>
                                @else
                                <div class="icon-container justify-content-{{vehicleLog($item->id)==0?"between":"center"}}">
                                    <div>
                                        <span class="badge bg-label-{{ $item->status == 1 ? 'success' : 'danger' }} mb-0 cursor-pointer" 
                                            wire:click="UpdateStatus('{{ $item->id }}')" 
                                            title="Update Vehicle Status">
                                            {{ $item->status == 1 ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                    @if(vehicleLog($item->id)==0)
                                        <div>
                                            {{-- <span class="icon tf-icons ri-edit-line text-primary cursor-pointer" 
                                                wire:click="UpdateVehicle('{{ $item->id }}')" 
                                                title="Update Vehicle"></span> --}}
                                    
                                            <span class="icon tf-icons ri-delete-bin-line text-danger cursor-pointer" 
                                                wire:click="deleteVehicle('{{ $item->id }}')" 
                                                title="Delete Vehicle"></span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                    <div class="d-flex justify-content-end mt-2">
                        {{ $data->links('pagination::bootstrap-4') }}
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    
      {{-- <div class="col-lg-4 col-md-6 mb-md-0 mb-4">
        <div class="row">
          <div class="col-12">
            <div class="card my-4">
              <div class="card-body px-0 pb-2 mx-4">
                <div class="d-flex justify-content-between mb-3">
                  <h5>{{$vehicle_number ? "Update Vehicle" : "Create Vehicle"}}</h5>
                </div>
                <form wire:submit.prevent="save">
                  <div class="row">
                    <div class="form-floating form-floating-outline mb-5 fv-plugins-icon-container">
                      <input type="text" wire:model="product_name" class="form-control border border-2 p-2"
                        placeholder="Enter Product Name" disabled>
                      <label> Product Name <span class="text-danger">*</span></label>
                    </div>
                    @error('product_name')
                    <p class='text-danger inputerror'>{{ $message }}</p>
                    @enderror
                    <div class="form-floating form-floating-outline mb-5 fv-plugins-icon-container">
                      <input type="text" wire:model="vehicle_number" class="form-control border border-2 p-2"
                        placeholder="Enter Vehicle Number">
                      <label>Vehicle Number <span class="text-danger">*</span></label>
                    </div>
                    @error('vehicle_number')
                    <p class='text-danger inputerror'>{{ $message }}</p>
                    @enderror
                    <div class="mb-2 text-end mt-4">
                        <button type="button" wire:click="resetSearch" class="btn btn-danger text-white mb-0 custom-input-sm ms-2">
                                <i class="ri-restart-line"></i>
                        </button>
                          <button type="submit" class="btn btn-secondary btn-sm add-new btn-primary waves-effect waves-light">
                              <span>{{ $vehicle_number ? "Update Vehicle" : "Create Vehicle" }}</span>
                          </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div> --}}
    </div>
    
    <div class="loader-container" wire:loading>
      <div class="loader"></div>
    </div>
    </div>
    @section('page-script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
         window.addEventListener('showConfirm', function (event) {
        let itemId = event.detail[0].itemId;
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('deleteItem', itemId); // Calls Livewire method directly
                Swal.fire("Deleted!", "Your item has been deleted.", "success");
            }
        });
    });
    </script>
    @endsection
    