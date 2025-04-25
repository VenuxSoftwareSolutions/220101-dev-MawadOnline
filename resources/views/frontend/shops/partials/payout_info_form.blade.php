<form id="payoutInfoForm" class="" action="{{ route('shops.payout_info') }}"
data-next-tab="payout-info" method="POST">
@csrf
<!-- ... Payout Info form fields ... -->
<div class="bg-white border mb-4">
    <div class="fs-20 fw-600 p-3 orange-text">
        {{ translate('Bank Information') }}
    </div>
    {{-- <div id="validation-errors" class="alert alert-danger"
        style="display: none;"></div> --}}

    <div class="p-3">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label><b>{{ translate('Bank Name') }}</b> <span
                            class="text-primary">*</span></label>
                    <input value="{{ $user->payout_information->bank_name ?? '' }}"
                        type="text" class="form-control rounded-0"
                        placeholder="{{ translate('Bank Name') }}" name="bank_name"
                        required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label><b>{{ translate('Account Name') }} </b><span
                            class="text-primary">*</span></label>
                    <input
                        value="{{ $user->payout_information->account_name ?? '' }}"
                        type="text" class="form-control rounded-0"
                        placeholder="{{ translate('Account Name') }}"
                        name="account_name" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label><b>{{ translate('Account Number') }} </b><span
                            class="text-primary">*</span></label>
                    <input
                        value="{{ $user->payout_information->account_number ?? '' }}"
                        type="text" class="form-control rounded-0"
                        placeholder="{{ translate('Account Number') }}"
                        name="account_number" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label><b>{{ __('profile.IBAN') }}</b><span
                            class="text-primary">*</span></label>
                    <input value="{{ $user->payout_information->iban ?? '' }}"
                        type="text" class="form-control rounded-0"
                        placeholder="{{ __('profile.IBAN') }}" name="iban" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label><b>{{ __('profile.Swift Code') }}</b><span
                            class="text-primary">*</span></label>
                    <input value="{{ $user->payout_information->swift_code ?? '' }}"
                        type="text" class="form-control rounded-0"
                        placeholder="{{ __('profile.Swift Code') }}"
                        name="swift_code" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label><b>{{ __('profile.IBAN Certificate') }}</b><span
                            class="text-primary">*</span></label>
                    @if (isset($user) && isset($user->payout_information) && $user->payout_information->iban_certificate)
                        <a class="old_file"
                            href="{{ static_asset($user->payout_information->iban_certificate) }}"
                            target="_blank">{{ translate('View IBAN Certificate') }}</a>
                        <input type="hidden" name="iban_certificate_old"
                            value="{{ $user->payout_information->iban_certificate }}">
                    @endif
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file"
                                class="form-control custom-file-input"
                                id="iban_certificate_input" name="iban_certificate"
                                accept=".pdf,.jpeg,.jpg,.png,.webp">
                            <label class="custom-file-label"
                                for="iban_certificate_input">{{ translate('Choose a file') }}</label>
                        </div>
                    </div>

                    <small>{{ translate('max_file_size_is_5mb_and_accepted_file_types_are_pdf_and_image_formats') }}</small>
                </div>






            </div>
        </div>

    </div>
</div>


<div class="text-right">
    <!-- Accept Terms & Conditions Checkbox -->
    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" id="acceptTermsCheckbox"
            onchange="enableRegisterButton()">
        <label class="form-check-label" for="acceptTermsCheckbox">
            {{ __('profile.Accept') }} <a href="#" target="_blank"
                onclick="openTermsPage()">{{ __('profile.Terms & Conditions') }}</a>
        </label>
    </div>

    <!-- Previous Button -->
    <button type="button" data-prv='warehouses'
        class="btn btn-info fw-600 rounded-0 prv-tab" style="font-size: 14px;">
        {{ translate('Previous') }}
    </button>

    <!-- Save as Draft Button -->
    <button type="button" class="btn btn-secondary fw-600 rounded-0 save-as-draft"
        data-action="save-as-draft" style="font-size: 14px;">
        {{ translate('Save as Draft') }}
    </button>

    <!-- Register Your e-Shop Button (Initially Disabled) -->
    <button id="registerShop" type="submit" class="btn btn-primary fw-600 rounded-0"
        style="font-size: 14px;" disabled>
        {{ __('profile.Register your e-Shop') }}
    </button>
</div>

<script>
    // Function to enable/disable Register Your e-Shop button based on checkbox state
    function enableRegisterButton() {
        var checkbox = document.getElementById('acceptTermsCheckbox');
        var registerButton = document.getElementById('registerShop');
        registerButton.disabled = !checkbox.checked;
    }

    // Function to open terms and conditions page in new tab
    function openTermsPage() {
        // Replace 'YOUR_TERMS_URL_HERE' with the actual URL agreed upon with Amine
        var termsURL = '{{route('terms-and-conditions')}}';
        window.open(termsURL, '_blank');
    }
</script>

</form>