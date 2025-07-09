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
                <h6>Banner</h6>
              </div>
              <div class="col-lg-6 col-5 my-auto text-end">
                <div class="ms-md-auto d-flex align-items-center">
                  <input type="text" wire:model.debounce.500ms="search"
                    class="form-control border border-2 p-2 custom-input-sm" placeholder="Enter Title">
                  <button type="button" wire:target="search" class="btn btn-dark text-white mb-0 custom-input-sm">
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
                            Image
                        </th>
                        <th
                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                            Title
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
                    @foreach($banners as $k => $banner)
                    <tr>
                        <td class="align-middle text-center">{{ $k + 1 }}</td>

                        {{-- Display Banner Image --}}
                        <td class="align-middle text-center">
                        <img src="{{ asset($banner->image) }}" alt="Banner Image" width="75" height="50" class="rounded">
                        </td>

                        {{-- Display Banner Title --}}
                        <td class="align-middle text-center">{{ ucwords($banner->title) }}</td>

                        {{-- Toggle Status --}}
                        <td class="align-middle text-center">
                            <div class="form-check form-switch">
                                <input class="form-check-input ms-auto" type="checkbox"
                                    id="flexSwitchCheckDefault{{ $banner->id }}" wire:click="toggleStatus({{ $banner->id }})"
                                    @if($banner->status) checked @endif>
                            </div>
                        </td>

                        {{-- Action Buttons --}}
                        <td class="align-middle text-end px-4">
                            <button wire:click="edit({{ $banner->id }})"
                                class="btn btn-sm btn-icon edit-record btn-text-secondary rounded-pill waves-effect btn-sm"
                                title="Edit">
                                <i class="ri-edit-box-line ri-20px text-info"></i>
                            </button>
                            <button wire:click="destroy({{ $banner->id }})"
                                class="btn btn-sm btn-icon delete-record btn-text-secondary rounded-pill waves-effect"
                                title="Delete">
                                <i class="ri-delete-bin-7-line ri-20px text-danger"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>


              <div class="d-flex justify-content-end mt-2">
                {{-- {{ $banners->links() }} --}} 
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
              <h5>{{$bannerId ? "Update Banner" : "Create Banner"}}</h5>
            </div>
            <form wire:submit.prevent="save">
              <div class="row">

                <div class="form-floating form-floating-outline mb-5 fv-plugins-icon-container">
                  <input type="text" wire:model="title" class="form-control border border-2 p-2"
                    placeholder="Enter Banner Title">
                  <label> Title <span class="text-danger">*</span></label>
                </div>
                @error('title')
                <p class='text-danger inputerror'>{{ $message }}</p>
                @enderror

                <div class="form-floating form-floating-outline mb-5 fv-plugins-icon-container">
                  <input type="file" wire:model="image" class="form-control border border-2 p-2">
                  <label> Image <span class="text-danger">*</span></label>
                </div>
                @error('image')
                <p class='text-danger inputerror'>{{ $message }}</p>
                @enderror

                <div class="mb-2 text-end mt-4">
                    <button type="button" wire:click="refresh" class="btn btn-danger text-white mb-0 custom-input-sm ms-2">
                            <i class="ri-restart-line"></i>
                    </button>
                      <button type="submit" class="btn btn-secondary btn-sm add-new btn-primary waves-effect waves-light">
                          <span>{{ $bannerId ? "Update Banner" : "Create Banner" }}</span>
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
