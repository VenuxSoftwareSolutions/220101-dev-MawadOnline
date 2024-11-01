<a href="{{ route('wishlists.index') }}" class="d-flex align-items-center text-dark" data-toggle="tooltip" data-title="{{ translate('Wishlist') }}" data-placement="top">
    <span class="position-relative d-inline-block">
        <svg width="28" height="28" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M17.337 27.8787C16.878 28.0404 16.122 28.0404 15.663 27.8787C11.748 26.5438 3 20.9753 3 11.5371C3 7.37079 6.3615 4 10.506 4C12.963 4 15.1365 5.18652 16.5 7.02023C17.8635 5.18652 20.0505 4 22.494 4C26.6385 4 30 7.37079 30 11.5371C30 20.9753 21.252 26.5438 17.337 27.8787Z" stroke="#F3F4F5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>

       <!-- @if(Auth::check() && count(Auth::user()->wishlists)>0)-->
            <span class="badge badge-primary badge-inline badge-pill absolute-top-right--10px">{{ count(Auth::user()->wishlists)}}</span>
      <!--  @endif -->
    </span>
</a>
