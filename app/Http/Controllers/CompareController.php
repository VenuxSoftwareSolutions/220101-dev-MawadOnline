<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Category;
use App\Models\Product;

class CompareController extends Controller
{
    public function index(Request $request)
    {
        //dd($request->session()->get('compare'));
        $categories = Category::all();
        return view('frontend.view_compare', compact('categories'));
    }

    //clears the session data for compare
    public function reset(Request $request)
    {
        $request->session()->forget('compare');
        return back();
    }

    //store comparing products ids in session
    public function addToCompare(Request $request)
    {
        $variantId = $request->id;
        $user = Auth::user();
        $compare = collect();
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
            if ($request->session()->has('compare')) {
                $compare = $request->session()->get('compare');
            }
    
            if (!isset($compare[$leafCategoryId])) {
                $compare[$leafCategoryId] = collect();
            }
    
            if (!$compare[$leafCategoryId]->contains($variantId)) {
                if ($compare[$leafCategoryId]->count() >= 3 ) {
                    $compare[$leafCategoryId]->forget(0);
                    $compare[$leafCategoryId]->push($request->id);
                }
                $compare[$leafCategoryId]->push($variantId);
            }
        
            $request->session()->put('compare', $compare);
        } else {
            return response()->json([
                'localStorageAction' => 'add',
                'variantId' => $variantId,
                'categoryId' => $leafCategoryId,
                'categoryName' => $leafCategoryName
            ]);
    
        }
        return view('frontend.' . get_setting('homepage_select') . '.partials.compare', compact('compare'));
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
