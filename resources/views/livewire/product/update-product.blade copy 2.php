<div class="container-fluid px-2 px-md-4">
  <form wire:submit.prevent="updateProduct" enctype="multipart/form-data">
    <div class="row gx-4 mb-4">
      <div class="col-auto my-auto">
        <div class="h-100">
          <h5 class="mb-1"> Update Product</h5>
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
            <span> Update Product</span>
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
        <div class="card card-plain h-100 p-4">
          <div class="card-body p-3">
            <div class="row">
              <!-- Product Title -->
              <div class="form-floating form-floating-outline mb-3 mt-2">
                <input type="text" wire:model="title" class="form-control border border-2 p-2"
                  placeholder="Enter Title">
                <label>Product Title <span class="text-danger">*</span></label>
              </div>
              @error('title')
              <p class="text-danger inputerror">{{ $message }}</p>
              @enderror

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
              <div class="form-floating form-floating-outline mb-2 mt-2">
                <textarea wire:model="short_desc" class="form-control border border-2 p-2"
                  placeholder="Enter Short Description" id="ecommerce-category-description"></textarea>
                <label>Short Description</label>
              </div>
              @error('short_desc')
              <p class="text-danger inputerror">{{ $message }}</p>
              @enderror

              <!-- Long Description -->
              <div class="form-floating form-floating-outline mb-2 mt-2">
                <textarea wire:model="long_desc" class="form-control border border-2 p-2" rows="5"
                  placeholder="Enter Long Description"></textarea>
                <label>Long Description</label>
              </div>
              @error('long_desc')
              <p class="text-danger inputerror">{{ $message }}</p>
              @enderror
            </div>
          </div>
        </div>
      </div>
      <!-- Right Card -->
      <div class="col-lg-4">
        <div class="card card-plain mb-3">
          <div class="card-body p-3">
            <h6>Product Image</h6>
            <!-- Product Image -->
            <div class="mb-2 mt-2">
              <input type="file" wire:model="image" id="image" accept="image/*"
                class="form-control border border-2 p-2 d-none" onchange="updateImage(event, 'image')">
              <img id="image-preview"
                src="{{ asset('assets/img/default-product.webp') }}"
                alt="Selected Image" class="w-80 h-52 object-cover rounded-lg border border-gray-300"
                style="width: 100%" onclick="document.getElementById('image').click()">
            </div>
            @error('image')
            <p class="text-danger inputerror">{{ $message }}</p>
            @enderror
          </div>
        </div>

        <div class="card card-plain mb-3">
        <div class="card-body p-3">
            <h6>Additional Product Images</h6>
            <div class="mb-2 mt-2">
                <input type="file" wire:model="multipleImages" id="multipleImages" accept="image/*" multiple
                    class="form-control border border-2 p-2 d-none" onchange="previewMultipleImages(event)">
                <div id="multiple-image-preview" class="row">
                    @foreach ($multipleImages as $key => $image)
                        <div class="col-6 mb-3">
                            <img src="" alt="Selected Image {{ $key + 1 }}"
                                class="w-100 h-100 object-cover rounded-lg border border-gray-300">
                        </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-secondary mt-2"
                    onclick="document.getElementById('multipleImages').click()">Upload Images</button>
            </div>
            @error('multipleImages.*')
                <p class="text-danger inputerror">{{ $message }}</p>
            @enderror
        </div>
    </div>
        <!-- Pricing -->
        <div class="card card-plain mb-3">
          <div class="card-body p-3">
            <h6>Pricing</h6>
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
                For Rent
              </label>
            </div>

            @if($is_rent)
            <div class="form-floating form-floating-outline mb-2 mt-2">
              <input type="number" wire:model="per_rent_price" class="form-control border border-2 p-2"
                placeholder="Enter Per Rent Price">
              <label>Per Rent Price</label>
            </div>
            @error('per_rent_price')
            <p class="text-danger inputerror">{{ $message }}</p>
            @enderror
            @endif
          </div>
        </div>
        <!-- Product Type -->
        <div class="card card-plain">
            <div class="card-body p-3">
                <h6>Product Tags & Key</h6>
                <!-- Selling Price -->
                <div class="mb-2">
                    @foreach ($product_type as $ptype)
                        <div class="form-check">
                            <input 
                                type="checkbox" 
                                id="product_type_{{ $ptype->id }}" 
                                wire:model="selectedProductTypes" 
                                value="{{ $ptype->id }}" 
                                class="form-check-input"
                                {{ in_array($ptype->title, $selectedProductTypes) ? 'checked' : '' }}
                            >
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
    <div class="row mt-3">
      <div class="col-lg-8">
        <div class="card card-plain h-100 p-4">
          <div class="card-body p-3">
            <div class="row">
              <h5 class="mb-4">Feature Information</h5>

              <!-- Loop through each feature -->
              @foreach($features as $index => $feature)
                <div class="col-12 mb-3 d-flex align-items-center" id="feature_{{ $index }}">
                    <!-- Feature Title Input -->
                    <div class="d-flex flex-column w-100 me-2">
                    <div class="form-floating form-floating-outline mb-2">
                        <input type="text" wire:model="features.{{ $index }}.title" class="form-control border border-2 p-2"
                        placeholder="Enter Feature Title">
                        <label>Title</label>
                    </div>
                    </div>

                    <!-- Remove Button for feature -->
                    <div>
                    <button type="button" class="btn btn-danger btn-sm" wire:click="removeFeature({{ $index }})">
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
    </div>
    <div class="row mt-3">
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
    </div>
  </form>
</div>

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
