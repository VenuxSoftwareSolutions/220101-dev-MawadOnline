<form id="warehousesForm" class="" action="{{ route('shops.warehouses') }}"
data-next-tab="payout-info" method="POST">
@csrf
<!-- ... Warehouses form fields ... -->
<div class="bg-white border mb-4">

    <div class="fs-20 fw-600 p-3 orange-text">
        {{ __('profile.location_information') }}
    </div>

    <div class="p-3">

        <div class="row warehouseRow" id="warehouseRows">
            <div class="col-md-6">
                <div class="form-group">
                    <label
                        for="warehouse_name"><b>{{ translate('Warehouse Name') }}</b><span
                            class="text-primary">*</span></label>
                    <input type="text" class="form-control"
                        placeholder="{{ translate('Warehouse Name') }}"
                        name="warehouse_name_add">

                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="state"><b>{{ translate('State/Emirate') }}</b><span
                            class="text-primary">*</span></label>
                    <select name="state_warehouse_add"
                        class="form-control rounded-0 emirateSelect"
                        id="emirateempire">
                        <option value="" selected>{{ translate('please_choose') }}
                        </option>
                        @foreach ($emirates as $emirate)
                            <option value="{{ $emirate->id }}">{{ $emirate->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="area"><b>{{ translate('Area') }}</b><span
                            class="text-primary">*</span></label>
                    <select name="area_warehouse_add"
                        class="form-control areaSelect">
                        <option value="" selected>
                            {{ translate('please_choose') }}
                        </option>
                        <!-- Options for area -->
                    </select>

                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="street"><b>{{ translate('Street') }}</b><span
                            class="text-primary">*</span></label>
                    <input type="text" class="form-control"
                        placeholder="{{ translate('Street') }}"
                        name="street_warehouse_add">
                    <small
                        class="text-muted">{{ translate('Example: 123 Main Street') }}</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="building"><b>{{ translate('Building') }}</b><span
                            class="text-primary">*</span></label>
                    <input type="text" class="form-control" id="building_warehouse_add" placeholder="{{ translate('Building') }}" name="building_warehouse_add">
                        
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="unit"><b>{{ translate('Unit/Office No.') }}</b><span
                            class="text-primary"></span></label>
                    <input type="text" class="form-control"
                        placeholder="{{ translate('Unit/Office No.') }}"
                        name="unit_add">
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
                @if (isset($user))
                    @foreach ($user->warehouses as $warehouse)
                        <tr class="warehouseRow"> </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
<div class="text-right">
    <!-- Previous Button -->
    <button type="button" data-prv='contact-person'
        class="btn btn-info fw-600 rounded-0 prv-tab">
        {{ translate('Previous') }}
    </button>

    <button type="button" class="btn btn-secondary fw-600 rounded-0 save-as-draft"
        data-action="save-as-draft">{{ translate('Save as Draft') }}</button>

    <button type="button" class="btn btn-primary fw-600 rounded-0" {{--
        onclick="switchTab('payout-info')"
        --}}>{{ translate('Save and Continue') }}</button>
</div>
</form>