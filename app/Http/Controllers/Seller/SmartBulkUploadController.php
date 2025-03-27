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
                    'jobId' => '81dc9bdb-52d0-4dc2-0036-dbd8313ed055',
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
                    'message' => 'File uploaded and sent to WebSocket successfully!',
                    'file_path' => $storedFilePath,
                    'websocket_response' => $response
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
            'jobId' => '81dc9bdb-52d0-4dc2-0036-dbd8313ed055',
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
            'jobId' => '81dc9bdb-52d0-4dc2-0036-dbd8313ed055',
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
        $validator = Validator::make($request->all(), [
            'image' => 'required|file|image|max:2048',
            'file_path' => 'required|string|max:512',
            'user_set_sku' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }
        try {
            $javaEndpoint = "{$this->apiUrl}/bulkupload/uploadProductImage";
            $file = $request->file('image');
            $jpgFilename = $this->toJpgFilename($file->getClientOriginalName());
            $originalPath = $this->normalizeCsvPath($request->input('file_path'));
            $fullJavaPath = pathinfo($originalPath, PATHINFO_DIRNAME) . '/' . $jpgFilename;

            $headers = [
                "Content-Type" => "application/octet-stream",
                "job-id" => "81dc9bdb-52d0-4dc2-0036-dbd8313ed055",
                "vendor-user-id" => 337,
                "file-name" => $jpgFilename,
                "file-path" => $fullJavaPath
            ];
            $csvStylePath = $headers['file-path'] . '/' . $jpgFilename;
            \Log::debug('Normalized CSV path', [
                'full_path' => $csvStylePath,
                'match_attempt' => "Looking for: " . ltrim($csvStylePath, '/')
            ]);

            if ($request->filled('user_set_sku')) {
                $headers["user-set-sku"] = $request->input('user_set_sku');
            }
            \Log::debug('Sending to Java API', [
                'headers' => $headers,
                'filename' => $jpgFilename,
                'file_path' => $request->input('file_path')

            ]);


            $response = Http::withHeaders($headers)
                ->withBody(file_get_contents($file->getRealPath()), 'application/octet-stream')
                ->post($javaEndpoint);
            \Log::debug('Java API response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            $responseData = $response->json();

            if (!$response->successful() || $responseData['success'] !== true) {
                return response()->json([
                    'success' => false,
                    'message' => $responseData['message'] ?? 'Image validation failed',
                    'code' => $responseData['code'] ?? -1
                ], 400);
            }

            $path = Storage::putFileAs(
                'bulk-uploads/' . $headers['job-id'],
                $file,
                $this->normalizeCsvPath($request->input('file_path'))
            );

            return response()->json([
                'success' => true,
                'path' => $path,
                'message' => 'Image uploaded and validated successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Upload failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage(),
                'code' => -1
            ], 500);
        }
    }

    private function toJpgFilename($filename)
    {
        $base = pathinfo($filename, PATHINFO_FILENAME);
        return $base . '.jpg';
    }
    private function normalizeCsvPath($path)
    {
        $path = preg_replace('/^[^\/]+\//', '', $path);

        $path = str_replace('\\', '/', $path);

        $path = preg_replace('/(_converted)+\.jpg$/i', '.jpg', $path);

        return trim($path, "/ \t\n\r\0\x0B");
    }
}