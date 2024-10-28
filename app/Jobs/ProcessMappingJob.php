<?php 

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessMappingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $allData;
    protected $userId;

    /**
     * Create a new job instance.
     *
     * @param array $allData
     */
    public function __construct(array $allData, int $userId)	
    {
        $this->allData = $allData;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        // Map headers to rows
        $mappedData = $this->mapHeadersToRowsFromData($this->allData);
        $groupedProducts = [];

        // Group data by SKU for parent-child relationship
        foreach ($mappedData as $row) {
            $sku = $row['SKU *'];  // Replace 'SKU *' with the actual header key for SKU
            $parentSku = $row['Parent SKU'] ?? null;

            if ($parentSku && isset($groupedProducts[$parentSku])) {
                // If a parent SKU exists, add as a child to the respective parent
                $groupedProducts[$parentSku]['children'][] = $row;
            } else {
                // Otherwise, store it as a new parent product
                $groupedProducts[$sku] = [
                    'parent' => $row,
                    'children' => []
                ];
            }
        }

        // Chunk the grouped products for processing
        $productsChunks = array_chunk($groupedProducts, 100); // Adjust chunk size as needed

        // Dispatch each chunk as a separate job
        foreach ($productsChunks as $productsChunk) {
            foreach ($productsChunk as $product) {
                // Dispatch a new job for each product
                ProcessProductsJob::dispatch($product, $this->userId);
            }
        }
    }



    private function mapHeadersToRowsFromData($data)
{
    // Extract headers from row 2 (index 1 in zero-based index)
    $headers = array_filter($data[1], function($header) {
        return !is_null($header) && $header !== ''; // Keep only non-null and non-empty headers
    });

    // Modify headers to replace names
    $headers = $this->replaceDuplicateHeaders($headers);

    // Initialize an array to store the mapped data
    $mappedData = [];

    // Iterate through each row starting from data[2]
    for ($rowIndex = 3; $rowIndex < count($data); $rowIndex++) {
        $row = $data[$rowIndex];

        // Check if the row is not empty (contains at least one non-empty value)
        if ($this->rowHasData($row)) {
            // Create an associative array for the row
            $rowData = [];
            foreach ($headers as $columnIndex => $header) {
                // Map each header to its corresponding value in the row
                $value = $row[$columnIndex] ?? null; // Get the value or null if not present

                // Only include the value if it's not null
                if (!is_null($value)) {
                    $rowData[$header] = $value;
                }
            }

            // Only add the rowData to the final array if it contains any data
            if (!empty($rowData)) {
                $mappedData[] = $rowData;
            }
        }
    }

    return $mappedData;
}



    /**
 * Replace duplicate headers with numbered suffixes.
 */
private function replaceDuplicateHeaders(array $headers)
{
    // Array to keep track of header counts
    $headerCount = [];

    // Iterate through the headers to modify them
    foreach ($headers as $index => $header) {
        // Increment the count for the current header
        if (!isset($headerCount[$header])) {
            $headerCount[$header] = 1; // Start counting from 1
        } else {
            $headerCount[$header]++; // Increment the count
        }

        // If it's not the first occurrence, append the count to the header
        if ($headerCount[$header] > 1) {
            $headers[$index] = $header . ' ' . $headerCount[$header]; // Add the count to the header
        }
    }

    return $headers;
}

/**
 * Check if the row has any non-empty cells.
 */
private function rowHasData($row)
{
    foreach ($row as $cell) {
        if (!empty($cell)) { // Check if the cell is not empty
            return true;
        }
    }
    return false; // Return false if all cells are empty
}



}
