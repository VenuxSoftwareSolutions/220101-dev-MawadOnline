<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attribute;
use App\Models\Color;
use App\Models\AttributeTranslation;
use App\Models\AttributeValue;
use App\Models\Unity;
use App\Models\ProductAttributeValues;
use CoreComponentRepository;
use App\Http\Requests\AttributeRequest;
use App\Services\AttributeService;
use Illuminate\Support\Facades\Auth;
use Str;

class AttributeController extends Controller
{
    protected $attributeService;

    public function __construct(AttributeService $attributeService) {
        $this->attributeService = $attributeService;
        // Staff Permission Check
        $this->middleware(['permission:view_product_attributes'])->only('index');
        $this->middleware(['permission:edit_product_attribute'])->only('edit');
        $this->middleware(['permission:delete_product_attribute'])->only('destroy');

        $this->middleware(['permission:view_product_attribute_values'])->only('show');
        $this->middleware(['permission:edit_product_attribute_value'])->only('edit_attribute_value');
        $this->middleware(['permission:delete_product_attribute_value'])->only('destroy_attribute_value');

        $this->middleware(['permission:view_colors'])->only('colors');
        $this->middleware(['permission:edit_color'])->only('edit_color');
        $this->middleware(['permission:delete_color'])->only('destroy_color');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        CoreComponentRepository::instantiateShopRepository();
        CoreComponentRepository::initializeCache();
        $attributes = Attribute::with('attribute_values')->orderBy('created_at', 'desc')->paginate(15);
        return view('backend.product.attribute.index', compact('attributes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $units = Unity::all();
        return view('backend.product.attribute.create', [
            'units' => $units
        ]);
    }

    /**
     * Store a newly created resource in Database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AttributeRequest $request)
    {
        $attribute = $this->attributeService->store($request->all());
        if($attribute == null){
            flash(translate('Attribute name already existe'))->error();
            return back();
        }else{
            flash(translate('Attribute has been inserted successfully'))->success();
            return redirect()->route('attributes.index');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['attribute'] = Attribute::findOrFail($id);
        $data['all_attribute_values'] = AttributeValue::with('attribute')->where('attribute_id', $id)->get();

        // echo '<pre>';print_r($data['all_attribute_values']);die;

        return view("backend.product.attribute.attribute_value.index", $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $lang      = $request->lang;
        $attribute = Attribute::findOrFail($id);
        $units = Unity::all();
        return view('backend.product.attribute.edit', compact('attribute','lang', 'units'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required'],
            'display_name_english' => ['required' , 'max:128'],
            'display_name_arabic' => ['required' , 'max:64'],
            'type_value' => ['required' , 'max:128']
        ]);

        $attribute = $this->attributeService->update($request->all());

        if($attribute == null){
            flash(translate('Attribute name already existe'))->error();
            return back();
        }else{
            if($attribute == "error"){
                flash(translate('Something went wrong!'))->error();
                return back();
            }else{
                flash(translate('Attribute has been updated successfully'))->success();
                return back();
            }

        }
    }

    public function get_id_to_delete_value($id, $language){
        $value = AttributeValue::find($id);
        $values_english = AttributeValue::where('attribute_id', $value->attribute_id)->where('lang', 'en')->get();
        $values_arabic = AttributeValue::where('attribute_id', $value->attribute_id)->where('lang', 'ar')->get();
        $values_english_ids = AttributeValue::where('attribute_id', $value->attribute_id)->where('lang', 'en')->pluck('id')->toArray();
        $values_arabic_ids = AttributeValue::where('attribute_id', $value->attribute_id)->where('lang', 'ar')->pluck('id')->toArray();
        if($language == 'arabic'){
            $key = array_search($id,$values_arabic_ids);
            if($key != null){
                $id_to_delete = $values_english[$key]->id;
                $check_first_value = ProductAttributeValues::where('id_attribute', $value->attribute_id)->where('id_values', $id)->get();
                $check_second_value = ProductAttributeValues::where('id_attribute', $value->attribute_id)->where('id_values', $values_english[$key]->id)->get();
                if((count($check_first_value) > 0) || (count($check_second_value) > 0)){
                    return response()->json(['status' => 'failed used', 'id_to_delete' => '']);
                }
                return response()->json(['status' => 'done', 'id_to_delete' => $id_to_delete]);
            }else{
                return response()->json(['status' => 'failed', 'id_to_delete' => '']);
            }
        }else{
            $key = array_search($id,$values_english_ids);
            if($key != null){
                $id_to_delete = $values_arabic[$key]->id;
                $check_first_value = ProductAttributeValues::where('id_attribute', $value->attribute_id)->where('id_values', $id)->get();
                $check_second_value = ProductAttributeValues::where('id_attribute', $value->attribute_id)->where('id_values', $values_arabic[$key]->id)->get();
                if((count($check_first_value) > 0) || (count($check_second_value) > 0)){
                    return response()->json(['status' => 'failed used', 'id_to_delete' => '']);
                }
                return response()->json(['status' => 'done', 'id_to_delete' => $id_to_delete]);
            }else{
                return response()->json(['status' => 'failed', 'id_to_delete' => '']);
            }
        }

    }

    public function delete_values(Request $request){
        $request->validate([
            'ids' => ['required'],
        ]);

        if (str_contains($request->ids, '-')) {
            $ids = explode("-", $request->ids);

            $values1 = AttributeValue::find($ids[0]);
            if ($values1 != null) {
                $values1->delete();
            }

            $values2 = AttributeValue::find($ids[1]);
            if ($values2 != null) {
                $values2->delete();
            }
            return response()->json(['status' => 'done']);

        }
    }

    public function search_value_is_used(Request $request){
        $request->validate([
            'value_id' => ['required'],
            'attribute_id' => ['attribute_id'],
        ]);

        $check = ProductAttributeValues::where('id_attribute', $request->attribute_id)->where('id_values', $request->value_id)->get();

        if(count($check) > 0){
            return response()->json(['status'=>'Exist']);
        }else{
            return response()->json(['status'=>'Not exist']);
        }
    }

    public function search_values_is_used_by_type(Request $request){
        $request->validate([
            'attribute_id' => ['required'],
        ]);

        $check = ProductAttributeValues::where('id_attribute', $request->attribute_id)->get();

        if(count($check) > 0){
            return response()->json(['status'=>'Exist']);
        }else{
            return response()->json(['status'=>'Not exist']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $attribute = Attribute::findOrFail($id);

        foreach ($attribute->attribute_translations as $key => $attribute_translation) {
            $attribute_translation->delete();
        }

        Attribute::destroy($id);
        flash(translate('Attribute has been deleted successfully'))->success();
        return redirect()->route('attributes.index');

    }

    public function store_attribute_value(Request $request)
    {
        $attribute_value = new AttributeValue;
        $attribute_value->attribute_id = $request->attribute_id;
        $attribute_value->value = ucfirst($request->value);
        $attribute_value->save();

        flash(translate('Attribute value has been inserted successfully'))->success();
        return redirect()->route('attributes.show', $request->attribute_id);
    }

    public function edit_attribute_value(Request $request, $id)
    {
        $attribute_value = AttributeValue::findOrFail($id);
        return view("backend.product.attribute.attribute_value.edit", compact('attribute_value'));
    }

    public function update_attribute_value(Request $request, $id)
    {
        $attribute_value = AttributeValue::findOrFail($id);

        $attribute_value->attribute_id = $request->attribute_id;
        $attribute_value->value = ucfirst($request->value);

        $attribute_value->save();

        flash(translate('Attribute value has been updated successfully'))->success();
        return back();
    }

    public function destroy_attribute_value($id)
    {
        $attribute_values = AttributeValue::findOrFail($id);
        AttributeValue::destroy($id);

        flash(translate('Attribute value has been deleted successfully'))->success();
        return redirect()->route('attributes.show', $attribute_values->attribute_id);

    }

    public function colors(Request $request) {
        $sort_search = null;
        $colors = Color::orderBy('created_at', 'desc');

        if ($request->search != null){
            $colors = $colors->where('name', 'like', '%'.$request->search.'%');
            $sort_search = $request->search;
        }
        $colors = $colors->paginate(10);

        return view('backend.product.color.index', compact('colors', 'sort_search'));
    }

    public function store_color(Request $request) {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:colors|max:255',
        ]);
        $color = new Color;
        $color->name = Str::replace(' ', '', $request->name);
        $color->code = $request->code;

        $color->save();

        flash(translate('Color has been inserted successfully'))->success();
        return redirect()->route('colors');
    }

    public function edit_color(Request $request, $id)
    {
        $color = Color::findOrFail($id);
        return view('backend.product.color.edit', compact('color'));
    }

    /**
     * Update the color.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_color(Request $request, $id)
    {
        $color = Color::findOrFail($id);

        $request->validate([
            'code' => 'required|unique:colors,code,'.$color->id,
        ]);

        $color->name = Str::replace(' ', '', $request->name);
        $color->code = $request->code;

        $color->save();

        flash(translate('Color has been updated successfully'))->success();
        return back();
    }

    public function destroy_color($id)
    {
        Color::destroy($id);

        flash(translate('Color has been deleted successfully'))->success();
        return redirect()->route('colors');

    }

    public function is_activated(Request $request){
        $user = Auth::user();
        if(($user->getRoleNames()->first() == "Super Admin") || ($user->hasPermissionTo('enabling_product_attribute'))){

            $attribute = Attribute::find($request->id);
            if ($attribute != null) {
                if($request->status == "true"){
                    $attribute->is_activated = 1;
                    $attribute->save();
                }else{
                    $attribute->is_activated = 0;
                    $attribute->save();
                }

                return response()->json(["status" => 'done'], 200);
            }else{
                return response()->json(["status" => 'failed'], 500);
            }
        }else{
            return response()->json(["status" => 'failed'], 500);
        }
    }

}
