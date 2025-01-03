@extends('seller.layouts.app')

<style>
    .customer-color {
        background-color: #f77b0b !important;
        border: #f77b0b !important;
    }
</style>

@section('panel_content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ __('stock.Warehouses') }}</h1>
            </div>
        </div>
    </div>
    <div class="card">
        <form class="form-horizontal" id="add_inventory_record" action="{{ route('seller.warehouses.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="p-3">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row warehouseRow" id="warehouseRows">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="warehouse_name">{{ translate('Warehouse Name') }}<span
                                        class="text-primary">*</span></label>
                                <input type="text" class="form-control" name="warehouse_name_add">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="state">{{ translate('State/Emirate') }}<span
                                        class="text-primary">*</span></label>
                                <select name="state_warehouse_add" class="form-control rounded-0 emirateSelect"
                                    id="emirateempire">
                                    <option value="" selected>{{ translate('please_choose') }}</option>
                                    @foreach ($emirates as $emirate)
                                        <option value="{{ $emirate->id }}">{{ $emirate->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="area">{{ translate('Area') }}<span class="text-primary">*</span></label>
                                <select name="area_warehouse_add" class="form-control areaSelect">
                                    <option value="" selected>
                                        {{ translate('please_choose') }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="street">{{ translate('Street') }}<span class="text-primary">*</span></label>
                                <input type="text" class="form-control" name="street_warehouse_add">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="building">{{ translate('Building') }}<span
                                        class="text-primary">*</span></label>
                                <input type="text" class="form-control" name="building_warehouse_add">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="unit">{{ translate('Unit/Office No.') }}<span
                                        class="text-primary"></span></label>
                                <input type="text" class="form-control" name="unit_add">
                            </div>
                        </div>

                        <div class="col-auto ml-auto">
                            <button type="button" class="btn btn-primary"
                                id="addRow">{{ translate('Add Warehouse') }}</button>
                        </div>
                    </div>
                    <table class="table mt-3" id="warehouseTable">
                        <thead class="thead-dark">
                            <tr>
                                <th>{{ translate('Warehouse Name') }}</th>
                                <th>{{ translate('State/Emirate') }}</th>
                                <th>{{ translate('Area') }}</th>
                                <th>{{ translate('Street') }}</th>
                                <th>{{ translate('Building') }}</th>
                                <th>{{ translate('Unit/Office No.') }}</th>
                                <th>{{ translate('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($warehouses as $warehouse)
                                <tr class="warehouseRow" data-warehouse-id="{{ $warehouse->id }}">
                                    <td><input value="{{ $warehouse->warehouse_name }}" type="text" class="form-control"
                                            name="warehouse_name[]" required>
                                        <input name="warehouse_id[]" type="hidden" value="{{ $warehouse->id }}">
                                    </td>
                                    <td>

                                        <select required name="state_warehouse[]"
                                            class="form-control rounded-0 emirateSelect" id="emirateempire">
                                            <option value="" selected>{{ translate('please_choose') }}</option>
                                            @foreach ($emirates as $emirate)
                                                <option value="{{ $emirate->id }}"
                                                    @if ($warehouse->emirate_id == $emirate->id) selected @endif>
                                                    {{ $emirate->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control areaSelect" name="area_warehouse[]" required>
                                            @php
                                                $areas = App\Models\State::where('emirate_id', $warehouse->emirate_id)->get();
                                            @endphp
                                            <option value="" selected>
                                                {{ translate('please_choose') }}</option>
                                            @foreach ($areas as $area)
                                                <option value="{{ $area->id }}"
                                                    @if ($area->id == $warehouse->area_id) selected @endif>
                                                    {{ $area->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control" value="{{ $warehouse->address_street }}"
                                            name="street_warehouse[]" required></td>
                                    <td><input type="text" class="form-control"
                                            value="{{ $warehouse->address_building }}" name="building_warehouse[]"
                                            required></td>
                                    <td><input type="text" class="form-control" value="{{ $warehouse->address_unit }}"
                                            name="unit_warehouse[]"></td>
                                    <td>
                                        @if (!$warehouse->checkWhHasProducts())
                                            <button type="button"
                                                class="btn btn-danger removeRow">{{ translate('Remove') }}</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-end">
                <button type="submit" id="saveButton" class="btn btn-primary">{{ translate('Save') }}</button>
            </div>
        </form>
    </div>
    <!-- Bootstrap Modal -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">{{ trans('messages.confirm_delete') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ trans('messages.delete_warning') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ trans('messages.cancel_button') }}</button>
                    <button type="button" class="btn btn-primary customer-color"
                        id="confirmDeleteBtn">{{ trans('messages.confirm_button') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">{{ trans('messages.delete_success') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ trans('messages.delete_success') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).on('change', '.emirateSelect', function() {
            let emirateId = $(this).val();

            let areaSelect = $(this).closest('.warehouseRow').find('.areaSelect');

            $.ajax({
                url: '{{ route('emirate.states', ['emirate_id' => ':id']) }}'.replace(':id', emirateId),
                method: 'GET',
                success: function(response) {
                    areaSelect.empty();
                    areaSelect.append(
                        '<option value="" selected>{{ translate('please_choose') }}</option>');

                    for (let i = 0; i < response.data?.length; i++) {
                        let id = response['data'][i].id;
                        let name = response['data'][i].name;

                        let option = `<option value="${id}">${name}</option>`;

                        areaSelect.append(option);
                    }
                },
                error: function(error) {
                    areaSelect.empty();
                    areaSelect.append(
                        '<option value="" selected>{{ translate('please_choose') }}</option>');
                    console.error('Error fetching areas:', error);
                }
            });
        });

        let warehouseIdToDelete;
        let rowToDelete;

        $('#warehouseTable').on('click', '.removeRow', function() {
            rowToDelete = $(this).closest('tr');
            warehouseIdToDelete = rowToDelete.data(
            'warehouse-id'); // Assuming each row has a data attribute for warehouse ID
            if (typeof warehouseIdToDelete !== 'undefined') {
                // Show the Bootstrap modal
                $('#deleteConfirmationModal').modal('show');
            } else {
                rowToDelete.remove();
            }
        });

        // Handle the confirmation button click event
        $('#confirmDeleteBtn').on('click', function() {
            // If user clicks "Yes", send AJAX request to remove the warehouse
            $.ajax({
                url: '{{ route('seller.warehouses.remove') }}', // Update with your backend route
                method: 'POST',
                data: {
                    warehouse_id: warehouseIdToDelete,
                    _token: '{{ csrf_token() }}', // Include the CSRF token
                },
                success: function(response) {
                    // Check if the response contains an error
                    if (response.error) {
                        // Show error message
                        toastr.error(response.error);
                    } else {
                        toastr.success('{{ trans('messages.delete_success') }}');
                    }
                },
                error: function(error) {
                    console.error('Error removing warehouse:', error);
                    toastr.error('{{ trans('messages.delete_error') }}',
                        '{{ trans('messages.error') }}');
                }
            });

            // Hide the modal after confirmation
            $('#deleteConfirmationModal').modal('hide');
        });

        $('#addRow').on('click', function() {
            let warehouseName = $('input[name="warehouse_name_add"]').val();
            let street = $('input[name="street_warehouse_add"]').val();
            let building = $('input[name="building_warehouse_add"]').val();
            let unit = $('input[name="unit_add"]').val();

            let stateSelect = $('select[name="state_warehouse_add"]');
            let stateValue = stateSelect.val(); // Get selected state value
            let stateSelectClone = stateSelect.clone(); // Clone the select element
            stateSelectClone.attr('name', 'state_warehouse[]'); // Set the cloned select element's name attribute
            stateSelectClone.val(stateValue); // Set the cloned select element's value
            stateSelectClone.prop('required', true); // Add required attribute to state select

            let areaSelect = $('select[name="area_warehouse_add"]');
            let areaValue = areaSelect.val(); // Get selected area value
            let areaSelectClone = areaSelect.clone(); // Clone the select element
            areaSelectClone.attr('name', 'area_warehouse[]'); // Set the cloned select element's name attribute
            areaSelectClone.addClass(
            'form-control areaSelect'); // Add the 'form-control' and 'areaSelect' classes to the cloned area select
            areaSelectClone.val(areaValue)
            areaSelectClone.prop('required', true); // Add required attribute to area select

            let translations = {
                fillAllRequiredFields: "{{ __('stock.fill_all_required_fields') }}",
                warehouseName: "{{ __('stock.warehouse_name') }}",
                state: "{{ __('stock.state') }}",
                area: "{{ __('stock.area') }}",
                street: "{{ __('stock.street') }}",
                building: "{{ __('stock.building') }}"
            };

            // Check if any input is empty
            if (!warehouseName || !stateValue || !areaValue || !street || !building) {
                let errorMsg = translations.fillAllRequiredFields;
                if (!warehouseName) {
                    errorMsg += '\n- ' + translations.warehouseName;
                }
                if (!stateValue) {
                    errorMsg += '\n- ' + translations.state;
                }
                if (!areaValue) {
                    errorMsg += '\n- ' + translations.area;
                }
                if (!street) {
                    errorMsg += '\n- ' + translations.street;
                }
                if (!building) {
                    errorMsg += '\n- ' + translations.building;
                }
                toastr.error(errorMsg);
                return; // Stop execution if any input is empty
            }


            const newRow = $('<tr class="warehouseRow">');

            // Create cells for the new row
            newRow.append('<td><input type="text" class="form-control" name="warehouse_name[]" value="' +
                warehouseName + '" required></td>');
            newRow.append('<td></td>'); // Placeholder for state select element
            newRow.append('<td></td>'); // Placeholder for area select element
            newRow.append('<td><input type="text" class="form-control" name="street_warehouse[]" value="' + street +
                '" required></td>');
            newRow.append('<td><input type="text" class="form-control" name="building_warehouse[]" value="' +
                building + '" required></td>');
            newRow.append('<td><input type="text" class="form-control" name="unit_warehouse[]" value="' + unit +
                '"></td>');
            newRow.append(
                '<td><button type="button" class="btn btn-danger removeRow">{{ translate('Remove') }}</button></td>'
                );

            // Append the new row to the table body
            $('#warehouseTable tbody').append(newRow);

            // Append cloned state and area select elements to the new row
            newRow.find('td:nth-child(2)').append(stateSelectClone); // Append state select
            newRow.find('td:nth-child(3)').append(areaSelectClone); // Append area select

            // Clear input fields after adding the new row
            $('input[name="warehouse_name_add"]').val('');
            $('select[name="state_warehouse_add"]').val('');
            $('select[name="area_warehouse_add"]').val('');
            $('input[name="street_warehouse_add"]').val('');
            $('input[name="building_warehouse_add"]').val('');
            $('input[name="unit_add"]').val('');
        });

        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('startTourButton').addEventListener('click', function(event) {
                event.preventDefault(); // Prevent the default anchor click behavior
                localStorage.setItem('guide_tour', '0'); // Set local storage as required
                window.location.href = '{{ route('seller.dashboard') }}'; // Redirect to the dashboard
            });
            if (localStorage.getItem('guide_tour') != '0') {
                if ({{ Auth::user()->tour }} == true | {{ Auth::user()->id }} != {{ Auth::user()->owner_id }}) {
                    return;
                }
            }
            let tour_steps = [
                @foreach ($tour_steps as $key => $step)
                    {
                        element: document.querySelector('#{{ $step->element_id }}'),
                        title: '{{ $step->getTranslation('title') }}',
                        intro: "{{ $step->getTranslation('description') }}",
                        position: '{{ $step->getTranslation('lang') === 'en' ? 'right' : 'left' }}'
                    },
                @endforeach
            ];
            let lang = '{{ $tour_steps[0]->getTranslation('lang') }}';
            let tour = introJs();
            let step_number = 0;
            tour.setOptions({
                steps: tour_steps,
                nextLabel: lang == 'en' ? 'Next' : 'التالي',
                prevLabel: lang == 'en' ? 'Back' : 'رجوع',
                exitOnEsc: false,
                exitOnOverlayClick: false,
                disableInteraction: true,
                overlayOpacity: 0.4,
                showStepNumbers: true,
                hidePrev: true,
                showProgress: true,
            });


            tour.onexit(function() {
                $.ajax({
                    url: "{{ route('seller.tour') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    }, // Include CSRF token for Laravel
                    success: function(response) {
                        // Handle success
                        console.log('User tour status updated successfully');
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error('Error updating user tour status:', error);
                    }
                });
                localStorage.setItem('guide_tour', '1'); // Set local storage as required
                setTimeout(function() {
                    window.location.href = '{{ route('seller.dashboard') }}';
                }, 500);
            });

            tour.onbeforechange(function(targetElement) {
                if (this._direction === 'backward') {
                    window.location.href =
                    '{{ route('seller.stocks.index') }}'; // Redirect to another page
                    sleep(60000);
                }
                step_number += 1;
                if (step_number == 3) {
                    window.location.href = '{{ route('seller.stock.operation.report') }}';
                    sleep(60000);
                }

                //tour.exit();
            });

            tour.start();
            tour.goToStepNumber(6);
        });
    </script>
@endsection
