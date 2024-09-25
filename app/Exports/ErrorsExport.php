<?php

// app/Exports/ErrorsExport.php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class ErrorsExport implements FromCollection, WithHeadings
{
    protected $errors;

    public function __construct(array $errors)
    {
        $this->errors = $errors;
    }

    public function collection()
    {
        // Convert each error string to an array
        $formattedErrors = array_map(function ($error) {
            return [$error];
        }, $this->errors);

        return new Collection($formattedErrors);
    }

    public function headings(): array
    {
        return ['Error Message'];
    }
}
