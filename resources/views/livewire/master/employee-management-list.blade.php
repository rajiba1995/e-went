
<div class="row mb-4">
    <div class="col-lg-12 d-flex justify-content-between">
        <div>
            <h5 class="mb-0">Employee Management</h5>
            <div>
                 <small class="text-dark fw-medium">Dashboard</small>
                 <small class="text-light fw-medium arrow">Employee</small>
            </div>
         </div>
        <div>
            <a href="{{route('admin.employee.create')}}"  class="btn btn-primary">
                <i class="ri-add-line ri-16px me-0 me-sm-2 align-baseline"></i>
                Add Employee
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
                                        Employee Details
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">
                                        Designation
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">
                                        Status
                                    </th>
                                    <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle px-4">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employees as $k => $employee)
                                    @php
                                        $colors = ['bg-label-primary', 'bg-label-success', 'bg-label-info', 'bg-label-secondary', 'bg-label-danger', 'bg-label-warning'];
                                        $colorClass = $colors[$k % count($colors)]; // Rotate colors based on index
                                    @endphp
                                    <tr>
                                        <td class="align-middle text-center">{{ $employees->firstItem()+$k }}</td>
                                        <td class="sorting_1" width="25%">
                                            <div class="d-flex justify-content-start align-items-center customer-name">
                                                <div class="avatar-wrapper me-3">
                                                    <div class="avatar avatar-sm">
                                                        @if ($employee->image)
                                                            <img src="{{ asset($employee->image) }}" alt="Avatar" class="rounded-circle">
                                                        @else
                                                            <div class="avatar-initial rounded-circle {{$colorClass}}">
                                                                {{ strtoupper(substr($employee->name, 0, 1)) }}{{ strtoupper(substr(strrchr($employee->name, ' '), 1, 1)) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <a href="{{ route('admin.customer.details', $employee->id) }}"
                                                        class="text-heading"><span class="fw-medium text-truncate">{{ ucwords($employee->name) }}</span>
                                                    </a>
                                                    <small class="text-truncate">{{$employee->country_code}} {{ $employee->mobile }}</small>
                                                <div>
                                            </div>
                                        </td>
                                        <td class="align-middle price-details text-center">
                                            {{$employee->designationData?$employee->designationData->name:"N/A"}}
                                        </td>

                                        <td class="align-middle text-sm text-center">
                                            <div class="form-check form-switch">
                                                <input
                                                    class="form-check-input ms-auto"
                                                    type="checkbox"
                                                    id="flexSwitchCheckDefault{{ $employee->id }}"
                                                    wire:click="toggleStatus({{ $employee->id }})"
                                                    @if($employee->status) checked @endif>
                                            </div>
                                        </td>
                                        <td class="align-middle text-end px-4">
                                            <a href="{{route('admin.employee.update',$employee->id)}}" class="btn btn-sm btn-icon edit-record btn-text-secondary rounded-pill waves-effect btn-sm" title="Edit">
                                                <i class="ri-edit-box-line ri-20px text-info"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                            <div class="d-flex justify-content-end mt-2">
                                {{ $employees->links('pagination::bootstrap-4') }}
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

