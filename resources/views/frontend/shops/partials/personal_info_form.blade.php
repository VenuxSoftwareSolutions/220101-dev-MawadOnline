<form id="shop" class="" action="{{ route('shops.store') }}" method="POST" enctype="multipart/form-data"
    data-next-tab="code_verification">
    @csrf
    <div class="bg-white border mb-4">
        <div class="fs-20 fw-600 p-3 orange-text">
            {{ translate('Personal Info') }}
        </div>

       
        <div class="p-3">
            <div class="form-group">
                <label><b>{{ translate('First Name') }} </b><span class="text-primary">*</span></label>
                <input type="text" class="form-control rounded-0"
                    value="{{ auth()->check() ? auth()->user()->first_name : old('first_name') }}" id="first_name"
                    placeholder="{{ translate('First Name') }}" name="first_name" required>

            </div>
            <div class="form-group">
                <label><b>{{ translate('Last name') }}</b> <span class="text-primary">*</span></label>
                <input type="text" class="form-control rounded-0" id="last_name"
                    value="{{ auth()->check() ? auth()->user()->last_name : old('last_name') }}"
                    placeholder="{{ translate('Last name') }}" name="last_name" required>
            </div>
            <div class="form-group">
                <label><b>{{ translate('Your Email') }} </b><span class="text-primary">*</span></label>
                <input id="email" type="email" class="form-control rounded-0"
                    value="{{ auth()->check() ? auth()->user()->email : '' }}" placeholder="{{ translate('Email') }}"
                    name="email" required>
                <div style="color: red;">
                    Email cannot be changed after the account is created
                </div>

            </div>
            <div class="form-group ">
                <label for="password">
                    <b>{{ translate('Password') }} <span class="text-primary">*</span></b>
                </label>
                <div class="position-relative">
                    <input type="password" id="password" name="password" class="form-control rounded-0"
                        autocomplete="off" required placeholder="{{ translate('Password') }}">
                    <i class="password-toggle las la-2x la-eye"></i>
                </div>

                <div id="password-strength" class="mt-2">
                    <div class="progress" style="height: 8px;">
                        <div id="strength-bar" class="progress-bar" role="progressbar" style="width: 0%;"
                            aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>

                    <div id="dict-loader" class="small text-muted mt-1">
                        <i class="las la-spinner la-pulse"></i> Loading dictionary…
                    </div>

                    <ul id="password-criteria" class="row list-unstyled mt-2" style="padding-left: 0">
                        <li class="col-6 mb-1" data-rule="length">
                            <span class="text-danger">✘</span> Minimum 8 characters
                        </li>
                        {{-- <li class="col-6 mb-1" data-rule="uppercase">
                            <span class="text-danger">✘</span> At least one uppercase
                        </li>
                        <li class="col-6 mb-1" data-rule="lowercase">
                            <span class="text-danger">✘</span> At least one lowercase
                        </li>
                        <li class="col-6 mb-1" data-rule="number">
                            <span class="text-danger">✘</span> At least one number
                        </li>
                        <li class="col-6 mb-1" data-rule="special">
                            <span class="text-danger">✘</span> At least one special
                        </li> --}}
                        <li class="col-6 mb-1" data-rule="allowedChars">
                            <span class="text-danger">✘</span> Only letters, numbers,
                            signs
                        </li>
                        <li class="col-6 mb-1" data-rule="maxNumbers">
                            <span class="text-danger">✘</span> No more than 3 numbers
                        </li>

                        <li class="col-6 mb-1" data-rule="noSeqNum">
                            <span class="text-danger">✘</span> No 3 consecutive numbers
                        </li>
                        <li class="col-6 mb-1" data-rule="noSeqChar">
                            <span class="text-danger">✘</span> No 3 consecutive letters
                        </li>
                        <li class="col-6 mb-1" data-rule="maxCategory">
                            <span class="text-danger">✘</span> No letter, number, or
                            symbol may appear more than three times.
                        </li>
                        <li class="col-6 mb-1" data-rule="noNameEmail">
                            <span class="text-danger">✘</span> No part of name, email or
                            personal information.
                        </li>
                        <li class="col-6 mb-1" data-rule="noDict">
                            <span class="text-danger">✘</span> No vocabularies.
                        </li>
                    </ul>
                </div>
            </div>
            <div class="form-group">
                <label for="password_confirmation"><b>{{ translate('Repeat Password') }}</b>
                    <span class="text-primary">*</span></label>
                <div class="position-relative">
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="form-control rounded-0" required placeholder="{{ translate('Confirm Password') }}">
                    <i class="password-toggle las la-2x la-eye" data-target="#password_confirmation"></i>
                </div>

            </div>
        </div>
    </div>
    <div class="text-right">
        <button type="button" data-action="register"
            class="btn btn-primary fw-600 rounded-0">{{ translate('Next') }}</button>
    </div>
</form>