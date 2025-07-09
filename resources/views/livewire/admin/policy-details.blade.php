<div>
    @if($active_tab==1)
        <div class="col-lg-12 d-flex justify-content-end">
            <button type="button"  class="btn btn-primary" wire:click="ActiveCreateTab(2)">
                <i class="ri-add-line ri-16px me-0 me-sm-2 align-baseline"></i>
                Create New Policy
            </button>
        </div>
    @else
        <div class="col-lg-12 d-flex justify-content-end">
            <button type="button" class="btn btn-dark btn-sm waves-effect waves-light" wire:click="ActiveCreateTab(1)" role="button">
                <i class="ri-arrow-go-back-line"></i> Back
            </button>
        </div>
    @endif
    {{-- New policy --}}
        <div class="card card-action my-4 {{$active_tab==2?"":"d-none"}}">
            <div class="card-header align-items-center flex-wrap gap-2">
            <h5 class="card-action-title mb-0">New Policy</h5>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="store">
                    <div class="form-floating form-floating-outline mb-5 fv-plugins-icon-container">
                      <input type="text" wire:model="title" class="form-control border border-2 p-2" placeholder="Enter title">
                      <label>Title</label>
                      @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-floating form-floating-outline mb-5 fv-plugins-icon-container">
                      <textarea wire:model="content" id="content" name="content" class="form-control border border-2 p-2" placeholder="Enter content" rows="3"></textarea>
                      @error('content') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="text-end">
                      <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-action my-4 {{$active_tab==3?"":"d-none"}}">
            <div class="card-header align-items-center flex-wrap gap-2">
            <h5 class="card-action-title mb-0">Update Policy</h5>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="updateItem">
                    <div class="form-floating form-floating-outline mb-5 fv-plugins-icon-container">
                    <input type="text" wire:model="title" class="form-control border border-2 p-2" placeholder="Enter title">
                    <label>Title</label>
                    @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-floating form-floating-outline mb-5 fv-plugins-icon-container">
                    <textarea wire:model="content" id="update_content" name="content" class="form-control border border-2 p-2" placeholder="Enter content" rows="3"></textarea>
                    @error('content') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="text-end">
                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                    </div>
                </form>
            </div>
        </div>


        <div class="card card-action my-4 {{$active_tab==1?"":"d-none"}}">
            <div class="card-header align-items-center flex-wrap gap-2">
            <h5 class="card-action-title mb-0">Our Policies</h5>
            </div>
            <div class="card-body">
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
                @foreach ($policies as $key=>$item)
                    <div class="accordion accordion-arrow-left" id="ecommerceBillingAccordionPayment{{$key+1}}">
                        <div class="accordion-item">
                            <div class="accordion-header d-flex justify-content-between align-items-center flex-wrap flex-sm-nowrap border-bottom border-secondary"
                                id="headingPaymentMaster">
                                <a class="accordion-button collapsed px-2" data-bs-toggle="collapse"
                                data-bs-target="#ecommerceBillingPaymentMaster{{$key+1}}" aria-expanded="false" aria-controls="headingPaymentMaster"
                                role="button">
                                <span class="accordion-button-information d-flex align-items-center gap-4">
                                    <span class="d-flex flex-column">
                                    <span class="h6 mb-1">{{ucwords($item->title)}}</span>
                                    <span class="mb-0 text-body fw-normal">Created:- {{date('M Y', strtotime($item->created_at))}}</span>
                                    </span>
                                </span>
                                </a>
                                <div class="d-flex gap-4 p-4 p-sm-2 py-sm-0 pt-0 ms-4 ms-sm-0">
                                <a href="javascript:void(0);" title="Edit" wire:click="EditItem({{$item->id}})"><i
                                    class="ri-edit-box-line ri-22px "></i></a>
                                {{-- <a href="javascript:void(0);">
                                    <i class="ri-delete-bin-7-line text-danger ri-22px "></i>
                                </a> --}}
                                
                                </div>
                            </div>
                            <div id="ecommerceBillingPaymentMaster{{$key+1}}" class="accordion-collapse collapse border-bottom border-secondary"
                                data-bs-parent="#ecommerceBillingAccordionPayment{{$key+1}}">
                                <div class="accordion-body my-6">
                                    {!!$item->content!!}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                
            </div>
        </div>

    <div class="loader-container" wire:loading>
        <div class="loader"></div>
    </div>
</div>
@section('page-script')
  <script type="text/javascript" src="{{ asset('build/ckeditor/ckeditor.js') }}"></script>
  <script>
  window.addEventListener('ck_editor_load', function(event) { 
    // Handle short_desc_editor
    var shortDescTextArea = document.getElementById('content');
    if (shortDescTextArea) {
      // Check if CKEditor instance already exists and destroy it
      if (CKEDITOR.instances['content']) {
        CKEDITOR.instances['content'].destroy(true);
      }
      
      // Initialize CKEditor for short_desc_editor
      if (typeof CKEDITOR !== 'undefined') {
        CKEDITOR.replace('content');

        // Sync CKEditor data to Livewire
        CKEDITOR.instances['content'].on('change', function() {
            @this.set('content', CKEDITOR.instances['content'].getData());
        });
      } else {
        console.error('CKEditor is not defined!');
      }
    }
    var UpdateTextArea = document.getElementById('update_content');
    if (UpdateTextArea) {
      // Check if CKEditor instance already exists and destroy it
      if (CKEDITOR.instances['update_content']) {
        CKEDITOR.instances['update_content'].destroy(true);
      }
      
      // Initialize CKEditor for short_desc_editor
      if (typeof CKEDITOR !== 'undefined') {
        CKEDITOR.replace('update_content');

        // Sync CKEditor data to Livewire
        CKEDITOR.instances['update_content'].on('change', function() {
            @this.set('content', CKEDITOR.instances['update_content'].getData());
        });
      } else {
        console.error('CKEditor is not defined!');
      }
    }
  });
  </script>
  @endsection
