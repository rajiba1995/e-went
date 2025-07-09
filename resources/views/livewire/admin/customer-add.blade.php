<div class="container py-4">
    <form wire:submit.prevent="saveProduct" enctype="multipart/form-data">
        <!-- Header Section -->
        <div class="row align-items-center mb-4">
            <div class="col-md-6">
                <h6 class="text-primary">Add New Customer</h6>
            </div>
            <div class="col-md-6 text-md-end">
                <a class="btn btn-dark btn-sm waves-effect waves-light" href="javascript:history.back();" role="button">
                    <i class="ri-arrow-go-back-line"></i> Back
                </a>
                <button type="submit" class="btn btn-primary btn-sm" wire:loading.attr="disabled">
                    <i class="ri-save-line"></i> Save Customer
                </button>
            </div>
        </div>

        <!-- Alerts -->
        @if(session()->has('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
        @endif
        @if(session()->has('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Form Section -->
        <div class="row">
            <!-- Left Section -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Customer ID -->
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="text" wire:model="customer_id" class="form-control" style="background-color:#fff; color:#8c57ff; font-weight:600; opacity: unset;" disabled>
                                    <label for="customer_id">Customer ID</label>
                                </div>
                            </div>

                            <!-- Customer Name -->
                            <div class="col-md-8">
                                <div class="form-floating">
                                    <input type="text" wire:model="name" class="form-control" placeholder="Enter Name">
                                    <label for="name">Customer Name <span class="text-danger">*</span></label>
                                </div>
                                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" wire:model="email" class="form-control" placeholder="Enter Email">
                                    <label for="email">Email<span class="text-danger">*</span></label>
                                </div>
                                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <!-- Mobile -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" wire:model="mobile" class="form-control" placeholder="Enter Mobile">
                                    <label for="mobile">Mobile<span class="text-danger">*</span></label>
                                </div>
                                @error('mobile') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <!-- Address -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea wire:model="address" class="form-control" placeholder="Enter Address"></textarea>
                                    <label for="address">Address</label>
                                </div>
                                @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <!-- City -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" wire:model="city" class="form-control" placeholder="Enter City">
                                    <label for="city">City</label>
                                </div>
                                @error('city') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <!-- Pincode -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" wire:model="pincode" class="form-control" placeholder="Enter Pincode">
                                    <label for="pincode">Pincode</label>
                                </div>
                                @error('pincode') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Section -->
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-body text-center">
                        <h5>Customer Image<span class="text-danger">*</span></h5>
                        <div class="mb-3">
                            <input type="file" wire:model="image" id="image" accept="image/*" class="d-none" onchange="updateImage(event)">
                            <img id="image-preview" src="{{ $image ? $image->temporaryUrl() : asset('assets/img/profile-image.webp') }}" class="img-fluid rounded-circle border" alt="Customer Image" style="width: 120px; height: 120px; object-fit: cover;" onclick="document.getElementById('image').click()">
                        </div>
                        @error('image') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5>Additional Documents</h5>
                        <div class="mb-3">
                            <label for="driving_licence" class="form-label">Driving Licence</label>
                            <input type="file" wire:model="driving_licence" id="driving_licence" class="form-control">
                            @error('driving_licence') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="govt_id_card" class="form-label">Government ID</label>
                            <input type="file" wire:model="govt_id_card" id="govt_id_card" class="form-control">
                            @error('govt_id_card') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="cancelled_cheque" class="form-label">Cancelled Cheque</label>
                            <input type="file" wire:model="cancelled_cheque" id="cancelled_cheque" class="form-control">
                            @error('cancelled_cheque') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="current_address_proof" class="form-label">Address Proof</label>
                            <input type="file" wire:model="current_address_proof" id="current_address_proof" class="form-control">
                            @error('current_address_proof') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="loader-container" wire:loading>
        <div class="loader"></div>
      </div>
</div>

<script>
    function updateImage(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('image-preview');
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
</script>
