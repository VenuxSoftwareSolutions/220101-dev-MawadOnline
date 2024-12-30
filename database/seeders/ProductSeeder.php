<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\ProductService;
use Auth;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Stripe\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $productService = new ProductService; // Create an instance of the ProductService

        // Simulate logging in a user
        $userId = 337; // Replace with the ID of the user you want to authenticate as
        $user = User::find($userId);
        Auth::login($user);

        for ($i = 0; $i < 1000000; $i++) {
            // Simulate mapped product data
            $mappedProduct = [
                'name' => $faker->word,
                'published_modal' => 0,
                'create_stock' => 0,
                'brand_id' => $faker->numberBetween(1, 1000), // Adjust based on your brands
                'unit' => $faker->randomElement(['piece', 'box', 'kg']),
                'country_code' => $faker->countryCode,
                'manufacturer' => $faker->company,
                'tags' => [json_encode($this->tagsHandling($faker->word))],
                'short_description' => $faker->sentence,
                'stock_visibility_state' => $faker->boolean,
                'refundable' => $faker->boolean,
                'video_provider' => $faker->randomElement(['YouTube', 'Vimeo']),
                'video_link' => $faker->url,
                'from' => [$faker->numberBetween(1, 100)],
                'to' => [$faker->numberBetween(100, 1000)],
                'unit_price' => [$faker->randomFloat(2, 10, 1000)],
                'date_range_pricing' => [
                    $faker->dateTimeThisYear()->format('d-m-Y H:i:s').' to '.$faker->dateTimeThisYear()->format('d-m-Y H:i:s'),
                    $faker->dateTimeThisYear()->format('d-m-Y H:i:s').' to '.$faker->dateTimeThisYear()->format('d-m-Y H:i:s'),
                    $faker->dateTimeThisYear()->format('d-m-Y H:i:s').' to '.$faker->dateTimeThisYear()->format('d-m-Y H:i:s'),
                ],
                'discount_type' => $faker->randomElement(['fixed', 'percentage']),
                'discount_amount' => [$faker->randomFloat(2, 1, 100)],
                'discount_percentage' => $faker->randomFloat(2, 1, 100),
                'sample_description' => $faker->sentence,
                'sample_price' => $faker->randomFloat(2, 5, 100),
                'length' => $faker->randomFloat(2, 1, 10),
                'width' => $faker->randomFloat(2, 1, 10),
                'height' => $faker->randomFloat(2, 1, 10),
                'weight' => $faker->randomFloat(2, 1, 10),
                'breakable' => $faker->boolean,
                'unit_weight' => 'kilograms',
                'min_third_party' => $faker->randomFloat(2, -10, 0),
                'max_third_party' => $faker->randomFloat(2, 0, 10),
                'from_shipping' => [$faker->numberBetween(1, 100)],
                'to_shipping' => [$faker->numberBetween(100, 1000)],

                'estimated_order' => json_encode([$faker->numberBetween(1, 10)]),
                'estimated_shipping' => json_encode([$faker->numberBetween(1, 10)]),
                'paid' => json_encode([$faker->randomElement(['buyer', 'seller'])]),
                'shipping_charge' => json_encode([$faker->randomElement(['flat_rate', 'per_unit'])]),
                'flat_rate_shipping' => json_encode([$faker->randomFloat(2, 5, 50)]),
                'charge_per_unit_shipping' => json_encode([$faker->randomFloat(2, 1, 10)]),
                'length_sample' => $faker->randomFloat(2, 1, 10),
                'width_sample' => $faker->randomFloat(2, 1, 10),
                'height_sample' => $faker->randomFloat(2, 1, 10),
                'package_weight_sample' => $faker->randomFloat(2, 1, 10),
                'breakable_sample' => $faker->boolean,
                'min_third_party_sample' => $faker->randomFloat(2, -10, 0),
                'max_third_party_sample' => $faker->randomFloat(2, 0, 10),
                'parent_id' => $faker->numberBetween(1, 1000), // Adjust based on your product types
                'product_sk' => $faker->unique()->ean8,
                'quantite_stock_warning' => $faker->numberBetween(1, 100),
                'description' => $faker->paragraph,
                'meta_title' => $faker->sentence,
                'meta_description' => $faker->paragraph,
                'button' => 'draft',
                //'submit_button' => null,
            ];

            // Store the product
            $product = $productService->store($mappedProduct);

            // Handle parent-child relationship
            if ($product->is_parent == 1) {
                $products = Product::where('parent_id', $product->id)->get();
                if (count($products) > 0) {
                    foreach ($products as $child) {
                        $child->categories()->attach($mappedProduct['parent_id']);
                    }
                }
            }

            // Attach category to the product
            $product->categories()->attach($mappedProduct['parent_id']);
        }
    }

    private function tagsHandling($product_tags)
    {
        $tags_array = explode(', ', $product_tags);

        return array_map(function ($tag) {
            return ['value' => trim($tag)];
        }, $tags_array);
    }
}
