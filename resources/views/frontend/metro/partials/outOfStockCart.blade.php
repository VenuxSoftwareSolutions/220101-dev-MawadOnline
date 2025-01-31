<div class="modal-body p-4 added-to-cart">
    <div class="text-center text-danger">
        {{-- <h2>{{translate('oops..')}}</h2> --}}
        {{-- <h3>{{translate('This item is out of stock!')}}</h3> --}}
        <h3 class="dark-c3 fs-24 font-prompt-md">
            {{ __('messages.item_out_of_stock') }}
        </h3>
        <svg width="64" height="64" class="mt-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M3.1709 7.43994L12.0009 12.5499L20.7709 7.46991" stroke="#DBDCDF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M12.001 21.61V12.54" stroke="#DBDCDF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M21.6106 9.17V14.83C21.6106 14.88 21.6106 14.92 21.6006 14.97C20.9006 14.36 20.0006 14 19.0006 14C18.0606 14 17.1906 14.33 16.5006 14.88C15.5806 15.61 15.0006 16.74 15.0006 18C15.0006 18.75 15.2106 19.46 15.5806 20.06C15.6706 20.22 15.7806 20.37 15.9006 20.51L14.0706 21.52C12.9306 22.16 11.0706 22.16 9.9306 21.52L4.59061 18.56C3.38061 17.89 2.39062 16.21 2.39062 14.83V9.17C2.39062 7.79 3.38061 6.11002 4.59061 5.44002L9.9306 2.48C11.0706 1.84 12.9306 1.84 14.0706 2.48L19.4106 5.44002C20.6206 6.11002 21.6106 7.79 21.6106 9.17Z" stroke="#DBDCDF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M23.001 18C23.001 18.75 22.791 19.46 22.421 20.06C22.211 20.42 21.941 20.74 21.631 21C20.931 21.63 20.011 22 19.001 22C17.541 22 16.271 21.22 15.581 20.06C15.211 19.46 15.001 18.75 15.001 18C15.001 16.74 15.581 15.61 16.501 14.88C17.191 14.33 18.061 14 19.001 14C21.211 14 23.001 15.79 23.001 18Z" stroke="#DBDCDF" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M20.0712 19.0399L17.9512 16.9299" stroke="#DBDCDF" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M20.0507 16.96L17.9307 19.0699" stroke="#DBDCDF" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>

    </div>
    <div class="text-center mt-4">
        <button class="btn btn-white cart-drop-btn-checkout text-secondary-base border-radius-12 fs-16 font-prompt py-2 w-210px" data-dismiss="modal">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M14.9998 19.92L8.47984 13.4C7.70984 12.63 7.70984 11.37 8.47984 10.6L14.9998 4.08002" stroke="#cb774b" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
            {{translate('Back to shopping')}}
        </button>
    </div>
</div>
