<?php

namespace App\Http\Controllers;

use App\Models\Address as AddressModel;
use App\Models\Cart;
use App\Models\Currency;
use App\Models\State;
use Carbon\Carbon;
use Exception;
use ExtremeSa\Aramex\API\Classes\Address;
use ExtremeSa\Aramex\Aramex;
use Log;

class AramexController extends Controller
{
    protected $clientInfo;

    protected $baseUri;

    protected $curl;

    public function __construct()
    {
        $this->clientInfo = [
            'Source' => 24,
            'AccountCountryCode' => env('ARAMEX_COUNTRY_CODE'),
            'AccountEntity' => env('ARAMEX_ENTITY'),
            'AccountPin' => env('ARAMEX_PIN'),
            'AccountNumber' => env('ARAMEX_NUMBER'),
            'UserName' => env('ARAMEX_USERNAME'),
            'Password' => env('ARAMEX_PASSWORD'),
            'Version' => 'v1',
        ];
        $this->baseUri = 'https://ws.sbx.aramex.net/ShippingAPI.V2';
        $this->curl = curl_init();
    }

    public function fetchCities(string $countryCode)
    {
        try {
            $cities = Aramex::fetchCities()
                ->setCountryCode($countryCode)
                ->run();

            return $cities->isSuccessful() ? $cities->getCities() : $cities->getNotificationMessages();
        } catch (Exception $e) {
            Log::error("Error while fetch cities, with message: {$e->getMessage()}");

            return response()->json(['error' => true, 'message' => __('Something went wrong!')], 500);
        }
    }

    public function fetchCountries()
    {
        try {
            return Aramex::fetchCountries()->run();
        } catch (Exception $e) {
            Log::error("Error while fetch countries, with message: {$e->getMessage()}");

            return response()->json(['error' => true, 'message' => __('Something went wrong!')], 500);
        }
    }

    public function validateAddress(string $line1, string $line2, string $city, string $countryCode)
    {
        try {
            return Aramex::validateAddress()
                ->setAddress(
                    (new Address)
                        ->setLine1($line1)
                        ->setLine2($line2)
                        ->setCity($city)
                        ->setCountryCode($countryCode)
                )->run();
        } catch (Exception $e) {
            Log::error("Error while validating address, with message: {$e->getMessage()}");

            return response()->json(['error' => true, 'message' => __('Something went wrong!')], 500);
        }
    }

    public function calculateRate(array $data = [])
    {
        try {
            $body = json_encode(count($data) > 0 ? $data : [
                'OriginAddress' => [
                    'Line1' => '2 main street',
                    'Line2' => null,
                    'Line3' => null,
                    'City' => 'Dubai',
                    'StateOrProvinceCode' => null,
                    'PostCode' => null,
                    'CountryCode' => 'AE',
                    'Longitude' => 0,
                    'Latitude' => 0,
                    'BuildingNumber' => null,
                    'BuildingName' => null,
                    'Floor' => null,
                    'Apartment' => null,
                    'POBox' => null,
                    'Description' => null,
                ],
                'DestinationAddress' => [
                    'Line1' => null,
                    'Line2' => null,
                    'Line3' => null,
                    'City' => 'Tunis',
                    'StateOrProvinceCode' => null,
                    'PostCode' => null,
                    'CountryCode' => 'TN',
                    'Longitude' => 0,
                    'Latitude' => 0,
                    'BuildingNumber' => null,
                    'BuildingName' => null,
                    'Floor' => null,
                    'Apartment' => null,
                    'POBox' => null,
                    'Description' => null,
                ],
                'ShipmentDetails' => [
                    'Dimensions' => null,
                    'ActualWeight' => [
                        'Unit' => 'KG',
                        'Value' => 55,
                    ],
                    'ChargeableWeight' => [
                        'Unit' => 'KG',
                        'Value' => 0.4,
                    ],
                    'DescriptionOfGoods' => null,
                    'GoodsOriginCountry' => null,
                    'NumberOfPieces' => 1,
                    'ProductGroup' => ARAMEX_PRODUCT_GROUP,
                    'ProductType' => ARAMEX_PRODUCT_TYPE,
                    'PaymentType' => ARAMEX_PAYMENT_TYPE,
                    'PaymentOptions' => null,
                    'CustomsValueAmount' => null,
                    'CashOnDeliveryAmount' => null,
                    'InsuranceAmount' => null,
                    'CashAdditionalAmount' => null,
                    'CashAdditionalAmountDescription' => null,
                    'CollectAmount' => null,
                    'Services' => '',
                    'Items' => null,
                    'DeliveryInstructions' => null,
                    'AdditionalProperties' => null,
                    'ContainsDangerousGoods' => false,
                ],
                'PreferredCurrencyCode' => 'AED',
                'ClientInfo' => $this->clientInfo,
                'Transaction' => null,
            ]);

            curl_setopt_array($this->curl, [
                CURLOPT_URL => $this->baseUri.'/RateCalculator/Service_1_0.svc/json/CalculateRate',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $body,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Accept: application/json',
                ],
            ]);

            $response = curl_exec($this->curl);

            return json_decode($response, true);
        } catch (Exception $e) {
            Log::error("Error while calculating rate using aramex api, with message: {$e->getMessage()}");

            return response()->json(['error' => true, 'message' => "There's an error"], 500);
        }
    }

    public function createPickup(array $input = [])
    {
        try {
            $body = json_encode(count($input) > 0 ? $input : [
                'ClientInfo' => [
                    'Source' => 24,
                    'AccountCountryCode' => 'AE',
                    'AccountEntity' => 'DXB',
                    'AccountPin' => '116216',
                    'AccountNumber' => '45796',
                    'UserName' => 'testingapi@aramex.com',
                    'Password' => 'R123456789$r',
                    'Version' => 'v1',
                ],
                'LabelInfo' => [
                    'ReportID' => 9201,
                    'ReportType' => 'URL',
                ],
                'Pickup' => [
                    'PickupAddress' => [
                        'Line1' => 'Test Address',
                        'Line2' => 'Test Address Line 2',
                        'Line3' => '',
                        'City' => 'Dubai',
                        'StateOrProvinceCode' => 'Dubai',
                        'PostCode' => '',
                        'CountryCode' => 'AE',
                        'Longitude' => 0,
                        'Latitude' => 0,
                        'BuildingNumber' => null,
                        'BuildingName' => null,
                        'Floor' => null,
                        'Apartment' => null,
                        'POBox' => null,
                        'Description' => null,
                    ],
                    'PickupContact' => [
                        'Department' => 'Test Department',
                        'PersonName' => 'Test Person Name',
                        'Title' => null,
                        'CompanyName' => 'Test Company Name',
                        'PhoneNumber1' => '97148707700',
                        'PhoneNumber1Ext' => null,
                        'PhoneNumber2' => null,
                        'PhoneNumber2Ext' => null,
                        'FaxNumber' => null,
                        'CellPhone' => '97148707700',
                        'EmailAddress' => 'pickupemail@test.com',
                        'Type' => null,
                    ],
                    'PickupLocation' => 'Reception',
                    'PickupDate' => '/Date('.Carbon::now()->valueOf().')/',
                    'ReadyTime' => '/Date('.Carbon::now()->valueOf().')/',
                    'LastPickupTime' => '/Date('.Carbon::now()->addDays(2)->valueOf().')/',
                    'ClosingTime' => '/Date('.Carbon::now()->addDays(2)->valueOf().')/',
                    'Comments' => '',
                    'Reference1' => '001',
                    'Reference2' => '',
                    'Vehicle' => 'Car',
                    'Shipments' => null,
                    'PickupItems' => [
                        [
                            'ProductGroup' => ARAMEX_PRODUCT_GROUP,
                            'ProductType' => ARAMEX_PRODUCT_TYPE,
                            'NumberOfShipments' => 1,
                            'PackageType' => 'Box',
                            'Payment' => ARAMEX_PAYMENT_TYPE,
                            'ShipmentWeight' => [
                                'Unit' => 'KG',
                                'Value' => 0.5,
                            ],
                            'ShipmentVolume' => null,
                            'NumberOfPieces' => 1,
                            'CashAmount' => null,
                            'ExtraCharges' => null,
                            'ShipmentDimensions' => [
                                'Length' => 0,
                                'Width' => 0,
                                'Height' => 0,
                                'Unit' => '',
                            ],
                            'Comments' => 'Test',
                        ],
                    ],
                    'Status' => 'Ready',
                    'ExistingShipments' => null,
                    'Branch' => '',
                    'RouteCode' => '',
                ],
                'Transaction' => [
                    'Reference1' => '',
                    'Reference2' => '',
                    'Reference3' => '',
                    'Reference4' => '',
                    'Reference5' => '',
                ],
            ]);

            curl_setopt_array($this->curl, [
                CURLOPT_URL => $this->baseUri.'/Shipping/Service_1_0.svc/json/CreatePickup',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $body,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Accept: application/json',
                ],
            ]);

            $response = curl_exec($this->curl);

            return json_decode($response, true);
        } catch (Exception $e) {
            Log::error("Error while creating pickup, with message: {$e->getMessage()}");

            return response()->json(['error' => true, 'message' => __('Something went wrong!')], 500);
        }
    }

    public function trackPickup(string $reference)
    {
        try {
            $body = json_encode([
                'ClientInfo' => $this->clientInfo,
                'Reference' => $reference,
            ]);

            curl_setopt_array($this->curl, [
                CURLOPT_URL => $this->baseUri.'/Tracking/Service_1_0.svc/json/TrackPickup',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $body,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Accept: application/json',
                ],
            ]);

            $response = curl_exec($this->curl);

            return json_decode($response, true);
        } catch (Exception $e) {
            Log::error("Error while getting track pickup, with message: {$e->getMessage()}");

            return response()->json(['error' => true, 'message' => __('Something went wrong!')], 500);
        }
    }

    public function createShipments(array $input = [])
    {
        try {
            $body = json_encode(count($input) > 0 ? $input : [
                'Shipments' => [
                    [
                        'Reference1' => '',
                        'Reference2' => '',
                        'Reference3' => '',
                        'Shipper' => [
                            'Reference1' => '',
                            'Reference2' => '',
                            'AccountNumber' => env('ARAMEX_NUMBER'),
                            'PartyAddress' => [
                                'Line1' => 'MBZ City, Abu Dhabi, UAE',
                                'Line2' => 'Test Address Line 2',
                                'Line3' => '',
                                'City' => 'Abu Dhabi',
                                'StateOrProvinceCode' => 'Abu Dhabi',
                                'PostCode' => '',
                                'CountryCode' => 'AE',
                                'Longitude' => 0,
                                'Latitude' => 0,
                                'BuildingNumber' => null,
                                'BuildingName' => null,
                                'Floor' => null,
                                'Apartment' => null,
                                'POBox' => null,
                                'Description' => null,
                            ],
                            'Contact' => [
                                'Department' => 'Test Department',
                                'PersonName' => 'Foulem',
                                'Title' => null,
                                'CompanyName' => 'Foulem Company',
                                'PhoneNumber1' => '0097148707700',
                                'PhoneNumber1Ext' => null,
                                'PhoneNumber2' => null,
                                'PhoneNumber2Ext' => null,
                                'FaxNumber' => null,
                                'CellPhone' => '0097143187777',
                                'EmailAddress' => 'shipperemail@test.com',
                                'Type' => null,
                            ],
                        ],
                        'Consignee' => [
                            'Reference1' => null,
                            'Reference2' => null,
                            'AccountNumber' => null,
                            'PartyAddress' => [
                                'Line1' => 'Test Address',
                                'Line2' => 'Test Address Line 2',
                                'Line3' => '',
                                'City' => 'Dubai',
                                'StateOrProvinceCode' => 'Dubai',
                                'PostCode' => '',
                                'CountryCode' => 'AE',
                                'Longitude' => 0,
                                'Latitude' => 0,
                                'BuildingNumber' => null,
                                'BuildingName' => null,
                                'Floor' => null,
                                'Apartment' => null,
                                'POBox' => null,
                                'Description' => null,
                            ],
                            'Contact' => [
                                'Department' => 'Department',
                                'PersonName' => 'Receiver',
                                'Title' => null,
                                'CompanyName' => 'Receiver Name/ Company Name',
                                'PhoneNumber1' => '000556893100',
                                'PhoneNumber1Ext' => null,
                                'PhoneNumber2' => null,
                                'PhoneNumber2Ext' => null,
                                'FaxNumber' => null,
                                'CellPhone' => '000556893000',
                                'EmailAddress' => 'reciveremail@test.com',
                                'Type' => null,
                            ],
                        ],
                        'ThirdParty' => null,
                        'ShippingDateTime' => '/Date('.Carbon::now()->valueOf().')/',
                        'DueDate' => '/Date('.Carbon::now()->addDays(3)->valueOf().')/',
                        'Comments' => null,
                        'PickupLocation' => '5 street independence',
                        'OperationsInstructions' => null,
                        'AccountingInstructions' => null,
                        'Details' => [
                            'Dimensions' => null,
                            'ActualWeight' => [
                                'Unit' => 'KG',
                                'Value' => 5,
                            ],
                            'ChargeableWeight' => null,
                            'DescriptionOfGoods' => 'Shoes',
                            'GoodsOriginCountry' => 'AE',
                            'NumberOfPieces' => 1,
                            'ProductGroup' => ARAMEX_PRODUCT_GROUP,
                            'ProductType' => ARAMEX_PRODUCT_TYPE,
                            'PaymentType' => ARAMEX_PAYMENT_TYPE,
                            'PaymentOptions' => null,
                            'CustomsValueAmount' => ['CurrencyCode' => 'USD', 'Value' => 50],
                            'CashOnDeliveryAmount' => null,
                            'InsuranceAmount' => null,
                            'CashAdditionalAmount' => null,
                            'CashAdditionalAmountDescription' => null,
                            'CollectAmount' => null,
                            'Services' => null,
                            'Items' => null,
                            'DeliveryInstructions' => null,
                        ],
                        // 'Attachments' => null,
                        'ForeignHAWB' => null,
                        'TransportType' => 0,
                        'PickupGUID' => null,
                        'Number' => null,
                        'ScheduledDelivery' => null,
                    ],
                ],
                'LabelInfo' => [
                    'ReportID' => 9729,
                    'ReportType' => 'URL',
                ],
                'ClientInfo' => $this->clientInfo,
                'Transaction' => [
                    'Reference1' => '',
                    'Reference2' => '',
                    'Reference3' => '',
                    'Reference4' => '',
                    'Reference5' => '',
                ],
            ]);

            curl_setopt_array($this->curl, [
                CURLOPT_URL => $this->baseUri.'/Shipping/Service_1_0.svc/json/CreateShipments',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $body,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Accept: application/json',
                ],
            ]);

            $response = curl_exec($this->curl);

            return json_decode($response, true);
        } catch (Exception $e) {
            Log::error("Error while getting create shipments, with message: {$e->getMessage()}");

            return response()->json(['error' => true, 'message' => __('Something went wrong!')], 500);
        }
    }

    public function trackShipments(string $id)
    {
        try {
            $body = json_encode([
                'Shipments' => [
                    $id,
                ],
                'GetLastTrackingUpdateOnly' => true,
                'ClientInfo' => $this->clientInfo,
                'Transaction' => [
                    'Reference1' => null,
                    'Reference2' => null,
                    'Reference3' => null,
                    'Reference4' => null,
                    'Reference5' => null,
                ],
            ]);

            curl_setopt_array($this->curl, [
                CURLOPT_URL => $this->baseUri.'/Tracking/Service_1_0.svc/json/TrackShipments',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $body,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Accept: application/json',
                ],
            ]);

            $response = curl_exec($this->curl);

            return json_decode($response, true);
        } catch (Exception $e) {
            Log::error("Error while getting track shipments, with message: {$e->getMessage()}");

            return response()->json(['error' => true, 'message' => __('Something went wrong!')], 500);
        }
    }

    public function cancelPickup(string $guid)
    {
        try {
            $body = json_encode([
                'ClientInfo' => $this->clientInfo,
                'Comments' => '',
                'PickupGUID' => $guid,
            ]);

            curl_setopt_array($this->curl, [
                CURLOPT_URL => $this->baseUri.'/Shipping/Service_1_0.svc/json/CancelPickup',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $body,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Accept: application/json',
                ],
            ]);

            $response = curl_exec($this->curl);

            return json_decode($response, true);
        } catch (Exception $e) {
            Log::error("Error while getting cancel pickup, with message: {$e->getMessage()}");

            return response()->json(['error' => true, 'message' => __('Something went wrong!')], 500);
        }
    }

    public function printLabel(string $shipmentNumber)
    {
        try {
            $body = json_encode([
                'ClientInfo' => $this->clientInfo,
                'LabelInfo' => [
                    'ReportID' => 9729,
                    'ReportType' => 'URL',
                ],
                'OriginEntity' => 'DXB',
                'ProductGroup' => ARAMEX_PRODUCT_GROUP,
                'ShipmentNumber' => $shipmentNumber,
            ]);

            curl_setopt_array($this->curl, [
                CURLOPT_URL => $this->baseUri.'/Shipping/Service_1_0.svc/json/PrintLabel',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $body,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Accept: application/json',
                ],
            ]);

            $response = curl_exec($this->curl);

            return json_decode($response, true);
        } catch (Exception $e) {
            Log::error("Error while printing label, with message: {$e->getMessage()}");

            return response()->json(['error' => true, 'message' => __('Something went wrong!')], 500);
        }
    }

    public function carts(string $user_id)
    {
        try {
            return Cart::with(['user', 'product', 'address'])
                ->where('user_id', $user_id)
                ->get()
                ->toArray();
        } catch (Exception $e) {
            Log::error("Error while getting card, with message: {$e->getMessage()}");

            return response()->json(['error' => true, 'message' => __('Something went wrong!')], 500);
        }
    }

    public function transformShipmentData(array $input)
    {
        try {
            /* Actual Weight = Package Weight x Qty x margin (1.03) */
            $package_weight = $input['product']['package_weight'] ?? collect($input['attributes'])->sum('value');
            $actualWeightValue = (float) ($package_weight * $input['orderDetails']['quantity'] * WEIGHT_MARGIN);

            $dimensions = $input['product']['package_weight'] !== null ? [
                'Length' => $input['product']['length'],
                'Width' => $input['product']['width'],
                'Height' => $input['product']['height'],
                'Unit' => 'cm',
            ] : null;
            $actualWeightUnit = $input['product']['unit_weight'] !== null ??
                $input['attributes'][0]['id_units'] === KG_UNITY_ATTRIBUTE_ID ? 'KG' : 'LB';

            $currency_code = Currency::find(get_setting('system_default_currency'))->code;

            return [
                'OriginAddress' => [
                    // we get those info from the 1st warehouse
                    'Line1' => "{$input['warehouseDetails'][0]['name']}, {$input['warehouseDetails'][0]['street']}",
                    'Line2' => $input['warehouseDetails'][0]['building'],
                    'Line3' => $input['warehouseDetails'][0]['unit'],
                    'City' => $input['warehouseDetails'][0]['area']['city'],
                    'StateOrProvinceCode' => $input['warehouseDetails'][0]['area']['emirate'],
                    'PostCode' => null,
                    'CountryCode' => $input['warehouseDetails'][0]['area']['country'],
                    'BuildingNumber' => $input['warehouseDetails'][0]['unit'],
                    'BuildingName' => $input['warehouseDetails'][0]['building'],
                    'Floor' => null,
                    'Apartment' => null,
                    'POBox' => null,
                    'Description' => null,
                ],
                'DestinationAddress' => [
                    'Line1' => $input['shippingAddress']['address'] ?? 'line 1',
                    'Line2' => null,
                    'Line3' => null,
                    'City' => $input['shippingAddress']['city'] ?? null,
                    'StateOrProvinceCode' => null,
                    'PostCode' => $input['shippingAddress']['postal_code'] ?? null,
                    // shipments are available only in AE for now
                    'CountryCode' => 'AE',
                    'Longitude' => 0,
                    'Latitude' => 0,
                    'BuildingNumber' => null,
                    'BuildingName' => null,
                    'Floor' => null,
                    'Apartment' => null,
                    'POBox' => null,
                    'Description' => null,
                ],
                'ShipmentDetails' => [
                    'Dimensions' => $dimensions,
                    'ActualWeight' => [
                        'Unit' => $actualWeightUnit,
                        'Value' => $actualWeightValue,
                    ],
                    'ChargeableWeight' => null,
                    'DescriptionOfGoods' => null,
                    'GoodsOriginCountry' => null,
                    // @todo replace it from data
                    'NumberOfPieces' => 1,
                    'ProductGroup' => ARAMEX_PRODUCT_GROUP,
                    'ProductType' => ARAMEX_PRODUCT_TYPE,
                    'PaymentType' => ARAMEX_PAYMENT_TYPE,
                    'PaymentOptions' => null,
                    'CustomsValueAmount' => null,
                    'CashOnDeliveryAmount' => null,
                    'InsuranceAmount' => null,
                    'CashAdditionalAmount' => null,
                    'CashAdditionalAmountDescription' => null,
                    'CollectAmount' => null,
                    'Services' => '',
                    'Items' => null,
                    'DeliveryInstructions' => null,
                    'AdditionalProperties' => null,
                    'ContainsDangerousGoods' => false,
                    'ShippingDate' => null,
                    'DueDate' => null,
                ],
                'PreferredCurrencyCode' => $currency_code ?? 'AED',
                'ClientInfo' => $this->clientInfo,
                'Transaction' => null,
            ];
        } catch (Exception $e) {
            Log::error("Error while transforming shipment data, with message: {$e->getMessage()}");

            return response()->json(['error' => true, 'message' => __('Something went wrong!')], 500);
        }
    }

    public function calculateOrderProductsCharge($user_id)
    {
        try {
            // @todo add dimensions: length, width, height
            $weight_attribute_id = WEIGHT_ATTRIBUTE_ID;

            if (request()->has('product_id')) {
                $data = Cart::where('user_id', $user_id)
                    ->where('product_id', request()->product_id)
                    ->get()
                    ->map(function ($cart) use ($weight_attribute_id) {
                        $shippingAddress = AddressModel::with(['city'])
                            ->where('id', $cart->address_id)
                            ->get()
                            ->map(fn ($data) => [
                                'address' => $data->address,
                                'city' => $data->state->name,
                            ])->first();

                        $attributes = $cart->product->productAttributeValues
                            ->filter(fn ($value) => $value->id_attribute === $weight_attribute_id)
                            ->values()
                            ->toArray();

                        $shippingOptions = $cart->product
                            ->shippingRelation
                            ->toArray();

                        $warehouseDetails = $cart->product->stockDetails->map(
                            fn ($stock) => [
                                'name' => $stock->warehouse->warehouse_name,
                                'street' => $stock->warehouse->address_street,
                                'building' => $stock->warehouse->address_building,
                                'unit' => $stock->warehouse->address_unit,
                                'area' => [
                                    'city' => $stock->warehouse->area->name,
                                    'emirate' => $stock->warehouse->area->emirate->name,
                                    'country' => 'AE',
                                ],
                            ]
                        )->toArray();

                        $orderDetails = $cart->toArray();

                        return [
                            'product' => [
                                'id' => $cart->product->id,
                                'name' => $cart->product->name,
                                'package_weight' => $cart->product->weight,
                                'length' => $cart->product->length,
                                'width' => $cart->product->width,
                                'unit_weight' => $cart->product->unit_weight !== null && $cart->product->unit_weight === 'kilograms' ? 'KG' : null,
                                'height' => $cart->product->height,
                            ],
                            'orderDetails' => $orderDetails,
                            'attributes' => $attributes,
                            'shippingAddress' => $shippingAddress,
                            'shippingOptions' => $shippingOptions,
                            'warehouseDetails' => $warehouseDetails,
                        ];
                    })->first();

                return response()->json([
                    'error' => false,
                    'data' => $this->calculateRate($this->transformShipmentData($data)),
                ]);
            }

            return response()->json(['error' => true, 'message' => __('Something went wrong!')], 500);
        } catch (Exception $e) {
            Log::error("Error while calculating order products charge, with message: {$e->getMessage()}");

            return response()->json(['error' => true, 'message' => __('Something went wrong!')], 500);
        }
    }

    public function getEmirateCities($emirate)
    {
        try {
            $body = json_encode([
                'ClientInfo' => $this->clientInfo,
                'CountryCode' => 'AE',
                'NameStartsWith' => null,
                'State' => $emirate,
            ]);

            curl_setopt_array($this->curl, [
                CURLOPT_URL => $this->baseUri.'/Location/Service_1_0.svc/json/FetchCities',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $body,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Accept: application/json',
                ],
            ]);

            $response = curl_exec($this->curl);
            $rawData = json_decode($response, true);

            return $rawData['HasErrors'] === false ? $rawData['Cities'] : [];
        } catch (Exception $e) {
            Log::error("Error while getting emirate $emirate states, with message: {$e->getMessage()}");

            return response()->json(['error' => true, 'message' => __('Something went wrong!')], 500);
        }
    }

    public function getEmirateStates($emirate_id)
    {
        try {
            $data['data'] = State::where(
                'emirate_id', $emirate_id
            )->orderBy('name', 'ASC')->get(["id", "name"]);

            return response()->json($data);
        } catch (Exception $e) {
            Log::error("Error while getting emirate $emirate_id sates, with message {$e->getMessage()}");

            return response()->json(['error' => true, 'message' => __('Something went wrong!')], 500);
        }
    }

    public function __destruct()
    {
        curl_close($this->curl);
    }
}
