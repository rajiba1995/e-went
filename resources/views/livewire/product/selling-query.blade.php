<div class="row mb-4">

      <div class="col-lg-12 col-md-6 mb-md-0 my-4">
          <div class="row">
              <div class="col-12">
                  <div class="card">
                      <div class="card-header pb-0">

                          <div class="row">
                              <div class="col-lg-6 col-7">
                                  <h6>Selling Queries</h6>
                              </div>

                          </div>
                          <div class="row mb-3 g-2 align-items-end">

                            <!-- Rider Filter -->

                            <!-- Product Type -->
                            <div class="col-md-2">
                              <label class="form-label text-uppercase small">Model</label>
                              <select wire:model="product_id" class="form-select border border-2 p-2 custom-input-sm"  wire:change="updateFilters('product_id', $event.target.value)">
                                <option value="" selected>Select Model</option>
                                  @foreach ($filterData['product_lists'] as $model)
                                    <option value="{{$model['id']}}" >{{$model['title']}}</option>
                                  @endforeach
                              </select>
                            </div>



                            <!-- Start Date -->
                            <div class="col-md-2">
                              <label class="form-label text-uppercase small">Start Date</label>
                              <input type="date" wire:model="start_date" wire:change="updateFilters('start_date', $event.target.value)" class="border border-2 p-2 custom-input-sm form-control">
                            </div>

                            <!-- End Date -->
                            <div class="col-md-2">
                              <label class="form-label text-uppercase small">End Date</label>
                              <input type="date" wire:model="end_date" wire:change="updateFilters('end_date', $event.target.value)" class="border border-2 p-2 custom-input-sm form-control">
                            </div>
                            <div class="col-md-1">
                              <a href="javascript:void(0)"
                                class="btn btn-danger text-white custom-input-sm" wire:click="resetPageField">
                                <i class="ri-restart-line"></i>
                              </a>
                            </div>
                            <!-- Export Button -->
                            <div class="col-md-1 d-grid">
                              <button wire:click="exportAll" class="btn btn-primary mt-3">
                                <i class="ri-download-line"></i> Export
                              </button>
                            </div>
                          </div>
                      </div>
                      <div class="card-body px-0 pb-2 mt-2">
                          <div class="table-responsive p-0">
                              <table class="table align-items-center mb-0">
                                  <thead>
                                      <tr>
                                          <th>User</th>
                                          <th>Product</th>
                                          <th>Post At</th>
                                          <th>Actions</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      @forelse ($data as $index => $item)
                                          <tr>
                                              <td>
                                                  {{ $item->user->name }}
                                              </td>

                                              <td>{{  $item->product->title }}</td>
                                              <td>{{ date('d-m-Y h:i A',strtotime($item->created_at)) }}</td>

                                              <td>
                                                <a href="javascript:void(0)" wire:click="viewQuery({{ $item->id }})" >
                                                  <span class="control"></span>
                                                </a>
                                                  {{-- <button   wire:click="viewQuery({{ $item->id }})"
                                                          class="btn btn-sm btn-icon delete-record btn-text-secondary rounded-pill waves-effect btn-sm" title="Delete">
                                                      <i class="ri-eye-line ri-20px cursor-pointer ri-20px text-danger"></i>
                                                  </button> --}}
                                              </td>
                                          </tr>
                                          @if($selectedID==$item->id)
                                          <tr>
                                            <td colspan="4" style="background: aliceblue;" >
                                              <div>
                                                @if(!empty($selectedQuery->user->profile_image))
                                                <img src="{{ asset($selectedQuery->user->profile_image) }}" alt="" class="img-thumbnail" width="100"><br>

                                                @endif
                                                <strong>Name:</strong> {{  $selectedQuery->user->name?? 'N/A' }}<br>
                                                <strong>Phone No:</strong> {{  $selectedQuery->phone }}<br>
                                                <strong>Email:</strong> {{ $selectedQuery->user->email ?? 'N/A' }}<br>
                                                <strong>Address:</strong> {{$selectedQuery->address ?? 'N/A' }}<br>
                                                <strong>Model Name:</strong> {{ $selectedQuery->product->title }}<br>
                                                <strong>Remarks:</strong> {{  $selectedQuery->remarks }}<br>
                                                <strong>Request Date:</strong> {{ date('d-m-Y h:i A',strtotime($selectedQuery->created_at)) }}<br>

                                                <br>

                                              </div>
                                            </td>
                                          </tr>
                                          @endif

                                      @empty
                                          <tr>
                                              <td colspan="4" class="text-center">
                                                  <div class="alert alert-warning mb-0">No parts found.</div>
                                              </td>
                                          </tr>

                                      @endforelse
                                  </tbody>
                              </table>
                              <div class="d-flex justify-content-end mt-3 paginator">
                                {{ $data->links() }} <!-- Pagination links -->
                            </div>
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

