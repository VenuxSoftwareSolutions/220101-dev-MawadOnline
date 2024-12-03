<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use App\Models\Area;
use App\Models\City;
use App\Models\Emirate;
use App\Models\State;
use Auth;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    // public function store(Request $request)
    // {
    //     $address = new Address;
    //     if ($request->has('customer_id')) {
    //         $address->user_id   = $request->customer_id;
    //     } else {
    //         $address->user_id   = Auth::user()->id;
    //     }
    //     $address->address       = $request->address;
    //     $address->country_id    = $request->country_id;
    //     $address->state_id      = $request->state_id;
    //     $address->city_id       = $request->city_id;
    //     $address->longitude     = $request->longitude;
    //     $address->latitude      = $request->latitude;
    //     $address->postal_code   = $request->postal_code;
    //     $address->phone         = $request->phone;
    //     $address->save();

    //     flash(translate('Address info Stored successfully'))->success();
    //     return back();
    // }
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'full_name'        => 'required|string|max:255',
            'phone'    => 'required|string',
            'country'       => 'required|exists:countries,id',
            'address'      => 'required|string|max:255',
            'building_name'    => 'required|string|max:255',
            'state'          => 'required|exists:emirates,id',
            'area_id'             => 'nullable|string|max:255',
            'landmark'         => 'nullable|string|max:255',
            'address_type'     => 'nullable|string|in:home,work,site,other',
            'delivery_instructions' => 'nullable|string|max:500',
        ]);

        // Create a new Address instance
        $address = new Address;

        // Assign values to the Address model fields
        $address->user_id             =  Auth::user()->id;
        $address->full_name           = $request->full_name;
        $address->phone               = $request->phone;
        $address->country_id          = $request->country;
        $address->address         = $request->address;
        $address->building_name       = $request->building_name;
        $address->emirate_id      = $request->state;
        $address->area_id       = $request->area_id;
        $address->landmark            = $request->landmark;
        $address->address_type        = $request->address_type;
        $address->delivery_instructions = $request->delivery_instructions;

        // Save the Address model
        $address->save();

        // Flash success message
        flash(translate('Address info stored successfully'))->success();
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['address_data'] = Address::findOrFail($id);
        // $data['states'] = State::where('status', 1)->where('country_id', $data['address_data']->country_id)->get();
        // $data['cities'] = City::where('status', 1)->where('state_id', $data['address_data']->state_id)->get();
        $data['states'] = Emirate::all();
        $data['cities'] = Area::where('emirate_id', $data['address_data']->state_id)->get();

        $returnHTML = view('frontend.'.get_setting('homepage_select').'.partials.address_edit_modal', $data)->render();
        return response()->json(array('data' => $data, 'html' => $returnHTML));
        //        return ;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    // public function update(Request $request, $id)
    // {
    //     $address = Address::findOrFail($id);

    //     $address->address       = $request->address;
    //     $address->country_id    = $request->country_id;
    //     $address->state_id      = $request->state_id;
    //     $address->city_id       = $request->city_id;
    //     $address->longitude     = $request->longitude;
    //     $address->latitude      = $request->latitude;
    //     $address->postal_code   = $request->postal_code;
    //     $address->phone         = $request->phone;

    //     $address->save();

    //     flash(translate('Address info updated successfully'))->success();
    //     return back();
    // }

    public function update(Request $request, $id)
{
    // Retrieve the address by ID
    $address = Address::findOrFail($id);

    // Validate the incoming request data
    $validatedData = $request->validate([
        'full_name'        => 'required|string|max:255',
        'phone'    => 'required|string',
        'country' => 'required|exists:countries,id',
        'state' => 'required|exists:emirates,id',
        'area_id' => 'nullable|exists:areas,id',
        'address' => 'required|string|max:255',
        'building_name' => 'required|string|max:255',
        'landmark' => 'nullable|string|max:255',
        'address_type' => 'nullable|in:home,work,site,other',
        'delivery_instructions' => 'nullable|string|max:500',
    ]);

    // Update the address data
    // $address->update($validatedData);
        $address->full_name           = $request->full_name;
        $address->phone               = $request->phone;
        $address->country_id          = $request->country;
        $address->address         = $request->address;
        $address->building_name       = $request->building_name;
        $address->emirate_id      = $request->state;
        $address->area_id       = $request->area_id;
        $address->landmark            = $request->landmark;
        $address->address_type        = $request->address_type;
        $address->delivery_instructions = $request->delivery_instructions;

        // Save the Address model
        $address->save();

    flash(translate('Address info updated successfully'))->success();
    return back();
}


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $address = Address::findOrFail($id);
        if (!$address->set_default) {
            $address->delete();
            return back();
        }
        flash(translate('Default address cannot be deleted'))->warning();
        return back();
    }

    public function getStates(Request $request)
    {
        $states = State::where('status', 1)->where('country_id', $request->country_id)->get();
        $html = '<option value="">' . translate("Select State") . '</option>';

        foreach ($states as $state) {
            $html .= '<option value="' . $state->id . '">' . $state->name . '</option>';
        }

        echo json_encode($html);
    }

    public function getCities(Request $request)
    {
        $cities = City::where('status', 1)->where('state_id', $request->state_id)->get();
        $html = '<option value="">' . translate("Select City") . '</option>';

        foreach ($cities as $row) {
            $html .= '<option value="' . $row->id . '">' . $row->getTranslation('name') . '</option>';
        }

        echo json_encode($html);
    }

    public function set_default($id)
    {
        foreach (Auth::user()->addresses as $key => $address) {
            $address->set_default = 0;
            $address->save();
        }
        $address = Address::findOrFail($id);
        $address->set_default = 1;
        $address->save();

        return back();
    }
}
