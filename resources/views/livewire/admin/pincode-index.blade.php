<div>
    <div class="row">
        <div class="col-lg-12 d-flex justify-content-end">
            <button type="button" class="btn btn-primary" wire:click="ModalImport(1)">
                <i class="ri-add-line ri-16px me-0 me-sm-2 align-baseline"></i>
                Import
            </button>
        </div>
        <div class="modal fade {{$modal_activity_class==1?"show d-block":""}}" id="uploadStockModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Upload Pincode via CSV</h5>
                        <button type="button" class="btn-close" wire:click="ModalImport(0)"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="uploadFile">
                            <div class="mb-3">
                                <label for="csvFile" class="form-label">Select CSV File</label>
                                <input class="form-control" type="file" id="csvFile" wire:model="csvFile" accept=".csv,.txt">
                                @error('csvFile') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <a href="{{ asset('assets/csv/city-sample-pincode.csv') }}" class="btn btn-link" download>
                                    <i class="ri-download-line"></i> Download Sample CSV File
                                </a>
                            </div>
                            <div class="text-end">
                                @if(session()->has('csv_error'))
                                    <div class="alert alert-danger">
                                        {{ session('csv_error') }}
                                    </div>
                                @endif
                                <button type="submit" class="btn btn-primary">Upload</button>
                            </div>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
      <div class="col-lg-9 col-md-6 mb-md-0 mb-4">
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
                  @if(session()->has('error'))
                  <div class="alert alert-danger">
                    {{ session('error') }}
                  </div>
                  @endif
                </div>
                <div class="row">
                  <div class="col-lg-6 col-7">
                    <h6>Pincode</h6>
                  </div>
                  <div class="col-lg-6 col-5 my-auto text-end">
                    <div class="ms-md-auto d-flex align-items-center">
                      <input type="text" wire:model.debounce.500ms="search"
                        class="form-control border border-2 p-2 custom-input-sm" placeholder="Enter city or pincodes">
                      <button type="button" wire:click="searchButtonClicked" class="btn btn-dark text-white mb-0 custom-input-sm">
                        <span class="material-icons">search</span>
                      </button>
                      <!-- Optionally, add a search icon button -->
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body px-0 pb-2">
                <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th
                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                                SL
                            </th>
                            <th
                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                                City
                            </th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                                Pincodes
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pincodes as $k => $code)
                        <tr>
                            <td class="align-middle text-center">{{ $k + 1 }}</td>
    
                            {{-- Display city Image --}}
                            <td class="align-middle text-center">
                                {{ ucwords($code->city->name) }}
                            </td>
                            <td class="align-middle text-center">
                                @php
                                    $pins = explode(',',$code->pins);
                                @endphp
                                @forelse ($pins as $item)
                                <button type="button" class="btn rounded-pill btn-xs btn-outline-{{PincodeStatus($item) == 1?"success":"danger"}} waves-effect waves-light mt-1">
                                   <span  wire:click="edit({{ PincodeId($item) }})"> {{ $item }} </span>
                                    <span class="tf-icons {{ PincodeStatus($item) == 1 ? 'ri-checkbox-circle-line text-success' : 'ri-close-circle-line text-danger' }} ri-16px ms-1_5" wire:click="UpdatePinStatus('{{$item}}')"></span>
                                    <span class="tf-icons ri-delete-bin-line text-danger ri-16px ms-1_5" wire:click="deletePinCode('{{ $item }}')"  title="Delete Pincode"></span>
                                </button>
                                @empty
                                    <div class="alert alert-danger">
                                        Data not found!
                                    </div>
                                @endforelse
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
    
    
                  <div class="d-flex justify-content-end mt-2">
                    {{-- {{ $banners->links() }} --}} 
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    
      <div class="col-lg-3 col-md-6 mb-md-0 mb-4">
        <div class="row">
          <div class="col-12">
            <div class="card my-4">
              <div class="card-body px-0 pb-2 mx-4">
                <div class="d-flex justify-content-between mb-3">
                  <h5>{{ $pinId ? "Update Pincode" : "Assign Pincode" }}</h5>
                </div>
                <form wire:submit.prevent="save">
                  <div class="row">
    
                    <div class="form-floating form-floating-outline mb-5 fv-plugins-icon-container">
                        <select class="form-control border border-2 p-2" wire:model="city_id">
                            <option value="" selected hidden>Select City...</option> <!-- Placeholder -->
                            @foreach ($cities as $item)
                                <option value="{{$item->id}}">{{ucwords($item->name)}}</option>
                            @endforeach
                        </select>
                        <label> City <span class="text-danger">*</span></label>
                    </div>
                    @error('city_id')
                    <p class='text-danger inputerror'>{{ $message }}</p>
                    @enderror
                    <div class="form-floating form-floating-outline mb-5 fv-plugins-icon-container">
                      <input type="text" wire:model="pincode" class="form-control border border-2 p-2"
                        placeholder="Enter Pincode">
                      <label> Pincode <span class="text-danger">*</span></label>
                    </div>
                    @error('pincode')
                    <p class='text-danger inputerror'>{{ $message }}</p>
                    @enderror
                    <div class="mb-2 text-end mt-4">
                        <button type="button" wire:click="refresh" class="btn btn-danger text-white mb-0 custom-input-sm ms-2">
                                <i class="ri-restart-line"></i>
                        </button>
                          <button type="submit" class="btn btn-secondary btn-sm add-new btn-primary waves-effect waves-light">
                              <span>{{ $pinId ? "Update" : "Save" }}</span>
                          </button>
                    </div>
                  </div>
                </form>
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
    @section('page-script')
    {{-- Include Tom Select CSS and JS (No jQuery Required) --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tom-select/2.2.2/css/tom-select.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tom-select/2.2.2/js/tom-select.complete.min.js"></script>

    <script>
       document.addEventListener('DOMContentLoaded', function () {
        let selects = document.querySelectorAll('.select2'); // Select elements with class "select2"
        selects.forEach(select => {
            new TomSelect(select, {
                placeholder: "Select City...",  // ✅ Set the placeholder
                allowEmptyOption: true,        // ✅ Allow an empty option for placeholder
                maxItems: 1                    // ✅ Ensure single selection
            });
        });
    });
    // Reinitialize after Livewire updates (if using Livewire)
    window.addEventListener('select2_reload', function () {
        document.querySelectorAll('.select2').forEach(select => {
            // Destroy existing TomSelect instance if it already exists
            if (select.tomselect) {
                select.tomselect.destroy(); 
                select.tomselect = null; // Ensure it's fully removed
            }

            // Initialize TomSelect
            new TomSelect(select, {
                placeholder: "Select City...",
                allowEmptyOption: true,
                maxItems: 1
            });
        });
    });
    </script>
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

    