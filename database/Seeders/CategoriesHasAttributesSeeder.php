<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CategoriesHasAttributesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Path to the CSV file
        $csvFile = 'database/data/att_cat.csv';

        // Check if the file exists
        if (File::exists($csvFile)) {
            // Open the CSV file and read its contents
            $data = array_map('str_getcsv', file($csvFile), array_fill(0, count(file($csvFile)), ';'));
            
            // Get the CSV header
            $header = array_shift($data);

            // Insert data into the categories_has_attributes table
            foreach ($data as $row) {
                // Combine the header with row data
                $rowData = array_combine($header, $row);

                // Split the 'category_id' by comma if there are multiple values
                $categoryIds = explode(',', $rowData['category_id']);
                
                // Loop through each category_id and insert the data
                foreach ($categoryIds as $categoryId) {
                    DB::table('categories_has_attributes')->insert([
                        'attribute_id' => $rowData['id'],
                        'category_id' => trim($categoryId),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        } else {
            $this->command->error('CSV file not found.');
        }
    }
}
