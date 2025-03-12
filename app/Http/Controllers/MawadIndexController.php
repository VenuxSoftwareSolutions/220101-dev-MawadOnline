<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Category;
use App\Models\Revision;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MawadIndexController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'avg');
        $categoriesWithRevisions = $this->getCategoriesWithRevisions();

        $top10Categories = $this->getTop10CategoriesEvolutionInLast7Days();

        return Inertia::render('Home', [
            "categories" => $categoriesWithRevisions,
            "top10Categories" => $top10Categories,
            "filter" => $filter
        ]);
    }

    public function getTop10CategoriesEvolutionInLast7Days()
    {
        $startDate = Carbon::now()->subDays(6)->toDateString();
        $endDate = Carbon::now()->toDateString();

        $categories = DB::table('categories as c')
            ->leftJoin('categories as parent', 'c.parent_id', '=', 'parent.id')
            ->join('product_categories', 'c.id', '=', 'product_categories.category_id')
            ->join('products', 'product_categories.product_id', '=', 'products.id')
            ->join('revisions', function ($join) use ($startDate, $endDate) {
                $join->on('revisions.revisionable_id', '=', 'products.id')
                     ->where('revisions.key', '=', 'unit_price')
                     ->whereBetween('revisions.created_at', [$startDate, $endDate]);
            })
            ->where('products.approved', 1)
            ->where('products.published', 1)
            ->select([
                'c.id',
                'c.name AS category_name',
                'parent.name AS parent_category_name',
                DB::raw('DATE(revisions.created_at) AS date'),
                DB::raw('AVG(revisions.new_value) AS price')
            ])
            ->groupBy('c.id', 'c.name', 'parent.name', 'date')
            ->orderBy('c.id')
            ->orderBy('date')
            ->get()->toArray();

        $formattedData = [];

        foreach ($categories as $data) {
            $categoryPrices = [];
            $dates = collect(range(0, 6))->map(function ($offset) {
                return Carbon::now()->subDays($offset)->toDateString();
            })->flip()->map(fn () => 0)->toArray();

            $dates[$data->date] = roundUpToTwoDigits($data->price);

            foreach ($dates as $date => $price) {
                $categoryPrices[] = [
                    'date' => $date,
                    'price' => $price
                ];
            }

            $formattedData[] = [
                "category" => $data->category_name,
                "parentCategory" => $data->parent_category_name,
                "evolution" => $categoryPrices
            ];
        }

        return $formattedData;
    }

    public function getCategoriesWithRevisions()
    {
        return Category::leftJoin('categories as parent_categories', 'categories.parent_id', '=', 'parent_categories.id')
            ->join('product_categories', 'categories.id', '=', 'product_categories.category_id')
            ->join('products', 'product_categories.product_id', '=', 'products.id')
            ->join('revisions', function ($join) {
                $join->on('revisions.revisionable_id', '=', 'products.id')
                     ->where('revisions.key', '=', 'unit_price')
                     // the requirements is the last 90 days
                     ->where('revisions.created_at', '>=', DB::raw('NOW() - INTERVAL 90 DAY'));
            })
            ->where('products.approved', 1)
            ->where('products.published', 1)
            ->select([
                'categories.id',
                'categories.name AS subcategory',
                'parent_categories.name as parentCategory',
                DB::raw('AVG(products.unit_price) AS avgPrice'),
                DB::raw('MIN(products.unit_price) AS lowestPrice'),
                DB::raw('MAX(revisions.created_at) AS last_revision_date'),
                DB::raw('CONCAT("[",
                    GROUP_CONCAT(
                        revisions.new_value ORDER BY revisions.created_at DESC SEPARATOR ","
                    ),
                "]") AS trend'),
                /* percentage change = 100.0 x (New Price - Old Price) / (Old Price) */
                DB::raw('(
                    ( (SELECT new_value FROM revisions
                       WHERE revisions.revisionable_id = products.id
                         AND revisions.key = "unit_price"
                       ORDER BY revisions.created_at DESC
                       LIMIT 1)
                    -
                    (SELECT old_value FROM revisions
                     WHERE revisions.revisionable_id = products.id
                       AND revisions.key = "unit_price"
                     ORDER BY revisions.created_at DESC
                     LIMIT 1)
                    ) /
                    (SELECT old_value FROM revisions
                     WHERE revisions.revisionable_id = products.id
                       AND revisions.key = "unit_price"
                     ORDER BY revisions.created_at DESC
                     LIMIT 1)
                ) * 100 AS priceChange')
            ])
            ->groupBy('categories.id', 'parent_categories.name', 'categories.name')
            ->get()
            ->map(function ($category) {
                $category->trend = json_decode($category->trend, true);
                $category->trend = array_reduce($category->trend, function ($acc, $num) {
                    if (empty($acc) || end($acc) !== $num) {
                        $acc[] = $num;
                    }
                    return $acc;
                }, []);

                $category->priceChange = roundUpToTwoDigits($category->priceChange);
                return $category;
            })->toArray();
    }
}
