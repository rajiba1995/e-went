<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

  <!-- ! Hide app brand if navbar-full -->
  <div class="app-brand demo">
    <a href="{{url('/')}}" class="app-brand-link">
      {{-- <span class="app-brand-logo demo me-1">
        @include('_partials.macros',["height"=>20])
      </span> --}}
      <img src="{{asset('assets/img/new-logo.png')}}" alt="" style="width: 80px; height: auto;">
      <span class="app-brand-text demo menu-text fw-semibold ms-2">Ewent</span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
      <i class="menu-toggle-icon d-xl-block align-middle"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1 ps">
    
    <li class="menu-item {{ (request()->is('admin/dashboard*')) ? 'open' : '' }}">
      <a href="{{route('admin.dashboard')}}" class="menu-link">
        <i class="menu-icon tf-icons ri-home-smile-line"></i>
        <div>Dashboards</div>
      </a>
    </li>
      @if (hasPermissionByParent('master_management'))
          <li class="menu-item {{ (request()->is('admin/master*')) ? 'open' : '' }}">
            <a href="#" class="menu-link menu-toggle waves-effect" target="_blank">
              <i class="menu-icon tf-icons ri-stock-line"></i>
              <div>Master Management</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item {{ (request()->is('admin/master/banner*')) ? 'open' : '' }}">
                <a href="{{route('admin.banner.index')}}" class="menu-link">
                  <div>Banner</div>
                </a>
              </li>
              <li class="menu-item {{ (request()->is('admin/master/why-ewent*')) ? 'open' : '' }}">
                <a href="{{route('admin.why-ewent')}}" class="menu-link">
                  <div>Why Ewent</div>
                </a>
              </li>
              <li class="menu-item {{ (request()->is('admin/master/faq*')) ? 'open' : '' }}">
                <a href="{{route('admin.faq.index')}}" class="menu-link">
                  <div>FAQ</div>
                </a>
              </li>
              <li class="menu-item {{ (request()->is('admin/master/policy-details*')) ? 'open' : '' }}">
                <a href="{{route('admin.policy-details')}}" class="menu-link">
                  <div>Policy Details</div>
                </a>
              </li>
            </ul>
          </li>
      @endif
      @if (hasPermissionByParent('employee_management'))
        <li class="menu-item {{ (request()->is('admin/employee*')) ? 'open' : '' }}">
          <a href="#" class="menu-link menu-toggle waves-effect" target="_blank">
            <i class="menu-icon tf-icons ri-stock-line"></i>
            <div>Employee Management</div>
          </a>
          <ul class="menu-sub">
            <li class="menu-item {{ (request()->is('admin/employee/list*')) ? 'open' : '' }}">
              <a href="{{route('admin.employee.list')}}" class="menu-link">
                <div>List</div>
              </a>
            </li>
            <li class="menu-item {{ (request()->is('admin/employee/designations*')) ? 'open' : '' }}">
              <a href="{{route('admin.designation.index')}}" class="menu-link">
                <div>Designations</div>
              </a>
            </li>
          </ul>
        </li>
      @endif
    {{-- <li class="menu-item {{ (request()->is('admin/master*')) ? 'open' : '' }}">
      <a href="#" class="menu-link menu-toggle waves-effect" target="_blank">
        <i class="menu-icon tf-icons ri-stock-line"></i>
        <div>Location Management</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item {{ (request()->is('admin/location/city*')) ? 'open' : '' }}">
          <a href="{{route('admin.city.index')}}" class="menu-link">
            <div>Cities</div>
          </a>
        </li>
        <li class="menu-item {{ (request()->is('admin/location/pincodes*')) ? 'open' : '' }}">
          <a href="{{route('admin.pincode.index')}}" class="menu-link">
            <div>Pincodes</div>
          </a>
        </li>
      </ul>
    </li> --}}
    @if (hasPermissionByParent('rider_management'))
      <li class="menu-item {{ (request()->is('admin/rider*')) ? 'open' : '' }}">
        <a href="#" class="menu-link menu-toggle waves-effect" target="_blank">
          <i class="menu-icon tf-icons ri-stock-line"></i>
          <div>Rider Management</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item {{ (request()->is('admin/rider*')) ? 'open' : '' }}">
            <a href="{{route('admin.customer.verification.list')}}" class="menu-link">
              <div>Verification</div>
            </a>
          </li>
          <li class="menu-item {{ (request()->is('admin/rider*')) ? 'open' : '' }}">
            <a href="{{route('admin.customer.engagement.list')}}" class="menu-link">
              <div>Engagement</div>
            </a>
          </li>
          {{-- <li class="menu-item ">
            <a href="https://demos.themeselection.com/materio-bootstrap-html-laravel-admin-template/demo-1/app/ecommerce/customer/details/notifications" class="menu-link">
              <div>Customer Details</div>
            </a>
          </li> --}}
        </ul>
      </li>
    @endif
    <li class="menu-item {{ (request()->is('admin/stock*')) ? 'open' : '' }}" style="">
      <a href="#" class="menu-link menu-toggle waves-effect" target="_blank">
        <i class="menu-icon tf-icons ri-store-line"></i>
        <div>Stock Management</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item {{ (request()->is('admin/stock/list')) ? 'open' : '' }}">
          <a href="{{route('admin.product.stocks')}}" class="menu-link">
            <div>Vehicle Stock</div>
          </a>
        </li>
      </ul>
    </li>
    @if (hasPermissionByParent('model_management'))
      <li class="menu-item {{ (request()->is('admin/models*')) ? 'open' : '' }}" style="">
        <a href="#" class="menu-link menu-toggle waves-effect" target="_blank">
          <i class="menu-icon tf-icons ri-store-line"></i>
          <div>Model Management</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item {{ (request()->is('admin/models/categories*')) ? 'open' : '' }}">
            <a href="{{route('admin.product.categories')}}" class="menu-link">
              <div>Categories</div>
            </a>
          </li>
    
          <li class="menu-item {{ (request()->is('admin/models/sub-categories*')) ? 'open' : '' }}">
            <a href="{{route('admin.product.sub_categories')}}" class="menu-link">
              <div>Subcategories</div>
            </a>
          </li>
          <li class="menu-item {{ (request()->is('admin/models/type*')) ? 'open' : '' }}">
            <a href="{{route('admin.product.type')}}" class="menu-link">
              <div>Keywords</div>
            </a>
          </li>

          <li class="menu-item {{ (request()->is('admin/models/list*')) ? 'open' : '' }}">
            <a href="{{route('admin.product.index')}}" class="menu-link">
              <div>Models</div>
            </a>
          </li>
          <li class="menu-item {{ (request()->is('admin/models/subscriptions')) ? 'open' : '' }}">
            <a href="{{route('admin.model.subscriptions')}}" class="menu-link">
              <div>Subscriptions</div>
            </a>
          </li>
        </ul>
      </li>
    @endif
     @if (hasPermissionByParent('vehicle_management'))
    <li class="menu-item {{ (request()->is('admin/vehicle*')) ? 'open' : '' }}" style="">
      <a href="#" class="menu-link menu-toggle waves-effect" target="_blank">
        <i class="menu-icon tf-icons ri-store-line"></i>
        <div>Vehicle Management</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item {{ (request()->is('admin/vehicle/list')) ? 'open' : '' }}">
          <a href="{{route('admin.vehicle.list')}}" class="menu-link">
            <div>Vehicles List</div>
          </a>
        </li>
  
        <li class="menu-item {{ (request()->is('admin/vehicle/create')) ? 'open' : '' }}">
          <a href="{{route('admin.vehicle.create')}}" class="menu-link">
            <div>Create Vehicle</div>
          </a>
        </li>
      </ul>
    </li>
    @endif
    @if (hasPermissionByParent('payment_management'))
      <li class="menu-item {{ (request()->is('admin/payment*')) ? 'open' : '' }}" style="">
        <a href="#" class="menu-link menu-toggle waves-effect" target="_blank">
          <i class="menu-icon tf-icons ri-store-line"></i>
          <div>Payment Management</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item {{ (request()->is('admin/payment/summary')) ? 'open' : '' }}">
            <a href="{{route('admin.payment.summary')}}" class="menu-link">
              <div>Summary</div>
            </a>
          </li>
          <li class="menu-item {{ (request()->is('admin/payment/vehicle/summary')) ? 'open' : '' }}">
            <a href="{{route('admin.payment.vehicle.summary')}}" class="menu-link">
              <div>Vehicle Summary</div>
            </a>
          </li>
          <li class="menu-item {{ (request()->is('admin/payment/user/payment-history')) ? 'open' : '' }}">
            <a href="{{route('admin.payment.user_payment_history')}}" class="menu-link">
              <div>User Payment History</div>
            </a>
          </li>
          <li class="menu-item {{ (request()->is('admin/payment/refund-summary')) ? 'open' : '' }}">
            <a href="{{route('admin.payment.refund.summary')}}" class="menu-link">
              <div>Payment Refund Summary</div>
            </a>
          </li>
        </ul>
      </li>
    @endif
    <li class="menu-item {{ (request()->is('admin/offer*')) ? 'open' : '' }}" style="">
      <a href="#" class="menu-link menu-toggle waves-effect" target="_blank">
        <i class="menu-icon tf-icons ri-store-line"></i>
        <div>Offer Management</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item {{ (request()->is('admin/offer/list*')) ? 'open' : '' }}">
          <a href="{{route('admin.offer.list')}}" class="menu-link">
            <div>Offer List</div>
          </a>
        </li>
      </ul>
    </li>
    <li class="menu-item {{ (request()->is('admin/bom-parts*')) ? 'open' : '' }}" style="">
      <a href="#" class="menu-link menu-toggle waves-effect" target="_blank">
        <i class="menu-icon tf-icons ri-store-line"></i>
        <div>BOM Parts</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item {{ (request()->is('admin/bom-parts*')) ? 'open' : '' }}">
          <a href="{{route('admin.bom_part.list')}}" class="menu-link">
            <div>Part List</div>
          </a>
        </li>
      </ul>
    </li>
   
    <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
      <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
    </div>
    <div class="ps__rail-y" style="top: 0px; right: 4px;">
      <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div>
    </div>
  </ul>
</aside>
