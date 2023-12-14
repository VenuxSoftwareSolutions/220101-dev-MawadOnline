<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unity;

class UnityController extends Controller
{
    public function index(){
        $units = Unity::orderBy('id', 'desc')->paginate(10);
        return view('backend.product.unites.index', [
            'units' => $units,
        ]);
    }

    public function create(){
        return view('backend.product.unites.create');
    }

    public function store(Request $request){
        $unit = new Unity();
        $unit->name = ['en' => $request->name_english, 'ar' => $request->name_arabic];
        $unit->save();

        return redirect()->route('units.index');
    }

    public function edit($id){
        $unit = Unity::find($id);

        if($unit){
            return view('backend.product.unites.edit', [
                'unit' => $unit
            ]);
        }else{
            return back();
        }
    }

    public function update(Request $request, $id){
        $unit = Unity::find($id);
        if($unit){
            $unit->name = ['en' => $request->name_english, 'ar' => $request->name_arabic];
            $unit->save();

            return redirect()->route('units.index');
        }else{
            return back();
        }

    }

    public function destroy($id){
        $unit = Unity::find($id);

        if($unit){
            $unit->delete();
            return response()->json(["status" => 'done'], 200);
        }else{
            return response()->json(["status" => 'failed'], 500);
        }
    }
}
