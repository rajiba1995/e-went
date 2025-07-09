<div class="container-fluid px-2 px-md-4">
  <form wire:submit.prevent="saveProduct" enctype="multipart/form-data">
    <div class="row gx-4 mb-4">
      <div class="col-auto my-auto">
        <div class="h-100">
          <h5 class="mb-0">Employee Management</h5>
          <div>
               <small class="text-dark fw-medium">Employee</small>
               <small class="text-light fw-medium arrow">New Employee</small>
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
            <span> Create Employee</span>
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
      <div class="col-lg-9">
        <div class="card card-plain p-4">
          <div class="card-body p-3">
            <div class="row">
              <!-- Product Title -->
              <div class="col-8">
                  <div class="form-floating form-floating-outline mb-3">
                    <input type="text" wire:model="name" class="form-control border border-2 p-2"
                      placeholder="Enter Name">
                    <label>Employee Name <span class="text-danger">*</span></label>
                  </div>
                  @error('name')
                  <p class="text-danger inputerror">{{ $message }}</p>
                  @enderror
              </div>
              <div class="col-4">
                <div class="mb-2 form-floating form-floating-outline">
                  <select wire:model="designation" wire:change="GetDesignation($event.target.value)"
                    class="form-select border border-2">
                    <option value="" selected hidden>Select Designation</option>
                      @foreach($designations as $designation_item)
                      <option value="{{ $designation_item->id }}">{{ $designation_item->name }}</option>
                      @endforeach
                  </select>
                  <label class="form-label">Designation<span class="text-danger">*</span></label>
                </div>
                @error('designation')
                <p class="text-danger inputerror">{{ $message }}</p>
                @enderror
              </div>

              <!-- Category Select -->
              <div class="col-6">
                <div class="form-floating form-floating-outline mb-3 mt-2">
                    <input type="text" wire:model="email" class="form-control border border-2 p-2"
                      placeholder="Enter Email">
                    <label>Email <span class="text-danger">*</span></label>
                  </div>
                  @error('email')
                  <p class="text-danger inputerror">{{ $message }}</p>
                  @enderror
              </div>
              <div class="col-6">
                <div class="form-floating form-floating-outline mb-3 mt-2">
                    <input type="text" wire:model="mobile" class="form-control border border-2 p-2"
                      placeholder="Enter Mobile">
                    <label>Mobile <span class="text-danger">*</span></label>
                  </div>
                  @error('mobile')
                  <p class="text-danger inputerror">{{ $message }}</p>
                  @enderror
              </div>
              
            </div>
          </div>
        </div>
      </div>
      <!-- Right Card -->
      <div class="col-lg-3">
        <div class="card card-plain mb-3">
          <div class="card-body p-3">
            <h6>Employee Image</h6>
            <!-- Product Image -->
            <div class="mb-2 mt-2">
              <input type="file" wire:model="image" id="image" accept="image/*"
                class="form-control border border-2 p-2 d-none" onchange="updateImage(event, 'image')">
              <img id="image-preview"
                src="{{ $image ? $image->temporaryUrl() : asset('assets/img/profile-image.webp') }}"
                alt="Selected Image" class="w-80 h-52 object-cover rounded-lg border border-gray-300"
                style="width: 100%" onclick="document.getElementById('image').click()">
            </div>
            @error('image')
            <p class="text-danger inputerror">{{ $message }}</p>
            @enderror
          </div>
        </div>

      </div>
    </div>
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

    // Check if a file was selected
    if (file) {
        const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        
        // Check if the selected file type is valid
        if (validTypes.includes(file.type)) {
            const reader = new FileReader();
            reader.onload = function (e) {
                if (imageElement) {
                    imageElement.src = e.target.result; // Update the image source with the file's data URL
                }
            };
            reader.readAsDataURL(file); // Read the file as a data URL for preview
        } else {
            // Alert user about invalid file type
            alert('Invalid file type. Please select a valid image (JPEG, PNG, GIF, WEBP).');
            fileInput.value = ''; // Reset the input field
            if (imageElement) {
                // Reset the image to the default preview image
                imageElement.src = '{{ asset('assets/img/profile-image.webp') }}';
            }
        }
    }
}
  
</script>

@endsection

