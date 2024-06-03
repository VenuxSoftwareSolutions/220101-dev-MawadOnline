@extends('seller.layouts.app')

@section('panel_content')
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ __('staff.Staff Information') }}</h5>
                </div>

                <form class="form-horizontal" action="{{ route('seller.staffs.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="name">{{ translate('First name') }}</label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="{{ translate('First name') }}" id="first_name"
                                    name="first_name" class="form-control" required value="{{ old('first_name') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="name">{{ translate('Last name') }}</label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="{{ translate('Last name') }}" id="last_name"
                                    name="last_name" class="form-control" required value="{{ old('last_name') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="email">{{ translate('Email') }}</label>
                            <div class="col-sm-9">
                                <input type="email" placeholder="{{ translate('Email') }}" id="email" name="email"
                                    class="form-control" required value="{{ old('email') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="mobile">{{ translate('Phone') }}</label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="{{ translate('Phone') }}" id="mobile" name="mobile"
                                    class="form-control" required value="{{ old('mobile') }}">
                            </div>
                        </div>
                        <div class="form-group row" id="role-selet">
                            <label class="col-sm-3 col-form-label" for="name">{{ translate('Roles') }}</label>
                            <div class="col-sm-9">
                                <select name="role_id[]" multiple required class="form-control aiz-selectpicker" onchange="checkRoleUsage()">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->getTranslation('name') }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{ translate('Save') }}</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    function checkRoleUsage() {
        // Get selected role IDs
        var selectedRoles = [];
        var selectedOptions = document.querySelector('[name="role_id[]"]').selectedOptions;

        for (var i = 0; i < selectedOptions.length; i++) {
            selectedRoles.push(selectedOptions[i].value);
        }

        // Make AJAX request
        $.ajax({
            url: "{{ route('seller.check.role') }}",
            type: 'GET',
            data: { roles: selectedRoles },
            success: function(response) {
                // Check if the role is used
                if (response.isUsed == 1) {
                    // Display warning message
                    var errorMessage = document.createElement('div');
                    errorMessage.classList.add('text-danger');
                    errorMessage.classList.add('role-error-message');
                    errorMessage.innerHTML = response.message;

                    var selectElement = document.querySelector('[id="role-selet"]');
                    // Check if error message already exists, if yes, remove it
                    var existingErrorMessage = document.querySelector('.role-error-message');
                    if (existingErrorMessage) {
                        existingErrorMessage.remove();
                    }
                    // Insert error message before select element
                    selectElement.parentNode.insertBefore(errorMessage, selectElement);
                } else {
                    // Remove error message if no error
                    var existingErrorMessage = document.querySelector('.role-error-message');
                    if (existingErrorMessage) {
                        existingErrorMessage.remove();
                    }
                }
            }
        });
    }

</script>
@endsection
