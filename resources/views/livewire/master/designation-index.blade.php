<div>
<div class="row mb-4">
  <div class="col-lg-8 col-md-6 mb-md-0 mb-4">
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
            </div>
            <div class="row">
              <div class="col-lg-6 col-7">
                <h6>Designations</h6>
              </div>
              <div class="col-lg-6 col-5 my-auto text-end">
                <div class="ms-md-auto d-flex align-items-center">
                  <input type="text" wire:model.debounce.500ms="search"
                    class="form-control border border-2 p-2 custom-input-sm" placeholder="Enter designation">
                  <button type="button" wire:click="btn_search" class="btn btn-dark text-white mb-0 custom-input-sm">
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
                            Name
                        </th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                            Status
                        </th>
                        <th
                            class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($designations as $k => $designation)
                    <tr>
                        <td class="align-middle text-center">{{ $k + $designations->firstItem() }}</td>

                        {{-- Display Banner Title --}}
                        <td class="align-middle text-center">{{ ucwords($designation->name) }}</td>

                        {{-- Toggle Status --}}
                        <td class="align-middle text-center">
                            <div class="form-check form-switch">
                                <input class="form-check-input ms-auto" type="checkbox"
                                    id="flexSwitchCheckDefault{{ $designation->id }}" wire:click="toggleStatus({{ $designation->id }})"
                                    @if($designation->status) checked @endif>
                            </div>
                        </td>

                        {{-- Action Buttons --}}
                        <td class="align-middle text-end px-4">
                          <a href="{{route('admin.designation.permission', $designation->id)}}">
                              <span class="badge bg-label-danger mb-0 cursor-pointer">Permission</span>
                          </a>
                            <button wire:click="edit({{ $designation->id }})"
                                class="btn btn-sm btn-icon edit-record btn-text-secondary rounded-pill waves-effect btn-sm"
                                title="Edit">
                                <i class="ri-edit-box-line ri-20px text-info"></i>
                            </button>
                                @php
                                    $Empl = App\Models\Admin::where('designation', $designation->id)->exists();
                                @endphp
                                @if(!$this->isDesignationAssigned($designation->id))
                                    <button wire:click="deleteDesignationWarning({{ $designation->id }})"
                                        class="btn btn-sm btn-icon delete-record btn-text-secondary rounded-pill waves-effect"
                                        title="Delete this designation">
                                        <i class="ri-delete-bin-7-line ri-20px text-danger"></i>
                                    </button>
                                @else
                                    <button class="btn btn-sm bg-secondary btn-icon delete-record btn-text-secondary rounded-pill waves-effect" wire:click="DesignationAssignedCheck"
                                        title="You cannot delete this designation because it is assigned to an employee.">
                                        <i class="ri-delete-bin-7-line ri-20px text-light"></i>
                                    </button>
                                @endif

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
              <div class="d-flex justify-content-end mt-2" wire:ignore>
                   {{ $designations->links('pagination::bootstrap-4') }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4 col-md-6 mb-md-0 mb-4">
    <div class="row">
      <div class="col-12">
        <div class="card my-4">
          <div class="card-body px-0 pb-2 mx-4">
            <div class="d-flex justify-content-between mb-3">
              <h5>{{$designationId ? "Update Designation" : "Create Designation"}}</h5>
            </div>
            <form wire:submit.prevent="save">
              <div class="row">

                <div class="form-floating form-floating-outline mb-5 fv-plugins-icon-container">
                  <input type="text" wire:model="name" class="form-control border border-2 p-2"
                    placeholder="Enter designation name">
                  <label> Name <span class="text-danger">*</span></label>
                </div>
                @error('name')
                <p class='text-danger inputerror'>{{ $message }}</p>
                @enderror

                <div class="mb-2 text-end mt-4">
                    <button type="button" wire:click="resetForm" class="btn btn-danger text-white mb-0 custom-input-sm ms-2">
                            <i class="ri-restart-line"></i>
                    </button>
                      <button type="submit" class="btn btn-secondary btn-sm add-new btn-primary waves-effect waves-light">
                          <span>{{ $designationId ? "Update Designation" : "Create Designation" }}</span>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
         window.addEventListener('updateDesignationData', function (event) {
            let itemId = event.detail[0].itemId;
            Swal.fire({
                title: "Delete Designation?",
                text: "Are you sure you want to delete the designation?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('destroy', itemId); // Livewire method
                    // Swal.fire("Deallocated!", "The vehicle has been deallocated for this user.", "success");
                }
            });
        });
         window.addEventListener('alertForDesabledItem', function (event) {
            Swal.fire({
                title: "Access denied",
                text: "You cannot delete this designation because it is assigned to an employee.",
                icon: "warning",
                showCancelButton: false,
                confirmButtonColor: "#3085d6",
                // cancelButtonColor: "#d33",
                confirmButtonText: "Okey"
             }).then((result) => {
                if (result.isConfirmed) {
                    // @this.call('destroy', itemId); // Livewire method
                    // Swal.fire("Deallocated!", "The vehicle has been deallocated for this user.", "success");
                }
            });
        });
    </script>
@endsection
