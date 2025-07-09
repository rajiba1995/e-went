<div class="container-fluid px-2 px-md-4">
  <form wire:submit.prevent="updateProduct" enctype="multipart/form-data">
    <div class="row gx-4 mb-4">
      <div class="col-auto my-auto">
        <div class="h-100">
          <h5 class="mb-0">Model Management</h5>
          <div>
            <small class="text-dark fw-medium">Model</small>
            <small class="text-light fw-medium arrow">Update Model</small>
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
          <button type="submit" class="btn btn-secondary btn-sm add-new btn-primary waves-effect waves-light"
            wire:loading.attr="disabled">
            <span> Update Model</span>
          </button>
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
      <div class="col-lg-8">
        <div class="card card-plain p-4">
          <div class="card-body p-3">
            <div class="row">
              <!-- Product Title -->
              <div class="col-9">
                <div class="form-floating form-floating-outline mb-3 mt-2">
                  <input type="text" wire:model="title" class="form-control border border-2 p-2"
                    placeholder="Enter Title">
                  <label>Model Title <span class="text-danger">*</span></label>
                </div>
                @error('title')
                <p class="text-danger inputerror">{{ $message }}</p>
                @enderror
              </div>
              <div class="col-3">
                <div class="form-floating form-floating-outline mb-3 mt-2">
                  <input type="text" wire:model="product_sku" class="form-control border border-2 p-2"
                    placeholder="Enter sku">
                  <label>Model SKU <span class="text-danger">*</span></label>
                </div>
                @error('product_sku')
                <p class="text-danger inputerror">{{ $message }}</p>
                @enderror
              </div>

              <!-- Category Select -->
              <div class="col-6">
                <div class="mb-2 form-floating form-floating-outline">
                  <select wire:model="category_id" wire:change="GetSubcat($event.target.value)"
                    class="form-select border border-2 p-2">
                    <option value="" selected hidden>Select Category</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                    @endforeach
                  </select>
                  <label class="form-label">Category</label>
                </div>
                @error('category_id')
                <p class="text-danger inputerror">{{ $message }}</p>
                @enderror
              </div>
              <!-- Subcategory Select -->
              <div class="col-6">
                <div class="form-floating form-floating-outline mb-2">
                  <select wire:model="sub_category_id" class="form-select border border-2 p-2">
                    <option value="" selected hidden>Select Subcategory</option>
                    @foreach($subcategories as $subcategory)
                    <option value="{{ $subcategory->id }}" {{ $subcategory->id == $sub_category_id ? 'selected' : '' }}>
                      {{ $subcategory->title }}</option>
                    @endforeach
                  </select>
                  <label class="form-label">Subcategory</label>
                </div>
                @error('sub_category_id')
                <p class="text-danger inputerror">{{ $message }}</p>
                @enderror
              </div>
              <!-- Short Description -->
              {{-- <div class="card card-plain mb-3 p-0" style="box-shadow: unset;">
                <div class="card-body p-3">
                  <h6>Product Short Description</h6>
                  <hr>
                  <!-- Selling Price -->
                  <div class="mb-2">
                    <textarea id="short_desc" wire:model="short_desc" class="form-control border border-2 p-2"
                        placeholder="Enter Short Description"></textarea>
                      @error('short_desc')
                      <p class="text-danger inputerror">{{ $message }}</p>
              @enderror
            </div>
          </div>
        </div> --}}
        <!-- Long Description -->
        {{-- <div class="card card-plain mb-3 p-0" style="box-shadow: unset;">
                <div class="card-body p-3">
                  <h6>Product Long Description</h6>
                  <hr>
                  <!-- Selling Price -->
                  <div class="mb-2">
                    <textarea id="long_desc" wire:model="long_desc" class="form-control border border-2 p-2"
                        placeholder="Enter Long Description"></textarea>
                      @error('long_desc')
                      <p class="text-danger inputerror">{{ $message }}</p>
        @enderror
      </div>
    </div>
</div> --}}
</div>
</div>
</div>
<div class="row p-0 mt-2">
  <div class="col-lg-6">
    <div class="card card-plain mb-3">
      <div class="card-body p-3">
        <h6>Availablity</h6>
        <hr>

        <!-- Selling Price -->
        <div class="mb-2">
          <label class="form-check-label" for="is_selling">
            <input type="checkbox" id="is_selling" wire:model="is_selling" class="form-check-input"
              wire:change="toggleSellingFields">
            For Selling
          </label>
        </div>

        @if($is_selling)
        <div class="form-floating form-floating-outline mb-2 mt-2">
          <input type="number" wire:model="base_price" class="form-control border border-2 p-2"
            placeholder="Enter Base Price">
          <label>Base Price</label>
        </div>
        @error('base_price')
        <p class="text-danger inputerror">{{ $message }}</p>
        @enderror

        <div class="form-floating form-floating-outline mb-2 mt-2">
          <input type="number" wire:model="display_price" class="form-control border border-2 p-2"
            placeholder="Enter Display Price">
          <label>Display Price</label>
        </div>
        @error('display_price')
        <p class="text-danger inputerror">{{ $message }}</p>
        @enderror
        @endif
        <!-- Rent -->
        <div class="mb-2">
          <label class="form-check-label" for="is_rent">
            <input type="checkbox" id="is_rent" wire:model="is_rent" class="form-check-input"
              wire:change="toggleRentFields">
            For Rental
          </label>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-6">
    <!-- Product Type -->
    <div class="card card-plain">
      <div class="card-body p-3">
        <h6>Model Tags & Key</h6>
        <!-- Selling Price -->
        <div class="mb-2">
          @foreach ($product_type as $k => $ptype)
          <div class="form-check">
            <input type="checkbox" id="product_type_{{ $ptype->id }}" wire:model="selectedProductTypes"
              value="{{ $ptype->title }}" class="form-check-input">
            <label class="form-check-label" for="product_type_{{ $ptype->id }}">
              {{ $ptype->title }}
            </label>
          </div>
          @endforeach
        </div>

      </div>
    </div>
  </div>
</div>
<div class="card card-plain mt-2 p-4">
  <div class="card-body p-3">
    <div class="row">
      <h5 class="mb-4">Feature Information</h5>

      <!-- Loop through each feature -->

      @foreach($features as $index => $feature)
      <div class="col-12 mb-3 d-flex align-items-center product-feature" id="feature_{{ $index }}">
        <!-- Feature Title Input -->
        <div class="d-flex flex-column w-100 me-2">
          <div class="form-floating form-floating-outline mb-2">
            <input type="text" wire:model="features.{{ $index }}.title" class="form-control border border-2 p-2"
              placeholder="Enter Feature Title">
            <label>Title</label>
          </div>
        </div>

        <!-- Remove Button for Feature -->
        <div>
          <button type="button" class="btn btn-danger btn-sm mt-n2-important" wire:click="removeFeature({{ $index }})">
            <i class="ri-close-circle-line"></i>
          </button>
        </div>
      </div>
      @error("features.$index.title")
      <p class="text-danger inputerror">{{ $message }}</p>
      @enderror
      @endforeach

      <!-- Add More Button -->
      <div class="col-12 text-end mt-3">
        <button type="button" class="btn btn-secondary btn-sm add-new btn-primary waves-effect waves-light"
          wire:click="addFeature">
          <i class="ri-add-circle-fill"> </i> Add
        </button>
      </div>
    </div>
  </div>
</div>
</div>
<!-- Right Card -->
<div class="col-lg-4">
  <div class="card card-plain mb-3">
    <div class="card-body p-3">
      <div class="mb-2">
        <label class="form-check-label" for="is_driving_licence_required">
          <input type="checkbox" id="is_driving_licence_required" wire:model="is_driving_licence_required"
            class="form-check-input">
          Driving Licence Required
        </label>
      </div>
    </div>
  </div>
  <div class="card card-plain mb-3">
    <div class="card-body p-3">
      <h6>Model Image</h6>
      <!-- Product Image -->
      <div class="mb-2 mt-2">
        <input type="file" wire:model="image" id="image" accept="image/*"
          class="form-control border border-2 p-2 d-none" onchange="updateImage(event, 'image')">
        <img id="image-preview" src="{{ asset($image) }}" alt="Selected Image"
          class="w-80 h-52 object-cover rounded-lg border border-gray-300" style="width: 100%"
          onclick="document.getElementById('image').click()">
      </div>
      @error('image')
      <p class="text-danger inputerror">{{ $message }}</p>
      @enderror
    </div>
  </div>


  <div class="card card-plain mb-3">
    <div class="card-body p-3">
      <h6>Additional Model Images</h6>
      <div class="mb-2 mt-2">
        <!-- Input for file selection -->
        <input type="file" wire:model="multipleImages" id="multipleImages" accept="image/*" multiple
          class="form-control border border-2 p-2 d-none" onchange="previewMultipleImages(event)">

        <!-- Drag and Drop Area -->
        <button type="button" onclick="document.getElementById('multipleImages').click()"
          class="border-0 bg-white w-100">
          <div id="drop-zone" class="drag-drop-area">
            <p>Choose your images.</p>
          </div>
        </button>
        <!-- Display existing images -->
        <div id="existing-images" class="row my-1 p-2">
          @foreach ($existingImages as $id => $image)
          <div class="col-6 mb-3 position-relative">
            <img src="{{ asset($image) }}" alt="Existing Image {{ $loop->iteration }}"
              class="w-100 h-100 object-cover rounded-lg border border-gray-300 p-3">
            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 p-1"
              wire:click="removeExistingImage({{ $id }})" title="Remove Image">
              <i class="ri-close-circle-line"></i>
            </button>
          </div>
          @endforeach
        </div>

        <!-- Display newly uploaded images -->
        <div id="new-images-preview" class="row my-1 p-2">
          @foreach ($multipleImages as $index => $image)
          <div class="col-6 mb-3 position-relative">
            <img src="{{ $image->temporaryUrl() }}" alt="New Image {{ $index + 1 }}"
              class="w-100 h-100 object-cover rounded-lg border border-gray-300 p-3">
            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 p-1"
              wire:click="removeNewImage({{ $index }})" title="Remove Image">
              <i class="ri-close-circle-line"></i>
            </button>
          </div>
          @endforeach
        </div>
      </div>
      @error('multipleImages.*')
      <p class="text-danger inputerror">{{ $message }}</p>
      @enderror
    </div>
  </div>
</div>
</div>

{{-- <div class="row mt-3">
      <div class="col-lg-8">
        <div class="card card-plain h-100 p-4">
          <div class="card-body p-3">
            <div class="row">
              <h5 class="mb-4">Meta Information</h5>
              <!-- Meta Title -->
              <div class="form-floating form-floating-outline mb-2 mt-2">
                <input type="text" wire:model="meta_title" class="form-control border border-2 p-2"
                  placeholder="Enter Meta Title">
                <label>Meta Title</label>
              </div>
              @error('meta_title')
              <p class="text-danger inputerror">{{ $message }}</p>
@enderror

<!-- Meta Keywords -->
<div class="form-floating form-floating-outline mb-2 mt-2">
  <textarea wire:model="meta_keyword" class="form-control border border-2 p-2"
    placeholder="Enter Meta Keywords"></textarea>
  <label>Meta Keywords</label>
</div>
@error('meta_keyword')
<p class="text-danger inputerror">{{ $message }}</p>
@enderror

<!-- Meta Description -->
<div class="form-floating form-floating-outline mb-2 mt-2">
  <textarea wire:model="meta_description" class="form-control border border-2 p-2"
    placeholder="Enter Meta Description"></textarea>
  <label>Meta Description </label>
</div>
@error('meta_description')
<p class="text-danger inputerror">{{ $message }}</p>
@enderror
</div>
</div>
</div>
</div>
</div> --}}
</form>
<div class="loader-container" wire:loading>
  <div class="loader"></div>
</div>
</div>
@section('page-script')
<script>
  function updateImage(event, name) {
    const fileInput = event.target;
    const file = fileInput.files[0];
    const imageElement = document.getElementById(`image-preview`);

    if (file) {
      const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
      if (validTypes.includes(file.type)) {
        const reader = new FileReader();
        reader.onload = function (e) {
          imageElement.src = e.target.result; // Update the image source
        };
        reader.readAsDataURL(file);
      } else {
        alert('Invalid file type. Please select a valid image (JPEG, PNG, GIF, WEBP).');
        fileInput.value = ''; // Reset the input field
        imageElement.src = '{{ asset('
        assets / img /
          default -product.webp ') }}'; // Reset to default
      }
    }
  }
</script>
<!-- Include CKEditor 5 -->
<script type="text/javascript" src="{{ asset('build/ckeditor/ckeditor.js') }}"></script>

<script>
  // Wait until the content is loaded before initializing CKEditor
  window.addEventListener('ck_editor_load', function (event) {
    // Handle short_desc
    var shortDescTextArea = document.getElementById('short_desc');
    if (shortDescTextArea) {
      // Check if CKEditor instance already exists and destroy it
      if (CKEDITOR.instances['short_desc']) {
        CKEDITOR.instances['short_desc'].destroy(true);
      }

      // Initialize CKEditor for short_desc
      if (typeof CKEDITOR !== 'undefined') {
        CKEDITOR.replace('short_desc');
        // Sync CKEditor data to Livewire
        CKEDITOR.instances['short_desc'].on('change', function () {
          @this.set('short_desc', CKEDITOR.instances['short_desc'].getData());
        });
      } else {
        console.error('CKEditor is not defined!');
      }
    }

    // Handle long_desc
    var longDescTextArea = document.getElementById('long_desc');
    if (longDescTextArea) {
      // Check if CKEditor instance already exists and destroy it
      if (CKEDITOR.instances['long_desc']) {
        CKEDITOR.instances['long_desc'].destroy(true);
      }

      // Initialize CKEditor for long_desc
      if (typeof CKEDITOR !== 'undefined') {
        CKEDITOR.replace('long_desc');
        // Sync CKEditor data to Livewire
        CKEDITOR.instances['long_desc'].on('change', function () {
          @this.set('long_desc', CKEDITOR.instances['long_desc'].getData());
        });
      } else {
        console.error('CKEditor is not defined!');
      }
    }
  });
</script>


@endsection
