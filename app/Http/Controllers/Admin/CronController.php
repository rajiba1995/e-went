<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\request;
use App\Models\Stock;
use App\Models\CronLog;
use App\Models\VehicleTimeline;
use App\Models\AsignedVehicle;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CronController extends Controller
{
    public function TestLog(){
        $logData = [
            'job_name' => 'TestCronLog',
            'url' => 'CLI or Scheduled Task',
            'request_payload' => null,
            'response' => null,
            'success' => true,
            'error_message' => 'test cron',
            'executed_at' => Carbon::now(),
        ];

        CronLog::create($logData);
    }
    public function DailyVehicleLog()
    {
        DB::beginTransaction();
        try {
            $timezone = env('APP_LOCAL_TIMEZONE', 'Asia/Kolkata'); // Default fallback
            $vehicles = Stock::select('id', 'vehicle_number', 'vehicle_track_id')->get();

            $logData = [
                'job_name' => 'DailyVehicleLog',
                'url' => request()->fullUrl() ?? url()->current() ?? 'CLI or Scheduled Task',
                'request_payload' => null,
                'response' => null,
                'success' => false,
                'error_message' => null,
                'executed_at' => now(),
            ];
            foreach ($vehicles as $item) {
                // Start and end timestamps for today in local timezone
                $startTime = Carbon::now($timezone)->startOfDay()->timestamp;
                $endTime = Carbon::now($timezone)->timestamp;

                $vehiclesUrl = 'https://app.loconav.sensorise.net/integration/api/v1/vehicles/' . $item->vehicle_track_id . '/timeline?startTime=' . $startTime . '&endTime=' . $endTime;

                $ch = curl_init($vehiclesUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    "User-Authentication: " . env('LOCONAV_TOKEN'),
                    "Accept: application/json"
                ]);

                $vehiclesResponse = curl_exec($ch);
                $curlError = curl_error($ch);
                curl_close($ch);

                if ($curlError) {
                    throw new \Exception("Curl Error: " . $curlError);
                }

                $response = json_decode($vehiclesResponse, true);
               
                $todayDate = Carbon::now($timezone)->toDateString();

                // if (isset($response['success']) && $response['success'] === true && !empty($response['data']['timeline'])) {
                //     $timeline = $response['data']['timeline'];
                //     $existing = VehicleTimeline::whereDate('created_at', $todayDate)->where('field', 'timeline')
                //         ->where('stock_id', $item->id)
                //         ->first();

                //     if ($existing) {
                //         $existing->value = json_encode($timeline);
                //         $existing->save();
                //     } else {
                //         $store = new VehicleTimeline;
                //         $store->stock_id = $item->id;
                //         $store->field = 'timeline';
                //         $store->value = json_encode($timeline);
                //         $store->save();
                //     }
                // }
                
                if (isset($response['success']) && $response['success'] === true && !empty($response['data']['stats'])) {
                    // Distance
                    if(!empty($response['data']['stats']['distance'])){
                        $existing = VehicleTimeline::whereDate('created_at', $todayDate)->where('field', 'distance')
                        ->where('stock_id', $item->id)
                        ->first();
                        if ($existing) {
                            $existing->value = $response['data']['stats']['distance']['value'];
                            $existing->unit = $response['data']['stats']['distance']['unit'];
                            $existing->save();
                        } else {
                            $store = new VehicleTimeline;
                            $store->stock_id = $item->id;
                            $store->field = 'distance';
                            $store->value = $response['data']['stats']['distance']['value'];
                            $store->unit = $response['data']['stats']['distance']['unit'];
                            $store->save();
                        }
                    }

                    // runningTime
                    if(!empty($response['data']['stats']['runningTime'])){
                        $existing = VehicleTimeline::whereDate('created_at', $todayDate)->where('field', 'runningTime')
                        ->where('stock_id', $item->id)
                        ->first();
                        if ($existing) {
                            $existing->value = $response['data']['stats']['runningTime']['value'];
                            $existing->unit = $response['data']['stats']['runningTime']['unit'];
                            $existing->save();
                        } else {
                            $store = new VehicleTimeline;
                            $store->stock_id = $item->id;
                            $store->field = 'runningTime';
                            $store->value = $response['data']['stats']['runningTime']['value'];
                            $store->unit = $response['data']['stats']['runningTime']['unit'];
                            $store->save();
                        }
                    }

                    // stoppageTime
                    if(!empty($response['data']['stats']['stoppageTime'])){
                        $existing = VehicleTimeline::whereDate('created_at', $todayDate)->where('field', 'stoppageTime')
                        ->where('stock_id', $item->id)
                        ->first();
                        if ($existing) {
                            $existing->value = $response['data']['stats']['stoppageTime']['value'];
                            $existing->unit = $response['data']['stats']['stoppageTime']['unit'];
                            $existing->save();
                        } else {
                            $store = new VehicleTimeline;
                            $store->stock_id = $item->id;
                            $store->field = 'stoppageTime';
                            $store->value = $response['data']['stats']['stoppageTime']['value'];
                            $store->unit = $response['data']['stats']['stoppageTime']['unit'];
                            $store->save();
                        }
                    }

                    // offlineTime
                    if(!empty($response['data']['stats']['offlineTime'])){
                        $existing = VehicleTimeline::whereDate('created_at', $todayDate)->where('field', 'offlineTime')
                        ->where('stock_id', $item->id)
                        ->first();
                        if ($existing) {
                            $existing->value = $response['data']['stats']['offlineTime']['value'];
                            $existing->unit = $response['data']['stats']['offlineTime']['unit'];
                            $existing->save();
                        } else {
                            $store = new VehicleTimeline;
                            $store->stock_id = $item->id;
                            $store->field = 'offlineTime';
                            $store->value = $response['data']['stats']['offlineTime']['value'];
                            $store->unit = $response['data']['stats']['offlineTime']['unit'];
                            $store->save();
                        }
                    }
                    // averageSpeed
                    if(!empty($response['data']['stats']['averageSpeed'])){
                        $existing = VehicleTimeline::whereDate('created_at', $todayDate)->where('field', 'averageSpeed')
                        ->where('stock_id', $item->id)
                        ->first();
                        if ($existing) {
                            $existing->value = $response['data']['stats']['averageSpeed']['value'];
                            $existing->unit = $response['data']['stats']['averageSpeed']['unit'];
                            $existing->save();
                        } else {
                            $store = new VehicleTimeline;
                            $store->stock_id = $item->id;
                            $store->field = 'averageSpeed';
                            $store->value = $response['data']['stats']['averageSpeed']['value'];
                            $store->unit = $response['data']['stats']['averageSpeed']['unit'];
                            $store->save();
                        }
                    }
                }

                DB::commit();
            }

            $logData['success'] = true;
            $logData['response'] = 'Timeline processed successfully.';

            CronLog::create($logData);

            return response()->json([
                'status' => true,
                'message' => 'Timeline processed successfully.',
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            $logData['error_message'] = $e->getMessage();
            $logData['response'] = 'TSomething went wrong.';

            CronLog::create($logData);
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong. ' . $e->getMessage(),
            ], 500);
        }
    }

    public function VehiclePaymentOverDue()
    {
        DB::beginTransaction();

        try {
            $timezone = env('APP_LOCAL_TIMEZONE', 'Asia/Kolkata'); // Default fallback
            $startTime = Carbon::now($timezone);
           
            $AsignedVehicles = AsignedVehicle::where('status', 'assigned')
                                ->where('end_date', '<', $startTime)
                                ->get();
            foreach ($AsignedVehicles as $item) {
                $item->status = 'overdue';
                $item->save();
            }

            $message = count($AsignedVehicles) . ' vehicle(s) marked as overdue.';

            CronLog::create([
                'job_name'         => 'VehiclePaymentOverDue',
                'url'              => request()->fullUrl(),
                'request_payload'  => json_encode([]), // if any input payload
                'response'         => $message,
                'success'          => 1,
                'error_message'    => null,
                'executed_at'      => Carbon::now(),
            ]);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => count($AsignedVehicles) . ' vehicle(s) marked as overdue.',
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            CronLog::create([
                'job_name'         => 'VehiclePaymentOverDue',
                'url'              => request()->fullUrl(),
                'request_payload'  => json_encode([]),
                'response'         => null,
                'success'          => 0,
                'error_message'    => $e->getMessage(),
                'executed_at'      => Carbon::now(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update vehicle statuses.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
    public function OverDueImmobilizerRequests()
    {
        DB::beginTransaction();

        try {
            $timezone = env('APP_LOCAL_TIMEZONE', 'Asia/Kolkata');
            $startTime = Carbon::now($timezone);
            $message = '';
            $payloadLog = [];
            $errors = [];

            $AsignedVehicles = AsignedVehicle::where('status', 'overdue')
                                ->where('end_date', '<', Carbon::now($timezone)->subDays(2))
                                ->get();

            foreach ($AsignedVehicles as $item) {
                if ($item->stock) {
                    $vehiclesUrl = 'https://app.loconav.sensorise.net/integration/api/v1/vehicles/' . $item->stock->vehicle_track_id . '/immobilizer_requests';
                    $payload = [
                        "value" => "IMMOBILIZE",
                    ];
                    $payloadLog[] = [
                        'vehicle_track_id' => $item->stock->vehicle_track_id,
                        'payload' => $payload,
                    ];

                    $ch = curl_init($vehiclesUrl);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        "User-Authentication: " . env('LOCONAV_TOKEN'),
                        "Accept: application/json",
                        "Content-Type: application/json"
                    ]);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
                    $vehiclesResponse = curl_exec($ch);
                    curl_close($ch);

                    $response = json_decode($vehiclesResponse, true);

                    if (isset($response['success']) && $response['success'] === true) {
                        if (!empty($response['data']['id'])) {
                            $stock = Stock::find($item->vehicle_id);
                            if ($stock) {
                                $stock->immobilizer_request_id = $response['data']['id'];
                                $stock->save();
                            }
                        }

                        if (!empty($response['data']['errors'])) {
                            $errors[] = $response['data']['errors'];
                        }

                    } else {
                        $errors[] = $response['data']['errors'][0]['message'] ?? 'Unknown error';
                    }
                }
            }

            $message = count($AsignedVehicles) . ' vehicle(s) processed for immobilizer.';

            CronLog::create([
                'job_name'         => 'OverDueImmobilizerRequests',
                'url'              => request()->fullUrl(),
                'request_payload'  => json_encode($payloadLog),
                'response'         => $message,
                'success'          => 1,
                'error_message'    => !empty($errors) ? json_encode($errors) : null,
                'executed_at'      => Carbon::now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            CronLog::create([
                'job_name'         => 'OverDueImmobilizerRequests',
                'url'              => request()->fullUrl(),
                'request_payload'  => json_encode([]),
                'response'         => null,
                'success'          => 0,
                'error_message'    => $e->getMessage(),
                'executed_at'      => Carbon::now(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send immobilizer requests.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
