
<div class="row mb-4">
     <!-- Button to open the modal -->
     <div class="col-lg-12 d-flex justify-content-between">
        <div>
            <h5 class="mb-0">Stock Management</h5>
            <div>
                 <small class="text-dark fw-medium">Model</small>
                 <small class="text-light fw-medium arrow">Stock</small>
            </div>
         </div>
        <div>
            <button type="button" class="btn btn-primary" wire:click="ModalActivity(1)">
                <i class="ri-add-line ri-16px me-0 me-sm-2 align-baseline"></i>
                Upload Stock
            </button>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade {{$modal_activity_class==1?"show d-block":""}}" id="uploadStockModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Stock via CSV</h5>
                    <button type="button" class="btn-close" wire:click="ModalActivity(0)"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="uploadFile">
                        <div class="mb-3">
                            <label for="csvFile" class="form-label">Select CSV File</label>
                            <input class="form-control" type="file" id="csvFile" wire:model="csvFile" accept=".csv,.txt">
                            @error('csvFile') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <a href="{{ asset('assets/csv/product-sample-stock.csv') }}" class="btn btn-link" download>
                                <i class="ri-download-line"></i> Download Sample CSV File
                            </a>
                        </div>
                        <div class="text-end">
                            @if(session()->has('csv_error'))
                                <div class="alert alert-danger">
                                    {{ session('csv_error') }}
                                </div>
                            @endif
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </div>
                    </form>
                </div>

            </div>
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
                            <div class="col-lg-6 col-7">
                            </div>
                            <div class="col-lg-6 col-5 my-auto text-end">
                                <div class="d-flex align-items-center">
                                    <input type="text" wire:model.debounce.300ms="search"
                                           class="form-control border border-2 p-2 custom-input-sm"
                                           placeholder="Search here...">
                                    <button type="button" wire:click="searchButtonClicked"
                                            class="btn btn-dark text-white mb-0 custom-input-sm ms-2">
                                        <span class="material-icons">search</span>
                                    </button>
                                    <!-- Refresh Button -->
                                    <button type="button" wire:click="resetSearch"
                                            class="btn btn-danger text-white mb-0 custom-input-sm ms-2 btn-sm">
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
                                    <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle" width="25%">
                                        Model
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                                        Total Quantity
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                                        Assigned Quantity
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                                        No of Sold
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                                        Available Quantity
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">
                                       Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $sl = 1;
                                @endphp
                                @foreach($stocks as $k => $stock)
                                    @if($stock->product)
                                        <tr>
                                            <td class="align-middle text-center">{{$stocks->firstItem()+ $k }}</td>
                                            <td class="sorting_1" width="25%">
                                                <div class="d-flex justify-content-start align-items-center product-name">
                                                    <div class="avatar-wrapper me-4">
                                                        <div class="avatar rounded-2 bg-label-secondary">
                                                            <img
                                                                src="{{ $stock->product && $stock->product->image ? asset($stock->product->image) : asset('assets/img/default-product.webp') }}"
                                                                alt="Product-9"
                                                                class="rounded-2">
                                                        </div>

                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <span class="text-heading fw-medium"> {{ $stock->product?ucwords($stock->product->title):"" }}</span>
                                                        <small class="text-truncate d-none d-sm-block"> {{ $stock->product?$stock->product->product_sku:"" }}</small></div>
                                                </div>
                                            </td>
                                            <td class="text-center">{{$stock->stock_count}}</td>
                                            <td class="text-center">
                                                {{GetProductWiseAssignedStock($stock->product_id)}}
                                            </td>
                                            <td class="text-center">
                                                {{GetProductWiseSoldStock($stock->product_id)}}
                                            </td>
                                            <td class="text-center">
                                                {{GetProductWiseAvailableStock($stock->product_id)}}
                                            </td>
                                            <td class="text-center">
                                                <a href="{{route('admin.product.stocks.vehicle', $stock->product_id)}}">
                                                    <span class="control"></span>
                                                </a>
                                            </td>
                                        </tr>
                                        @php
                                            $sl++;
                                        @endphp
                                    @endif
                                @endforeach
                            </tbody>
                        </table>

                            <div class="d-flex justify-content-end mt-2">
                                {{ $stocks->links() }}
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

