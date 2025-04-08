<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Revision;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class MawadIndexController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'avg');
        $period = $request->query('period', '1w');

        $categoriesWithRevisions = $this->getCategoriesWithRevisions();

        $defaultPeriod = 7;

        $selectedCategoriesEvolution = $this->getSelectedCategoriesEvolutionInLastDays($defaultPeriod, $filter);

        $selectedCategories = $this->getTopSelectedCategoriesInLastDays($period);

        $defaultCurrency = get_system_default_currency();

        $categoryId = $request->query('category_id', $selectedCategories[0]->id);

        $categoryPrices = $this->getPriceEvolution($categoryId, $period, $filter);

        return Inertia::render('Home', [
            'categories' => $categoriesWithRevisions,
            'selectedCategories' => $selectedCategories,
            'selectedCategoriesEvolution' => $selectedCategoriesEvolution,
            'categoryPrices' => $categoryPrices,
            'filter' => $filter,
            'defaultCurrency' => $defaultCurrency->symbol,
            'language' => app()->getLocale(),
        ]);
    }

    public function getFormattedDateRanges($period = 7)
    {
        $startDate = Carbon::now()->subDays($period - 1)->toDateString();
        $endDate = Carbon::now()->toDateString();

        return [
            "$startDate 00:00:00", "$endDate 23:59:59",
        ];
    }

    public function getTopSelectedCategoriesInLastDays($period = 7)
    {
        $startDate = match ($period) {
            7 => Carbon::now()->subDays(6),
            '1w' => Carbon::now()->subDays(6),
            '2w' => Carbon::now()->subDays(13),
            '1m' => Carbon::now()->subMonth(),
            '3m' => Carbon::now()->subMonths(2),
            '6m' => Carbon::now()->subMonths(5),
            '1y' => Carbon::now()->subMonths(11),
            default => Carbon::now()->subDays(7)
        };

        $datesRanges = [$startDate, Carbon::now()];

        return DB::table('categories as c')
            ->join('product_categories', 'c.id', '=', 'product_categories.category_id')
            ->join('products', 'product_categories.product_id', '=', 'products.id')
            ->join('revisions', function ($join) use ($datesRanges) {
                $join->on('revisions.revisionable_id', '=', 'products.id')
                    ->where('revisions.key', '=', 'unit_price')
                    ->whereBetween('revisions.created_at', $datesRanges);
            })
            ->where('products.approved', 1)
            ->where('products.published', 1)
            ->where("c.selected", 1)
            ->select([
                'c.id',
                'c.name',
            ])
            ->groupBy('c.id')
            ->get()
            ->toArray();
    }

    public function getSelectedCategoriesEvolutionInLastDays($period = 7, $filter = 'avg')
    {
        $datesRanges = $this->getFormattedDateRanges($period);

        $selectedCategoriesIds = DB::table('categories as c')
            ->join('product_categories', 'c.id', '=', 'product_categories.category_id')
            ->join('products', 'product_categories.product_id', '=', 'products.id')
            ->join('revisions', function ($join) use ($datesRanges) {
                $join->on('revisions.revisionable_id', '=', 'products.id')
                    ->where('revisions.key', '=', 'unit_price')
                    ->whereBetween('revisions.created_at', $datesRanges);
            })
            ->where('products.approved', 1)
            ->where('products.published', 1)
            ->where("c.selected", 1)
            ->select([
                'c.id',
            ])
            ->groupBy('c.id')
            ->pluck('c.id')
            ->toArray();

        $categories = DB::table('categories as c')
            ->leftJoin('categories as parent', 'c.parent_id', '=', 'parent.id')
            ->leftJoin('categories as grandparent', 'parent.parent_id', '=', 'grandparent.id')
            ->join('product_categories', 'c.id', '=', 'product_categories.category_id')
            ->join('products', 'product_categories.product_id', '=', 'products.id')
            ->join('revisions', function ($join) use ($datesRanges) {
                $join->on('revisions.revisionable_id', '=', 'products.id')
                    ->where('revisions.key', '=', 'unit_price')
                    ->whereBetween('revisions.created_at', $datesRanges);
            })
            ->whereIn('c.id', $selectedCategoriesIds)
            ->select([
                'c.id',
                'revisions.revisionable_id as product_id',
                'c.name AS category_name',
                'parent.name AS parent_category_name',
                 'grandparent.name AS main_category_name',
                DB::raw('DATE(revisions.created_at) AS date'),
                $filter === 'avg' ?
                    DB::raw('AVG(revisions.mwd_new_value) AS price')
                    : DB::raw('MIN(revisions.mwd_new_value) AS price'),
            ])
            ->groupBy('c.id', 'c.name', 'parent.name', 'grandparent.name', 'date')
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

            if (! isset($formattedData[$categoryId])) {
                $formattedData[$categoryId] = [
                    'category' => $data->category_name,
                    'parentCategory' => $data->parent_category_name,
                    'mainCategory' => $data->main_category_name,
                    'evolution' => [],
                    'product_id' => $data->product_id,
                ];
            }

            $formattedData[$categoryId]['evolution'][$data->date] = $data->price;
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

                $filledEvolution[] = [
                    'date' => $date,
                    'price' => roundUpToTwoDigits($lastKnownPrice),
                ];
            }

            $categoryData['evolution'] = $filledEvolution;

            $lastElementIndex = count($categoryData["evolution"]) - 1;
            $lastPrice = $categoryData["evolution"][$lastElementIndex]["price"];
            $penultimatePrice = $categoryData["evolution"][$lastElementIndex - 1]["price"];

            $absoluteChange = roundUpToTwoDigits($lastPrice - $penultimatePrice);
            $percentageChange = $penultimatePrice ? roundUpToTwoDigits(($absoluteChange / $penultimatePrice) * 100) : 0;

            $categoryData['priceChange'] = [
                'absolute' => $absoluteChange,
                'formattedAbsolute' => single_price($absoluteChange),
                'percentage' => $percentageChange,
                'firstPrice' => $penultimatePrice,
                'lastPrice' => $lastPrice,
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
                     // the requirements is the last 14 days (2 weeks)
                    ->where('revisions.created_at', '>=', DB::raw('NOW() - INTERVAL 14 DAY'));
            })
            ->where('products.approved', 1)
            ->where('products.published', 1)
            ->where("categories.selected", 1)
            ->select([
                'categories.id',
                'categories.name AS subcategory',
                'parent_categories.name as parentCategory',
                DB::raw('AVG(revisions.mwd_new_value) AS avgPrice'),
                DB::raw('MIN(revisions.mwd_new_value) AS lowestPrice'),
                DB::raw('MAX(revisions.created_at) AS last_revision_date'),
                DB::raw('CONCAT("[",
                    GROUP_CONCAT(
                        revisions.mwd_new_value ORDER BY revisions.created_at DESC SEPARATOR ","
                    ),
                "]") AS trend'),
                /* percentage change = 100.0 x (New Price - Old Price) / (Old Price) */
                DB::raw('(
                    ( (SELECT mwd_new_value FROM revisions
                       WHERE revisions.revisionable_id = products.id
                         AND revisions.key = "unit_price"
                       ORDER BY revisions.created_at DESC
                       LIMIT 1)
                    -
                    (SELECT mwd_old_value FROM revisions
                     WHERE revisions.revisionable_id = products.id
                       AND revisions.key = "unit_price"
                     ORDER BY revisions.created_at DESC
                     LIMIT 1)
                    ) /
                    (SELECT mwd_old_value FROM revisions
                     WHERE revisions.revisionable_id = products.id
                       AND revisions.key = "unit_price"
                     ORDER BY revisions.created_at DESC
                     LIMIT 1)
                ) * 100 AS priceChange'),
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

    public function fixRevisionsUnitPriceMwdCommission()
    {
        $revisions = Revision::withTrashed()
            ->where('revisionable_type', "App\Models\Product")
            ->where('key', 'unit_price')->get();

        $revisions->each(function ($revision) {
            $revision->mwd_new_value = calculateMwdIndexPrice(
                $revision->revisionable_id,
                $revision->new_value
            );

            if ($revision->old_value !== null) {
                $revision->mwd_old_value = calculateMwdIndexPrice(
                    $revision->revisionable_id,
                    $revision->old_value
                );
            }

            $revision->save();
        });
    }

    public function getPriceEvolution($categoryId, $period, $filter = 'avg')
    {
        $startDate = match ($period) {
            '1w' => Carbon::now()->subDays(6),
            '2w' => Carbon::now()->subDays(13),
            '1m' => Carbon::now()->subMonth(),
            '3m' => Carbon::now()->subMonths(2),
            '6m' => Carbon::now()->subMonths(5),
            '1y' => Carbon::now()->subMonths(11),
            default => Carbon::now()->subDays(7)
        };

        $datesRange = [$startDate, Carbon::now()];

        // grouping method (by day or by month)
        $groupBy = in_array($period, ['1w', '2w', '1m'])
            ? 'DATE(revisions.created_at)'
            : 'DATE_FORMAT(revisions.created_at, "%Y-%m")';

        $rawData = DB::table('revisions')
            ->join('products', 'products.id', '=', 'revisions.revisionable_id')
            ->where('revisions.key', 'unit_price')
            ->where('products.category_id', $categoryId)
            ->where("products.approved", 1)
            ->where('products.published', 1)
            ->whereBetween('revisions.created_at', $datesRange)
            ->select([
                DB::raw("$groupBy as date"),
                $filter === 'avg'
                    ? DB::raw('AVG(revisions.mwd_new_value) as price')
                    : DB::raw('MIN(revisions.mwd_new_value) as price'),
            ])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->map(function ($data) {
                $data->price = roundUpToTwoDigits($data->price);
                return $data;
            })->keyBy('date')
            ->toArray();

        // generate full range of dates (days or months)
        $datesPeriod = collect();
        $current = clone $startDate;
        $now = Carbon::now();

        while ($current < $now) {
            $formattedDate = in_array($period, ['1w', '2w', '1m'])
                ? $current->format('Y-m-d')
                : $current->format('Y-m');

            $datesPeriod->push($formattedDate);

            in_array($period, ['1w', '2w', '1m']) ? $current->addDay() : $current->addMonth();
        }

        // fill missing dates with last known price
        $filledData = [];
        $lastPrice = null;

        foreach ($datesPeriod as $date) {
            if (isset($rawData[$date])) {
                $lastPrice = $rawData[$date]->price;
            }

            $filledData[] = (object) [
                'date' => $date,
                'price' => $lastPrice !== null ? roundUpToTwoDigits($lastPrice) : 0,
            ];
        }

        return $filledData;
    }
}
