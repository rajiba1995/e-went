<div>
  @if (session()->has('message'))
  <div class="alert alert-success">{{ session('message') }}</div>
@endif
  <div class="row mb-4">


    <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
      <div class="row">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-body px-0 pb-2 mx-4">
              <div class="d-flex justify-content-between mb-3">
                <h5>Reset Password</h5>
              </div>
              <form wire:submit.prevent="changePassword">
                <div class="row">

                  <div class="form-floating form-floating-outline">
                    <input type="password" wire:model.defer="current_password"
                    class="form-control border border-2 p-2 @error('current_password') is-invalid @enderror">
                    <label>Current Password </label>
                    @error('current_password') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-floating form-floating-outline mb-5 fv-plugins-icon-container mt-4">
                  <input type="password" wire:model.defer="new_password"
                   class="form-control border border-2 p-2 @error('new_password') is-invalid @enderror">
                    <label>New Password</label>
                    @error('new_password') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-floating form-floating-outline mb-5 fv-plugins-icon-container mt-4">
                  <input type="password" wire:model.defer="new_password_confirmation"
                   class="form-control border border-2 p-2 @error('new_password_confirmation') is-invalid @enderror">
                    <label>Confirm New Password</label>
                    @error('new_password_confirmation') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                  <div class="mb-2 text-end mt-4">
                      <button type="button" wire:click="resetForm" class="btn btn-danger text-white mb-0 custom-input-sm ms-2">
                              <i class="ri-restart-line"></i>
                      </button>
                        <button type="submit" class="btn btn-secondary btn-sm add-new btn-primary waves-effect waves-light">
                            <span>Submit</span>
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


