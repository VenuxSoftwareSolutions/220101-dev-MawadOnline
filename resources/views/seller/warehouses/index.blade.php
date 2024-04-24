@extends('seller.layouts.app')

@section('panel_content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ __('stock.Warehouses') }}</h1>
            </div>
        </div>
    </div>
    <div class="card">
        <form class="form-horizontal" id="add_inventory_record" action="{{ route('seller.warehouses.store') }}"
            method="POST">
            @csrf
            {{-- <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6">{{ __('stock.Add Inventory Record') }}</h5>
            </div>
        </div> --}}
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
                                    <!-- Options for area -->
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
                                <td><input value="{{ $warehouse->warehouse_name }}"
                                        type="text" class="form-control"
                                        name="warehouse_name[]" required>
                                    <input name="warehouse_id[]" type="hidden" value="{{ $warehouse->id }}">
                                    </td>
                                <td>

                                    <select required name="state_warehouse[]" class="form-control rounded-0 emirateSelect" id="emirateempire">
                                        <option value="" selected>{{ translate('please_choose') }}</option>
                                        @foreach ($emirates as $emirate)
                                            <option value="{{ $emirate->id }}" @if ($warehouse->emirate_id == $emirate->id) selected @endif>
                                                {{ $emirate->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                </td>
                                <td>
                                    <select class="form-control areaSelect"
                                        name="area_warehouse[]" required>
                                        @php
                                            $areas = App\Models\Area::where('emirate_id', $warehouse->emirate_id)->get();
                                        @endphp
                                        <option value="" selected>
                                            {{ translate('please_choose') }}</option>
                                        @foreach ($areas as $area)
                                            <option value="{{ $area->id }}"
                                                @if ($area->id == $warehouse->area_id) selected @endif>
                                                {{ $area->name }}</option>
                                        @endforeach

                                        <!-- Options for area -->
                                    </select>
                                </td>
                                <td><input type="text" class="form-control"
                                        value="{{ $warehouse->address_street }}"
                                        name="street_warehouse[]" required></td>
                                <td><input type="text" class="form-control"
                                        value="{{ $warehouse->address_building }}"
                                        name="building_warehouse[]" required></td>
                                <td><input type="text" class="form-control"
                                        value="{{ $warehouse->address_unit }}"
                                        name="unit_warehouse[]" required></td>
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
                <button type="submit" id="saveButton" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).on('change', '.emirateSelect', function() {

            // $('').on('change', function() {
            var emirateId = $(this).val();

            var areaSelect = $(this).closest('.warehouseRow').find('.areaSelect');

            // Make an AJAX call to get areas based on the selected emirate
            $.ajax({
                url: '{{ route('get.area', ['id' => ':id']) }}'.replace(':id', emirateId),
                method: 'GET',
                success: function(response) {
                    // Update the options in the area select
                    areaSelect.empty();
                    areaSelect.append(
                        '<option value="" selected>{{ translate('please_choose') }}</option>');

                    // Add options based on the response
                    // $.each(response, function(index, area) {
                    //     console.log(area)
                    //     areaSelect.append('<option value="' + area[0].id + '">' + area[0].name + '</option>');
                    // });
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

                            areaSelect.append(option);
                        }
                    }
                },
                error: function(error) {
                    console.error('Error fetching areas:', error);
                }
            });
        });
         // Add event listener to remove button
         $('#warehouseTable').on('click', '.removeRow', function() {
            var row = $(this).closest('tr');
            var warehouseId = row.data('warehouse-id'); // Assuming each row has a data attribute for warehouse ID
            if (typeof warehouseId !== 'undefined') {
                 // Show confirmation dialog
            Swal.fire({
                title: '{{ trans('messages.confirm_delete') }}',
                text: '{{ trans('messages.delete_warning') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '{{ trans('messages.confirm_button') }}',
                cancelButtonText: '{{ trans('messages.cancel_button') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send AJAX request to remove the warehouse
                    $.ajax({
                        url: '{{route('seller.warehouses.remove')}}', // Update with your backend route
                        method: 'POST',
                        data: {
                            warehouse_id: warehouseId,
                            _token: '{{ csrf_token() }}', // Include the CSRF token

                        },
                        success: function(response) {
                             // Check if the response contains an error
                            if (response.error) {
                                // Show error message
                                toastr.error(response.error);
                            } else {
                            // Remove the row from the table on success
                            row.remove();
                            Swal.fire(
                                '{{ trans('messages.delete_success') }}',
                                '{{ trans('messages.delete_success') }}',
                                'success'
                            );
                            }

                        },
                        error: function(error) {
                            console.error('Error removing warehouse:', error);
                            Swal.fire(
                                '{{ trans('messages.error') }}',
                                '{{ trans('messages.delete_error') }}',
                                'error'
                            );
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire(
                        '{{ trans('messages.cancelled') }}',
                        '{{ trans('messages.warehouse_safe') }}',
                        'error'
                    );
                }
            });
            } else {
                row.remove();
            }


        });
        $('#addRow').on('click', function() {
                var warehouseName = $('input[name="warehouse_name_add"]').val();
                var state = $('select[name="state_warehouse_add"]').val();
                var stateText = $('select[name="state_warehouse_add"] option:selected').text();
                var area = $('select[name="area_warehouse_add"]').val();
                var areaText = $('select[name="area_warehouse_add"] option:selected').text();
                var street = $('input[name="street_warehouse_add"]').val();
                var building = $('input[name="building_warehouse_add"]').val();
                var unit = $('input[name="unit_add"]').val();
                // Check if any input is empty
                if (!warehouseName || !state || !area || !street || !building || !unit) {
                    // Show toast with translated message
                    toastr.error('{{ translate('Please fill in all fields.') }}');
                    return; // Stop execution if any input is empty
                }
                const newRow = $('<tr>');

                // Create cells
                newRow.append(
                    '<td><input type="text" class="form-control" name="warehouse_name[]" value="' +
                    warehouseName + '" required></td>');
                newRow.append(
                    '<td><select required name="state_warehouse[]" class="form-control rounded-0 emirateSelect"><option value="' +
                    state + '" selected>' + stateText + '</option></select></td>');
                newRow.append(
                    '<td><select class="form-control areaSelect" name="area_warehouse[]" required><option value="' +
                    area + '" selected>' + areaText + '</option></select></td>');
                newRow.append(
                    '<td><input type="text" class="form-control" name="street_warehouse[]" value="' +
                    street + '" required></td>');
                newRow.append(
                    '<td><input type="text" class="form-control" name="building_warehouse[]" value="' +
                    building + '" required></td>');
                newRow.append(
                    '<td><input type="text" class="form-control" name="unit_warehouse[]" value="' +
                    unit + '" required></td>');
                newRow.append(
                    '<td><button type="button" class="btn btn-danger removeRow">Remove</button></td>');

                $('#warehouseTable tbody').append(newRow);

                // Clear input fields
                $('input[name="warehouse_name_add"]').val('');
                $('select[name="state_warehouse_add"]').val('');
                $('select[name="area_warehouse_add"]').val('');
                $('input[name="street_warehouse_add"]').val('');
                $('input[name="building_warehouse_add"]').val('');
                $('input[name="unit_add"]').val('');
            });
    </script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        if ({{Auth::user()->tour}} == true | {{Auth::user()->id}} != {{Auth::user()->owner_id}}) {
            return;
        }
        var tour_steps = [
            @foreach($tour_steps as $key => $step)
            {
                element: document.querySelector('#{{$step->element_id}}'),
                title: '{{$step->title}}',
                intro: "{{$step->description}}",
                position: 'right'
            },
            @endforeach
        ];

        let tour = introJs();
        let step_number = 0 ;
        tour.setOptions({
            steps: tour_steps ,
            doneLabel: 'Next', // Replace the "Done" button with "Next"
            exitOnEsc : false ,
            exitOnOverlayClick : false ,
            disableInteraction : true ,
            overlayOpacity : 0.4 ,
            showStepNumbers : true ,
            hidePrev : true ,
            showProgress :true ,
        });

        tour.onexit(function() {
            $.ajax({
                url: "{{ route('seller.tour') }}",
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' }, // Include CSRF token for Laravel
                success: function(response) {
                    // Handle success
                    console.log('User tour status updated successfully');
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error('Error updating user tour status:', error);
                }
            });
            setTimeout(function() {
                window.location.href = '{{ route("seller.dashboard") }}';
            }, 500);
        });

        tour.onbeforechange(function(targetElement) {
            step_number += 1 ;
            if (step_number == 3) {
            window.location.href = '{{ route("seller.stock.operation.report") }}';
            sleep(60000);
            }

            //tour.exit();
        });

    tour.start();
    tour.goToStepNumber(6);
    });
</script>
@endsection
