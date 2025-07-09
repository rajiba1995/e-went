
<div class="row mb-4">
    <div class="col-lg-12 d-flex justify-content-between">
        <div>
            <h5 class="mb-0">Model Management</h5>
            <div>
                 <small class="text-dark fw-medium">Dashboard</small>
                 <small class="text-light fw-medium arrow">Models</small>
            </div>
         </div>
        <div>
            <a href="{{route('admin.product.add')}}"  class="btn btn-primary">
                <i class="ri-add-line ri-16px me-0 me-sm-2 align-baseline"></i>
                Add Model
            </a>
        </div>
    </div>
    <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header pb-0">
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
                            <div class="col-lg-12 d-flex justify-content-end my-auto">
                                <div class="d-flex align-items-center">
                                    <input type="text" wire:model.debounce.300ms="search" 
                                           class="form-control border border-2 p-2 custom-input-sm" 
                                           placeholder="Search here...">
                                    <button type="button" wire:click="searchButtonClicked" 
                                            class="btn btn-dark text-white mb-0 custom-input-sm ms-2">
                                        <span class="material-icons">search</span>
                                    </button>
                                    <!-- Refresh Button -->
                                    <button type="button" wire:click="resetSearch" class="btn btn-danger text-white mb-0 custom-input-sm ms-2">
                                            <i class="ri-restart-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2 mt-2">
                        <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0 product-list">
                            <thead>
                                <tr>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                                        SL
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle" width="25%">
                                        Title
                                    </th>
                                    <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">
                                        Selling Price
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">
                                        Status
                                    </th>
                                    <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $k => $product)
                                    <tr>
                                        <td class="align-middle text-center">{{ $k + 1 }}</td>
                                        <td class="sorting_1" width="25%">
                                            <div class="d-flex justify-content-start align-items-center product-name">
                                                <div class="avatar-wrapper me-4">
                                                    <div class="avatar rounded-2 bg-label-secondary"><img
                                                            src="{{asset($product->image)}}"
                                                            alt="Product-9" class="rounded-2"></div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="badge bg-label-primary">{{$product->product_sku}}</span>
                                                    <span class="text-heading fw-medium"> {{ ucwords($product->title) }}</span>
                                                    <small class="text-truncate d-none d-sm-block"> {{ $product->types }}</small></div>
                                            </div>
                                        </td>
                                        <td class="align-middle price-details">
                                            <ul>
                                                @if($product->is_selling == 1)
                                                    <span class="badge bg-label-success price-title">SELLING PRICE</span>
                                                    <li class="price-item">
                                                        <span class="label">Actual Price:</span>
                                                        <span class="actual-value">{{env('APP_CURRENCY')}}{{ $product->base_price }}</span>
                                                    </li>
                                                    <li class="price-item">
                                                        <span class="label">Offer Price:</span>
                                                        <span class="value">{{env('APP_CURRENCY')}}{{ $product->display_price }}</span>
                                                    </li>
                                                @endif
                                                {{-- @if(count($product->rentalprice)> 0)
                                                    <span class="badge bg-label-danger price-title">RENT PRICE</span>
                                                    <li class="price-item">
                                                        <span class="label">Duration:({{$product->rentalprice[0]->duration}} Days) </span>
                                                        <span class="value">{{env('APP_CURRENCY')}}{{ $product->rentalprice[0]->price }}</span>
                                                    </li>
                                                @endif --}}
                                            </ul>
                                        </td>
                                                                            
                                        <td class="align-middle text-sm text-center">
                                            <div class="form-check form-switch">
                                                <input 
                                                    class="form-check-input ms-auto" 
                                                    type="checkbox" 
                                                    id="flexSwitchCheckDefault{{ $product->id }}" 
                                                    wire:click="toggleStatus({{ $product->id }})"
                                                    @if($product->status) checked @endif>
                                            </div>
                                        </td>
                                        <td class="align-middle text-end px-4">
                                            {{-- <div class="btn-group" role="group" aria-label="Basic example">
                                                <button type="button" class="btn btn-outline-secondary waves-effect {{$product->is_featured==1?"active":""}}" title="Mark as Featured" wire:click="setAsFeatured({{ $product->id }})">
                                                    Featured
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary waves-effect {{$product->is_new_arrival==1?"active":""}}" title="Mark as New Arrival" wire:click="setAsNewArrival({{ $product->id }})">
                                                    New Arrival
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary waves-effect {{$product->is_bestseller==1?"active":""}}" title="Mark as Bestseller" wire:click="setAsBestseller({{ $product->id }})">
                                                    Bestseller
                                                </button>
                                            </div> --}}
                                            <button data-bs-toggle="modal" data-bs-target="#productDetailsModal{{$product->id}}" class="btn btn-sm btn-icon view-record btn-text-secondary rounded-pill waves-effect btn-sm" title="View">
                                                <i class="ri-eye-line ri-20px text-primary"></i> 
                                            </button>
                                            
                                            <a href="{{route('admin.product.update',$product->id)}}" class="btn btn-sm btn-icon edit-record btn-text-secondary rounded-pill waves-effect btn-sm" title="Edit">
                                                <i class="ri-edit-box-line ri-20px text-info"></i>
                                            </a>
                                            <button wire:click="DeleteItem({{ $product->id }})" class="btn btn-sm btn-icon delete-record btn-text-secondary rounded-pill waves-effect btn-sm" title="Delete">
                                                <i class="ri-delete-bin-7-line ri-20px text-danger"></i>
                                            </button>
                                            <a href="{{route('admin.product.stocks.vehicle',$product->id)}}">
                                            <span class="control"></span></a>
                                            <div class="modal fade" id="productDetailsModal{{$product->id}}" tabindex="-1" aria-labelledby="productDetailsLabel{{$product->id}}" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="productDetailsLabel{{$product->id}}">Details of {{$product->title}}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="table-responsive text-nowrap">
                                                                <table class="table table-striped text-start">
                                                                    <thead>
                                                                        @if($product->category)
                                                                            <tr>
                                                                                <th>Category</th>
                                                                                <th>{{$product->category->title}}</th>
                                                                            </tr>
                                                                        @endif
                                                                    </thead>
                                                                    <tbody class="table-border-bottom-0">
                                                                        {{-- <tr>
                                                                            <td>Stock Qty.</td>
                                                                            <td>{{$product->stock_qty}}</td>
                                                                        </tr> --}}
                                                                        <tr>
                                                                            <td>Features</td>
                                                                            <td>
                                                                                @if(count($product->features)>0)
                                                                                <ul>
                                                                                    @foreach($product->features as $items)
                                                                                        <li>{{$items->title}}</li>
                                                                                    @endforeach
                                                                                </ul>
                                                                                @else
                                                                                    <div class="alert alert-danger">
                                                                                        features not available
                                                                                    </div>
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                            <div class="d-flex justify-content-end mt-2">
                                {{ $products->links('pagination::bootstrap-4') }}
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
@section('page-script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.addEventListener('showConfirm', function (event) {
        let itemId = event.detail[0].itemId;
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('destroy', itemId); // Calls Livewire method directly
                // Swal.fire("Deleted!", "Your item has been deleted.", "success");
            }
        });
    });
</script>
@endsection

