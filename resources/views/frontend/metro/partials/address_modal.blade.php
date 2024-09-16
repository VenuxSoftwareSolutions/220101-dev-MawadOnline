<!-- New Address Modal -->
<div class="modal fade" id="new-address-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ translate('New Address') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{-- <form class="form-default" role="form" action="{{ route('addresses.store') }}" method="POST">
                @csrf
                <div class="modal-body c-scrollbar-light">
                    <div class="p-3">
                        <!-- Address -->
                        <div class="row">
                            <div class="col-md-2">
                                <label>{{ translate('Address')}}</label>
                            </div>
                            <div class="col-md-10">
                                <textarea class="form-control mb-3 rounded-0" placeholder="{{ translate('Your Address')}}" rows="2" name="address" required></textarea>
                            </div>
                        </div>

                        <!-- Country -->
                        <div class="row">
                            <div class="col-md-2">
                                <label>{{ translate('Country')}}</label>
                            </div>
                            <div class="col-md-10">
                                <div class="mb-3">
                                    <select class="form-control aiz-selectpicker rounded-0" data-live-search="true" data-placeholder="{{ translate('Select your country') }}" name="country_id" required>
                                        <option value="">{{ translate('Select your country') }}</option>
                                        @foreach (get_active_countries() as $key => $country)
                                            <option @if ($country->id == 229)
                                                    selected
                                            @endif value="{{ $country->id }}">{{ $country->name }}</option>
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
                                <select class="form-control mb-3 aiz-selectpicker rounded-0" data-live-search="true" name="state_id" required>

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

                                </select>
                            </div>
                        </div>

                        @if (get_setting('google_map') == 1)
                            <!-- Google Map -->
                            <div class="row mt-3 mb-3">
                                <input id="searchInput" class="controls" type="text" placeholder="{{translate('Enter a location')}}">
                                <div id="map"></div>
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
                                    <input type="text" class="form-control mb-3 rounded-0" id="longitude" name="longitude" readonly="">
                                </div>
                            </div>
                            <!-- Latitude -->
                            <div class="row">
                                <div class="col-md-2" id="">
                                    <label for="exampleInputuname">{{ translate('Latitude')}}</label>
                                </div>
                                <div class="col-md-10" id="">
                                    <input type="text" class="form-control mb-3 rounded-0" id="latitude" name="latitude" readonly="">
                                </div>
                            </div>
                        @endif

                        <!-- Postal code -->
                        <div class="row">
                            <div class="col-md-2">
                                <label>{{ translate('Postal code')}}</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" class="form-control mb-3 rounded-0" placeholder="{{ translate('Your Postal Code')}}" name="postal_code" value="" required>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="row">
                            <div class="col-md-2">
                                <label>{{ translate('Phone')}}</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" class="form-control mb-3 rounded-0" placeholder="{{ translate('+880')}}" name="phone" value="" required>
                            </div>
                        </div>
                        <!-- Save button -->
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary rounded-0 w-150px">{{translate('Save')}}</button>
                        </div>
                    </div>
                </div>
            </form> --}}
            <form class="form-default" role="form" action="{{ route('addresses.store') }}" method="POST">
                @csrf
                <div class="modal-body c-scrollbar-light">
                    <div class="p-3">
                        <!-- Full Name -->
                        <div class="row">
                            <div class="col-md-4">
                                <label>{{ translate('Full Name') }} *</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control mb-3 rounded-0" placeholder="{{ translate('Your Full Name')}}" name="full_name" required>
                            </div>
                        </div>

                        <!-- Mobile Number -->
                        <div class="row">
                            <div class="col-md-4">
                                <label>{{ translate('Mobile Number') }} *</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control mb-3 rounded-0" placeholder="{{ translate('+971')}}" name="phone"        pattern="^(\+971|0971)?[0-9]{9}$"
                                required>
                            </div>
                        </div>

                        <!-- Country -->
                        <div class="row">
                            <div class="col-md-4">
                                <label>{{ translate('Country/Region') }} *</label>
                            </div>
                            {{-- <div class="col-md-8">
                                <div class="mb-3">
                                    <select class="form-control aiz-selectpicker rounded-0" data-live-search="true" data-placeholder="{{ translate('Select your country') }}" name="country_id" required>
                                        <option value="">{{ translate('Select your country') }}</option>
                                        @foreach (get_active_countries() as $key => $country)
                                            <option value="{{ $country->id }}" {{ $country->id == 229 ? 'selected' : '' }}>{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}

                            <div class="col-md-8">
                                <div class="mb-3">
                                    <select class="form-control aiz-selectpicker rounded-0" data-live-search="true" data-placeholder="{{ translate('Select your country') }}" name="country" required>
                                        <option value="">{{ translate('Select your country') }}</option>
                                        @foreach (get_active_countries() as $key => $country)
                                            <option value="{{ $country->id }}" {{ $country->id == 229 ? 'selected' : '' }}>{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Street Name/Number -->
                        <div class="row">
                            <div class="col-md-4">
                                <label>{{ translate('Street Name/Number') }} *</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control mb-3 rounded-0" placeholder="{{ translate('Street Name/Number')}}" name="address" required>
                            </div>
                        </div>

                        <!-- Building Name/Number -->
                        <div class="row">
                            <div class="col-md-4">
                                <label>{{ translate('Building Name/Number') }} *</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control mb-3 rounded-0" placeholder="{{ translate('Building Name/Number')}}" name="building_name" required>
                            </div>
                        </div>

                         <!-- State -->
                         {{-- <div class="row">
                            <div class="col-md-4">
                                <label>{{ translate('State')}} *</label>
                            </div>
                            <div class="col-md-8">
                                <select class="form-control mb-3 aiz-selectpicker rounded-0" data-live-search="true" name="state_id" required>

                                </select>
                            </div>
                        </div>

                        <!-- City -->
                        <div class="row">
                            <div class="col-md-4">
                                <label>{{ translate('City')}}</label>
                            </div>
                            <div class="col-md-8">
                                <select class="form-control mb-3 aiz-selectpicker rounded-0" data-live-search="true" name="city_id">

                                </select>
                            </div>
                        </div> --}}
                             <!-- State -->
                        <div class="row">
                            <div class="col-md-4">
                                <label>{{ translate('State/Emirate') }} *</label>
                            </div>
                            <div class="col-md-8">
                                <select id="emirateempire" class="form-control mb-3 aiz-selectpicker rounded-0" data-live-search="true" name="state" required>
                                    <option value="" selected>{{ translate('please_choose') }}</option>
                                    @foreach ($emirates as $emirate)
                                        <option value="{{ $emirate->id }}">{{ $emirate->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                            <!-- City -->
                            <div class="row">
                                <div class="col-md-4">
                                    <label>{{ translate('Area')}} *</label>
                                </div>
                                <div class="col-md-8">
                                    <select  id="areaempire" class="form-control mb-3 aiz-selectpicker rounded-0" data-live-search="true" name="area_id" required>

                                    </select>
                                </div>
                            </div>
                        {{-- <div class="row">
                            <div class="col-md-4">
                                <label><b>{{ translate('Area') }} </b><span
                                        class="text-primary">*</span></label>
                                <select required name="area_id"
                                    class="form-control rounded-0" id="areaempire">
                                    <option value="" selected>
                                        {{ translate('please_choose') }}
                                    </option>


                                </select>
                            </div>
                        </div> --}}

                        <!-- Nearest Landmark (Optional) -->
                        <div class="row">
                            <div class="col-md-4">
                                <label>{{ translate('Nearest Landmark') }}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control mb-3 rounded-0" placeholder="{{ translate('Nearest Landmark')}}" name="landmark">
                            </div>
                        </div>

                        <!-- Address Type (Optional) -->
                        <div class="row">
                            <div class="col-md-4">
                                <label>{{ translate('Address Type') }}</label>
                            </div>
                            <div class="col-md-8">
                                <div>
                                    <label><input type="radio" name="address_type" value="home"> {{ translate('Home')}}</label>
                                    <label><input type="radio" name="address_type" value="work"> {{ translate('Work')}}</label>
                                    <label><input type="radio" name="address_type" value="site"> {{ translate('Site')}}</label>
                                    <label><input type="radio" name="address_type" value="other"> {{ translate('Other')}}</label>
                                </div>
                            </div>
                        </div>

                        <!-- Delivery Instructions (Optional) -->
                        <div class="row">
                            <div class="col-md-4">
                                <label>{{ translate('Delivery Instructions') }}</label>
                            </div>
                            <div class="col-md-8">
                                <textarea class="form-control mb-3 rounded-0" placeholder="{{ translate('Delivery Instructions')}}" rows="2" name="delivery_instructions"></textarea>
                            </div>
                        </div>

                        <!-- Save button -->
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary rounded-0 w-150px">{{ translate('Save')}}</button>
                        </div>
                    </div>
                </div>
            </form>


        </div>
    </div>
</div>

<!-- Edit Address Modal -->
<div class="modal fade" id="edit-address-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ translate('New Address') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body c-scrollbar-light" id="edit_modal_body">

            </div>
        </div>
    </div>
</div>

@section('script')
    <script type="text/javascript">
    // A $( document ).ready() block.


        function add_new_address(){
            $('#new-address-modal').modal('show');
        }

        function edit_address(address) {
            var url = '{{ route("addresses.edit", ":id") }}';
            url = url.replace(':id', address);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: 'GET',
                success: function (response) {
                    $('#edit_modal_body').html(response.html);
                    $('#edit-address-modal').modal('show');
                    AIZ.plugins.bootstrapSelect('refresh');

                    @if (get_setting('google_map') == 1)
                        var lat     = -33.8688;
                        var long    = 151.2195;

                        if(response.data.address_data.latitude && response.data.address_data.longitude) {
                            lat     = parseFloat(response.data.address_data.latitude);
                            long    = parseFloat(response.data.address_data.longitude);
                        }

                        initialize(lat, long, 'edit_');
                    @endif
                }
            });
        }

        $(document).on('change', '[name=country_id]', function() {
            var country_id = $(this).val();
            get_states(country_id);
        });

        $(document).on('change', '[name=state_id]', function() {
            var state_id = $(this).val();
            get_city(state_id);
        });

        function get_states(country_id) {
            $('[name="state"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('get-state')}}",
                type: 'POST',
                data: {
                    country_id  : country_id
                },
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj != '') {
                        $('[name="state_id"]').html(obj);
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            });
        }

        function get_city(state_id) {
            $('[name="city"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('get-city')}}",
                type: 'POST',
                data: {
                    state_id: state_id
                },
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj != '') {
                        $('[name="city_id"]').html(obj);
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            });
        }

    </script>
<script>
    $( document ).ready(function() {
        // get_states(229);
        // $('#emirateempire').change(function() {
            $(document).on('change', '[name=state]', function() {

                var getAreaUrl = "{{ route('get.area', ['id' => ':id']) }}";

                var id = $(this).val();

                $('[name=area_id]').find('option').remove();

                // AJAX request
                $.ajax({
                    //  url: '/user/getArea/'+id,

                    url: getAreaUrl.replace(':id', id), // Use the route variable
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {

                        var len = 0;
                        if (response['data'] != null) {
                            len = response['data'].length;
                        }

                        if (len > 0) {
                            // Read data and create <option >
                            for (var i = 0; i < len; i++) {

                                var id = response['data'][i].id;
                                var name = response['data'][i].name_translated;

                                var option = "<option value='" + id + "'>" + name + "</option>";

                                $("[name=area_id]").append(option);
                                AIZ.plugins.bootstrapSelect('refresh');

                            }
                        }

                    }
                });
            });
});
</script>

    @if (get_setting('google_map') == 1)
        @include('frontend.'.get_setting('homepage_select').'.partials.google_map')
    @endif


@endsection
