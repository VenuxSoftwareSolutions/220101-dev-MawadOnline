<?php

namespace App\Imports;

use App\Models\Product;
use App\Services\ProductService;
use App\Services\ProductTaxService;
use App\Services\ProductFlashDealService;
use App\Services\ProductStockService;
use App\Services\ProductUploadsService;
use App\Services\ProductPricingService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProductsBulkImport implements ToCollection, WithStartRow, WithMultipleSheets
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

    /**
     * Specify the sheets to import.
     *
     * @return array
     */
    public function sheets(): array
    {
        return [
            1 => $this, // Indexing starts at 0, so 1 represents the second sheet
        ];
    }

    /**
     * Specify the starting row for the import.
     *
     * @return int
     */
    public function startRow(): int
    {
        return 4; // Start processing from row 4
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if ($this->rowHasData($row)) {
                // Limit the row to the first 115 columns (A to DK)
                $row = $row->slice(0, 115);

                $product = [
                    'Product Information' => [
                        'Product Type' => $row[0],
                        'Product Name' => $row[1],
                        'Brand' => $row[2],
                        'Unit of Sale' => $row[3],
                        'Country of origin' => $row[4],
                        'Manufacturer' => $row[5],
                        'Tags' => $row[6],
                        'Short Description' => $row[7],
                        'Long Description' => $row[8],
                        'Show Stock Quantity' => $row[9],
                        'Refundable' => $row[10],
                        // Additional columns can be added here...
                    ],
                    'Product Media' => [
                        'Base Folder'=> $row[11],
                        'Gallery Image 1'=> $row[12],
                        'Gallery Image 2'=> $row[13],
                        'Gallery Image 3'=> $row[14],
                        'Gallery Image 4'=> $row[15],
                        'Gallery Image 5'=> $row[16],
                        'Gallery Image 6'=> $row[17],
                        'Gallery Image 7'=> $row[18],
                        'Gallery Image 8'=> $row[19],
                        'Gallery Image 9'=> $row[20],
                        'Gallery Image 10'=> $row[21],

                        'Video Provider' => $row[22],
                        'video Link' => $row[23],
                        // Additional columns can be added here...
                    ],

                    'Product Documentation' => [
                        'Base Folder Document' => $row[24],
                        'Document 1' => $row[25],
                        'Document 2' => $row[26],
                        'Document 3' => $row[27],
                        'Document 4' => $row[28],
                        'Document 5' => $row[29],
                        'Document 6' => $row[30],
                        'Document 7' => $row[31],
                        'Document 8' => $row[32],
                        'Document 9' => $row[33],
                        'Document 10' => $row[34],

                        // Additional columns can be added here...
                    ],

                    'Product Specification' => [
                        'SKU' => $row[35],
                        'Type' => $row[36],
                        'Concrete Grade' => $row[37],
                        'Energy Saving' => $row[38],
                        'Product Shape 2' => $row[39],
                        'Material' => $row[40],
                        'Weight' => $row[41],
                        'Manufacturer' => $row[42],
                        'Color' => $row[43],
                        'Use' => $row[44],
                        'Dimensions' => $row[45],
                        'Compressive Strength' => $row[46],
                        'Suitable Content' => $row[47],

                        // Additional columns can be added here...
                    ],


                    'Product Selling Options' => [
                        'Parent SKU' => $row[48],
                        'Variation Theme' => $row[49],
                        // Additional columns can be added here...
                    ],


                    'Pricing Configuration' => [
                        [
                            'From Quantity' => $row[50],
                            'To Quantity' => $row[51],
                            'Unit Price' => $row[52],
                            'Discount Start Date' => $row[53],
                            'Discount End Date' => $row[54],
                            'Discount Type' => $row[55],
                            'Discount Amount' => $row[56],
                            'Discount Percentage' => $row[57],
                        ],
                        [
                            'From Quantity' => $row[59],
                            'To Quantity' => $row[60],
                            'Unit Price' => $row[61],
                            'Discount Start Date' => $row[62],
                            'Discount End Date' => $row[63],
                            'Discount Type' => $row[64],
                            'Discount Amount' => $row[65],
                            'Discount Percentage' => $row[66],
                        ],
                        [
                            'From Quantity' => $row[68],
                            'To Quantity' => $row[69],
                            'Unit Price' => $row[70],
                            'Discount Start Date' => $row[71],
                            'Discount End Date' => $row[72],
                            'Discount Type' => $row[73],
                            'Discount Amount' => $row[74],
                            'Discount Percentage' => $row[75],
                        ]

                        // Additional columns can be added here...
                    ],


                    'Sample Pricing Configuration' => [
                        'Sample Available' => $row[76],
                        'Sample Description' => $row[77],
                        'Sample Price' => $row[78],

                        // Additional columns can be added here...
                    ],


                    'Product Package Dimension' => [
                        'Length' => $row[79],
                        'Width' => $row[80],
                        'Height' => $row[81],
                        'Weight' => $row[82],
                        'Breakable' => $row[83],
                        'Min Temperature' => $row[84],
                        'Max Temperature' => $row[85],

                        // Additional columns can be added here...
                    ],


                    'Product Shipping' => [
                        'From Quantity' => $row[87],
                        'To Quantity' => $row[88],
                        'Shipper' => $row[89],
                        'Order Preparation Days' => $row[90],
                        'Shipping Days' => $row[91],
                        'Paid By' => $row[92],
                        'Shipping Charge By' => $row[93],
                        'Flat Rate Amount' => $row[94],
                        'Charge per Limit Of Sale' => $row[95],

                        // Additional columns can be added here...
                    ],


                    'Sample Package Dimension' => [
                        'Length' => $row[97],
                        'Width' => $row[98],
                        'Height' => $row[99],
                        'Weight' => $row[100],
                        'Breakable' => $row[101],
                        'Min Temperature' => $row[102],
                        'Max Temperature' => $row[103],

                        // Additional columns can be added here...
                    ],


                    'Sample Shipper' => [
                        'Shipper' => $row[105],
                        'Order Preparation Days' => $row[106],
                        'Shipping Days' => $row[107],
                        'Paid By' => $row[108],
                        'Shipping Charge Amount' => $row[109],

                        // Additional columns can be added here...
                    ],

                    'Inventory Stock' => [
                        'Warehouse' => $row[110],
                        'Quantity' => $row[111],
                        'Comment' => $row[112],
                        
                        // Additional columns can be added here...
                    ],

                    'Seo Meta Tags' => [
                        'Meta Title' => $row[113],
                        'Description' => $row[114],
                        // Additional columns can be added here...
                    ],
                    
                ];

                $mappedProduct = $this->productMapping($product);

               // dd($mappedProduct);
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

                flash(translate('Products imported successfully'))->success();

            }
        }
    }

    private function rowHasData($row)
    {
        // Check if the row has any non-empty cells
        foreach ($row as $cell) {
            if (!empty($cell)) {
                return true;
            }
        }
        return false;
    }

    private function productMapping(array $product)
    {
        Request::merge([
            'name'=> $product['Product Information']['Product Name'],
            'published_modal'=> 0,
            'create_stock'=> 0,
            'brand_id'=> $this->idExtracting($product['Product Information'],'Brand'),
            'unit'=> $product['Product Information']['Unit of Sale'],
            'country_code'=> $this->idExtracting($product['Product Information'],'Country of origin'),
            'manufacturer'=> $product['Product Information']['Manufacturer'],
            'tags'=>  [json_encode($this->tagsHandling($product))],
            'short_description'=> $product['Product Information']['Short Description'],
            'stock_visibility_state'=> $product['Product Information']['Show Stock Quantity'],
            'refundable'=> $product['Product Information']['Refundable'],
            'video_provider'=> $product['Product Media']['Video Provider'],
            'video_link'=> $product['Product Media']['video Link'],
            'from'=> 
                $this->extractData($product['Pricing Configuration'],'From Quantity'),
            'to'=>              
                $this->extractData($product['Pricing Configuration'],'To Quantity'),
            'unit_price'=>
                $this->extractData($product['Pricing Configuration'],'Unit Price'),
            'date_range_pricing'=> [
                '20-07-2024 00:00:00 to 22-08-2024 23:59:00',
                '20-07-2024 00:00:00 to 22-08-2024 23:59:00',
                '20-07-2024 00:00:00 to 22-08-2024 23:59:00'
            ],
               // $this->extractData($product['Pricing Configuration'],'Discount Start Date'),,
            'discount_type'=> 
                $this->extractData($product['Pricing Configuration'],'Discount Type'),
            'discount_amount'=> 
                $this->extractData($product['Pricing Configuration'],'Discount Amount'),
            'discount_percentage'=>        
                $this->extractData($product['Pricing Configuration'],'Discount Percentage'),

            'sample_description'=> $product['Sample Pricing Configuration']['Sample Description'],
            'sample_price'=> $product['Sample Pricing Configuration']['Sample Price'],
            'length'=> $product['Product Package Dimension']['Length'],
            'width'=> $product['Product Package Dimension']['Width'],
            'height'=> $product['Product Package Dimension']['Height'],
            'weight'=> $product['Product Package Dimension']['Weight'],
            'breakable'=>$product['Product Package Dimension']['Breakable'],
            "unit_weight" => "kilograms",
            'min_third_party'=> $product['Product Package Dimension']['Min Temperature'],
            'max_third_party'=> $product['Product Package Dimension']['Max Temperature'],
            'from_shipping'=> [
                $product['Product Shipping']['From Quantity'],
            ],
            'to_shipping'=> [
                $product['Product Shipping']['To Quantity'],
            ],

            'shipper'=>[
                [
                    $product['Product Shipping']['Shipper']
                ],
            ],

            'estimated_order'=>[
                $product['Product Shipping']['Order Preparation Days']
            ],
            'estimated_shipping'=> [
                $product['Product Shipping']['Shipping Days']
            ],
            'paid'=>[
                $product['Product Shipping']['Paid By']
            ],
            'shipping_charge'=> [
                $product['Product Shipping']['Shipping Charge By']

            ],
            'flat_rate_shipping'=> [
                $product['Product Shipping']['Flat Rate Amount']
            ],
            'charge_per_unit_shipping'=> [
                $product['Product Shipping']['Charge per Limit Of Sale']
            ],
            'length_sample'=> $product['Sample Package Dimension']['Length'],
            'width_sample'=> $product['Sample Package Dimension']['Width'],
            'height_sample'=> $product['Sample Package Dimension']['Height'],
            'package_weight_sample'=> $product['Sample Package Dimension']['Weight'],
            'breakable_sample' => $product['Sample Package Dimension']['Breakable'],
            'min_third_party_sample'=> $product['Sample Package Dimension']['Min Temperature'],
            'max_third_party_sample'=>$product['Sample Package Dimension']['Max Temperature'],
            'parent_id'=> $this->idExtracting($product['Product Information'],'Product Type'),
            'product_sk'=> $product['Product Specification']['SKU'],
            'quantite_stock_warning'=> $product['Inventory Stock']['Quantity'],
            'description'=> $product['Product Information']['Long Description'],
            'meta_title'=> $product['Seo Meta Tags']['Meta Title'],
            'meta_description'=> $product['Seo Meta Tags']['Description'],
            'button'=> 'draft',
            'submit_button'=> null,
        ]);

        return Request::except('bulk_file');
    }



    function extractData($pricingConfigurations, $key) {
        $data = [];
        
        foreach ($pricingConfigurations as $config) {
            if (isset($config[$key])) {
                $value = $config[$key];
                if ($key === 'Discount Type') {
                    if ($value === 'Percentage') {
                        $value = 'percent';
                    } elseif ($value === 'Flat') {
                        $value = 'amount';
                    }
                }
                $data[] = $value;
            }
        }
        
        return $data;
    }


    private function idExtracting($product, $key)
    {
        $value_string = $product[$key];
        $value_parts = explode('-', $value_string);
        $extracted_id = (int) $value_parts[0];
        return $extracted_id;
    }

    private function tagsHandling($product)
    {
        $product_tags = $product['Product Information']['Tags']; // Example input for tags
        $tags_array = explode(', ', $product_tags);
        $formatted_tags = array_map(function ($tag) {
            return ['value' => trim($tag)];
        }, $tags_array);
        return $formatted_tags;
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
        $data = [];
        foreach (explode(',', str_replace(' ', '', $urls)) as $url) {
            $data[] = $this->downloadThumbnail($url);
        }
        return implode(',', $data);
    }
}
