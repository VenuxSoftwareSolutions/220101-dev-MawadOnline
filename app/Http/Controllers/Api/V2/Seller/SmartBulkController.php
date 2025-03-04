<?php

namespace App\Http\Controllers\Api\V2\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SmartBulkController extends Controller

{
    private $apiUrl;
    public function __construct()
    {
        $this->apiUrl = env('API_URL', 'http://194.31.150.9:5050/mwd/rest');
    }


}
