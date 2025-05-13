<!-- New Address Modal -->
<div class="modal fade" id="new-address-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-17 font-prompt mt-1" id="exampleModalLabel">{{ translate('New Address') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-default" role="form" action="{{ route('addresses.store') }}" method="POST">
                @csrf
                <div class="modal-body c-scrollbar-light font-prompt">
                    <div class="p-3">
                        <!-- Full Name -->
                        <div class="row">
                            <div class="col-md-4 fs-14 pl-2 pr-0">
                                <label class="mt-2">{{ translate('Full Name') }} *</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control mb-3 border-radius-8px"
                                    placeholder="{{ translate('Your Full Name') }}" name="full_name" required>
                            </div>
                        </div>

                        <!-- Mobile Number -->
                        <div class="row">
                            <div class="col-md-4 fs-14 pl-2 pr-0">
                                <label class="mt-2">{{ translate('Mobile Number') }} *</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control mb-3 border-radius-8px"
                                    placeholder="{{ translate('+971') }}" name="phone"
                                    pattern="^(\+971|0971)?[0-9]{9}$" required>
                            </div>
                        </div>

                        <!-- Country -->
                        <div class="row">
                            <div class="col-md-4 fs-14 pl-2 pr-0">
                                <label class="mt-2">{{ translate('Country/Region') }} *</label>
                            </div>

                            <div class="col-md-8">
                                <div class="mb-3">
                                    <select class="form-control aiz-selectpicker border-radius-8px" data-live-search="true"
                                        data-placeholder="{{ translate('Select your country') }}" name="country"
                                        required>
                                        <option value="">{{ translate('Select your country') }}</option>
                                        @foreach (get_active_countries() as $key => $country)
                                            <option value="{{ $country->id }}"
                                                {{ $country->id == 229 ? 'selected' : '' }}>{{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Street Name/Number -->
                        <div class="row">
                            <div class="col-md-4 fs-14 pl-2 pr-0">
                                <label class="mt-2">{{ translate('Street Name/Number') }} *</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control mb-3 border-radius-8px"
                                    placeholder="{{ translate('Street Name/Number') }}" name="address" required>
                            </div>
                        </div>

                        <!-- Building Name/Number -->
                        <div class="row">
                            <div class="col-md-4 fs-14 pl-2 pr-0">
                                <label class="mt-2">{{ translate('Building Name/Number') }} *</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control mb-3 border-radius-8px"
                                    placeholder="{{ translate('Building Name/Number') }}" name="building_name"
                                    required>
                            </div>
                        </div>

                        <!-- State -->
                        <div class="row">
                            <div class="col-md-4 fs-14 pl-2 pr-0">
                                <label class="mt-2">{{ translate('State/Emirate') }} *</label>
                            </div>
                            <div class="col-md-8">
                                <select id="emirateempire" class="form-control mb-3 aiz-selectpicker border-radius-8px"
                                    data-live-search="true" name="state" required>
                                    <option value="" selected>{{ translate('please_choose') }}</option>
                                    @foreach ($emirates as $emirate)
                                        <option value="{{ $emirate->id }}">{{ $emirate->name }}</option>
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
                                <select id="areaempire" class="form-control mb-3 aiz-selectpicker border-radius-8px"
                                    data-live-search="true" name="area_id" required>
                                </select>
                            </div>
                        </div>

                        <!-- Nearest Landmark (Optional) -->
                        <div class="row">
                            <div class="col-md-4 fs-14 pl-2 pr-0">
                                <label class="mt-2">{{ translate('Nearest Landmark') }}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control mb-3 border-radius-8px"
                                    placeholder="{{ translate('Nearest Landmark') }}" name="landmark">
                            </div>
                        </div>

                        <!-- Address Type (Optional) -->
                        <div class="row">
                            <div class="col-md-4 fs-14 pl-2 pr-0">
                                <label>{{ translate('Address Type') }}</label>
                            </div>
                            <div class="col-md-8">
                                <div>
                                    <input class="fs-14 font-prompt radio-shipping" type="radio" name="address_type" id="address-type-home" value="home">
                                        <label for="address-type-home" class="custom-radio-label">{{ translate('Home') }}</label>
                                    <input class="fs-14 font-prompt radio-shipping" type="radio" name="address_type" id="address-type-work" value="work">
                                        <label for="address-type-work" class="custom-radio-label">{{ translate('Work') }}</label>
                                    <input class="fs-14 font-prompt radio-shipping" type="radio" name="address_type" id="address-type-site" value="site">
                                        <label for="address-type-site" class="custom-radio-label">{{ translate('Site') }}</label>
                                    <input class="fs-14 font-prompt radio-shipping" type="radio" name="address_type" id="address-type-other" value="other">
                                        <label for="address-type-other" class="custom-radio-label">{{ translate('Other') }}</label>
                                </div>
                            </div>
                        </div>

                        <!-- Delivery Instructions (Optional) -->
                        <div class="row mt-2">
                            <div class="col-md-4 fs-14 pl-2 pr-0">
                                <label class="mt-2">{{ translate('Delivery Instructions') }}</label>
                            </div>
                            <div class="col-md-8">
                                <textarea class="form-control mb-3 border-radius-8px no-resize-txtarea" placeholder="{{ translate('Delivery Instructions') }}" rows="2"
                                    name="delivery_instructions"></textarea>
                            </div>
                        </div>

                        <!-- Save button -->
                        <div class="form-group text-right">
                            <button type="submit"
                                class="w-150px btn btn-secondary-base btn-ori-40 text-white border-radius-12 fs-15 font-prompt py-2">{{ translate('Save') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Address Modal -->
<div class="modal fade" id="edit-address-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-17 font-prompt mt-1" id="exampleModalLabel">{{ translate('New Address') }}</h5>
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
    <script>
        function add_new_address() {
            $('#new-address-modal').modal('show');
        }

        function edit_address(address) {
            let url = '{{ route('addresses.edit', ':id') }}';
            url = url.replace(':id', address);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: 'GET',
                success: function(response) {
                    $('#edit_modal_body').html(response.html);
                    $('#edit-address-modal').modal('show');
                    AIZ.plugins.bootstrapSelect('refresh');

                    @if (get_setting('google_map') == 1)
                        let lat = -33.8688;
                        let long = 151.2195;

                        if (response.data.address_data.latitude && response.data.address_data.longitude) {
                            lat = parseFloat(response.data.address_data.latitude);
                            long = parseFloat(response.data.address_data.longitude);
                        }

                        initialize(lat, long, 'edit_');
                    @endif
                }
            });
        }

        $(document).on('change', '[name=country_id]', function() {
            let country_id = $(this).val();
            get_states(country_id);
        });

        $(document).on('change', '[name=state_id]', function() {
            let state_id = $(this).val();
            get_city(state_id);
        });

        function get_states(country_id) {
            $('[name="state"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('get-state') }}",
                type: 'POST',
                data: {
                    country_id: country_id
                },
                success: function(response) {
                    let obj = JSON.parse(response);
                    if (obj != '') {
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
                url: "{{ route('get-city') }}",
                type: 'POST',
                data: {
                    state_id: state_id
                },
                success: function(response) {
                    let obj = JSON.parse(response);
                    if (obj != '') {
                        $('[name="city_id"]').html(obj);
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            });
        }

        $(document).ready(function() {
            $(document).on('change', '[name=state]', function() {
                let url = "{{ route('emirate.states', ['emirate_id' => ':id']) }}";
                let id = $(this).val();

                $('[name=area_id]').find('option').remove();

                $.ajax({
                    url: url.replace(':id', id),
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.data !== null && response.data?.length > 0) {
                            for (let i = 0; i < response.data.length; i++) {
                                let id = response['data'][i].id;
                                let name = response['data'][i].name;

                                $("[name=area_id]").append(`
                                    <option value='${id}'>
                                        ${name}
                                    </option>
                                `);
                                AIZ.plugins.bootstrapSelect('refresh');
                            }
                        }
                    },
                    error: function() {
                        AIZ.plugins.notify('{{ __("Something went wrong!") }}');
                    }
                });
            });
        });
    </script>

    @if (get_setting('google_map') == 1)
        @include('frontend.' . get_setting('homepage_select') . '.partials.google_map')
    @endif
@endsection
