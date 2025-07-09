<div class="container">
    <div class="col-lg-12 d-flex justify-content-between">
        <div>
            <h5 class="mb-0">Designation Permission</h5>
            <div>
                 <small class="text-light fw-medium"><a href="{{route('admin.designation.index')}}">Designations</a></small>
                 <small class="text-light fw-medium arrow">{{$designation_name}}</small>
            </div>
         </div>
        <div>
            <a class="btn btn-dark btn-sm" href="javascript:history.back();" role="button">
                <i class="ri-arrow-go-back-line ri-16px me-0 me-sm-2 align-baseline"></i>
                Back
            </a>
        </div>
    </div>
    
    <div class="card my-2">
        <div class="card-header pb-0">
            <div class="row">
                @if(session()->has('message'))
                    <div class="alert alert-success">{{ session('message') }}</div>
                @endif
                <div class="row">
                    @foreach($allPermissions as $parentName => $permissions)
                        <div class="col-12">
                            <h6 class="text-uppercase font-weight-bold">{{ ucfirst(str_replace('_', ' ', $parentName)) }}</h6> <!-- Parent Name -->
                        </div>
                
                            @foreach($permissions as $permission)
                                <div class="col-md-3"> <!-- Each checkbox in a col-3 -->
                                    <label class="cursor-pointer">
                                        <input type="checkbox" wire:model.defer="permissions" value="{{ $permission['id'] }}" wire:change="updatePermissions">
                                        {{ ucfirst(str_replace('_', ' ', $permission['name'])) }}
                                    </label>
                                </div>
                            @endforeach
                    
                
                        <div class="col-12">
                            <hr> <!-- Separator after each parent group -->
                        </div>
                    @endforeach
                </div>
                
                

                {{-- <button wire:click="updatePermissions" class="btn btn-primary mt-2">Update Permissions</button> --}}
            </div>
        </div>
    </div>
    <div class="loader-container" wire:loading>
        <div class="loader"></div>
    </div>
</div>
