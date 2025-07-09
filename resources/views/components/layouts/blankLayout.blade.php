@extends('components/layouts/commonMaster' )
<style>
    .pagination .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
    }

    .pagination .page-link {
        color: #007bff;
    }

</style>
@section('layoutContent')

<!-- Content -->
@yield('content')
<!--/ Content -->

@endsection
