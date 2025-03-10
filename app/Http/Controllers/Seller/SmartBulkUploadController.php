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
    public function __construct()
    {
        $this->apiUrl = env('API_URL', 'http://194.31.150.9:5050/mwd/rest');
    }

    public function uploadVendorProducts(Request $request)
    {
        // Validate file
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,txt|max:5120'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }
        $file = $request->file('file');

            $upload = new Upload;
            $upload->file_original_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $path = 'uploads/all/';
            $filename = preg_replace('/[^A-Za-z0-9\-]/', '', $upload->file_original_name) . '.csv' ;
            $extension = 'csv';

            try {
               
                $filename = 'test.csv';
                //$size = Storage::disk('local')->size($path . $filename);
    
                $upload->extension = $extension;
                $upload->file_name = 'public/' . $path . $filename;
                $upload->user_id = Auth::id();
                $upload->type = 'document';
                $upload->file_size = '27271';
                $upload->save();
    
                $wsUrl = "ws://194.31.150.9:5050/mwd/ws/bulkupload/setVendorProductsFile";
                $jobId = md5(uniqid(rand(), true)); // Generate UUID
            
                $data = [
                    'jobId' => '81dc9bdb-52d0-4dc2-0036-dbd8313ed055',
                    'vendorUserId' => 335,
                    'vendorProductsFile' =>  'vendorfile.csv'
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
                    'file_path' => $path,
                    'websocket_response' => $response
                ]);
        
            } catch (\Exception $e) {
                return response()->json(['error' => 'File upload failed: ' . $e->getMessage()], 500);
            }
    
        
    }
    public function setShippingConfig(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'job_id' => 'required|string',
            'vendor_user_id' => 'required|numeric',
            'shipping_details' => 'required|array',
            'shipping_details.*.from_qty' => 'required|numeric',
            'shipping_details.*.to_qty' => 'required|numeric',
            'shipping_details.*.charge' => 'required|numeric',
            'mwd3pProductShippingEnabled' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $vendorProductShipping = array_map(function ($item) {
            return [
                'fromQty' => (float)$item['from_qty'],
                'toQty'   => (float)$item['to_qty'],
                'charge'  => (float)$item['charge']
            ];
        }, $request->input('shipping_details'));
        $requestBody = [
            'jobId' => '81dc9bdb-52d0-4dc2-0036-dbd8313ed055',
            'vendorUserId' => (int)$request->input('vendor_user_id'),
            'vendorProductShipping' => $vendorProductShipping,
            'mwd3pProductShippingEnabled' => filter_var($request->input('mwd3pProductShippingEnabled'), FILTER_VALIDATE_BOOLEAN)
        ];
        Log::info('Shipping Config Request Payload:', $requestBody);
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

}
