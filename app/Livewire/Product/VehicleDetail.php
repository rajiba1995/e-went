<?php

namespace App\Livewire\Product;

use Livewire\Component;
use App\Models\Stock;
use App\Models\VehicleTimeline;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class VehicleDetail extends Component
{
    public $vehicle_id;
    public $vehicle;
    public $get_immobilize_request=[];
    public $immobilizer_status;
    public $immobilizer_id;
    public $map;
    public $active_tab = "Today Trips";
    public $vehicle_main_details,$start_date,$end_date;
    public $VehicleLastKnow;
    public $vehicle_timeline = [];
    public $ignation_status = 'OFF';
    public $movement = [
        'status'=>'N/A', 
        'status_since_millis'=>'',
        'time_ago'=>'N/A',
        'last_online'=>'N/A',
    ];
    public $speedData = [
        'display_name'=>'Speed', 
        'value'=>'N/A',
        'unit'=>'N/A',
    ];
    public $day_wise_distance_travelled = [
        'value'=>'...', 
        'unit'=>'...',
    ];
    public $day_wise_distance_timeline;

    public $callCount = 0;
    public function mount($vehicle_id){

        $this->vehicle = Stock::where('vehicle_track_id', $vehicle_id)->first();
        // if(!$this->vehicle){
        //     abort(404);
        // }
        $this->vehicle_id = $vehicle_id;
        $this->immobilizer_status = $this->vehicle->immobilizer_status;
        $this->immobilizer_id = $this->vehicle->immobilizer_request_id;
        $vehiclesUrl = 'https://app.loconav.sensorise.net/integration/api/v1/vehicles/'.$this->vehicle_id;

        $ch = curl_init($vehiclesUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "User-Authentication:" . env('LOCONAV_TOKEN'),
            "Accept: application/json"
        ]);

        $vehiclesResponse = curl_exec($ch);
        curl_close($ch);

        $vehiclesData = json_decode($vehiclesResponse, true);
        // dd($vehiclesData);
        if (!$vehiclesData) {
            $this->vehicle_main_details = ['error' => 'Failed to fetch data'];
        } else {
            $this->vehicle_main_details = $vehiclesData;
        }

        $timestamp = time();
        $carbonTime = Carbon::createFromTimestamp($timestamp, 'UTC'); 

        // Convert the UTC time to Asia/Kolkata timezone
        $carbonTimeInKolkata = $carbonTime->setTimezone(env('APP_LOCAL_TIMEZONE'));

        // Get the start of today (00:00:00) in the local timezone
        $startTime = Carbon::today()->setTimezone(env('APP_LOCAL_TIMEZONE'))->timestamp;

        // Get the current time in the local timezone
        $endTime = Carbon::now()->setTimezone(env('APP_LOCAL_TIMEZONE'))->timestamp;

        // Get the start of the current week (in the local timezone)
        $startOfWeek = Carbon::now()->startOfWeek()->setTimezone(env('APP_LOCAL_TIMEZONE'))->timestamp;

        // Calling methods with adjusted time
        $this->day_wise_distance_travelled($startTime, $endTime);
        // $this->weekly_distance_travelled($startOfWeek, $endTime);
        $this->day_wise_vehicle_timeline($startTime, $endTime);
         $this->LiveLocationByMap();
        $this->VehicleLastKnow();
    }
    public function LiveLocationByMap(){
        $vehiclesUrl = 'https://app.loconav.sensorise.net/integration/api/v1/vehicles/'.$this->vehicle_id.'/live_share_link';
        
        $ch = curl_init($vehiclesUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "User-Authentication: " . env('LOCONAV_TOKEN'),
            "Accept: application/json"
        ]);

        $vehiclesResponse = curl_exec($ch);
        curl_close($ch);

        $this->map = json_decode($vehiclesResponse, true);
        // dd($this->map);
    }
    public function VehicleLastKnow(){
        $vehiclesUrl = 'https://app.loconav.sensorise.net/integration/api/v1/vehicles/telematics/last_known';
        $payload = [
            "vehicleIds" => [$this->vehicle_id],
            "sensors" => [
                "gps",
                "vehicleBatteryLevel",
                "numberOfSatellites"
            ]
        ];

        $ch = curl_init($vehiclesUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true); // Set as POST request
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "User-Authentication: " . env('LOCONAV_TOKEN'),
            "Accept: application/json",
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload)); // Pass JSON body

        $vehiclesResponse = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($vehiclesResponse, true);
        if($response['success']==true){
            $this->VehicleLastKnow = $response['data']['values'][0];

            if (
                isset($this->VehicleLastKnow['gps']) &&
                isset($this->VehicleLastKnow['gps']['ignition']) &&
                isset($this->VehicleLastKnow['gps']['ignition']['value'])
            ) {
                $this->ignation_status = $this->VehicleLastKnow['gps']['ignition']['value'];
            }

            // movement
            if (
                isset($this->VehicleLastKnow['gps']) &&
                isset($this->VehicleLastKnow['gps']['movement']) 
            ) {
                $statusMillis = $this->VehicleLastKnow['gps']['movement']['statusSinceMillis'];

                $this->movement['status'] = $this->VehicleLastKnow['gps']['movement']['movementStatus'];
                $this->movement['status_since_millis'] = $statusMillis;
                if($statusMillis){
                    // Convert millis to seconds (in case it's float)
                    $timestamp = (int)$statusMillis;
                    // Use Carbon for nice formatting (recommended)
                    $carbonTime = Carbon::createFromTimestamp($timestamp, 'UTC'); // Create the timestamp in UTC


                    // Convert the UTC time to Asia/Kolkata timezone
                    $carbonTimeInKolkata = $carbonTime->setTimezone(env('APP_LOCAL_TIMEZONE'));
                    $this->movement['time_ago'] = $carbonTimeInKolkata->diffForHumans(); // e.g., "11 minutes ago"
                    $this->movement['last_online'] = $carbonTimeInKolkata->format('F d, Y, g:i A'); 
                }
                
            }

            // Speed
            if (
                isset($this->VehicleLastKnow['gps']) &&
                isset($this->VehicleLastKnow['gps']['speed']) 
            ) {

                $this->speedData['display_name'] = $this->VehicleLastKnow['gps']['speed']['displayName'];
                $this->speedData['value'] = $this->VehicleLastKnow['gps']['speed']['value'];
                $this->speedData['unit'] = $this->VehicleLastKnow['gps']['speed']['unit']; 
            }


        }else{
            $this->VehicleLastKnow = null;
        }
    }
    public function MobilizationRequest($value){
        $vehiclesUrl = 'https://app.loconav.sensorise.net/integration/api/v1/vehicles/'.$this->vehicle_id.'/immobilizer_requests';
        $payload = [
            "value" => $value,
        ];
        // dd($payload);
        $ch = curl_init($vehiclesUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true); // Set as POST request
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "User-Authentication: " . env('LOCONAV_TOKEN'),
            "Accept: application/json",
            "Content-Type: application/json"
        ]);
        
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload)); // Pass JSON body

        $vehiclesResponse = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($vehiclesResponse, true);

        if($response['success']==true){
            if ($response['success'] === true && !empty($response['data']['errors'])) {
                $message = $response['data']['errors'];
                session()->flash('error', $message);
                return false;
            }
            // Decode and debug response
            if(isset($response['data']['id']) && $value=="IMMOBILIZE"){
                $stock = Stock::find($this->vehicle->id);
                $stock->immobilizer_request_id = $response['data']['id'];
                $stock->save();
            }
            if(isset($response['data']['id']) && $value=="MOBILIZE"){
                $stock = Stock::find($this->vehicle->id);
                $stock->immobilizer_status = "MOBILIZE";
                $stock->immobilizer_request_id = null;
                $stock->save();
                $this->get_immobilize_request =  [];
                $this->immobilizer_id = null;
                $this->immobilizer_status = 'MOBILIZE';
            }
                
        }else{
            if ($response['success'] === false && !empty($response['data']['errors'])) {
                // Since errors is an array of arrays, get the first message
                $message = $response['data']['errors'][0]['message'] ?? 'Unknown error occurred';
                session()->flash('error', $message);
                return false;
            }
        }
    }

    public function day_wise_vehicle_timeline($startTime, $endTime){
        $vehiclesUrl = 'https://app.loconav.sensorise.net/integration/api/v1/vehicles/'.$this->vehicle_id.'/timeline?startTime='.$startTime.'&endTime='.$endTime.'';
        
        $ch = curl_init($vehiclesUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "User-Authentication: " . env('LOCONAV_TOKEN'),
            "Accept: application/json"
        ]);

        $vehiclesResponse = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($vehiclesResponse, true);
        // dd($response);
        if($response['success']==true && $response['data']==true){
            $this->day_wise_distance_timeline=$response['data'];
        }
    }
    public function weekly_distance_travelled($startTime, $endTime){
        $vehiclesUrl = 'https://app.loconav.sensorise.net/integration/api/v1/vehicles/'.$this->vehicle_id.'/distance_travelled?startTime='.$startTime.'&endTime='.$endTime.'';
        
        $ch = curl_init($vehiclesUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "User-Authentication: " . env('LOCONAV_TOKEN'),
            "Accept: application/json"
        ]);

        $vehiclesResponse = curl_exec($ch);
        curl_close($ch);

        $distance_travelled = json_decode($vehiclesResponse, true);
    }
    public function day_wise_distance_travelled($startTime, $endTime){
        $vehiclesUrl = 'https://app.loconav.sensorise.net/integration/api/v1/vehicles/'.$this->vehicle_id.'/distance_travelled?startTime='.$startTime.'&endTime='.$endTime.'';
        
        $ch = curl_init($vehiclesUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "User-Authentication: " . env('LOCONAV_TOKEN'),
            "Accept: application/json"
        ]);

        $vehiclesResponse = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($vehiclesResponse, true);
        // dd($response);
        if($response['success']==true && $response['data']==true){
            $this->day_wise_distance_travelled['value']=$response['data']['distance']['value'];
            $this->day_wise_distance_travelled['unit']=$response['data']['distance']['unit'];
        }
    }
    public function updateDate($field,$value){
        $this->$field = $value;
        if($this->start_date && $this->end_date){
            $this->active_tab = "Trip For:".date('d-m-Y', strtotime($this->start_date))." To ". date('d-m-Y', strtotime($this->end_date));
            $vehicle_timeline = VehicleTimeline::where('stock_id', $this->vehicle->id)->whereBetween('created_at', [
                $this->start_date . ' 00:00:00', 
                $this->end_date . ' 23:59:59'
            ])->get();
            
            $distance = 0;
            $runningTime = 0;
            $stoppageTime = 0;
            $offlineTime = 0;
            $averageSpeed = 0;

            foreach($vehicle_timeline as $key=>$item){
                if ($item->field == "distance") {
                    $distance += (float) $item->value; // Accumulate distance
                }
                if ($item->field == "runningTime") {
                    $runningTime += (float) $item->value; // Accumulate distance
                }
                if ($item->field == "stoppageTime") {
                    $stoppageTime += (float) $item->value; // Accumulate distance
                }
                if ($item->field == "offlineTime") {
                    $offlineTime += (float) $item->value; // Accumulate distance
                }
                if ($item->field == "averageSpeed") {
                    $averageSpeed += (float) $item->value; // Accumulate distance
                }
            }
            $this->vehicle_timeline = [
                'distance' => $distance,
                'distance_unit' => "km",
                'runningTime' => $runningTime,
                'running_time_unit' => "minutes",
                'stoppageTime' => $stoppageTime,
                'stoppage_time_unit' => "minutes",
                'offlineTime' => $offlineTime,
                'offline_time_unit' => "minutes",
                'averageSpeed' => $averageSpeed,
                'average_speed_unit' => "km/h",
            ];
        }
    }

    public function resetPageField(){
        $this->active_tab = "Today Trips";
        $this->reset(['start_date', 'end_date','vehicle_timeline']);
    }
    public function refreshItems()
    {
        $new_vehicle = Stock::where('id', $this->vehicle->id)->first();
         if(isset($new_vehicle->immobilizer_request_id)){
            $vehiclesUrl = 'https://app.loconav.sensorise.net/integration/api/v1/vehicles/immobilization_requests/' . $new_vehicle->immobilizer_request_id;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $vehiclesUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "User-Authentication: " . env('LOCONAV_TOKEN'),
                "Accept: application/json",
                "Content-Type: application/json"
            ]);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); // Make sure it's GET, POST, or PUT based on API

            $immobilization_response = curl_exec($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Capture HTTP status code
            curl_close($ch);
            // Decode and debug response
            $mobilize_response = json_decode($immobilization_response, true);
            if(isset($mobilize_response['success']) && $mobilize_response['success'] === true){
                if(isset($mobilize_response['data']) && $mobilize_response['data']['status'] ==="error"){
                    $new_vehicle->immobilizer_status = 'MOBILIZE';
                    $new_vehicle->immobilizer_request_id = null;
                    $new_vehicle->save();
                }
            }
            
            if(isset($mobilize_response['success']) && $mobilize_response['success'] === true){
                if(isset($mobilize_response['data']) && $mobilize_response['data']['status'] =="success"){
                        $new_vehicle->immobilizer_status = 'IMMOBILIZE';
                        $new_vehicle->immobilizer_request_id = null;
                        $new_vehicle->save();
                }
            }
            
             $this->get_immobilize_request =  $mobilize_response;
             $this->immobilizer_id = $new_vehicle->immobilizer_request_id;
             $this->immobilizer_status = $new_vehicle->immobilizer_status;
         }else{
            $this->get_immobilize_request = $mobilize_response['success'] = 0;
         }
        
          
    }
    public function render()
    {
        return view('livewire.product.vehicle-detail');
    }
}
