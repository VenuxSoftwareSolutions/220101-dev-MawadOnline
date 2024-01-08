<?php

namespace Database\seeds;

use App\Models\Category;
use App\Models\CategoryTranslation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use League\Csv\Reader;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    // public function run()
    // {
    //     $csv = Reader::createFromPath('database/data/categories.csv');
    //     $csv->setHeaderOffset(0);

    //     $lastMainCategoryId = null;
    //     $lastSubCategoryId = null;

    //     $i = 0;
    //     // Iterate through each row in the CSV
    //     foreach ($csv as $row) {

    //         if ($row['category name'] != null) {
    //             // Create main category if not exists
    //             $categoryName = $row['category name'];
    //             $categorySlug = Str::slug($categoryName);

    //             $category = Category::firstOrCreate([
    //                 'name' => $categoryName,
    //                 'slug' => $categorySlug,
    //                 'parent_id' => 0, // Assuming 0 indicates a top-level category
    //                 // ... other fields
    //             ]);

    //             $lastMainCategoryId = $category->id;

    //             $category_translation = CategoryTranslation::firstOrCreate([
    //                 'name' => $row['category name ar'],
    //                 'lang' => 'ar',
    //                 'category_id' => $category->id
    //                 // ... other fields
    //             ]);

    //             if ($row['sub category name'] != null) {

    //                 // Create subcategory linked to the last main category
    //                 $subCategoryName = $row['sub category name'];
    //                 $subCategorySlug = Str::slug($subCategoryName);

    //                 $subCategory = Category::firstOrCreate([
    //                     'name' => $subCategoryName,
    //                     'slug' => $subCategorySlug,
    //                     'parent_id' => $lastMainCategoryId,
    //                     // ... other fields
    //                 ]);

    //                 $subCategory_translation = CategoryTranslation::firstOrCreate([
    //                     'name' => $row['sub category name ar'],
    //                     'lang' => 'ar',
    //                     'category_id' => $subCategory->id
    //                     // ... other fields
    //                 ]);
    //             }


    //             if ($row['child category name'] != null) {

    //                 // Create subcategory linked to the last main category
    //                 $childCategoryName = $row['child category name'];
    //                 $childCategorySlug = Str::slug($childCategoryName);

    //                 $childCategory = Category::firstOrCreate([
    //                     'name' => $childCategoryName,
    //                     'slug' => $childCategorySlug,
    //                     'parent_id' => $subCategory->id,
    //                     // ... other fields
    //                 ]);

    //                 $childCategory_translation = CategoryTranslation::firstOrCreate([
    //                     'name' => $row['sub category name ar'],
    //                     'lang' => 'ar',
    //                     'category_id' => $childCategory->id
    //                     // ... other fields
    //                 ]);
    //             }
    //         } else {
    //             // Ensure we have a main category to link this subcategory to
    //             if ($lastMainCategoryId !== null && $row['sub category name'] != null) {
    //                 // Create subcategory linked to the last main category
    //                 $subCategoryName = $row['sub category name'];
    //                 $subCategorySlug = Str::slug($subCategoryName);

    //                 $subCategory = Category::firstOrCreate([
    //                     'name' => $subCategoryName,
    //                     'slug' => $subCategorySlug,
    //                     'parent_id' => $lastMainCategoryId,
    //                     // ... other fields
    //                 ]);

    //                 $subCategory_translation = CategoryTranslation::firstOrCreate([
    //                     'name' => $row['sub category name ar'],
    //                     'lang' => 'ar',
    //                     'category_id' => $subCategory->id
    //                     // ... other fields
    //                 ]);
    //             }
    //         }

    //         $i++;
    //         if ($i == 6) {
    //             dd($row);
    //         }
    //     }
    // }

    public function run()
    {
        $csv = Reader::createFromPath('database/data/categories.csv');
        $csv->setHeaderOffset(0);

        $lastMainCategoryId = null;
        $lastSubCategoryId = null;

        // Iterate through each row in the CSV
        foreach ($csv as $row) {
            // Check if it's a new main category
            if (!empty($row['category name'])) {
                // Create main category
                $categoryName = $row['category name'];
                $categorySlug = Str::slug($categoryName);

                $category = Category::firstOrCreate([
                    'name' => $categoryName,
                    'slug' => $categorySlug,
                    'parent_id' => 0,
                    // ... other fields
                ]);

                $lastMainCategoryId = $category->id;
                $lastSubCategoryId = null; // Reset last subcategory ID

                $category_translation = CategoryTranslation::firstOrCreate([
                    'name' => $row['category name ar'],
                    'lang' => 'ar',
                    'category_id' => $category->id
                    // ... other fields
                ]);
            }

            // Check if it's a new subcategory or continue with the last main category
            if (!empty($row['sub category name'])) {
                $subCategoryName = $row['sub category name'];
                $subCategorySlug = Str::slug($subCategoryName);

                $subCategory = Category::firstOrCreate([
                    'name' => $subCategoryName,
                    'slug' => $subCategorySlug,
                    'parent_id' => $lastMainCategoryId, // Use last main category ID
                    // ... other fields
                ]);

                $lastSubCategoryId = $subCategory->id; // Update last subcategory ID

                $subCategory_translation = CategoryTranslation::firstOrCreate([
                    'name' => $row['sub category name ar'],
                    'lang' => 'ar',
                    'category_id' => $subCategory->id
                    // ... other fields
                ]);
            }

            // Check if it's a new child category or continue with the last subcategory
            if (!empty($row['child category name'])) {
                $childCategoryName = $row['child category name'];
                $childCategorySlug = Str::slug($childCategoryName);

                $childCategory = Category::firstOrCreate([
                    'name' => $childCategoryName,
                    'slug' => $childCategorySlug,
                    'parent_id' => $lastSubCategoryId ? $lastSubCategoryId : $lastMainCategoryId,
                    // ... other fields
                ]);

                $childCategory_translation = CategoryTranslation::firstOrCreate([
                    'name' => $row['child category name ar'],
                    'lang' => 'ar',
                    'category_id' => $childCategory->id
                    // ... other fields
                ]);
            }
        }
    }
}
