<div class="row mb-4">
  <!-- FAQ List Section -->
  <div class="col-lg-7 col-md-6 mb-md-0 mb-4">
    <div class="card">
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
            <h6>FAQ</h6>
          </div>
            <div class="col-lg-6 col-5 my-auto text-end">
                <div class="ms-md-auto d-flex align-items-center">
                    <input type="text" wire:model.debounce.500ms="search" class="form-control border border-2 p-2 custom-input-sm" placeholder="Search FAQ">
                    <button type="button" class="btn btn-dark text-white mb-0 custom-input-sm" wire:click="searchFaq" wire:loading.attr="disabled">
                        <span class="material-icons">search</span>
                    </button>
                    <button type="button" wire:click="refresh" class="btn btn-danger text-white mb-0 custom-input-sm ms-2">
                            <i class="ri-restart-line"></i>
                    </button>
                </div>
            </div>
        </div>
      </div>
      <div class="card-body px-0 pb-2">
        <div class="table-responsive p-0">
          <table class="table align-items-center mb-0">
            <thead>
              <tr class="text-center text-secondary text-xxs font-weight-bolder">
                <th >SL</th>
                <th >Question</th>
                <th >Answer</th>
                <th class="text-end text-secondary text-xxs font-weight-bolder">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($faqs as $k => $faq)
              <tr class="align-middle">
                <td>{{ $k + 1 }}</td>
                <td>{{ $faq->question }}</td>
                <td>{!! $faq->answer !!}</td>
                <td class="align-middle text-end px-4">
                  <button wire:click="edit({{ $faq->id }})" class="btn btn-sm btn-icon edit-record btn-text-secondary rounded-pill waves-effect" title="Edit">
                    <i class="ri-edit-box-line text-info"></i>
                  </button>
                  <button wire:click="delete({{ $faq->id }})" class="btn btn-sm btn-icon delete-record btn-text-secondary rounded-pill waves-effect" title="Delete">
                    <i class="ri-delete-bin-7-line text-danger"></i>
                  </button>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- FAQ Create/Update Section -->
  <div class="col-lg-5 col-md-6 mb-md-0 mb-4">
    <div class="card">
      <div class="card-body px-0 pb-2 mx-4">
        <h5>{{ $faqId ? 'Update FAQ' : 'Create FAQ' }}</h5>
        <form wire:submit.prevent="store">
          <div class="form-floating form-floating-outline mb-5 fv-plugins-icon-container">
            <input type="text" wire:model="question" class="form-control border border-2 p-2" placeholder="Enter Question">
            <label>Question</label>
            @error('question') <span class="text-danger">{{ $message }}</span> @enderror
          </div>
          <div class="form-floating form-floating-outline mb-5 fv-plugins-icon-container">
            <textarea wire:model="answer" id="answer" name="answer" class="form-control border border-2 p-2" placeholder="Enter Answer" rows="3"></textarea>
            @error('answer') <span class="text-danger">{{ $message }}</span> @enderror
          </div>
          <div class="text-end">
            <button type="button" wire:click="refresh" class="btn btn-danger text-white mb-0 custom-input-sm ms-2">
                    <i class="ri-restart-line"></i>
            </button>
            <button type="submit" class="btn btn-primary btn-sm">{{ $faqId ? 'Update FAQ' : 'Create FAQ' }}</button>
          </div>
        </form>
      </div>
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
    var shortDescTextArea = document.getElementById('answer');
    if (shortDescTextArea) {
      // Check if CKEditor instance already exists and destroy it
      if (CKEDITOR.instances['answer']) {
        CKEDITOR.instances['answer'].destroy(true);
      }
      
      // Initialize CKEditor for short_desc_editor
      if (typeof CKEDITOR !== 'undefined') {
        CKEDITOR.replace('answer');

        // Sync CKEditor data to Livewire
        CKEDITOR.instances['answer'].on('change', function() {
            @this.set('answer', CKEDITOR.instances['answer'].getData());
        });
      } else {
        console.error('CKEditor is not defined!');
      }
    }
  });
  </script>
  @endsection
