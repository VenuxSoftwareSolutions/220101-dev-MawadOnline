@props(['notifications', 'is_linkable' => false])


@forelse($notifications as $notification)
    <li class="list-group-item d-flex justify-content-between align-items- py-3">
        <div class="media text-inherit">
            <div class="media-body">
                <p class="mb-1 text-truncate-2">
                    @php $user_type = auth()->user()->user_type; @endphp

                    @if ($notification->type == 'App\Notifications\OrderNotification')
                        {{ translate('Order code: ') }}
                        @if ($is_linkable)
                            @php
                                if ($user_type == 'admin'){
                                    $route = route('all_orders.show', encrypt($notification->data['order_id']));
                                }
                                if ($user_type == 'seller'){
                                    $route = route('seller.orders.show', encrypt($notification->data['order_id']));
                                }
                            @endphp
                            <a href="{{ $route }}">
                        @endif

                        {{ $notification->data['order_code'] }}

                        @if ($is_linkable)
                            </a>
                        @endif

                        {{ translate(' has been ' . ucfirst(str_replace('_', ' ', $notification->data['status']))) }}

                    @elseif ($notification->type == 'App\Notifications\ShopVerificationNotification')
                        @if ($user_type == 'admin')
                            @if ($is_linkable)
                                <a href="{{ route('sellers.show_verification_request', $notification->data['id']) }}">
                            @endif
                            {{ $notification->data['name'] }}:
                            @if ($is_linkable)
                                </a>
                            @endif
                        @else
                            {{ translate('Your ') }}
                        @endif
                        {{ translate('verification request has been '.$notification->data['status']) }}
                    @elseif ($notification->type == 'App\Notifications\ShopProductNotification')
                        @php
                            $product_id     = $notification->data['id'];
                            $product_type   = $notification->data['type'];
                            $product_name   = $notification->data['name'];
                            $lang           = env('DEFAULT_LANGUAGE');

                            $route = $user_type == 'admin'
                                    ? ( $product_type == 'physical'
                                        ? route('products.seller.edit', ['id'=>$product_id, 'lang'=>$lang])
                                        : route('digitalproducts.edit', ['id'=>$product_id, 'lang'=>$lang] ))
                                    : ( $product_type == 'physical'
                                        ? route('seller.products.edit', ['id'=>$product_id, 'lang'=>$lang])
                                        : route('seller.digitalproducts.edit',  ['id'=>$product_id, 'lang'=>$lang] ));
                        @endphp

                        {{ translate('Product : ') }}
                        @if ($is_linkable)
                            <a href="{{ $route }}">{{ $product_name }}</a>
                        @else
                            {{ $product_name }}
                        @endif

                        {{ translate(' is').' '.$notification->data['status'] }}
                    @elseif ($notification->type == 'App\Notifications\PayoutNotification')
                        @php
                            $route = $user_type == 'admin'
                                    ? ( $notification->data['status'] == 'pending' ? route('withdraw_requests_all') : route('sellers.payment_histories'))
                                    : ( $notification->data['status'] == 'pending' ? route('seller.money_withdraw_requests.index') : route('seller.payments.index'));

                        @endphp

                         {{ $user_type == 'admin' ? $notification->data['name'].': ' : translate('Your') }}
                         @if ($is_linkable )
                             <a href="{{ $route }}">{{ translate('payment') }}</a>
                         @else
                             {{ translate('payment') }}
                         @endif
                         {{ single_price($notification->data['payment_amount']).' '.translate('is').' '.translate($notification->data['status']) }}

                    @elseif ($notification->type == 'App\Notifications\CustomStatusNotification' && isset($notification->data['message']))
                    <!-- Access notification data -->
                            @if ($notification->data['newStatus'] == 'Suspended')
                            {{ __('messages.suspended_notification', ['reason' =>$notification->data['suspendedTitle'] ?? ""]) }}
                            @elseif ($notification->data['newStatus'] == 'Pending Approval')
                            {{ __('messages.registration_completed_notif') }}
                            @elseif ($notification->data['newStatus'] == 'Closed')
                            {{ __('messages.vendor_closed') }}
                            @elseif ($notification->data['newStatus'] == 'Pending Closure')
                            {{ __('messages.pending_closure') }}
                            @elseif ($notification->data['newStatus'] == 'Enabled')
                            {{ __('messages.approved') }}
                            @elseif ($notification->data['newStatus'] == 'Rejected')
                            {{ __('messages.registration_rejected') }}
                        @endif
                        @elseif ($notification->type == 'App\Notifications\NewRegistrationNotification')
                        <!-- Access notification data -->
                        @if ($notification->data)
                        <p>{{ $notification->data['admin_message'] }}</p>
                        {{-- <p>User Name: {{ $notification->data['user_name'] }}</p>
                        <p>User Email: {{ $notification->data['user_email'] }}</p> --}}

                        @endif


                    @elseif ($notification->type == 'App\Notifications\VendorProfileChangesWebNotification')
                        <!-- Handle other notification types -->
                        <p>{{ $notification->data['message'] }}</p>
                        <p>User Name: {{ $notification->data['vendor_name'] }}</p>
                        <p>User Email: {{ $notification->data['email'] }}</p>
                        <p>
                            <a href="{{route('vendor.registration.view',$notification->data['vendor_id']) }}">View Vendor Profile</a>
                        </p>
                        @elseif ($notification->type == 'App\Notifications\ChangesApprovedNotification')
                        <!-- Handle other notification types -->

                        <p>{{ $notification->data['message'] }}</p>
                        @elseif ($notification->type == 'App\Notifications\ModificationRejectedNotification')
                        <!-- Handle other notification types -->

                        <p>{{ $notification->data['message'] }}</p>
                    @endif

                </p>
                <small class="text-muted">
                    {{ date('F j Y, g:i a', strtotime($notification->created_at)) }}
                </small>
            </div>
        </div>
    </li>
@empty
    <li class="list-group-item">
        <div class="py-4 text-center fs-16">
            {{ translate('No notification found') }}
        </div>
    </li>
@endforelse
