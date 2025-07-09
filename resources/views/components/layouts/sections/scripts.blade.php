
@livewireScripts
@vite([
  'resources/assets/vendor/libs/jquery/jquery.js',
  'resources/assets/vendor/libs/popper/popper.js',
  'resources/assets/vendor/js/bootstrap.js',
  'resources/assets/vendor/libs/node-waves/node-waves.js',
  'resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js',
  'resources/assets/vendor/js/menu.js'
])

@yield('vendor-script')
<!-- END: Page Vendor JS-->
<!-- BEGIN: Theme JS-->
@vite(['resources/assets/js/main.js'])

<!-- END: Theme JS-->
<!-- Pricing Modal JS-->
@stack('pricing-script')
<!-- END: Pricing Modal JS-->
{{-- 
<script type="text/javascript" src="{{asset('build/assets/jquery-CbdDuLi-.js')}}"></script>
<script type="text/javascript" src="{{asset('build/assets/datatables-bootstrap5-DVZaE8TT.js')}}"></script>
<script type="text/javascript" src="{{asset('build/assets/tables-datatables-basic-Ct2mwToG.js')}}"></script> --}}
{{-- <script type="text/javascript" src="{{asset('build/assets/helpers-B9_VIWCr.js')}}"></script>
<script type="text/javascript" src="{{asset('build/assets/jquery-CbdDuLi-.js')}}"></script>
<script type="text/javascript" src="{{asset('build/assets/popper-DNZnuk_L.js')}}"></script>
<script type="text/javascript" src="{{asset('build/assets/perfect-scrollbar-CLUWhEAQ.js')}}"></script>
<script type="text/javascript" src="{{asset('build/assets/bootstrap-B-W6M1Y3.js')}}"></script>
<script type="text/javascript" src="{{asset('build/assets/node-waves-XDuO7R8f.js')}}"></script>
<script type="text/javascript" src="{{asset('build/assets/hammer-36U3igM9.js')}}"></script>
<script type="text/javascript" src="{{asset('build/assets/typeahead-BKwBoP4T.js')}}"></script>
<script type="text/javascript" src="{{asset('build/assets/menu-CY9lYqyY.js')}}"></script>
<script type="text/javascript" src="{{asset('build/assets/katex-CxFYIbTB.js')}}"></script>
<script type="text/javascript" src="{{asset('build/assets/quill-DyR7XnS1.js')}}"></script>
<script type="text/javascript" src="{{asset('build/assets/select2-Cg3gXliv.js')}}"></script>
<script type="text/javascript" src="{{asset('build/assets/jquery-repeater-DKsedPjg.js')}}"></script>
<script type="text/javascript" src="{{asset('build/assets/dropzone-CHTiEaL-.js')}}"></script>
<script type="text/javascript" src="{{asset('build/assets/flatpickr-C_1WDX6v.js')}}"></script>
<script type="text/javascript" src="{{asset('build/assets/tagify-D6IG1b0s.js')}}"></script>
<script type="text/javascript" src="{{asset('build/assets/main-DRGn0ueN.js')}}"></script> --}}

<!-- BEGIN: Page JS-->
@yield('page-script')
<!-- END: Page JS-->
