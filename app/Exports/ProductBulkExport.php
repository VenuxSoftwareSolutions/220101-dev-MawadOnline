<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductBulkExport implements WithHeadings, ShouldAutoSize, WithEvents
{
    /**
     * @return array
     */
    public function headings(): array
    {
        $product_information = [
            'Product type (leaf cat)',
            'Product Name *',
            'Brand *',
            'Unit of Sale',
            'Country of origin',
            'Manufacturer *',
            'Tags *',
            'Short Description *',
            'Long description',
            'Show Stock Quantity',
            'Refundable',
        ];

        $product_media = [
            'GI 1', 'GI 2', 'GI 3', 'GI 4', 'GI 5',
            'GI 6', 'GI 7', 'GI 8', 'GI 9', 'GI 10',
            'Video provider', 'Video Link',
        ];


        $product_documentation = [
            'Doc 1', 'Doc 2', 'Doc 3', 'Doc 4', 'Doc 5',
            'Doc 6', 'Doc 7', 'Doc 8', 'Doc 9', 'Doc 10',
        ];
        return [
            $product_information,
            $product_media,
            $product_documentation,

            'SKU', 'color', 'Height', 'length', 'width',
            'weight', 'Grade',
            'Parent SKU', 'variation theme',
            'From qty', 'to qty', 'unit price', 'discount (start/end)', 'Discount type', 'Discount Amount',
            'Discount Percentage',
            'From qty', 'to qty', 'unit price', 'discount (start/end)', 'Discount type', 'Discount Amount',
            'Discount Percentage',
            'From qty', 'to qty', 'unit price', 'discount (start/end)', 'Discount type', 'Discount Amount',
            'Discount Percentage',

            'Sample description', 'Sample price',
            'Length', 'width', 'Height', 'package weight', 'breakable', 'temp min', 'temp max',

            'From qty', 'to qty', 'shipper', 'order prep', 'order shipping', 'paid by', 'shipping charge', 'flat rate amount',
            'charge per unit of sale',
            'From qty', 'to qty', 'shipper', 'order prep', 'order shipping', 'paid by', 'shipping charge', 'flat rate amount',
            'charge per unit of sale',
            'From qty', 'to qty', 'shipper', 'order prep', 'order shipping', 'paid by', 'shipping charge', 'flat rate amount',
            'charge per unit of sale',

            'Length', 'width', 'Height', 'package weight', 'breakable', 'temp min', 'temp max',

            'shipper', 'order prep', 'order shipping', 'paid by', 'shipping charge',

            'Warehouse', 'quantity', 'Comment',

            'meta title', 'description'
        ];
    }


    /**
     * Register the events.
     *
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Shift data down by 2 rows
                $sheet->insertNewRowBefore(1, 2);

                // Apply the headings
                $sheet->fromArray($this->headings(), null, 'A3');

                // Optionally, apply some styling to the headings
                $sheet->getStyle('A3:ZZ3')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['argb' => 'FFFFE699'],
                    ],
                ]);
            },
        ];
    }
}
