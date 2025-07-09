
<div class="container-fluid px-2 px-md-4">
    <div class="row gx-4 mb-4">
        <div class="col-auto my-auto">
            <div class="h-100">
                <h5 class="mb-1">
                    Update Product
                </h5>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
    <form wire:submit.prevent="updateProduct" enctype="multipart/form-data">
            <div class="nav-wrapper position-relative end text-end">
                <!-- Back Button -->
                <a class="btn btn-dark btn-sm" href="javascript:history.back();" role="button">
                    <i class="ri-arrow-go-back-line ri-16px me-0 me-sm-2 align-baseline"></i>
                    Back
                </a>
                <button type="submit" class="btn btn-secondary btn-sm add-new btn-primary waves-effect waves-light " 
                        wire:loading.attr="disabled">
                    <span> 
                        Update Product
                    </span>
                </button>
            </div>
        </div>
    </div>
        <div class="row">
            <!-- Left Card -->
            <div class="col-lg-8">
                <div class="card card-plain h-100 p-4">
                    <div class="card-body p-3">
                        <div class="row">
                          <div class="col-6"> 
                            <!-- Category Select -->
                            <div class="mb-2 form-floating form-floating-outline">
                                <select wire:model="category_id" wire:change="GetSubcat($event.target.value)" class="form-select border border-2 p-2">
                                    <option value="" selected hidden>Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{$category->id == $category_id ? 'selected' : ""}}>{{ $category->title }}</option>
                                    @endforeach
                                </select>
                                <label class="form-label">Category</label>
                            </div>
                            @error('category_id')
                                <p class="text-danger inputerror">{{ $message }}</p>
                            @enderror
                         </div> 
                         <div class="col-6">
                            <!-- Subcategory Select -->
                            <div class="form-floating form-floating-outline mb-2">
                                <select wire:model="sub_category_id" class="form-select border border-2 p-2">
                                    <option value="" selected hidden>Select Subcategory</option>
                                    @foreach($subcategories as $subcategory)
                                        <option value="{{ $subcategory->id }}" {{ $subcategory->id == $sub_category_id ? 'selected' : '' }}>{{ $subcategory->title }}</option>
                                    @endforeach
                                </select>
                                <label class="form-label">Subcategory</label>
                            </div>
                            @error('sub_category_id')
                                <p class="text-danger inputerror">{{ $message }}</p>
                            @enderror
                        </div>  
                            <!-- Product Title -->
                            <div class="form-floating form-floating-outline mb-2 mt-2">
                                <input type="text" wire:model="title" class="form-control border border-2 p-2" placeholder="Enter Title">
                                <label>Product Title <span class="text-danger">*</span></label>
                            </div>
                            @error('title')
                                <p class="text-danger inputerror">{{ $message }}</p>
                            @enderror

                            <!-- Product Image -->
                            <label class="form-label">Product Image <span class="text-danger">*</span></label>
                            <div class="ms-md-auto pe-md-3 d-flex align-items-center mb-2">
                                <input type="file" wire:model="image" class="form-control border border-2 p-2">
                            </div>
                            @if(is_object($image))
                                <div>
                                    <img src="{{ $image->temporaryUrl() }}" alt="Preview" width="50">
                                </div>
                            @elseif ($productId)
                                <!-- Show existing image if no new image is uploaded -->
                                <div>
                                    <img src="{{ asset('storage/' . $image) }}" alt="Current Image" width="100">
                                </div>
                            @endif
                            @error('image')
                                <p class="text-danger inputerror">{{ $message }}</p>
                            @enderror

                            <!-- Short Description -->
                            <div class="form-floating form-floating-outline mb-2 mt-2">
                                <textarea wire:model="short_desc" class="form-control border border-2 p-2" placeholder="Enter Short Description"></textarea>
                                <label>Short Description <span class="text-danger">*</span></label>
                            </div>
                            @error('short_desc')
                                <p class="text-danger inputerror">{{ $message }}</p>
                            @enderror

                            <!-- Long Description -->
                            <div class="form-floating form-floating-outline mb-2 mt-2">
                                <textarea wire:model="long_desc" class="form-control border border-2 p-2" rows="5" placeholder="Enter Long Description"></textarea>
                                <label>Long Description <span class="text-danger">*</span></label>
                            </div>
                            @error('long_desc')
                                <p class="text-danger inputerror">{{ $message }}</p>
                            @enderror

                            <!-- Meta Title -->
                            <div class="form-floating form-floating-outline mb-2 mt-2">
                                <input type="text" wire:model="meta_title" class="form-control border border-2 p-2" placeholder="Enter Meta Title">
                                <label>Meta Title <span class="text-danger">*</span></label>
                            </div>
                            @error('meta_title')
                                <p class="text-danger inputerror">{{ $message }}</p>
                            @enderror

                            <!-- Meta Keywords -->
                            <div class="form-floating form-floating-outline mb-2 mt-2">
                                <textarea wire:model="meta_keyword" class="form-control border border-2 p-2" placeholder="Enter Meta Keywords"></textarea>
                                <label>Meta Keywords <span class="text-danger">*</span></label>
                            </div>
                            @error('meta_keyword')
                                <p class="text-danger inputerror">{{ $message }}</p>
                            @enderror

                            <!-- Meta Description -->
                            <div class="form-floating form-floating-outline mb-2 mt-2">
                                <textarea wire:model="meta_description" class="form-control border border-2 p-2" placeholder="Enter Meta Description"></textarea>
                                <label>Meta Description <span class="text-danger">*</span></label>
                            </div>
                            @error('meta_description')
                                <p class="text-danger inputerror">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <!-- Right Card -->
            <div class="col-lg-4">
                <div class="card card-plain">
                    <div class="card-body p-3">
                        <h6>Pricing</h6>
                        <hr>
                        <!-- Selling Price -->
                        <div class="mb-2">
                            <label class="form-check-label" for="is_selling">
                                <input type="checkbox" id="is_selling" wire:model="is_selling" class="form-check-input" wire:change="toggleSellingFields"  @if ($is_selling) checked @endif>
                                Selling Price
                            </label>
                        </div>
                        @if($is_selling)
                            <div class="form-floating form-floating-outline mb-2 mt-2">
                                <input type="number" wire:model="base_price" class="form-control border border-2 p-2" placeholder="Enter Base Price">
                                <label>Base Price</label>
                            </div>
                            @error('base_price')
                                <p class="text-danger inputerror">{{ $message }}</p>
                            @enderror
                            <div class="form-floating form-floating-outline mb-2 mt-2">
                                <input type="number" wire:model="display_price" class="form-control border border-2 p-2" placeholder="Enter Display Price">
                                <label>Display Price</label>
                            </div>
                            @error('display_price')
                                <p class="text-danger inputerror">{{ $message }}</p>
                            @enderror
                        @endif

                        <!-- Rent -->
                        <div class="mb-2">
                            <label class="form-check-label" for="is_rent">
                                <input type="checkbox" id="is_rent" wire:model="is_rent" class="form-check-input" wire:change="toggleRentFields"  @if ($is_rent) checked @endif>
                                Rent
                            </label>
                        </div>
                        @if($is_rent)
                            <div class="form-floating form-floating-outline mb-2 mt-2">
                                <input type="number" wire:model="per_rent_price" class="form-control border border-2 p-2" placeholder="Enter Per Rent Price">
                                <label>Per Month Rent</label>
                            </div>
                            @error('per_rent_price')
                                <p class="text-danger inputerror">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
