<form id="codeVerificationForm" class="" action="{{ route('verify.code') }}" method="POST"
    data-next-tab="business_info">
    @csrf
    <div class="bg-white border mb-4">
        <div class="fs-15 fw-600 p-3">
        </div>
        <div class="p-3">

            <!-- ... Code Verification form fields ... -->
            <div class="form-group">
                <label><b>{{ translate('Verification Code') }}</b> <span class="text-primary">*</span></label>
                <input type="hidden" value="{{ $user->email ?? '' }}" name="email" id="emailAccount">
                <input type="text" class="form-control rounded-0" placeholder="{{ translate('Enter Code') }}"
                    name="verification_code" required maxlength="6" pattern="[0-9]{6}">
                <small class="text-muted">{{ translate('a_6digit_code') }}</small>
            </div>
        </div>
    </div>
    <div class="text-right">
        <!-- Previous Button -->
        <button type="button" data-prv='personal-info' class="btn btn-info fw-600 rounded-0 prv-tab">
            {{ translate('previous') }}
        </button>
        @if (!Auth::user() || (Auth::user()->owner_id == null || Auth::user()->owner_id == Auth::user()->id))
            <button id="verifyCodeBtn" type="button"
                class="btn btn-primary fw-600 rounded-0">{{ translate('Next') }}</button>
        @else
            <button type="submit" class="btn btn-primary fw-600 rounded-0">{{ translate('Finish') }}</button>
        @endif

        <button id="resendCodeBtn" type="button"
            class="btn btn-secondary fw-600 rounded-0">{{ translate('Resend Code') }}</button>

    </div>
</form>