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

        $top10Categories = $this->getTopCategoriesEvolutionInLastDays(7, $filter);

        return Inertia::render('Home', [
            "categories" => $categoriesWithRevisions,
            "top10Categories" => $top10Categories,
            "filter" => $filter
        ]);
    }

    public function getFormattedDateRanges($period = 7)
    {
        $startDate = Carbon::now()->subDays($period - 1)->toDateString();
        $endDate = Carbon::now()->toDateString();
        return [
            "$startDate 00:00:00", "$endDate 23:59:59"
        ];
    }

    public function getTopCategoriesEvolutionInLastDays($period = 7, $filter = "avg")
    {
        $datesRanges = $this->getFormattedDateRanges($period);

        // top 10 categories based on price changes
        $topCategories = DB::table('categories as c')
            ->join('product_categories', 'c.id', '=', 'product_categories.category_id')
            ->join('products', 'product_categories.product_id', '=', 'products.id')
            ->join('revisions', function ($join) use ($datesRanges) {
                $join->on('revisions.revisionable_id', '=', 'products.id')
                     ->where('revisions.key', '=', 'unit_price')
                     ->whereBetween('revisions.created_at', $datesRanges);
            })
            ->where('products.approved', 1)
            ->where('products.published', 1)
            ->select([
                'c.id',
            ])
            ->groupBy('c.id')
            ->limit(10)
            ->pluck('c.id')
            ->toArray();

        $categories = DB::table('categories as c')
            ->leftJoin('categories as parent', 'c.parent_id', '=', 'parent.id')
            ->join('product_categories', 'c.id', '=', 'product_categories.category_id')
            ->join('products', 'product_categories.product_id', '=', 'products.id')
            ->join('revisions', function ($join) use ($datesRanges) {
                $join->on('revisions.revisionable_id', '=', 'products.id')
                     ->where('revisions.key', '=', 'unit_price')
                     ->whereBetween('revisions.created_at', $datesRanges);
            })
            ->where('products.approved', 1)
            ->where('products.published', 1)
            ->whereIn('c.id', $topCategories)
            ->select([
                'c.id',
                'revisions.revisionable_id as product_id',
                'c.name AS category_name',
                'parent.name AS parent_category_name',
                DB::raw('DATE(revisions.created_at) AS date'),
                DB::raw('AVG(revisions.mwd_new_value) AS avg_price'),
                DB::raw('MIN(revisions.mwd_new_value) AS lowest_price')
            ])
            ->groupBy('c.id', 'c.name', 'parent.name', 'date')
            ->orderBy('c.id')
            ->orderBy('date')
            ->get()
            ->toArray();

        $formattedData = [];
        $dateRange = [];

        // generate an array of the last X days
        for ($i = 0; $i < $period; $i++) {
            $dateRange[] = Carbon::now()->subDays($period - 1 - $i)->toDateString();
        }

        foreach ($categories as $data) {
            $categoryId = $data->id;

            if (!isset($formattedData[$categoryId])) {
                $formattedData[$categoryId] = [
                    "category" => $data->category_name,
                    "parentCategory" => $data->parent_category_name,
                    "evolution" => [],
                    "product_id" => $data->product_id
                ];
            }

            $formattedData[$categoryId]['evolution'][$data->date] = $filter === "avg" ?
                $data->avg_price : $data->lowest_price;
        }

        foreach ($formattedData as &$categoryData) {
            $lastKnownPrice = 0;
            $filledEvolution = [];
            $firstPrice = 0;
            $lastPrice = 0;

            foreach ($dateRange as $date) {
                if (isset($categoryData['evolution'][$date])) {
                    $lastKnownPrice = $categoryData['evolution'][$date];
                }

                if (!$firstPrice && $lastKnownPrice !== 0) {
                    $firstPrice = $lastKnownPrice;
                }

                $lastPrice = $lastKnownPrice;

                $filledEvolution[] = [
                    'date' => $date,
                    'price' => roundUpToTwoDigits($lastKnownPrice)
                ];
            }

            $absoluteChange = roundUpToTwoDigits($lastPrice - $firstPrice);
            $percentageChange = $firstPrice ? roundUpToTwoDigits(($absoluteChange / $firstPrice) * 100) : 0;

            $categoryData['evolution'] = $filledEvolution;
            $categoryData['priceChange'] = [
                "absolute" => $absoluteChange,
                "formattedAbsolute" => single_price($absoluteChange),
                "percentage" => $percentageChange,
                "firstPrice" => $firstPrice,
                "lastPrice" => $lastPrice
            ];
        }

        return array_values($formattedData);
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
