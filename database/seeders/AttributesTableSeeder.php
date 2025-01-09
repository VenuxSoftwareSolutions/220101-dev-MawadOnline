<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use League\Csv\Reader;

class AttributesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Path to the CSV file
        $filePath = base_path('database/data/attributes.csv');

        // Open the CSV file and read the contents
        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0); // Set header offset to 0 to treat the first row as header


        // Iterate through the records and insert them into the database
        foreach ($csv as $record) {


            Attribute::create([
                'name'                  => $record['display_name'],
                'name_display_english'  => $record['system_name'],
                'name_display_arabic'   => $record['display_name'],
                'description_english'   => $record['Description'],
                'description_english'   => $record['Description'],
                'type_value'        => $record['value_data_type'],
                

            ]);
        }
    }
}
