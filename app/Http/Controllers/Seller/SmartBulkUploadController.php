<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Upload;
use Auth;
use WebSocket\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class SmartBulkUploadController extends Controller
{
    private $apiUrl;
    private $wsUrl;
    public function __construct()
    {
        $this->apiUrl = env('API_URL', 'http://194.31.150.9:5050/mwd/rest');
        $this->wsUrl = env('WS_URL', 'ws://194.31.150.9:5050/mwd/ws');
    }

    public function uploadVendorProducts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,txt|max:51200'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }
        if ($request->hasFile('file')) {

            $file = $request->file('file');
            $fileHash = hash_file('sha256', $file->path());
            $jobId = Str::uuid()->toString(); 
            session([
                'bulk_job' => [
                    'id' => $jobId,
                    'file_hash' => $fileHash,
                    'steps_completed' => 0
                ]
            ]);
    
            $extension = strtolower($file->getClientOriginalExtension());

            $upload = new Upload;
            $upload->file_original_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $path = '/';
            $filename = preg_replace('/[^A-Za-z0-9\-]/', '', $upload->file_original_name) . '.' . $extension;
            // Save file in /home/mwd/SmartBulkUpload
            $storedFilePath = $file->storeAs($path, $filename, 'mwd_storage');

            try {

                $size = Storage::disk('mwd_storage')->size($storedFilePath);


                $upload->extension = $extension;
                $upload->file_name = '/home/mwd/SmartBulkUpload/' . $filename;
                $upload->user_id = Auth::id();
                $upload->type = 'document';
                $upload->file_size = $size;
                $upload->save();
                $wsUrl = "{$this->wsUrl}/bulkupload/setVendorProductsFile";
                $data = [
                    'jobId' => $jobId,
                    'vendorUserId' => Auth::id(),
                    'vendorProductsFile' => basename($filename)
                ];

                $client = new Client($wsUrl);

                // Send data
                $client->send(json_encode($data));

                // Receive response
                $response = $client->receive();

                // Close connection
                $client->close();

                return response()->json([
                    'success' => true,
                    'message' => 'File uploaded and sent to Server successfully!',
                    'file_path' => $storedFilePath,
                    'websocket_response' => $response,
                    'job_id' => $jobId,
                ]);

            } catch (\Exception $e) {
                return response()->json(['error' => 'File upload failed: ' . $e->getMessage()], 500);
            }
        }
        return response()->json(['error' => 'No file provided.'], 400);

    }
    public function setShippingConfig(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'job_id' => 'required|string',
            'shipping_details' => 'required|array',
            'shipping_details.*.from_qty' => 'required|numeric',
            'shipping_details.*.to_qty' => 'required|numeric',
            'shipping_details.*.charge' => 'required|numeric',
            'mwd3p_enabled' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $vendorProductShipping = array_map(function ($item) {
            return [
                'fromQty' => (float) $item['from_qty'],
                'toQty' => (float) $item['to_qty'],
                'charge' => (float) $item['charge']
            ];
        }, $request->input('shipping_details'));
        $requestBody = [
            'jobId' => $request->input('job_id'), 
            'vendorUserId' => Auth::id(),
            'vendorProductShipping' => $vendorProductShipping,
            'mwd3pProductShippingEnabled' => filter_var($request->input('mwd3p_enabled'), FILTER_VALIDATE_BOOLEAN)
        ];
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->post("{$this->apiUrl}/bulkupload/setShippingConfig", $requestBody);

            return response()->json($response->json());
        } catch (\Exception $e) {
            Log::error('Error in setShippingConfig:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'API call failed'
            ], 500);
        }
    }
    public function setDiscountConfig(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_id' => 'required|string',
            'discount_config.*.fromQty' => 'required|numeric',
            'discount_config.*.toQty' => 'required|numeric',
            'discount_config.*.startDate' => 'required|date',
            'discount_config.*.endDate' => 'required|date|after:start_date',
            'discount_config.*.pct' => 'required|numeric|min:0|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $discountConfig = array_map(function ($item) {
            return [
                'fromQty' => (int) $item['fromQty'],
                'toQty' => (int) $item['toQty'],
                'startDate' => $item['startDate'],
                'endDate' => $item['endDate'],
                'pct' => (float) $item['pct']
            ];
        }, $request->input('discount_config'));

        $requestBody = [
            'jobId' => $request->input('job_id'),            
            'vendorUserId' => Auth::id(),
            'discountConfig' => $discountConfig
        ];


        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->post("{$this->apiUrl}/bulkupload/setDiscountConfig", $requestBody);

            return response()->json($response->json());
        } catch (\Exception $e) {
            Log::error('Error in setDiscountConfig:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'API call failed'
            ], 500);
        }
    }
    public function submitJob(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $requestBody = [
            'jobId' => $request->input('job_id'),
            'vendorUserId' => Auth::id(),
        ];


        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->post("{$this->apiUrl}/bulkupload/submitJob", $requestBody);

            return response()->json($response->json());
        } catch (\Exception $e) {
            Log::error('Error in submitJob:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'API call failed'
            ], 500);
        }
    }
    public function uploadImage(Request $request)
    {
        try {
            $headers = array_filter($request->headers->all(), function($key) {
                return !in_array(strtolower($key), ['x-csrf-token', 'host']);
            }, ARRAY_FILTER_USE_KEY);

            $content = $request->getContent();

            $response = Http::withHeaders($headers)
                ->withBody($content, 'application/octet-stream')
                ->post("{$this->apiUrl}/bulkupload/uploadProductImage");

            return response()->json(
                $response->json(),
                $response->status()
            );

        } catch (\Exception $e) {
            Log::error('Bulk image upload failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Upload service unavailable',
                'code' => -1
            ], 503);
        }
    }
    public function uploadDoc(Request $request)
    {
        try {
            $headers = array_filter($request->headers->all(), function($key) {
                return !in_array(strtolower($key), ['x-csrf-token', 'host']);
            }, ARRAY_FILTER_USE_KEY);

            $content = $request->getContent();

            $response = Http::withHeaders($headers)
                ->withBody($content, 'application/octet-stream')
                ->post("{$this->apiUrl}/bulkupload/uploadProductDoc");

            return response()->json(
                $response->json(),
                $response->status()
            );

        } catch (\Exception $e) {
            Log::error('Bulk document upload failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Upload service unavailable',
                'code' => -1
            ], 503);
        }
    }
    public function checkJobStatus(Request $request)
    {
        $currentJob = session('bulk_job', null);
        $fileHash = $request->input('file_hash');

        if ($currentJob && $currentJob['file_hash'] === $fileHash) {
            return response()->json([
                'active_job' => true,
                'job_id' => $currentJob['id'],
                'completed_steps' => $currentJob['steps_completed']
            ]);
        }

        return response()->json([
            'active_job' => false
        ]);
    }
}