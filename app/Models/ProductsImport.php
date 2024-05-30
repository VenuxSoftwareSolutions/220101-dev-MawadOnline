<?php

namespace App\Models;

use App\Models\Product;
use App\Models\ProductStock;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;
use Auth;
use Carbon\Carbon;
use Storage;

//class ProductsImport implements ToModel, WithHeadingRow, WithValidation
class ProductsImport implements ToCollection, WithHeadingRow, WithValidation, ToModel
{
    private $rows = 0;
    private $products = [];
    private $keys = [];

    const SECTION_KEYS = [
        'Product Information' => [
            'Product type',
            'Product Name *',
            'Brand *',
            'Unit of Sale',
            'Country of origin',
            'Manufacturer *',
            'Tags *',
            'Short Description *',
            'Long description',
            'Show Stock Quantity',
            'Refundable'
        ],
        'Product Media' => [
            'GI 1',
            'GI 2',
            'GI 3',
            'GI 4',
            'GI 5',
            'GI 6',
            'GI 7',
            'GI 8',
            'GI 9',
            'GI 10',
            'Video provider',
            'video Link'
        ],
        'Product documentation' => [
            'Doc 1',
            'Doc 2',
            'Doc 3',
            'Doc 4',
            'Doc 5',
            'Doc 6',
            'Doc 7',
            'Doc 8',
            'Doc 9',
            'Doc 10', 
        ],
        'Product specification' => [
            'SKU',
            'Att 1',
            'Att 2',
            'Att 3',
            'Att 4',
            'Att 5',
            'Att 6',
        ],
        'Product selling option' => [
            'Grouping',
            'Parent SKU',
            'variation theme',
        ],
        'Default Pricing Configuration' => [
            'From qty',
            'to qty',
            'unit price',
            'discount (start/end)',
            'Discount type',
            'Discount Amount',
            'Discount Percentage',
        ],
    ];

    const SECTION_RANGES = [
        'Product Information' => ['start' => 'A', 'end' => 'K'],
        'Product Media' => ['start' => 'L', 'end' => 'W'],
        'Product documentation' => ['start' => 'X', 'end' => 'AG'],
        'Product specification' => ['start' => 'AH', 'end' => 'AN'],
        'Product selling option' => ['start' => 'AO', 'end' => 'AQ'],
        'Default Pricing Configuration' => ['start' => 'AR', 'end' => 'AX'],

        // Add ranges for other sections as needed
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
                        $product[$section][$subKey] = $row[$i] ?? null;
                    }
                }

                $this->products[] = $product;
            }
            dd($this->products);

            flash(translate('Products imported successfully'))->success();
        }
    }


    private function initializeProductStructure()
    {
        $product = [];
        foreach (self::SECTION_KEYS as $section => $subKeys) {
            $product[$section] = array_fill_keys($subKeys, null);
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
    public function model(array $row)
    {
        ++$this->rows;
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }

    public function rules(): array
    {
        return [
            // Can also use callback validation rules
            'unit_price' => function ($attribute, $value, $onFailure) {
                if (!is_numeric($value)) {
                    $onFailure('Unit price is not numeric');
                }
            }
        ];
    }

    public function downloadThumbnail($url)
    {
        try {
            $upload = new Upload;
            $upload->external_link = $url;
            $upload->type = 'image';
            $upload->save();

            return $upload->id;
        } catch (\Exception $e) {
        }
        return null;
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
