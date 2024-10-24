<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BuildingMaterialImport;
use Illuminate\Support\Facades\Storage;

class ValidateBulkUploadFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;

    /**
     * Create a new job instance.
     *
     * @param $filePath
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
         // Load the file from storage
        $file = Storage::path('storage/'.$this->filePath);

        $errors = [];
        $data = Excel::toArray([], $file)[1]; // Assuming you want to use the second sheet


         // Get the headers from the second row (row index 1)
        $headers = $data[1];

        // Map the headers to their corresponding column letters or index numbers
        $headerMapping = $this->getHeaderMapping($headers);

        dd($headerMapping);
        $columnMapping = [
            'A' => 0, 'B' => 1, 'C' => 2, 'D' => 3, 'E' => 4, 'F' => 5, 'G' => 6, 'H' => 7, 
            'J' => 8, 'K' => 9, 'L' => 10, 'M' => 11, 'AJ' => 35, 'AY' => 50, 'AZ' => 51, 
            'BA' => 52, 'BY' => 76, 'CB' => 79, 'CC' => 80, 'CD' => 81, 'CE' => 82, 
            'CF' => 83, 'CG' => 84, 'CH' => 85, 'CJ' => 87, 'CK' => 88, 'CL' => 89, 'CM' => 90
        ];

        // Start iterating from the 4th row (index 3 in zero-based index)
        for ($rowIndex = 3; $rowIndex < count($data); $rowIndex++) {
            $row = $data[$rowIndex];
            if ($this->rowHasData($row)) {
                foreach ($columnMapping as $columnLetter => $columnIndex) {
                    $this->validateCell($errors, $rowIndex, $columnLetter, $row, ['required', 'max:255'], $columnIndex);
                }
            }
        }

        // Process validation errors (you can log them, save them, or return them)
        if (!empty($errors)) {
            dd($errors);
            // Handle errors, such as logging them, notifying the user, etc.
        }
    }

    private function rowHasData($row)
    {
        foreach ($row as $cell) {
            if (!empty($cell)) {
                return true;
            }
        }
        return false;
    }


    /**
     * Get the mapping of headers with their corresponding column letters.
     *
     * @param array $headers
     * @return array
     */
    private function getHeaderMapping($headers)
    {
        $headerMapping = [];
        foreach ($headers as $columnIndex => $headerName) {
            $columnLetter = $this->getColumnLetter($columnIndex);
            $headerMapping[$columnLetter] = $headerName;
        }

        return $headerMapping;
    }

    /**
     * Convert a column index to a column letter (e.g., 0 -> A, 26 -> AA).
     *
     * @param int $index
     * @return string
     */
    private function getColumnLetter($index)
    {
        $letters = '';
        while ($index >= 0) {
            $letters = chr($index % 26 + 65) . $letters;
            $index = floor($index / 26) - 1;
        }
        return $letters;
    }


    private function validateCell(&$errors, $rowIndex, $columnLetter, $row, $rules, $columnIndex)
    {
        $value = $row[$columnIndex] ?? null;

        $validator = \Validator::make(
            [$columnLetter => $value],
            [$columnLetter => $rules]
        );

        if ($validator->fails()) {
            $errors[$rowIndex][$columnLetter] = $validator->errors()->first($columnLetter);
        }
    }
}
