<div class="aiz-sidebar-wrap">
    <div class="aiz-sidebar left c-scrollbar">
        <div class="aiz-side-nav-logo-wrap">
            <div class="d-block text-center my-3">
                {{-- @php
                    $vendor = \App\Models\User::find(Auth::user()->owner_id);
                @endphp
                @if (optional($vendor->shop)->logo != null)
                    <img class="mw-100 mb-3" src="{{ uploaded_asset(optional($vendor->shop)->logo) }}"
                        class="brand-icon" alt="{{ get_setting('site_name') }}">
                @else
                    <img class="mw-100 mb-3" src="{{ uploaded_asset(get_setting('header_logo')) }}" class="brand-icon"
                        alt="{{ get_setting('site_name') }}">
                @endif
                <h3 class="fs-16  m-0 text-primary">{{ optional($vendor->shop)->name }}</h3>
                <p class="text-primary">{{ $vendor->email }}</p> --}}
            </div>
        </div>
        <div class="aiz-side-nav-wrap">
            <div class="ripple d-flex justify-content-center py-1">
                <img class="top-logo" style="width: 140px;height: 40px" src="{{ asset('public/images/vendor_logo.svg') }} "
                    class="brand-icon" alt="{{ get_setting('site_name') }}">
            </div>
            <div class="ripple d-flex justify-content-center py-2 pt-1">
                <span>{{ Auth::user()->email }}</span>
            </div>
            <div class="px-20px mb-3">
                <input class="form-control bg-soft-secondary border-0 form-control-sm" type="text" name=""
                    placeholder="{{ translate('Search in menu') }}" id="menu-search" onkeyup="menuSearch()">
            </div>
            <ul class="aiz-side-nav-list" id="search-menu">
            </ul>
            <ul class="aiz-side-nav-list" id="main-menu" data-toggle="aiz-side-menu">
                <li id="AddProduct" class="aiz-side-nav-item changes-button">
                    <a href="{{ route('seller.products.create')}}" class="btn btn-primary d-flex align-items-center">
                        <i class="las la-plus-square aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text ml-2">{{ __('sidenav.Add_Product') }}</span>
                    </a>
                </li>

                <li id="dashboard" class="aiz-side-nav-item">
                    <a href="{{ route('seller.dashboard') }}" class="aiz-side-nav-link">
                        <i class="las la-home aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Dashboard') }}</span>
                    </a>
                </li>

                @canany(['seller_show_product', 'seller_view_product_reviews'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-shopping-cart aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ __('sidenav.product_management') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <!--Submenu-->
                        <ul class="aiz-side-nav-list level-2">
                            @can('seller_show_product')
                                <li id="products" class="aiz-side-nav-item">
                                    <a href="{{ route('seller.products') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['seller.products', 'seller.products.create', 'seller.products.edit']) }}">
                                        <span class="aiz-side-nav-text">{{ __('sidenav.Products_list') }}</span>
                                    </a>
                                </li>
                            @endcan
                            <li id="catalog" class="aiz-side-nav-item">
                                <a href="{{ route('catalog.search_page') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['catalog.search_page']) }}">
                                    <span class="aiz-side-nav-text">{{ __('sidenav.mawad_catalogue') }}</span>
                                </a>
                            </li>

                            {{-- <li id="catalog" class="aiz-side-nav-item">
                                <a href="{{ route('seller.product_bulk_upload.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes([ 'seller.product_bulk_upload.index']) }}">
                                    <span class="aiz-side-nav-text">{{ __('sidenav.bulk_upload') }}</span>
                                </a>
                            </li> --}}
                            {{-- @can('seller_product_bulk_import')
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('seller.product_bulk_upload.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['product_bulk_upload.index']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Product Bulk Upload') }}</span>
                                </a>
                            </li>
                            @endcan --}}
                            {{-- @can('seller_show_digital_products')
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('seller.digitalproducts') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['seller.digitalproducts', 'seller.digitalproducts.create', 'seller.digitalproducts.edit']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Digital Products') }}</span>
                                </a>
                            </li>
                            @endcan --}}
                            @can('seller_view_product_reviews')
                                <li id="reviews" class="aiz-side-nav-item">
                                    <a href="{{ route('seller.reviews') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['seller.reviews']) }}">
                                        <span class="aiz-side-nav-text">{{ __('sidenav.Product_Reviews') }}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
                {{-- <li id="catalog" class="aiz-side-nav-item">
                    <a href="{{ route('catalog.search_page') }}"
                        class="aiz-side-nav-link {{ areActiveRoutes(['catalog.search_page']) }}">
                        <i class="las la-folder-open aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('MawadCatalog search page') }}</span>
                    </a>
                </li> --}}
                @canany(['seller_add_inventory', 'seller_inventory_history'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-warehouse fa-2xs aiz-side-nav-icon	"></i>
                            <span class="aiz-side-nav-text">{{ __('stock.Inventory Management') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <!--Submenu-->

                        <ul class="aiz-side-nav-list level-2">
                            @can('seller_add_inventory')
                                <li id="stock" class="aiz-side-nav-item">
                                    <a href="{{ route('seller.stocks.index') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{ __('stock.Add/Remove Stock') }}</span>
                                    </a>
                                </li>
                            @endcan
                            <li class="aiz-side-nav-item">
                                <a id="warehouses" href="{{ route('seller.warehouses.index') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{ __('stock.Warehouses') }}</span>
                                </a>
                            </li>
                            @can('seller_inventory_history')
                                <li id="stock_details" class="aiz-side-nav-item">
                                    <a href="{{ route('seller.stock.operation.report') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['seller.stock.search']) }}">
                                        <span class="aiz-side-nav-text">{{ __('stock.Stock Operation Details') }}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>

                    </li>
                @endcanany
                {{-- <li class="aiz-side-nav-item">
                    <a href="{{ route('seller.uploaded-files.index') }}"
                        class="aiz-side-nav-link {{ areActiveRoutes(['seller.uploaded-files.index', 'seller.uploads.create']) }}">
                        <i class="las la-folder-open aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Uploaded Files') }}</span>
                    </a>
                </li> --}}
                @can('seller_view_all_orders')
                    <li id="order" class="aiz-side-nav-item">
                        <a href="{{ route('seller.orders.index') }}"
                            class="aiz-side-nav-link {{ areActiveRoutes(['seller.orders.index', 'seller.orders.show']) }}">
                            <i class="las la-money-bill aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ __('sidenav.Orders Management') }}</span>
                        </a>
                    </li>
                @endcan
                @if (addon_is_activated('seller_subscription'))
                    {{-- @canany(['seller_view_package_list', 'seller_view_all_packages'])
                        <li class="aiz-side-nav-item">
                            <a href="#" class="aiz-side-nav-link">
                                <i class="las la-shopping-cart aiz-side-nav-icon"></i>
                                <span class="aiz-side-nav-text">{{ translate('e-Shop Package') }}</span>
                                <span class="aiz-side-nav-arrow"></span>
                            </a>
                            <ul class="aiz-side-nav-list level-2"> --}}
                    @can('seller_view_package_list')
                        <li id="packages" class="aiz-side-nav-item">
                            <a href="{{ route('seller.seller_packages_list') }}" class="aiz-side-nav-link">
                                <i class="las la-shopping-cart aiz-side-nav-icon"></i>
                                <span class="aiz-side-nav-text">{{ __('sidenav.Packages') }}</span>
                            </a>
                        </li>
                    @endcan
                    {{-- @can('seller_view_all_packages')
                                <li id="package_list" class="aiz-side-nav-item">
                                    <a href="{{ route('seller.packages_payment_list') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{ translate('Purchase Packages') }}</span>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany --}}
                @endif

                {{-- @can('seller_view_all_staffs')
                    <li id="staff" class="aiz-side-nav-item">
                        <a href="{{ route('seller.staffs.index') }}"
                            class="aiz-side-nav-link {{ areActiveRoutes(['seller.staffs.index', 'seller.staffs.create', 'seller.staffs.edit']) }}">
                            <i class="las la-users aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Staffs') }}</span>
                        </a>
                    </li>
                @endcan --}}

                {{-- @canany(['seller_view_staff_roles', 'seller_view_all_staffs'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-shopping-cart aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Staffs') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <!--Submenu-->
                        <ul class="aiz-side-nav-list level-2">
                            @can('seller_view_all_staffs')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('seller.staffs.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['seller.staffs.index', 'seller.staffs.create', 'seller.staffs.edit'])}}">
                                        <span class="aiz-side-nav-text">{{ translate('All staffs') }}</span>
                                    </a>
                                </li>
                            @endcan

                            @can('seller_view_staff_roles')
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('seller.roles.index')}}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['seller.roles.index', 'seller.roles.create', 'seller.roles.edit'])}}">
                                        <span class="aiz-side-nav-text">{{ translate('Staff permissions') }}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany --}}
                @canany(['seller_view_all_leases', 'seller_view_all_sales'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-shopping-cart aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ __('staff.Billing') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <!--Submenu-->
                        <ul class="aiz-side-nav-list level-2">
                            @can('seller_view_all_leases')
                                <li id="lease" class="aiz-side-nav-item">
                                    <a href="{{ route('seller.lease.index') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['seller.lease.index']) }}">
                                        <span class="aiz-side-nav-text">{{ __('staff.e-Shop lease') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('seller_view_all_sales')
                                <li id="sales" class="aiz-side-nav-item">
                                    <a href="{{ route('seller.sales.index') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['seller.sales.index']) }}">
                                        <span class="aiz-side-nav-text">{{ __('staff.Sales') }}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                @php
                    $support_ticket = DB::table('tickets')
                        ->where('client_viewed', 0)
                        ->where('user_id', Auth::user()->owner_id)
                        ->count();
                @endphp
                @can('seller_view_support_tickets')
                    <li id="support_tickets" class="aiz-side-nav-item">
                        <a href="{{ route('seller.support_ticket.index') }}"
                            class="aiz-side-nav-link {{ areActiveRoutes(['seller.support_ticket.index']) }}">
                            <i class="las la-atom aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ __('sidenav.Contact_us') }}</span>
                            @if ($support_ticket > 0)
                                <span class="badge badge-inline badge-success">{{ $support_ticket }}</span>
                            @endif
                        </a>
                    </li>
                @endcan

                <li id="setting" id="profile" class="aiz-side-nav-item">
                    <a href="{{ route('seller.profile.index') }}" class="aiz-side-nav-link">
                        <i class="las la-user aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ __('profile.e_shop_profile') }}</span>
                    </a>
                </li>

                <li id="help" id="help_centre" class="aiz-side-nav-item">
                    <a href="{{ route('seller.help-center.index') }}" class="aiz-side-nav-link">
                        <div class="svg-change" style="width: 30px;
                            height: 22px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-exclamation-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                <path
                                    d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z" />
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text">{{ __('help.Help_center') }}</span>
                    </a>
                </li>

                <li id="startTourButton" class="aiz-side-nav-item changes-button">
                    <a href="javascript:void(0);" class="btn btn-primary d-flex align-items-center">
                        <div class="svg-change d-flex align-items-center" style="width: 30px; height: 22px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-rocket-takeoff" viewBox="0 0 16 16">
                                <path d="M9.752 6.193c.599.6 1.73.437 2.528-.362s.96-1.932.362-2.531c-.599-.6-1.73-.438-2.528.361-.798.8-.96 1.933-.362 2.532"/>
                                <path d="M15.811 3.312c-.363 1.534-1.334 3.626-3.64 6.218l-.24 2.408a2.56 2.56 0 0 1-.732 1.526L8.817 15.85a.51.51 0 0 1-.867-.434l.27-1.899c.04-.28-.013-.593-.131-.956a9 9 0 0 0-.249-.657l-.082-.202c-.815-.197-1.578-.662-2.191-1.277-.614-.615-1.079-1.379-1.275-2.195l-.203-.083a10 10 0 0 0-.655-.248c-.363-.119-.675-.172-.955-.132l-1.896.27A.51.51 0 0 1 .15 7.17l2.382-2.386c.41-.41.947-.67 1.524-.734h.006l2.4-.238C9.005 1.55 11.087.582 12.623.208c.89-.217 1.59-.232 2.08-.188.244.023.435.06.57.093q.1.026.16.045c.184.06.279.13.351.295l.029.073a3.5 3.5 0 0 1 .157.721c.055.485.051 1.178-.159 2.065m-4.828 7.475.04-.04-.107 1.081a1.54 1.54 0 0 1-.44.913l-1.298 1.3.054-.38c.072-.506-.034-.993-.172-1.418a9 9 0 0 0-.164-.45c.738-.065 1.462-.38 2.087-1.006M5.205 5c-.625.626-.94 1.351-1.004 2.09a9 9 0 0 0-.45-.164c-.424-.138-.91-.244-1.416-.172l-.38.054 1.3-1.3c.245-.246.566-.401.91-.44l1.08-.107zm9.406-3.961c-.38-.034-.967-.027-1.746.163-1.558.38-3.917 1.496-6.937 4.521-.62.62-.799 1.34-.687 2.051.107.676.483 1.362 1.048 1.928.564.565 1.25.941 1.924 1.049.71.112 1.429-.067 2.048-.688 3.079-3.083 4.192-5.444 4.556-6.987.183-.771.18-1.345.138-1.713a3 3 0 0 0-.045-.283 3 3 0 0 0-.3-.041Z"/>
                                <path d="M7.009 12.139a7.6 7.6 0 0 1-1.804-1.352A7.6 7.6 0 0 1 3.794 8.86c-1.102.992-1.965 5.054-1.839 5.18.125.126 3.936-.896 5.054-1.902Z"/>
                              </svg>

                        </div>
                        <span class="aiz-side-nav-text ml-2">{{ __('dashboard.Start Tour') }}</span>
                    </a>
                </li>


                {{-- @can('seller_shop_settings')
                    <li id="setting" class="aiz-side-nav-item">
                        <a href="{{ route('seller.shop.index') }}"
                            class="aiz-side-nav-link {{ areActiveRoutes(['seller.shop.index']) }}">
                            <i class="las la-cog aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Shop Setting') }}</span>
                        </a>
                    </li>
                @endcan --}}

                {{-- @can('seller_shop_payment_history')
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('seller.payments.index') }}"
                            class="aiz-side-nav-link {{ areActiveRoutes(['seller.payments.index']) }}">
                            <i class="las la-history aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Payment History') }}</span>
                        </a>
                    </li>
                @endcan --}}

                {{-- @can('seller_view_withdraw_requests')
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('seller.money_withdraw_requests.index') }}"
                            class="aiz-side-nav-link {{ areActiveRoutes(['seller.money_withdraw_requests.index']) }}">
                            <i class="las la-money-bill-wave-alt aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Money Withdraw') }}</span>
                        </a>
                    </li>
                @endcan --}}

                {{-- @can('seller_view_commission_history')
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('seller.commission-history.index') }}" class="aiz-side-nav-link">
                            <i class="las la-file-alt aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Commission History') }}</span>
                        </a>
                    </li>
                @endcan --}}

                {{-- @if (get_setting('conversation_system') == 1)
                    @php
                        $conversation = \App\Models\Conversation::where('sender_id', Auth::user()->owner_id)
                            ->where('sender_viewed', 0)
                            ->get();
                    @endphp
                    @can('seller_view_conversations')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('seller.conversations.index') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['seller.conversations.index', 'seller.conversations.show']) }}">
                                <i class="las la-comment aiz-side-nav-icon"></i>
                                <span class="aiz-side-nav-text">{{ translate('Conversations') }}</span>
                                @if (count($conversation) > 0)
                                    <span class="badge badge-success">({{ count($conversation) }})</span>
                                @endif
                            </a>
                        </li>
                    @endcan
                @endif --}}

                {{-- @if (get_setting('product_query_activation') == 1)
                    @can('seller_view_product_query')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('seller.product_query.index') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['seller.product_query.index']) }}">
                                <i class="las la-question-circle aiz-side-nav-icon"></i>
                                <span class="aiz-side-nav-text">{{ translate('Product Queries') }}</span>

                            </a>
                        </li>
                    @endcan
                @endif --}}
                {{-- @if (get_setting('coupon_system') == 1)
                    @can('seller_view_all_coupons')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('seller.coupon.index') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['seller.coupon.index', 'seller.coupon.create', 'seller.coupon.edit']) }}">
                                <i class="las la-bullhorn aiz-side-nav-icon"></i>
                                <span class="aiz-side-nav-text">{{ translate('Coupon') }}</span>
                            </a>
                        </li>
                    @endcan
                @endif --}}
                {{-- @if (addon_is_activated('wholesale') && get_setting('seller_wholesale_product') == 1)
                    @can('seller_view_all_wholesale_products')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('seller.wholesale_products_list') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['wholesale_product_create.seller', 'wholesale_product_edit.seller']) }}">
                                <i class="las la-luggage-cart aiz-side-nav-icon"></i>
                                <span class="aiz-side-nav-text">{{ translate('Wholesale Products') }}</span>
                            </a>
                        </li>
                    @endcan
                @endif --}}
                {{-- @if (addon_is_activated('auction') && get_setting('seller_auction_product') == 1)
                    <li class="aiz-side-nav-item">
                        <a href="javascript:void(0);" class="aiz-side-nav-link">
                            <i class="las la-gavel aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Auction') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('auction_products.seller.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['auction_products.seller.index', 'auction_product_create.seller', 'auction_product_edit.seller', 'product_bids.seller']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('All Auction Products') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('auction_products_orders.seller') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['auction_products_orders.seller']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Auction Product Orders') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif --}}

                {{-- @if (addon_is_activated('pos_system') && get_setting('pos_activation_for_seller') != null && get_setting('pos_activation_for_seller') != 0)
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-tasks aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('POS System') }}</span>
                            @if (env('DEMO_MODE') == 'On')
                                <span class="badge badge-inline badge-danger">Addon</span>
                            @endif
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('poin-of-sales.seller_index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['poin-of-sales.seller_index']) }}">
                                    <i class="las la-fax aiz-side-nav-icon"></i>
                                    <span class="aiz-side-nav-text">{{ translate('POS Manager') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('pos.configuration') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{ translate('POS Configuration') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif --}}

                {{-- @if (addon_is_activated('refund_request'))
                    @can('seller_view_all_refund_request')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('seller.vendor_refund_request') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['seller.vendor_refund_request', 'reason_show']) }}">
                                <i class="las la-backward aiz-side-nav-icon"></i>
                                <span class="aiz-side-nav-text">{{ translate('Received Refund Request') }}</span>
                            </a>
                        </li>
                    @endcan
                @endif --}}



            </ul><!-- .aiz-side-nav -->
        </div><!-- .aiz-side-nav-wrap -->
    </div><!-- .aiz-sidebar -->
    <div class="aiz-sidebar-overlay"></div>
</div><!-- .aiz-sidebar -->
