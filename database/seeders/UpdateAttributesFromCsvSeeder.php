<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class UpdateAttributesFromCsvSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Path to the CSV file
        $csvFilePath = database_path('data/attr_values.csv');

        // Read the CSV file with semicolon as the delimiter
        $csv = Reader::createFromPath($csvFilePath, 'r');
        $csv->setDelimiter(';'); // Set the delimiter to semicolon
        $csv->setHeaderOffset(0); // First row contains headers

        // Get the records from the CSV
        $records = $csv->getRecords();

        // Loop through each record and update the attributes

        // Loop through each record and update the attributes
        foreach ($records as $record) {

            // Update the attributes table
            DB::table('attributes')
                ->where('id', $record['id'])
                ->update([
                    'name' => $record['name'],
                    'name_display_english' => $record['name_display_english'],
                    'type_value' => $record['new_type_value'],
                ]);

            // Update the attribute_values table
            if (!empty($record['possible_values'])) {
                // Split the possible values by comma and trim spaces
                $possibleValues = array_map('trim', explode(',', $record['possible_values']));

                // Create an array for storing JSON values
                $jsonValues = [];

                foreach ($possibleValues as $value) {
                    // Prepare the JSON structure for each value
                    // Here, assuming the keys for your language are "en" for English and "ar" for Arabic
                    $jsonValues[] = [
                        'en' => $value, // You may adjust this according to your logic
                        'ar' => $value, // Example function for translation
                    ];
                }

                // First, delete existing values for this attribute
                DB::table('attribute_values')
                    ->where('attribute_id', $record['id'])
                    ->delete();

                // Insert the new possible values
                foreach ($jsonValues as $jsonValue) {
                    DB::table('attribute_values')->insert([
                        'attribute_id' => $record['id'],
                        'value' => json_encode($jsonValue), // Store as JSON
                    ]);
                }
            }
        }

        $this->command->info('Attributes and possible values updated from CSV!');
    }

    // Example function for translation (you should implement your own logic)
    private function translateToArabic($value)
    {
        // Implement translation logic here, for now, it returns a placeholder
        return 'ترجمة لـ ' . $value; // Placeholder for Arabic translation
    }
}
