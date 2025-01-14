<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ManualProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Path to the CSV file
        $filePath = storage_path('app/products.csv');

        // Open the file for reading
        if (($handle = fopen($filePath, 'r')) !== FALSE) {
            // Read the first row as headers
            $headers = fgetcsv($handle, 1000, ';');

            // Loop through the remaining rows
            while (($data = fgetcsv($handle, 1000, ';')) !== FALSE) {
                // Combine headers with data
                $row = array_combine($headers, $data);

                // Insert into the database
                DB::table('products')->insert([
                    'category_id'        => $row['category id'],
                    'user_id'        => 330,
                    'name'       => $row['Product Name *'],
                    'sku'                => $row['sku'],
                    'brand_id'              => 467,
                    'short_description'  => $row['Short Description *'],
                    'published' => 0,
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ]);
            }

            // Close the file
            fclose($handle);
        }
    }
}
