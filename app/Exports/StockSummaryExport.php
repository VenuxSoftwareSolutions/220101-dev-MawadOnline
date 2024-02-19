<?php

namespace App\Exports;

use App\StockSummary;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StockSummaryExport implements FromCollection, WithHeadings
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Execute the query and fetch the stock data
        $stocks = $this->query;

        // Format the data for export
        $formattedData = $stocks->map(function ($stock) {
            return [
                'Product/Variant' => $stock->product_variant_name, // Update with the actual field name
                'SKU' => $stock->sku, // Update with the actual field name
                'Warehouse' => $stock->warehouse_name,
                'Quantity' => $stock->current_total_quantity,
                'Last Update Date/Time' => $stock->updated_at,
            ];
        });

        return $formattedData;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Define the column headings
        return [
            'Product/Variant',
            'SKU',
            'Warehouse',
            'Quantity',
            'Last Update Date/Time',
        ];
    }
}
