<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Products;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Services\ProductFlashDealService;
use App\Services\ProductPricingService;
use App\Services\ProductService;
use App\Services\ProductStockService;
use App\Services\ProductTaxService;
use App\Services\ProductUploadsService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Request;
class ProductsBulkImport implements ToCollection, WithHeadingRow, WithValidation
{
    protected $productService;
    protected $productTaxService;
    protected $productFlashDealService;
    protected $productStockService;
    protected $productUploadsService;
    protected $productPricingService;

    public function __construct(
        ProductService $productService,
        ProductTaxService $productTaxService,
        ProductFlashDealService $productFlashDealService,
        ProductStockService $productStockService,
        ProductUploadsService $productUploadsService,
        ProductPricingService $productPricingService
    ) {
        $this->productService = $productService;
        $this->productTaxService = $productTaxService;
        $this->productFlashDealService = $productFlashDealService;
        $this->productStockService = $productStockService;
        $this->productUploadsService = $productUploadsService;
        $this->productPricingService = $productPricingService;
    }

    private $rows = 0;
    private $products = [];

    const SECTION_KEYS = [
        'Product Information' => [
            'Product type', 'Product Name', 'Brand', 'Unit of Sale', 'Country of origin', 
            'Manufacturer', 'Tags', 'Short Description', 'Long description', 
            'Show Stock Quantity', 'Refundable'
        ],
        'Product Media' => [
            'GI 1', 'GI 2', 'GI 3', 'GI 4', 'GI 5', 'GI 6', 'GI 7', 'GI 8', 'GI 9', 
            'GI 10','G11', 'Video Provider', 'video Link'
        ],
        'Product documentation' => [
            'Doc 1', 'Doc 2', 'Doc 3', 'Doc 4', 'Doc 5', 'Doc 6', 'Doc 7', 'Doc 8', 'Doc 9', 'Doc 10'
        ],
        'Product specification' => [
            'SKU', 'Att 1', 'Att 2', 'Att 3', 'Att 4', 'Att 5', 'Att 6'
        ],
        'Product selling option' => [
            'Grouping', 'Parent SKU', 'variation theme'
        ],
        'Default Pricing Configuration' => [
            'From qty', 'to qty', 'unit price', 'discount (start/end)', 
            'Discount type', 'Discount Amount', 'Discount Percentage'
        ],
    ];

    const SECTION_RANGES = [
        'Product Information' => ['start' => 'A', 'end' => 'K'],
        'Product Media' => ['start' => 'L', 'end' => 'X'],
        'Product documentation' => ['start' => 'X', 'end' => 'AG'],
        'Product specification' => ['start' => 'AH', 'end' => 'AN'],
        'Product selling option' => ['start' => 'AO', 'end' => 'AQ'],
        'Default Pricing Configuration' => ['start' => 'AQ', 'end' => 'AW'],
    ];

    public function collection(Collection $rows)
    {
        $canImport = true;
        $user = Auth::user();

        if ($canImport) {
            foreach ($rows as $index => $row) {
                if ($index === 0 || $index === 1) {
                    // Skip the first row which contains headers
                    continue;
                }

                $product = $this->initializeProductStructure();
                foreach (self::SECTION_RANGES as $section => $range) {
                    $startCol = $this->columnLetterToIndex($range['start']);
                    $endCol = $this->columnLetterToIndex($range['end']);

                    $subKeys = self::SECTION_KEYS[$section];

                    for ($i = $startCol, $subKeyIndex = 0; $i <= $endCol && $subKeyIndex < count($subKeys); $i++, $subKeyIndex++) {
                        $subKey = $subKeys[$subKeyIndex];
                        $product[$section][$subKey] = $row[$i] ?? "";
                    }
                }

                $checkEmptyData = $this->checkEmptyData($product);
                if($checkEmptyData)
                {
                    //$this->products[] = $product;

                    $mappedProduct = $this->productMapping($product);

                    dd($mappedProduct);
                    $product=  $this->productService->store($mappedProduct);
                   
                    if($product->is_parent == 1){
                        $products = Product::where('parent_id', $product->id)->get();
                        if(count($products) > 0){
                            foreach($products as $child){
                                $child->categories()->attach($mappedProduct["parent_id"]);
                            }
                        }
                    }
                    $product->categories()->attach($mappedProduct["parent_id"]);
            
                }
                

                // Store product using product service
                

               
            }

            // Flash success message
            flash(translate('Products imported successfully'))->success();
        }
    }


    private function productMapping(array $product)
    {
        Request::merge([
            'name'=> $product['Product Information']['Product Name'],
            'published_modal'=> 0,
            'create_stock'=> 0,
            'brand_id'=> $this->idExtracting($product,'Brand'),
            'unit'=> $product['Product Information']['Unit of Sale'],
            'country_code'=> $this->idExtracting($product,'Country of origin'),
            'manufacturer'=> $product['Product Information']['Manufacturer'],
            'tags'=>  [json_encode($this->tagsHandling($product))],
            'short_description'=> $product['Product Information']['Short Description'],
            'stock_visibility_state'=> $product['Product Information']['Show Stock Quantity'],
            'refundable'=> $product['Product Information']['Refundable'],
            'video_provider'=> $product['Product Media']['Video Provider'],
            'video_link'=> $product['Product Media']['video Link'],
            'from'=> [
                []
            ],
            'to'=>[
                []
            ],
            'unit_price'=> [
                []
            ],
            'date_range_pricing'=> [
                []
            ],
            'discount_type'=> [
                []
            ],
            'discount_amount'=> [
                []
            ],
            'discount_percentage'=> [
                []
            ],
            'sample_description'=> null,
            'sample_price'=> null,
            'length'=> null,
            'width'=> null,
            'height'=> null,
            'weight'=> null,
            'min_third_party'=> null,
            'max_third_party'=> null,
            'from_shipping'=> [
                []
            ],
            'to_shipping'=> [
                []
            ],
            'estimated_order'=>[
                []
            ],
            'estimated_shipping'=> [
                []
            ],
            'paid'=>[
                []
            ],
            'shipping_charge'=> [
                []
            ],
            'flat_rate_shipping'=> [
                []
            ],
            'charge_per_unit_shipping'=> [
                []
            ],
            'length_sample'=> null,
            'width_sample'=> null,
            'height_sample'=> null,
            'package_weight_sample'=> null,
            'min_third_party_sample'=> null,
            'max_third_party_sample'=> null,
            'parent_id'=> null,
            'product_sk'=> null,
            'quantite_stock_warning'=> null,
            'description'=> null,
            'meta_title'=> null,
            'meta_description'=> null,
            'button'=> 'draft',
            'submit_button'=> null,
        ]);


        return Request::except('bulk_file');
    }

    private function idExtracting($product,$key)
    {
        $value_string = $product['Product Information'][$key];

        // Split the comma-separated string into an array
        $value_parts = explode('-', $value_string);

        // Transform the tags array into the desired format
        $extracted_id = (int) $value_parts[0];


        return $extracted_id;
    }

    private function tagsHandling($product)
    {
        $product_tags = $product['Product Information']['Tags']; // Example input for tags

        // Split the comma-separated string into an array
        $tags_array = explode(', ', $product_tags);

        // Transform the tags array into the desired format
        $formatted_tags = array_map(function($tag) {
            return ['value' => trim($tag)];
        }, $tags_array);

        return $formatted_tags;
    }
    private function checkEmptyData(array $product): bool
    {
        if(
            $product['Product Information']['Product Name'] !== "" &&
            $product['Product Information']['Brand'] != ""         &&
             $product['Product Information']['Brand'] != ""       &&
             $product['Product Information']['Manufacturer'] != "")
        {
            return true;
        }else{
            return false;
        }
    }

    private function initializeProductStructure()
    {
        $product = [];
        foreach (self::SECTION_KEYS as $section => $subKeys) {
            $product[$section] = array_fill_keys($subKeys, "");
        }
        return $product;
    }

    private function columnLetterToIndex($column)
    {
        $column = strtoupper($column);
        $length = strlen($column);
        $index = 0;

        for ($i = 0; $i < $length; $i++) {
            $index = $index * 26 + ord($column[$i]) - ord('A') + 1;
        }

        return $index - 1; // Convert to 0-based index
    }

    public function rules(): array
    {
        return [
            'unit_price' => function ($attribute, $value, $onFailure) {
                if (!is_numeric($value)) {
                    $onFailure('Unit price is not numeric');
                }
            },
        ];
    }

    public function downloadThumbnail($url)
    {
        try {
            $upload = new Upload();
            $upload->external_link = $url;
            $upload->type = 'image';
            $upload->save();

            return $upload->id;
        } catch (\Exception $e) {
            \Log::error('Error downloading thumbnail: ' . $url . ' - ' . $e->getMessage());
        }
        return "";
    }

    public function downloadGalleryImages($urls)
    {
        $data = array();
        foreach (explode(',', str_replace(' ', '', $urls)) as $url) {
            $data[] = $this->downloadThumbnail($url);
        }
        return implode(',', $data);
    }
}

