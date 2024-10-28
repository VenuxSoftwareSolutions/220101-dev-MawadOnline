<?php 

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ErrorsExport implements FromArray, WithHeadings
{
    protected $errors;
    protected $headerMapping;


    public function __construct(array $errors, array $headerMapping)
    {
        $this->headerMapping = $headerMapping;

        $this->errors = $this->formatErrors($errors);
    }

    /**
     * Format errors into a human-readable structure.
     */
    private function formatErrors($errors)
    {
        $formattedErrors = [];
        foreach ($errors as $rowIndex => $errorDetails) {
            foreach ($errorDetails as $columnLetter => $errorMessage) {
                $headerName = $this->headerMapping[$columnLetter] ?? 'Unknown Header';
                $formattedErrors[] = [
                    'Row' => $rowIndex + 1, // Excel is 1-based index
                    'Column' => $columnLetter,
                    'Header' => $headerName, // Include the column header
                    'Error Message' => $errorMessage
                ];
            }
        }

        return $formattedErrors;
    }

    /**
     * Return the array of data that will be written to Excel.
     */
    public function array(): array
    {
        return $this->errors;
    }

    /**
     * Define the headings for the Excel sheet.
     */
    public function headings(): array
    {
        return [
            'Row',
            'Column',
            'Header',
            'Error Message',
        ];
    }
}
