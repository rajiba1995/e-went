<div class="row mb-4">
    @if($active_tab==1)
        <div class="col-lg-12 d-flex justify-content-end">
            <button type="button"  class="btn btn-primary" wire:click="ActiveCreateTab(2)">
                <i class="ri-add-line ri-16px me-0 me-sm-2 align-baseline"></i>
                Create New Part
            </button>
            <button type="button"  class="btn btn-warning ms-2" wire:click="openFile()">
                <i class="ri-file-text-line ri-16px me-0 me-sm-2 align-baseline "></i>
                Import
            </button>
            <a href="{{ asset('storage/uploads/sample_csv/bom_part.csv') }}" target="_blank">
            <button type="button"  class="btn btn-success ms-2" >
                            <i class="ri-download-line ri-16px me-0 me-sm-2 align-baseline "></i>
                            Download
                        </button>
            </a>

        </div>
          <div class="col-lg-12 justify-content-left">
    <div class="row">
      @if(session()->has('message'))
      <div class="alert alert-success" id="flashMessage">
        {{ session('message') }}
      </div>
      @endif

    </div>
  </div>

       <form wire:submit.prevent="import" enctype="multipart/form-data" method="post">
        <div class="mb-3">
            <input type="file" wire:model="csv_file" class="form-control" id="csv_file" style="display: none;" ency>
            @error('csv_file') <span class="text-danger">{{ $message }}</span> @enderror
           <button type="submit" style="display:none;" id="csv_submit"></button>

        </div>


    </form>
    @else
        <div class="col-lg-12 d-flex justify-content-end">
            <button type="button" class="btn btn-dark btn-sm waves-effect waves-light" wire:click="ActiveCreateTab(1)" role="button">
                <i class="ri-arrow-go-back-line"></i> Back
            </button>
        </div>
    @endif

    @if($active_tab==2)
    <div class="col-lg-12 col-md-6 mb-md-0 my-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5>New Part</h5>
                        <form wire:submit.prevent="newSubmit">
                            <div class="row">
                                <!-- Product (Dropdown) -->
                                <div class="col-4 mb-3">
                                    <label for="product_id" class="form-label">Product <span class="text-danger">*</span></label>
                                    <select
                                        class="form-select @error('product_id') is-invalid @enderror"
                                        id="product_id"
                                        wire:model="product_id"
                                    >
                                        <option value="" hidden>Select product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->title }}</option> <!-- Adjust field name if needed -->
                                        @endforeach
                                    </select>
                                    @error('product_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <!-- Part Name -->
                                <div class="col-4 mb-3">
                                    <label for="part_name" class="form-label">Part Name <span class="text-danger">*</span></label>
                                    <input
                                        type="text"
                                        class="form-control @error('part_name') is-invalid @enderror"
                                        id="part_name"
                                        placeholder="Enter part name"
                                        wire:model="part_name"
                                    >
                                    @error('part_name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <!-- Part Number -->
                                <div class="col-4 mb-3">
                                    <label for="part_number" class="form-label">Part Number </label>
                                    <input
                                        type="text"
                                        class="form-control @error('part_number') is-invalid @enderror"
                                        id="part_number"
                                        placeholder="Enter part number"
                                        wire:model="part_number"
                                    >
                                    @error('part_number') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="row">
                                <!-- Part Unit -->
                                <div class="col-4 mb-3">
                                    <label for="part_unit" class="form-label">Part Unit <span class="text-danger">*</span></label>
                                    <input
                                        type="text"
                                        class="form-control @error('part_unit') is-invalid @enderror"
                                        id="part_unit"
                                        placeholder="Enter part unit"
                                        wire:model="part_unit"
                                    >
                                    @error('part_unit') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <!-- Part Price -->
                                <div class="col-4 mb-3">
                                    <label for="part_price" class="form-label">Part Price <span class="text-danger">*</span></label>
                                    <input
                                        type="number" step="0.01"
                                        class="form-control @error('part_price') is-invalid @enderror"
                                        id="part_price"
                                        placeholder="Enter part price"
                                        wire:model="part_price"
                                    >
                                    @error('part_price') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <!-- Warranty In Days -->
                                <div class="col-4 mb-3">
                                    <label for="warranty_in_day" class="form-label">Warranty (in days) </label>
                                    <input
                                        type="number"
                                        class="form-control @error('warranty_in_day') is-invalid @enderror"
                                        id="warranty_in_day"
                                        placeholder="Enter warranty days"
                                        wire:model="warranty_in_day"
                                    >
                                    @error('warranty_in_day') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="row">
                                <!-- Warranty Yes/No -->
                                <div class="col-4 mb-3">
                                    <label for="warranty" class="form-label">Warranty Available </label>
                                    <select
                                        class="form-select @error('warranty') is-invalid @enderror"
                                        id="warranty"
                                        wire:model="warranty">
                                        <option value="" hidden>Select warranty status</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                    @error('warranty') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <!-- Image (optional upload) -->
                                <div class="col-8 mb-3">
                                    <label for="image" class="form-label">Image</label>
                                    <input
                                        type="file"
                                        class="form-control @error('image') is-invalid @enderror"
                                        id="image"
                                        wire:model="image"
                                    >
                                    @error('image') <span class="text-danger">{{ $message }}</span> @enderror

                                    @if ($image)
                                        <div class="mt-2">
                                            <img src="{{ $image->temporaryUrl() }}" alt="Preview" style="max-height: 100px;">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary btn-sm">Create Part</button>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>
    @elseif($active_tab==3)
        <div class="col-lg-12 col-md-6 mb-md-0 my-4">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5>Edit Part</h5>
                           <form wire:submit.prevent="updatePart">
                                <div class="row">
                                    <!-- Product -->
                                    <div class="col-4 mb-3">
                                        <label for="editProductId" class="form-label">Product <span class="text-danger">*</span></label>
                                        <select
                                            class="form-select @error('product_id') is-invalid @enderror"
                                            id="editProductId"
                                            wire:model="product_id"
                                        >
                                            <option value="" hidden>Select product</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->title }}</option> <!-- Adjust field name -->
                                            @endforeach
                                        </select>
                                        @error('product_id') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <!-- Part Name -->
                                    <div class="col-4 mb-3">
                                        <label for="editPartName" class="form-label">Part Name <span class="text-danger">*</span></label>
                                        <input
                                            type="text"
                                            class="form-control @error('part_name') is-invalid @enderror"
                                            id="editPartName"
                                            placeholder="Enter part name"
                                            wire:model="part_name"
                                        >
                                        @error('part_name') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Part Number -->
                                    <div class="col-4 mb-3">
                                        <label for="editPartNumber" class="form-label">Part Number <span class="text-danger">*</span></label>
                                        <input
                                            type="text"
                                            class="form-control @error('part_number') is-invalid @enderror"
                                            id="editPartNumber"
                                            placeholder="Enter part number"
                                            wire:model="part_number"
                                        >
                                        @error('part_number') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Part Unit -->
                                    <div class="col-4 mb-3">
                                        <label for="editPartUnit" class="form-label">Part Unit <span class="text-danger">*</span></label>
                                        <input
                                            type="text"
                                            class="form-control @error('part_unit') is-invalid @enderror"
                                            id="editPartUnit"
                                            placeholder="Enter part unit"
                                            wire:model="part_unit"
                                        >
                                        @error('part_unit') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Part Price -->
                                    <div class="col-4 mb-3">
                                        <label for="editPartPrice" class="form-label">Part Price <span class="text-danger">*</span></label>
                                        <input
                                            type="number" step="0.01"
                                            class="form-control @error('part_price') is-invalid @enderror"
                                            id="editPartPrice"
                                            placeholder="Enter part price"
                                            wire:model="part_price"
                                        >
                                        @error('part_price') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Warranty In Days -->
                                    <div class="col-4 mb-3">
                                        <label for="editWarrantyInDay" class="form-label">Warranty (in days) <span class="text-danger">*</span></label>
                                        <input
                                            type="number"
                                            class="form-control @error('warranty_in_day') is-invalid @enderror"
                                            id="editWarrantyInDay"
                                            placeholder="Enter warranty days"
                                            wire:model="warranty_in_day"
                                        >
                                        @error('warranty_in_day') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Warranty -->
                                    <div class="col-4 mb-3">
                                        <label for="editWarranty" class="form-label">Warranty Available <span class="text-danger">*</span></label>
                                        <select
                                            class="form-select @error('warranty') is-invalid @enderror"
                                            id="editWarranty"
                                            wire:model="warranty"
                                        >
                                            <option value="" hidden>Select warranty status</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                        @error('warranty') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Image Upload (optional) -->
                                    <div class="col-8 mb-3">
                                        <label for="editImage" class="form-label">Image</label>
                                        <input
                                            type="file"
                                            class="form-control @error('image') is-invalid @enderror"
                                            id="editImage"
                                            wire:model="image"
                                        >
                                        @error('image') <span class="text-danger">{{ $message }}</span> @enderror

                                        @if ($image)
                                            <div class="mt-2">
                                                <img src="{{ $image->temporaryUrl() }}" alt="Preview" style="max-height: 100px;">
                                            </div>
                                        @elseif($existingImageUrl)
                                            <div class="mt-2">
                                                <img src="{{ $existingImageUrl }}" alt="Existing Image" style="max-height: 100px;">
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                    <button type="button" class="btn btn-secondary btn-sm" wire:click="resetForm">Cancel</button>
                                </div>
                            </form>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="col-lg-12 col-md-6 mb-md-0 my-4">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="row">
                                @if(session()->has('success'))
                                    <div class="alert alert-success" id="flashMessage">
                                        {{ session('success') }}
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
                                    <h6>BOM Parts</h6>
                                </div>
                                <div class="col-lg-6 col-5 my-auto text-end">
                                    <div class="d-flex align-items-center">
                                        <input type="text" wire:model.debounce.300ms="search"
                                               class="form-control border border-2 p-2 custom-input-sm"
                                               placeholder="Search here...">
                                        <button type="button" wire:click="searchButtonClicked"
                                                class="btn btn-dark text-white mb-0 custom-input-sm ms-2">
                                            <span class="material-icons">search</span>
                                        </button>
                                        <!-- Refresh Button -->
                                        <button type="button" wire:click="resetSearch"
                                                class="btn btn-danger text-white mb-0 custom-input-sm ms-2">
                                                <i class="ri-restart-line"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pb-2 mt-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Model</th>
                                            <th>Part Name</th>
                                            <th>Part Number</th>
                                            <th>Warranty</th>
                                            <th>Price</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($bom_parts as $index => $part)
                                            <tr>
                                                <td>
                                                    <img src="{{ $part->image ? asset($part->image) : asset('assets/img/no-data.png') }}" alt="Existing Image" style="max-height: 70px;">
                                                </td>

                                                <td>{{ optional($part->product)->title??'N/A' }}</td>
                                                <td>{{ $part->part_name }}</td>
                                                <td>{{ $part->part_number }}</td>
                                                <td>{{ $part->warranty }}</td>
                                                <td>{{ env('APP_CURRENCY') }}{{ number_format($part->part_price, 2) }}</td>
                                                <td>
                                                    <button wire:click="editPart({{ $part->id }})"
                                                            class="btn btn-sm btn-icon edit-record btn-text-secondary rounded-pill waves-effect btn-sm" title="Edit">
                                                        <i class="ri-edit-box-line ri-20px text-info"></i>
                                                    </button>
                                                    <button wire:click="deletePart({{ $part->id }})"
                                                            class="btn btn-sm btn-icon delete-record btn-text-secondary rounded-pill waves-effect btn-sm" title="Delete">
                                                        <i class="ri-delete-bin-7-line ri-20px text-danger"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">
                                                    <div class="alert alert-warning mb-0">No parts found.</div>
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
    @endif
    <div class="loader-container" wire:loading>
        <div class="loader"></div>
      </div>
</div>
@section('page-script')
<script>
   window.addEventListener('openFile', function (event) {
   document.getElementById('csv_file').click();


});
document.addEventListener('DOMContentLoaded', function () {
        const csvInput = document.getElementById('csv_file');
        if (csvInput) {
            csvInput.addEventListener('change', function () {
                setTimeout(() => {
                    Livewire.dispatch('autoImportCSV');
                }, 3000);
            });
        } else {
            console.warn('#csv_file not found');
        }
    });
</script>
@endsection


