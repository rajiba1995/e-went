<div>
  <div class="position-relative">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner py-6 mx-4">
  
        <!-- Login -->
        <div class="card p-7">
          <!-- Logo -->
          <div class="app-brand justify-content-center mt-5">
            <a href="{{ url('/') }}" class="app-brand-link gap-3">
              <span class="app-brand-text demo text-heading fw-semibold">Admin</span>
            </a>
          </div>
          <!-- /Logo -->
  
          <div class="card-body mt-1">
            <h4 class="mb-1">Welcome to Ewent! 👋🏻</h4>
            <p class="mb-5">Please sign-in to your account and start the adventure</p>
  
            <form wire:submit.prevent="login" class="mb-5">
              @if(session()->has('message'))
                  <div class="alert alert-danger" id="flashMessage">
                      {{ session('message') }}
                  </div>
              @endif
                  @csrf
              <div class="form-floating form-floating-outline mb-5">
                <input type="text" wire:model="email" class="form-control" id="email" placeholder="Enter your email">
                <label for="email">Email</label>
                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
              </div>
              <div class="mb-5">
                <div class="form-password-toggle">
                  <div class="input-group input-group-merge">
                    <div class="form-floating form-floating-outline">
                      <input type="password" wire:model="password" id="password" class="form-control" placeholder="Password" />
                      <label for="password">Password</label>
                    </div>
                    <span class="input-group-text cursor-pointer"><i class="ri-eye-off-line ri-20px"></i></span>
                  </div>
                </div>
                @error('password') <span class="text-danger">{{ $message }}</span> @enderror
              </div>
              <div class="mb-5">
                <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
              </div>
            </form>
  
          </div>
        </div>
        <!-- /Login -->
        <img src="{{ asset('assets/img/tree-3.png') }}" alt="auth-tree" class="authentication-image-object-left d-none d-lg-block">
        <img src="{{ asset('assets/img/login-back1.webp') }}" alt="auth-tree" class="authentication-image-object-right d-none d-lg-block">
      </div>
    </div>
  </div>
</div>
