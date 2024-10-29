<div class="modal-body p-4 added-to-cart">
    <div class="text-center text-danger">
        {{-- <h2>{{translate('oops..')}}</h2> --}}
        {{-- <h3>{{translate('This item is out of stock!')}}</h3> --}}
        <h3 style="color: #ca764a; margin: 0;">
            {{ __('messages.item_out_of_stock') }}
        </h3>
    </div>
    <div class="text-center mt-5">
        <button class="btn btn-outline-primary" data-dismiss="modal">{{translate('Back to shopping')}}</button>
    </div>
</div>
