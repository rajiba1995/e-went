<!-- BEGIN: Theme CSS-->
<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap" rel="stylesheet">

@vite(['resources/assets/vendor/fonts/remixicon/remixicon.scss'])
<!-- Core CSS -->
@vite([
  'resources/assets/vendor/scss/core.scss',
  'resources/assets/vendor/scss/theme-default.scss',
  'resources/assets/css/demo.css'
])

<link rel="stylesheet" href="{{asset('build/assets/datatable.css')}}">
<link rel="stylesheet" href="{{asset('build/assets/style.css')}}">
<link rel="stylesheet" href="{{asset('build/assets/core-BdqwDRjO.css')}}">
<link rel="stylesheet" href="{{asset('build/assets/page-auth.css')}}">
<link rel="stylesheet" href="{{asset('build/assets/theme-semi-dark-V_cttLte.css')}}">
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/44.1.0/ckeditor5.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
{{-- <link rel="stylesheet" href="{{asset('build/assets/demo-Z-YEgnIY.css')}}">

<!-- Vendor Styles -->
<link rel="stylesheet" href="{{asset('build/assets/perfect-scrollbar-CTjXBMlg.css')}}">
<link rel="stylesheet" href="{{asset('build/assets/typeahead-rBaq-1fs.css')}}">
<link rel="stylesheet" href="{{asset('build/assets/typography-oDgXljhw.css')}}">
<link rel="stylesheet" href="{{asset('build/assets/katex-BVEIEImU.css')}}">
<link rel="stylesheet" href="{{asset('build/assets/editor-BvIzxBHF.css')}}">
<link rel="stylesheet" href="{{asset('build/assets/select2-ClQQumpp.css')}}">
<link rel="stylesheet" href="{{asset('build/assets/dropzone-XGRPuCYR.css')}}">
<link rel="stylesheet" href="{{asset('build/assets/flatpickr-Dat3zG_1.css')}}">
<link rel="stylesheet" href="{{asset('build/assets/tagify-5GCBa-Uz.css')}}"> --}}


@vite(['resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.scss'])
@yield('vendor-style')

<!-- Page Styles -->
@yield('page-style')
