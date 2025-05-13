{{-- <form class="form-default" role="form" action="{{ route('addresses.update', $address_data->id) }}" method="POST">
    @csrf
    <div class="p-3">
        <!-- Address -->
        <div class="row">
            <div class="col-md-2">
                <label>{{ translate('Address')}}</label>
            </div>
            <div class="col-md-10">
                <textarea class="form-control mb-3 rounded-0" placeholder="{{ translate('Your Address')}}" rows="2" name="address" required>{{ $address_data->address }}</textarea>
            </div>
        </div>

        <!-- Country -->
        <div class="row">
            <div class="col-md-2">
                <label>{{ translate('Country')}}</label>
            </div>
            <div class="col-md-10">
                <div class="mb-3">
                    <select class="form-control aiz-selectpicker rounded-0" data-live-search="true" data-placeholder="{{ translate('Select your country')}}" name="country_id" id="edit_country" required>
                        <option value="">{{ translate('Select your country') }}</option>
                        @foreach (get_active_countries() as $key => $country)
                        <option value="{{ $country->id }}" @if($address_data->country_id == $country->id) selected @endif>
                            {{ $country->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- State -->
        <div class="row">
            <div class="col-md-2">
                <label>{{ translate('State')}}</label>
            </div>
            <div class="col-md-10">
                <select class="form-control mb-3 aiz-selectpicker rounded-0" name="state_id" id="edit_state"  data-live-search="true" required>
                    @foreach ($states as $key => $state)
                        <option value="{{ $state->id }}" @if($address_data->state_id == $state->id) selected @endif>
                            {{ $state->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- City -->
        <div class="row">
            <div class="col-md-2">
                <label>{{ translate('City')}}</label>
            </div>
            <div class="col-md-10">
                <select class="form-control mb-3 aiz-selectpicker rounded-0" data-live-search="true" name="city_id" required>
                    @foreach ($cities as $key => $city)
                        <option value="{{ $city->id }}" @if($address_data->city_id == $city->id) selected @endif>
                            {{ $city->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        @if (get_setting('google_map') == 1)
            <!-- Google Map -->
            <div class="row mt-3 mb-3">
                <input id="edit_searchInput" class="controls" type="text" placeholder="Enter a location">
                <div id="edit_map"></div>
                <ul id="geoData">
                    <li style="display: none;">Full Address: <span id="location"></span></li>
                    <li style="display: none;">Postal Code: <span id="postal_code"></span></li>
                    <li style="display: none;">Country: <span id="country"></span></li>
                    <li style="display: none;">Latitude: <span id="lat"></span></li>
                    <li style="display: none;">Longitude: <span id="lon"></span></li>
                </ul>
            </div>
            <!-- Longitude -->
            <div class="row">
                <div class="col-md-2" id="">
                    <label for="exampleInputuname">{{ translate('Longitude')}}</label>
                </div>
                <div class="col-md-10" id="">
                    <input type="text" class="form-control mb-3 rounded-0" id="edit_longitude" name="longitude" value="{{ $address_data->longitude }}" readonly="">
                </div>
            </div>
            <!-- Latitude -->
            <div class="row">
                <div class="col-md-2" id="">
                    <label for="exampleInputuname">{{ translate('Latitude')}}</label>
                </div>
                <div class="col-md-10" id="">
                    <input type="text" class="form-control mb-3 rounded-0" id="edit_latitude" name="latitude" value="{{ $address_data->latitude }}" readonly="">
                </div>
            </div>
        @endif

        <!-- Postal code -->
        <div class="row">
            <div class="col-md-2">
                <label>{{ translate('Postal code')}}</label>
            </div>
            <div class="col-md-10">
                <input type="text" class="form-control mb-3 rounded-0" placeholder="{{ translate('Your Postal Code')}}" value="{{ $address_data->postal_code }}" name="postal_code" value="" required>
            </div>
        </div>

        <!-- Phone -->
        <div class="row">
            <div class="col-md-2">
                <label>{{ translate('Phone')}}</label>
            </div>
            <div class="col-md-10">
                <input type="text" class="form-control mb-3 rounded-0" placeholder="{{ translate('+880')}}" value="{{ $address_data->phone }}" name="phone" value="" required>
            </div>
        </div>

        <!-- Save button -->
        <div class="form-group text-right">
            <button type="submit" class="btn btn-primary rounded-0 w-150px">{{translate('Save')}}</button>
        </div>
    </div>
</form> --}}

<form class="form-default" role="form" action="{{ route('addresses.update', $address_data->id) }}" method="POST">
    @csrf
    <div class="p-3 font-prompt">
        <!-- Full Name -->
        <div class="row">
            <div class="col-md-4 fs-14 pl-2 pr-0">
                <label class="mt-2">{{ translate('Full Name') }} *</label>
            </div>
            <div class="col-md-8">
                <input type="text" class="form-control mb-3 border-radius-8px" placeholder="{{ translate('Your Full Name') }}" name="full_name" value="{{ $address_data->full_name }}" required>
            </div>
        </div>

        <!-- Phone -->
        <div class="row">
            <div class="col-md-4 fs-14 pl-2 pr-0">
                <label class="mt-2">{{ translate('Phone') }} *</label>
            </div>
            <div class="col-md-8">
                <input type="text" class="form-control mb-3 border-radius-8px" placeholder="{{ translate('+971')}}" name="phone" pattern="^(\+971|0971)?[0-9]{9}$" value="{{ $address_data->phone }}" required>
            </div>
        </div>

        <!-- Country -->
        <div class="row">
            <div class="col-md-4 fs-14 pl-2 pr-0">
                <label class="mt-2">{{ translate('Country/Region') }} *</label>
            </div>
            {{-- <div class="col-md-8">
                <select class="form-control aiz-selectpicker rounded-0" data-live-search="true" data-placeholder="{{ translate('Select your country') }}" name="country_id" required>
                    <option value="">{{ translate('Select your country') }}</option>
                    @foreach (get_active_countries() as $key => $country)
                        <option value="{{ $country->id }}" @if($address_data->country_id == $country->id) selected @endif>{{ $country->name }}</option>
                    @endforeach
                </select>
            </div> --}}
            <div class="col-md-8">
                <select class="form-control aiz-selectpicker border-radius-8px" data-live-search="true" data-placeholder="{{ translate('Select your country') }}" name="country" required>
                    <option value="">{{ translate('Select your country') }}</option>
                    @foreach (get_active_countries() as $key => $country)
                        <option value="{{ $country->id }}" @if($address_data->country_id == $country->id) selected @endif>{{ $country->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- <!-- State -->
        <div class="row mt-4">
            <div class="col-md-4">
                <label>{{ translate('State') }} *</label>
            </div>
            <div class="col-md-8">
                <select class="form-control mb-3 aiz-selectpicker rounded-0" name="state_id" data-live-search="true" required>
                    @foreach ($states as $key => $state)
                        <option value="{{ $state->id }}" @if($address_data->state_id == $state->id) selected @endif>{{ $state->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- City -->
        <div class="row">
            <div class="col-md-4">
                <label>{{ translate('City') }}</label>
            </div>
            <div class="col-md-8">
                <select class="form-control mb-3 aiz-selectpicker rounded-0" data-live-search="true" name="city_id">
                    <option value="" disabled selected>{{ translate('Select your city') }}</option>

                    @foreach ($cities as $key => $city)
                        <option value="{{ $city->id }}" @if($address_data->city_id == $city->id) selected @endif>{{ $city->name }}</option>
                    @endforeach
                </select>
            </div>
        </div> --}}
          <!-- State -->
          <div class="row mt-4">
            <div class="col-md-4 fs-14 pl-2 pr-0">
                <label class="mt-2">{{ translate('State/Emirate') }} *</label>
            </div>
            <div class="col-md-8">
                <select class="form-control mb-3 aiz-selectpicker border-radius-8px" name="state" data-live-search="true" required>
                    @foreach ($states as $key => $state)
                        <option value="{{ $state->id }}" @if($address_data->state_id == $state->id) selected @endif>{{ $state->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- City -->
        <div class="row">
            <div class="col-md-4 fs-14 pl-2 pr-0">
                <label class="mt-2">{{ translate('Area') }} *</label>
            </div>
            <div class="col-md-8">
                <select class="form-control mb-3 aiz-selectpicker border-radius-8px" data-live-search="true" name="area_id" required>
                    <option value="" disabled selected>{{ translate('Select your city') }}</option>

                    @foreach ($cities as $key => $city)
                        <option value="{{ $city->id }}" @if($address_data->city_id == $city->id) selected @endif>{{ $city->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>


        <!-- Street Name/Number -->
        <div class="row">
            <div class="col-md-4 fs-14 pl-2 pr-0">
                <label class="mt-2">{{ translate('Street Name/Number') }} *</label>
            </div>
            <div class="col-md-8">
                <input type="text" class="form-control mb-3 border-radius-8px" placeholder="{{ translate('Street Name/Number') }}" name="address" value="{{ $address_data->address }}" required>
            </div>
        </div>

        <!-- Building Name/Number -->
        <div class="row">
            <div class="col-md-4 fs-14 pl-2 pr-0">
                <label class="mt-2">{{ translate('Building Name/Number') }} *</label>
            </div>
            <div class="col-md-8">
                <input type="text" class="form-control mb-3 border-radius-8px" placeholder="{{ translate('Building Name/Number') }}" name="building_name" value="{{ $address_data->building_name }}" required>
            </div>
        </div>

        <!-- Nearest Landmark (Optional) -->
        <div class="row">
            <div class="col-md-4 fs-14 pl-2 pr-0">
                <label class="mt-2">{{ translate('Nearest Landmark') }}</label>
            </div>
            <div class="col-md-8">
                <input type="text" class="form-control mb-3 border-radius-8px" placeholder="{{ translate('Nearest Landmark') }}" name="landmark" value="{{ $address_data->landmark }}">
            </div>
        </div>

        <!-- Address Type (Optional) -->
        <div class="row">
            <div class="col-md-4 fs-14 pl-2 pr-0">
                <label>{{ translate('Address Type') }}</label>
            </div>
            <div class="col-md-8">
                <div>
                    <input class="fs-14 font-prompt radio-shipping" type="radio" name="address_type" id="address-type-home-edit" value="home" @if($address_data->address_type == 'home') checked @endif>
                    <label for="address-type-home-edit" class="custom-radio-label">{{ translate('Home') }}</label>
                    <input class="fs-14 font-prompt radio-shipping" type="radio" name="address_type" id="address-type-work-edit" value="work" @if($address_data->address_type == 'work') checked @endif>
                    <label for="address-type-work-edit" class="custom-radio-label">{{ translate('Work') }}</label>
                    <input class="fs-14 font-prompt radio-shipping" type="radio" name="address_type" id="address-type-site-edit" value="site" @if($address_data->address_type == 'site') checked @endif>
                    <label for="address-type-site-edit" class="custom-radio-label">{{ translate('Site') }}</label>
                    <input class="fs-14 font-prompt radio-shipping" type="radio" name="address_type" id="address-type-other-edit" value="other" @if ($address_data->address_type == 'other') checked @endif>
                    <label for="address-type-other-edit" class="custom-radio-label">{{ translate('Other') }}</label>
                </div>
            </div>
        </div>

        <!-- Delivery Instructions (Optional) -->
        <div class="row mt-2">
            <div class="col-md-4 fs-14 pl-2 pr-0">
                <label class="mt-2">{{ translate('Delivery Instructions') }}</label>
            </div>
            <div class="col-md-8">
                <textarea class="form-control mb-3 border-radius-8px no-resize-txtarea" placeholder="{{ translate('Delivery Instructions') }}" rows="2" name="delivery_instructions">{{ $address_data->delivery_instructions }}</textarea>
            </div>
        </div>

        <!-- Save button -->
        <div class="form-group text-right">
            <button type="submit" class="w-150px btn btn-secondary-base btn-ori-40 text-white border-radius-12 fs-15 font-prompt py-2">{{ translate('Save') }}</button>
        </div>
    </div>
</form>
