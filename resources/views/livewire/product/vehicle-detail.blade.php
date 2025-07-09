<div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />


   <div class="row mb-5">
        <div class="col-auto my-auto">
            <div class="h-100">
            <h5 class="mb-0">Vehicle Details</h5>
            <div>
                <small class="text-dark fw-medium">Dashboard </small>
                <small class="text-light fw-medium arrow"><a href="{{route('admin.vehicle.list')}}">Vehicles</a></small>
                <small class="text-light fw-medium arrow">{{$vehicle_id}}</small>
            </div>
            </div>
        </div>
   </div>
   @if($this->vehicle_main_details['success']==false)
    <div class="alert alert-danger">
       {{$this->vehicle_main_details['data']['errors'][0]['message']}}
    </div>
   @endif
   @if ($this->vehicle_main_details['success']==true)
    <div class="row mb-5">
        <div class="col-md-8">
            <div class="white-part p-4">
                <div class="row">
                    <div class="col-md-7">
                        <div class="single-holder">
                            <img src="https://img.freepik.com/free-vector/red-scooter_1308-82607.jpg?t=st=1743594040~exp=1743597640~hmac=0c75bd02fe98ba4888a5213b52bcc76545883ef75b35f712e65692e6e90e2eda&w=900" >
                        </div>
                    </div>
                    <div class="col-md-5">

                        <div class="vehicle-status mb-5">
                            <figure>
                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0" viewBox="0 0 47.855 47.855" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M23.928 19.371c-5.675 0-10.866 2.886-13.885 7.719a2.5 2.5 0 0 0 4.241 2.648c2.099-3.361 5.705-5.367 9.644-5.367s7.544 2.006 9.644 5.367a2.497 2.497 0 0 0 3.444.797 2.5 2.5 0 0 0 .796-3.445c-3.018-4.833-8.208-7.719-13.884-7.719z" style="" fill="#010002" data-original="#010002" class=""></path><path d="M47.476 21.07C42.365 12.883 33.562 7.996 23.928 7.996S5.492 12.883.38 21.07a2.5 2.5 0 1 0 4.241 2.648c4.192-6.714 11.41-10.722 19.307-10.722s15.115 4.008 19.307 10.722a2.498 2.498 0 0 0 3.444.796 2.5 2.5 0 0 0 .797-3.444z" style="" fill="#010002" data-original="#010002" class=""></path><circle cx="23.928" cy="35.745" r="4.114" style="" fill="#010002" data-original="#010002" class=""></circle></g></svg>
                            </figure>
                            <figcaption>
                                <h5>{{$movement['status']}}</h5>
                                <h6>Last Online</h6>
                                <span>{{$movement['time_ago']}}</span><br>
                                <span>{{$movement['last_online']}}</span>
                            </figcaption>
                        </div>

                        {{-- <div class="battery-holder mb-5">
                            <span>SOC</span>
                            <div class="horri-battery">
                                <div class="battery-round"></div>
                                <div class="battery-inner" data-width="30%" style="width:30%;"></div>
                            </div>
                        </div> --}}

                        <div class="pill-box mb-5">
                            <div class="box">
                                <input type="checkbox" for="ignation" name="ignation" {{$ignation_status=='ON'?"checked":""}}>
                                <div class="color-box"></div>
                                <span class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0" viewBox="0 0 64 64" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M55.019 27.497H30.154A13.468 13.468 0 0 0 4 32.026c.22 14.54 19.71 18.625 25.784 5.468l2.69 1.77a.997.997 0 0 0 1.25-.12l3.938-3.829 3.73 3.81a1.027 1.027 0 0 0 .74.3.974.974 0 0 0 .72-.33l3.389-3.77 4.139 3.84a1.022 1.022 0 0 0 1.18.13l7.568-4.31a.876.876 0 0 0 .29-.25 4.978 4.978 0 0 0-4.4-7.238zm-41.811 8.278a3.75 3.75 0 0 1 0-7.498 3.75 3.75 0 0 1 0 7.498z" fill="#000000" opacity="1" data-original="#000000" class=""></path><path d="M13.208 30.276a1.75 1.75 0 0 0 0 3.5 1.75 1.75 0 0 0 0-3.5z" fill="#000000" opacity="1" data-original="#000000" class=""></path></g></svg>
                                </span>
                                <h6>Ignation State</h6>
                                <span class="value">{{$ignation_status}}</span>
                            </div>

                            {{-- <div class="box">
                                <input type="checkbox" for="session" name="session">
                                <div class="color-box"></div>
                                <span class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0" viewBox="0 0 455.838 455.838" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M454.968 160.667a4.983 4.983 0 0 0-.595-.719C393.884 99.46 313.461 66.147 227.919 66.147c-85.537 0-165.953 33.306-226.439 93.788A5.007 5.007 0 0 0 0 163.487c0 1.326.526 2.598 1.465 3.536l33.113 33.114a4.998 4.998 0 0 0 7.07 0c49.836-49.839 115.988-77.285 186.271-77.285 70.282 0 136.434 27.447 186.271 77.284a4.997 4.997 0 0 0 7.069 0l33.113-33.114a5 5 0 0 0 .596-6.355z" fill="#000000" opacity="1" data-original="#000000" class=""></path><path d="M227.919 153.719c-62.056 0-120.461 24.231-164.458 68.229a5 5 0 0 0 .001 7.071l33.01 33.009a4.999 4.999 0 0 0 7.07 0c33.277-33.277 77.448-51.605 124.377-51.605 46.931 0 91.102 18.328 124.376 51.605a5.001 5.001 0 0 0 7.071 0l33.011-33.009a5 5 0 0 0 0-7.072c-43.997-43.996-102.402-68.228-164.458-68.228z" fill="#000000" opacity="1" data-original="#000000" class=""></path><path d="M227.919 241.292c-38.701 0-75.126 15.11-102.564 42.549a4.996 4.996 0 0 0-1.465 3.537c0 1.326.525 2.598 1.465 3.535l33.01 33.01a4.999 4.999 0 0 0 7.07 0c16.719-16.719 38.909-25.926 62.484-25.926s45.767 9.209 62.485 25.926a4.998 4.998 0 0 0 7.069 0l33.01-33.01a5 5 0 0 0 0-7.07c-27.437-27.441-63.862-42.551-102.564-42.551zM227.919 334.083c-13.521 0-26.229 5.264-35.784 14.822a4.999 4.999 0 0 0 .001 7.07l32.248 32.25a4.999 4.999 0 0 0 7.07 0l32.248-32.25a5.001 5.001 0 0 0 0-7.071c-9.556-9.557-22.264-14.821-35.783-14.821z" fill="#000000" opacity="1" data-original="#000000" class=""></path></g></svg>
                                </span>
                                <h6>Session</h6>
                                <span class="value">ON</span>
                            </div> --}}

                            <div class="box">
                                <input type="checkbox" for="travel" name="travel">
                                <div class="color-box"></div>
                                <span class="icon">
                                    <i class="fa-solid fa-road"></i>
                                </span>
                                
                                <h6>Today Travelled</h6>
                                <span class="value">{{$day_wise_distance_travelled['value']}}{{$day_wise_distance_travelled['unit']}}</span>
                            </div>

                            <div class="box">
                                <input type="checkbox" for="speed" name="speed">
                                <div class="color-box"></div>
                                <span class="icon">
                                    <i class="fa-solid fa-tachometer-alt"></i>
                                </span>
                                <h6>{{$speedData['display_name']}}</h6>
                                <span class="value" id="vehicle-speed">{{$speedData['value']}} {{$speedData['unit']}}</span>
                            </div>
                            

                        </div>
                    </div>
                </div>
                    <div>
                        @if(session()->has('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                    </div>
                <div class="row" wire:poll.5000ms="refreshItems">
                    <div>
                       @if(isset($get_immobilize_request['success']) && $get_immobilize_request['success'] === false)
                            <div class="alert alert-danger">
                                <p><strong>Error:</strong> {{ $get_immobilize_request['data']['errors'][0]['message'] ?? 'Unknown error' }}</p>
                            </div>
                        @elseif(isset($get_immobilize_request['success']) && $get_immobilize_request['success'] === true)
                            <div class="alert alert-success">
                                <p><strong>Immobilize ID:</strong> {{ $get_immobilize_request['data']['id'] }}</p>
                                <p><strong>Immobilize Status:</strong> {{ $get_immobilize_request['data']['status'] }}</p>
                                <p><strong>Message:</strong> {{ $get_immobilize_request['data']['message'] ?? 'N/A' }}</p>
                                <p><strong>Requested At:</strong> 
                                    {{
                                        \Carbon\Carbon::createFromTimestamp($get_immobilize_request['data']['createdAt'])
                                            ->setTimezone(env('APP_LOCAL_TIMEZONE', 'Asia/Kolkata'))
                                            ->format('d M Y, h:i A')
                                    }}
                                </p>
                            </div>
                        @endif

                    </div>
                    <div class="text-end">
                        <div class="text-end">
                            @if($immobilizer_status=="MOBILIZE" && !$immobilizer_id)
                                <a href="javascript:void(0)" class="btn btn-success" wire:click="MobilizationRequest('IMMOBILIZE')">
                                <span class="px-2">Immobilize Now</span> <i class="fa-solid fa-unlock"></i>
                                </a>
                            @endif
                            @if(isset($get_immobilize_request['success']) && $get_immobilize_request['success'] === true)
                                <a href="javascript:void(0)" class="btn btn-dark text-light">
                                   <span class="px-2">Please wait for confirmation</span><i class="fa-solid fa-lock"></i>
                                </a>
                            @else
                                @if($immobilizer_status=="IMMOBILIZE")
                                    <a href="javascript:void(0)" class="btn btn-danger" wire:click="MobilizationRequest('MOBILIZE')">
                                    <span class="px-2">Mobilize Now</span><i class="fa-solid fa-lock"></i>
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="white-part p-4 mb-4">
                @if($map['success']==true)
                    <div class="heading-group">
                        <h5>Live Location</h5>
                        {{-- <a href="#" class="avarage-btm">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0" viewBox="0 0 64 64" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><g data-name="Layer 81"><path d="M57.29 4.71h-12.5a2 2 0 0 0 0 4h10.5v10.5a2 2 0 0 0 4 0V6.71a2 2 0 0 0-2-2zM19.21 4.71H6.71a2 2 0 0 0-2 2v12.5a2 2 0 0 0 4 0V8.71h10.5a2 2 0 0 0 0-4zM19.21 55.29H8.71v-10.5a2 2 0 0 0-4 0v12.5a2 2 0 0 0 2 2h12.5a2 2 0 0 0 0-4zM57.29 42.79a2 2 0 0 0-2 2v10.5h-10.5a2 2 0 0 0 0 4h12.5a2 2 0 0 0 2-2v-12.5a2 2 0 0 0-2-2z" fill="#000000" opacity="1" data-original="#000000"></path></g></g></svg>
                            fullscreen
                        </a> --}}
                    </div>

                    <div class="map-view text-center p-4">
                        <p>Please click below to view the map:</p>
                        <a href="{{ $map['data']['shareLink'] }}" target="_blank" class="btn btn-success">
                            View Live Tracking Map
                        </a>
                    </div>
                    
                @else
                    <div class="alert alert-danger">
                        Location not found!
                    </div>
                @endif
            </div>

            <div class="white-part p-4">
                <div class="heading-group">
                    <h5>Vehicles Primery Informations</h5>
                </div>
                <ul class="icon-list">
                    <li><i class="fa-solid fa-circle-arrow-right"></i> Register Number: <span>{{strtoupper($vehicle->vehicle_number)}}</span></li>
                    <li><i class="fa-solid fa-circle-arrow-right"></i> Chassis Number: <span>{{$vehicle->chassis_number}}</span></li>
                    <li><i class="fa-solid fa-circle-arrow-right"></i> lOP IMEI: <span>{{$vehicle->imei_number}}</span></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="d-flex align-items-center justify-content-end flex-wrap gap-2">
        <div style="max-width: 250px;
            margin-bottom: 20px;" class="text-start text-uppercase">
            <label for="startDate" class="form-label small mb-1">Start Date</label>
            <input type="date" id="startDate" wire:model="start_date" class="form-control border-2 p-2 custom-input-sm" wire:change="updateDate('start_date', $event.target.value)">
        </div>
            
        <div style="max-width: 250px;
            margin-bottom: 20px;" class="text-start text-uppercase">
            <label for="endDate" class="form-label small mb-1">End Date</label>
            <input type="date" id="endDate" wire:model="end_date" class="form-control border-2 p-2 custom-input-sm" wire:change="updateDate('end_date', $event.target.value)">
        </div>
        <a href="javascript:void(0)"
            class="btn btn-danger text-white custom-input-sm" wire:click="resetPageField">
            <i class="ri-restart-line"></i>
        </a>
    </div>
    <div class="full-info-area mt-5">
        <ul class="tab-nav">
            {{-- <li><a href="#tab1">Battery Metrics</a></li>
            <li><a href="#tab2">Motor Metrics</a></li> --}}
            <li><a href="#tab3">{{$active_tab}}</a></li>
            {{-- <li><a href="#tab4">Odometter Logs</a></li> --}}
            {{-- <li><a href="#tab5">Historical Logs</a></li>
            <li><a href="#tab6">Immobilize History</a></li> --}}
        </ul>
        <div class="tab-content-holder">
            {{-- <div class="tab-content" id="tab1">
                <div class="pill-box pill-box-new mb-5">
                    <div class="box">
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><g data-name="3 Battery"><path d="M354.44 68.79H157.56A32.62 32.62 0 0 0 125 101.37v361.05A32.62 32.62 0 0 0 157.56 495h196.88A32.62 32.62 0 0 0 387 462.42v-361a32.62 32.62 0 0 0-32.56-32.63zm-43.6 231.76a27.76 27.76 0 0 1-9.36 20.85l-69.08 61.24a6.24 6.24 0 0 1-10.09-6.57l16.32-51.25a9.45 9.45 0 0 0-9-12.32 28.46 28.46 0 0 1-28.47-28.5 28.49 28.49 0 0 1 7.65-19.41l77.49-83.06a6.24 6.24 0 0 1 10.52 6.11l-22.62 73.17a9.27 9.27 0 0 0 8.85 12 27.78 27.78 0 0 1 27.79 27.73zM307.18 34.84A17.86 17.86 0 0 0 289.34 17h-66.68a17.86 17.86 0 0 0-17.84 17.84v21.95h102.36z" fill="#000000" opacity="1" data-original="#000000" class=""></path></g></g></svg>
                        </span>
                        <h6>Battery ID</h6>
                        <span class="value">AKY_KJO_25698663</span>
                    </div>

                    <div class="box">
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><g data-name="3 Battery"><path d="M354.44 68.79H157.56A32.62 32.62 0 0 0 125 101.37v361.05A32.62 32.62 0 0 0 157.56 495h196.88A32.62 32.62 0 0 0 387 462.42v-361a32.62 32.62 0 0 0-32.56-32.63zm-43.6 231.76a27.76 27.76 0 0 1-9.36 20.85l-69.08 61.24a6.24 6.24 0 0 1-10.09-6.57l16.32-51.25a9.45 9.45 0 0 0-9-12.32 28.46 28.46 0 0 1-28.47-28.5 28.49 28.49 0 0 1 7.65-19.41l77.49-83.06a6.24 6.24 0 0 1 10.52 6.11l-22.62 73.17a9.27 9.27 0 0 0 8.85 12 27.78 27.78 0 0 1 27.79 27.73zM307.18 34.84A17.86 17.86 0 0 0 289.34 17h-66.68a17.86 17.86 0 0 0-17.84 17.84v21.95h102.36z" fill="#000000" opacity="1" data-original="#000000" class=""></path></g></g></svg>
                        </span>
                        <h6>Pack Current (A)</h6>
                        <span class="value">-15.56</span>
                    </div>

                    <div class="box">
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><g data-name="3 Battery"><path d="M354.44 68.79H157.56A32.62 32.62 0 0 0 125 101.37v361.05A32.62 32.62 0 0 0 157.56 495h196.88A32.62 32.62 0 0 0 387 462.42v-361a32.62 32.62 0 0 0-32.56-32.63zm-43.6 231.76a27.76 27.76 0 0 1-9.36 20.85l-69.08 61.24a6.24 6.24 0 0 1-10.09-6.57l16.32-51.25a9.45 9.45 0 0 0-9-12.32 28.46 28.46 0 0 1-28.47-28.5 28.49 28.49 0 0 1 7.65-19.41l77.49-83.06a6.24 6.24 0 0 1 10.52 6.11l-22.62 73.17a9.27 9.27 0 0 0 8.85 12 27.78 27.78 0 0 1 27.79 27.73zM307.18 34.84A17.86 17.86 0 0 0 289.34 17h-66.68a17.86 17.86 0 0 0-17.84 17.84v21.95h102.36z" fill="#000000" opacity="1" data-original="#000000" class=""></path></g></g></svg>
                        </span>
                        <h6>Pack Voltage (V)</h6>
                        <span class="value">52.69</span>
                    </div>

                    <div class="box">
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><g data-name="3 Battery"><path d="M354.44 68.79H157.56A32.62 32.62 0 0 0 125 101.37v361.05A32.62 32.62 0 0 0 157.56 495h196.88A32.62 32.62 0 0 0 387 462.42v-361a32.62 32.62 0 0 0-32.56-32.63zm-43.6 231.76a27.76 27.76 0 0 1-9.36 20.85l-69.08 61.24a6.24 6.24 0 0 1-10.09-6.57l16.32-51.25a9.45 9.45 0 0 0-9-12.32 28.46 28.46 0 0 1-28.47-28.5 28.49 28.49 0 0 1 7.65-19.41l77.49-83.06a6.24 6.24 0 0 1 10.52 6.11l-22.62 73.17a9.27 9.27 0 0 0 8.85 12 27.78 27.78 0 0 1 27.79 27.73zM307.18 34.84A17.86 17.86 0 0 0 289.34 17h-66.68a17.86 17.86 0 0 0-17.84 17.84v21.95h102.36z" fill="#000000" opacity="1" data-original="#000000" class=""></path></g></g></svg>
                        </span>
                        <h6>Remaining Capacity (Ah)</h6>
                        <span class="value">66km</span>
                    </div>

                    <div class="box">
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><g data-name="3 Battery"><path d="M354.44 68.79H157.56A32.62 32.62 0 0 0 125 101.37v361.05A32.62 32.62 0 0 0 157.56 495h196.88A32.62 32.62 0 0 0 387 462.42v-361a32.62 32.62 0 0 0-32.56-32.63zm-43.6 231.76a27.76 27.76 0 0 1-9.36 20.85l-69.08 61.24a6.24 6.24 0 0 1-10.09-6.57l16.32-51.25a9.45 9.45 0 0 0-9-12.32 28.46 28.46 0 0 1-28.47-28.5 28.49 28.49 0 0 1 7.65-19.41l77.49-83.06a6.24 6.24 0 0 1 10.52 6.11l-22.62 73.17a9.27 9.27 0 0 0 8.85 12 27.78 27.78 0 0 1 27.79 27.73zM307.18 34.84A17.86 17.86 0 0 0 289.34 17h-66.68a17.86 17.86 0 0 0-17.84 17.84v21.95h102.36z" fill="#000000" opacity="1" data-original="#000000" class=""></path></g></g></svg>
                        </span>
                        <h6>Remaining Range</h6>
                        <span class="value">66km</span>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="white-part p-4 battery-text-style">
                            <h5>Cell Temp 1</h5>
                            <span>33<sup>&#176;</sup>C</span>
                            <div class="vertical-battery">
                                <div class="battery-round"></div>
                                <div class="battery-inner" data-height="33%" style="height:33%;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="white-part p-4 battery-text-style">
                            <h5>Cell Temp 2</h5>
                            <span>40<sup>&#176;</sup>C</span>
                            <div class="vertical-battery">
                                <div class="battery-round"></div>
                                <div class="battery-inner" data-height="40%" style="height:40%;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="white-part p-4 battery-text-style">
                            <h5>Cell Temp 3</h5>
                            <span>39<sup>&#176;</sup>C</span>
                            <div class="vertical-battery">
                                <div class="battery-round"></div>
                                <div class="battery-inner" data-height="39%" style="height:39%;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="white-part p-4 battery-text-style">
                            <h5>Cell Temp 4</h5>
                            <span>36<sup>&#176;</sup>C</span>
                            <div class="vertical-battery">
                                <div class="battery-round"></div>
                                <div class="battery-inner" data-height="36%" style="height:36%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div> --}}

            {{-- <div class="tab-content" id="tab2">
                <div class="pill-box pill-box-new mb-5">
                    <div class="box">
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0" viewBox="0 0 438.529 438.529" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M436.25 181.438c-1.529-2.002-3.524-3.193-5.995-3.571l-52.249-7.992c-2.854-9.137-6.756-18.461-11.704-27.98 3.422-4.758 8.559-11.466 15.41-20.129 6.851-8.661 11.703-14.987 14.561-18.986 1.523-2.094 2.279-4.281 2.279-6.567 0-2.663-.66-4.755-1.998-6.28-6.848-9.708-22.552-25.885-47.106-48.536-2.275-1.903-4.661-2.854-7.132-2.854-2.857 0-5.14.855-6.854 2.567l-40.539 30.549c-7.806-3.999-16.371-7.52-25.693-10.565l-7.994-52.529c-.191-2.474-1.287-4.521-3.285-6.139C255.95.806 253.623 0 250.954 0h-63.38c-5.52 0-8.947 2.663-10.278 7.993-2.475 9.513-5.236 27.214-8.28 53.1a163.366 163.366 0 0 0-25.981 10.853l-39.399-30.549c-2.474-1.903-4.948-2.854-7.422-2.854-4.187 0-13.179 6.804-26.979 20.413-13.8 13.612-23.169 23.841-28.122 30.69-1.714 2.474-2.568 4.664-2.568 6.567 0 2.286.95 4.57 2.853 6.851 12.751 15.42 22.936 28.549 30.55 39.403-4.759 8.754-8.47 17.511-11.132 26.265l-53.105 7.992c-2.093.382-3.9 1.621-5.424 3.715C.76 182.531 0 184.722 0 187.002v63.383c0 2.478.76 4.709 2.284 6.708 1.524 1.998 3.521 3.195 5.996 3.572l52.25 7.71c2.663 9.325 6.564 18.743 11.704 28.257-3.424 4.761-8.563 11.468-15.415 20.129-6.851 8.665-11.709 14.989-14.561 18.986-1.525 2.102-2.285 4.285-2.285 6.57 0 2.471.666 4.658 1.997 6.561 7.423 10.284 23.125 26.272 47.109 47.969 2.095 2.094 4.475 3.138 7.137 3.138 2.857 0 5.236-.852 7.138-2.563l40.259-30.553c7.808 3.997 16.371 7.519 25.697 10.568l7.993 52.529c.193 2.471 1.287 4.518 3.283 6.14 1.997 1.622 4.331 2.423 6.995 2.423h63.38c5.53 0 8.952-2.662 10.287-7.994 2.471-9.514 5.229-27.213 8.274-53.098a163.044 163.044 0 0 0 25.981-10.855l39.402 30.84c2.663 1.712 5.141 2.563 7.42 2.563 4.186 0 13.131-6.752 26.833-20.27 13.709-13.511 23.13-23.79 28.264-30.837 1.711-1.902 2.569-4.09 2.569-6.561 0-2.478-.947-4.862-2.857-7.139-13.698-16.754-23.883-29.882-30.546-39.402 3.806-7.043 7.519-15.701 11.136-25.98l52.817-7.988c2.279-.383 4.189-1.622 5.708-3.716 1.523-2.098 2.279-4.288 2.279-6.571v-63.376c.005-2.474-.751-4.707-2.278-6.707zm-165.304 89.501c-14.271 14.277-31.497 21.416-51.676 21.416-20.177 0-37.401-7.139-51.678-21.416-14.272-14.271-21.411-31.498-21.411-51.673 0-20.177 7.135-37.401 21.411-51.678 14.277-14.272 31.504-21.411 51.678-21.411 20.179 0 37.406 7.139 51.676 21.411 14.274 14.277 21.413 31.501 21.413 51.678 0 20.175-7.138 37.403-21.413 51.673z" fill="#000000" opacity="1" data-original="#000000" class=""></path></g></svg>
                        </span>
                        <h6>Anti Theft Status</h6>
                        <span class="value">Safe</span>
                    </div>

                    <div class="box">
                        <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0" viewBox="0 0 53 53" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M37.64 10.77c.39.4.64.95.64 1.55v15.04c0 1.21-.98 2.19-2.19 2.19s-2.2-.98-2.2-2.19H26.5c-2.29 0-4.13-1.85-4.13-4.12V22l-10.04-.04c-1.55-.01-2.16 1.17-2.31 1.55-.15.36-.55 1.62.54 2.71l5.16 5.16c1.96 1.97 2.51 4.77 1.46 7.34-1.07 2.56-3.45 4.15-6.21 4.15H5.61c-.58 0-1.11-.24-1.5-.62-.38-.38-.61-.91-.61-1.5 0-1.16.94-2.11 2.11-2.11h5.36c1.54.01 2.15-1.18 2.31-1.54.14-.36.55-1.63-.55-2.73l-5.16-5.16c-1.95-1.95-2.51-4.77-1.45-7.32 1.06-2.56 3.44-4.16 6.21-4.15.1 0 7.53-.04 10.04-.06v-1.23c0-2.28 1.84-4.12 4.13-4.12h7.39v-.01a2.198 2.198 0 0 1 3.75-1.55zM49.5 24.48c0 .55-.22 1.05-.58 1.41-.36.36-.86.58-1.41.58h-7.23v-3.98h7.23c1.1 0 1.99.89 1.99 1.99zM47.51 17.19h-7.23v-3.97h7.23a1.987 1.987 0 0 1 1.41 3.39c-.36.36-.86.58-1.41.58z" fill="#000000" opacity="1" data-original="#000000" class=""></path></g></svg>
                        </span>
                        <h6>Charger Connecting</h6>
                        <span class="value">Idle</span>
                    </div>

                    <div class="box">
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><g data-name="3 Battery"><path d="M354.44 68.79H157.56A32.62 32.62 0 0 0 125 101.37v361.05A32.62 32.62 0 0 0 157.56 495h196.88A32.62 32.62 0 0 0 387 462.42v-361a32.62 32.62 0 0 0-32.56-32.63zm-43.6 231.76a27.76 27.76 0 0 1-9.36 20.85l-69.08 61.24a6.24 6.24 0 0 1-10.09-6.57l16.32-51.25a9.45 9.45 0 0 0-9-12.32 28.46 28.46 0 0 1-28.47-28.5 28.49 28.49 0 0 1 7.65-19.41l77.49-83.06a6.24 6.24 0 0 1 10.52 6.11l-22.62 73.17a9.27 9.27 0 0 0 8.85 12 27.78 27.78 0 0 1 27.79 27.73zM307.18 34.84A17.86 17.86 0 0 0 289.34 17h-66.68a17.86 17.86 0 0 0-17.84 17.84v21.95h102.36z" fill="#000000" opacity="1" data-original="#000000" class=""></path></g></g></svg>
                        </span>
                        <h6>Cruise Status</h6>
                        <span class="value">Off</span>
                    </div>

                    <div class="box">
                        <span class="icon">
                            <i class="fa-solid fa-plug"></i>
                        </span>
                        <h6>Current</h6>
                        <span class="value">NA</span>
                    </div>

                    <div class="box">
                        <span class="icon">
                            <i class="fa-solid fa-road-circle-check"></i>
                        </span>
                        <h6>Total Travel</h6>
                        <span class="value">6596km</span>
                    </div>

                </div>
            </div> --}}

            <div class="tab-content" id="tab3">
                @if($day_wise_distance_timeline && null !== $day_wise_distance_timeline['stats'])
                <div class="pill-box pill-box-new mb-5">
                    @if(isset($day_wise_distance_timeline['stats']['distance']))
                        <div class="box">
                            <span class="icon">
                                <i class="fa-solid fa-road"></i>
                            </span>
                            <h6>Total Travel</h6>
                            @if($start_date && $end_date)
                                <span class="value">{{$vehicle_timeline['distance']}} {{$vehicle_timeline['distance_unit']}}</span>
                            @else
                                <span class="value">{{$day_wise_distance_timeline['stats']['distance']['value']}} {{$day_wise_distance_timeline['stats']['distance']['unit']}}</span>
                            @endif
                        </div>
                    @endif
                    @if(isset($day_wise_distance_timeline['stats']['runningTime']))
                        <div class="box">
                            <span class="icon">
                                <i class="fa-solid fa-clock"></i>
                            </span>
                            <h6>Running Time</h6>
                             @if($start_date && $end_date)
                                <span class="value">{{$vehicle_timeline['runningTime']}} {{$vehicle_timeline['running_time_unit']}}</span>
                            @else
                                <span class="value">{{$day_wise_distance_timeline['stats']['runningTime']['value']}} {{$day_wise_distance_timeline['stats']['runningTime']['unit']}}</span>
                            @endif
                        </div>
                    @endif
                    @if(isset($day_wise_distance_timeline['stats']['stoppageTime']))
                        <div class="box">
                            <span class="icon">
                                <i class="fa-solid fa-clock"></i>
                            </span>
                            <h6>Stoppage Time</h6>
                            @if($start_date && $end_date)
                                <span class="value">{{$vehicle_timeline['stoppageTime']}} {{$vehicle_timeline['stoppage_time_unit']}}</span>
                            @else
                                <span class="value">{{$day_wise_distance_timeline['stats']['stoppageTime']['value']}} {{$day_wise_distance_timeline['stats']['stoppageTime']['unit']}}</span>
                            @endif
                        </div>
                    @endif
                    @if(isset($day_wise_distance_timeline['stats']['offlineTime']))
                        <div class="box">
                            <span class="icon">
                                <i class="fa-solid fa-clock"></i>
                            </span>
                            <h6>Offline Time</h6>
                            @if($start_date && $end_date)
                                <span class="value">{{$vehicle_timeline['offlineTime']}} {{$vehicle_timeline['offline_time_unit']}}</span>
                            @else
                                <span class="value">{{$day_wise_distance_timeline['stats']['offlineTime']['value']}} {{$day_wise_distance_timeline['stats']['offlineTime']['unit']}}</span>
                            @endif
                        </div>
                    @endif
                    @if(isset($day_wise_distance_timeline['stats']['averageSpeed']))
                        <div class="box">
                            <span class="icon">
                                <i class="fa-solid fa-tachometer-alt"></i>
                            </span>
                            <h6>Average Speed</h6>
                            @if($start_date && $end_date)
                                <span class="value">{{$vehicle_timeline['averageSpeed']}} {{$vehicle_timeline['average_speed_unit']}}</span>
                            @else
                            <span class="value">{{$day_wise_distance_timeline['stats']['averageSpeed']['value']}} {{$day_wise_distance_timeline['stats']['averageSpeed']['unit']}}</span>
                            @endif
                        </div>
                    @endif
                </div>
                @else
                    <div class="row">
                        <div class="col-md-12">
                            <div class="white-part p-5">
                                <div class="alert alert-danger">
                                    Data not found!
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row my-5">
                    <div class="col-md-4">
                        <div class="white-part p-5">
                            <div class="heading-group">
                                <h5>Day Locations</h5>
                            </div>
                            @if($day_wise_distance_timeline && null !== $day_wise_distance_timeline['startLocation'] && null !== $day_wise_distance_timeline['endLocation'])
                                <ul class="icon-list">
                                    <li><i class="fa-solid fa-circle-arrow-right"></i> Start Location: <br>
                                        <span>
                                            <strong>Address:</strong> {{$day_wise_distance_timeline['startLocation']['address']}}
                                        </span>
                                    </li>
                                    <li><i class="fa-solid fa-circle-arrow-right"></i> End Location: <br>
                                        <span>
                                            <strong>Address:</strong> {{$day_wise_distance_timeline['endLocation']['address']}}
                                        </span>
                                    </li>
                                </ul>
                            @else
                                <div class="alert alert-danger">
                                   Data not found!
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-md-8">
                        <div class="white-part p-5">

                            <div class="heading-group">
                                <h5>Vehicle Movement Timeline</h5>
                            </div>
                           
                            <div class="movement_overflow">
                                @if($day_wise_distance_timeline && null !== $day_wise_distance_timeline['timeline'])
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Movement Status</th>
                                                <th>Start Location</th>
                                                <th>End Location</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($day_wise_distance_timeline['timeline'] as $timeline)
                                                <tr>
                                                    <td class="text-{{$timeline['movementStatus']=="Moving"?"success":"danger"}}">{{$timeline['movementStatus']}}</td>
                                                    <td>
                                                        <strong>Start Time:</strong> {{ \Carbon\Carbon::createFromTimestamp($timeline['startLocation']['timestamp'])->setTimezone('Asia/Kolkata')->format('F d, Y, g:i A T') }} <br>
                                                        <strong>Address:</strong> <small>{{$timeline['endLocation']['address']}}</small>
                                                    </td>
                                                    <td>
                                                        <strong>Stop Time:</strong> {{ \Carbon\Carbon::createFromTimestamp($timeline['endLocation']['timestamp'])->setTimezone('Asia/Kolkata')->format('F d, Y, g:i A T') }} <br>
                                                        <strong>Address:</strong> <small>{{$timeline['endLocation']['address']}}</small>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3">
                                                        <div class="alert alert-danger">
                                                            Data not found!
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="tab-content" id="tab4">
                ascasccsac
            </div> --}}

            {{-- <div class="tab-content" id="tab5">
                ascasccsac
            </div>

            <div class="tab-content" id="tab6">
                ascasccsac
            </div> --}}
        </div>
    </div>
   <div class="loader-container" wire:loading wire:target="updateDate,resetPageField,MobilizationRequest">
        <div class="loader"></div>
    </div>

   @endif

<script>

$('.tab-nav li:first-child').addClass('active');
$('.tab-content').hide();
$('.tab-content:first').show();

// Click function
$('.tab-nav li').click(function(){
  $('.tab-nav li').removeClass('active');
  $(this).addClass('active');
  $('.tab-content').hide();
  
  var activeTab = $(this).find('a').attr('href');
  $(activeTab).fadeIn();
  return false;
});    
</script>
</div>
