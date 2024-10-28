<?php

namespace App\Jobs;

use App\Exports\ErrorsExport;
use App\Mail\ValidationReportMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Mail;

class ValidateBulkUploadFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;

    protected $fileModelId; // 5 minutes

    public function __construct($filePath, $fileModelId)
    {
        $this->filePath = $filePath;
        $this->fileModelId = $fileModelId;
    }

    public function handle()
    {
        $fileModel = \App\Models\BulkUploadFile::find($this->fileModelId);
        if (!$fileModel) {
            throw new \Exception("File model not found: $this->fileModelId");
            }
            
        $fileModel->status = 'processing';
        $fileModel->save();
        

        // Load the file from storage
        $file = Storage::path('storage/'.$this->filePath);
        if (!file_exists($file)) {
            throw new \Exception("File does not exist: $file");
        }

        // Extract data from the file
        $data = Excel::toArray([], $file)[1]; // Assuming you're working with the second sheet

        // Get the headers from the second row (row index 1)
        $headers = $data[1];

        // Map the headers to their corresponding column letters
        $headerMapping = $this->getHeaderMapping($headers);

        // Filter headers with a trailing '*'
        $requiredColumns = $this->getRequiredColumns($headerMapping);

        $errors = [];


         // Start iterating through the rows (assuming data starts from row 4 - index 3)
        for ($rowIndex = 3; $rowIndex < count($data); $rowIndex++) {
            $row = $data[$rowIndex];

            if ($this->rowHasData($row)) {
                foreach ($requiredColumns as $columnLetter => $headerName) {
                    $columnIndex = $this->getColumnIndex($columnLetter);
                    $this->validateCell($errors, $rowIndex, $columnLetter, $row, ['required', 'max:255'], $columnIndex);
                }
            }
        }



        if (!empty($errors)) {
            // Handle validation errors (e.g., logging, notifying users)
            $reportPath = $this->generateReport($errors,$data);

            Mail::to($fileModel->user->email)->send(new ValidationReportMail($reportPath));

            $fileModel->status = 'failed';
            $fileModel->save();
        }

           // No errors, collect all data (including optional fields)
        
           Dispatch(new ProcessMappingJob($data,$fileModel->user_id));
            $fileModel->status = 'processing';
            $fileModel->save();
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
            // Ensure we only map non-empty headers
            if (!empty($headerName)) {
                $headerMapping[$columnLetter] = $headerName;
            }
        }

        return $headerMapping;
    }


    /**
     * Get the required columns based on headers ending with '*'.
     *
     * @param array $headerMapping
     * @return array
     */
    private function getRequiredColumns($headerMapping)
    {
        $requiredColumns = [];
        foreach ($headerMapping as $columnLetter => $headerName) {
            if (substr($headerName, -1) === '*') {
                $requiredColumns[$columnLetter] = $headerName;
            }
        }

        return $requiredColumns;
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

    /**
     * Convert a column letter to its index (A -> 0, B -> 1, AA -> 26, etc.)
     *
     * @param string $columnLetter
     * @return int
     */
    private function getColumnIndex($columnLetter)
    {
        $index = 0;
        $letters = str_split(strtoupper($columnLetter));
        foreach ($letters as $i => $letter) {
            $index = $index * 26 + (ord($letter) - 64);
        }

        return $index - 1;
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

     private function generateReport($errors,$data)
    {
        // Define a unique file name for the report
        $fileName = 'validation_errors_report_' . now()->timestamp . '.xlsx';
        $filePath = 'reports/' . $fileName;

        $header_mapping = $this->getHeaderMapping($data[1]);
        // Pass both the errors and header mapping to the export class
        Excel::store(new ErrorsExport($errors, $header_mapping), $filePath, 'local');

        return Storage::path($filePath);
    }

}

