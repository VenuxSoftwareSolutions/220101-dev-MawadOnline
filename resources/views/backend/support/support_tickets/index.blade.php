@extends('backend.layouts.app')

@section('content')
    <div class="card">
        <form id="sort_support" method="GET">
            <div class="card-header row gutters-5">
                <div class="col text-center text-md-left">
                    <h5 class="mb-md-0 h6">{{ translate('Support Desk') }}</h5>
                </div>
                <div class="d-flex justify-content-between" style="gap: 10px">
                    <div class="input-group input-group-sm">
                        <select class="select2 form-control" multiple="multiple" id="search_status" name="search_status[]">
                            <option value="pending" selected>{{ __('Pending') }}</option>
                            <option value="solved">{{ __('Solved') }}</option>
                        </select>
                    </div>
                    <div class="input-group input-group-sm">
                        <select class="form-control select2" id="search_sub_order_status" name="search_sub_order_status[]"
                            multiple="multiple">
                            <option value="pending" @if ($search_sub_order_status === 'pending') selected @endif>{{ __('Pending') }}
                            </option>
                            <option value="in_preparation" @if ($search_sub_order_status === 'in_preparation') selected @endif>
                                {{ __('In preparation') }}
                            </option>
                            <option value="ready_for_shipment" @if ($search_sub_order_status === 'ready_for_shipment') selected @endif>
                                {{ __('Ready for shipment') }}
                            </option>
                            <option value="on_the_way" @if ($search_sub_order_status === 'on_the_way') selected @endif>
                                {{ __('On the way') }}
                            </option>
                            <option value="delivered" @if ($search_sub_order_status === 'delivered') selected @endif>
                                {{ __('Delivered') }}
                            </option>
                            <option value="cancelled" @if ($search_sub_order_status === 'cancelled') selected @endif>
                                {{ __('Canceled') }}
                            </option>
                        </select>
                    </div>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" id="search" name="search"
                            value="{{ old('search', request()->search) }}"
                            placeholder="{{ translate('Type & press Enter') }}">
                    </div>
                </div>
            </div>
        </form>

        <div class="card-body">
            @if ($errors->any())
                <div class="col alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <table class="aiz-table" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th data-breakpoints="lg">{{ translate('Ticket ID') }}</th>
                        <th data-breakpoints="lg">{{ translate('Sending Date') }}</th>
                        <th>{{ translate('Subject') }}</th>
                        <th>{{ translate('Status') }}</th>
                        <th data-breakpoints="lg">{{ translate('Buyer') }}</th>
                        <th data-breakpoints="lg">{{ translate('Buyer email') }}</th>
                        <th data-breakpoints="lg">{{ translate('Vendor') }}</th>
                        <th data-breakpoints="lg">{{ translate('Vendor Business name') }}</th>
                        <th data-breakpoints="lg">{{ translate('Sub-order status') }}</th>
                        <th data-breakpoints="lg">{{ translate('Order ID') }}</th>
                        <th data-breakpoints="lg">{{ translate('Product variant') }}</th>
                        <th class="text-right">{{ translate('Options') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tickets as $key => $ticket)
                        @if ($ticket->user != null)
                            <tr>
                                <td>#{{ $ticket->code }}</td>
                                <td>{{ $ticket->created_at }} @if ($ticket->viewed == 0)
                                        <span class="badge badge-inline badge-info">{{ translate('New') }}</span>
                                    @endif
                                </td>
                                <td>{{ $ticket->subject }}</td>
                                <td>
                                    @if ($ticket->status == 'pending')
                                        <span class="badge badge-inline badge-danger">{{ translate('Pending') }}</span>
                                    @elseif ($ticket->status == 'open')
                                        <span class="badge badge-inline badge-secondary">{{ translate('Open') }}</span>
                                    @else
                                        <span class="badge badge-inline badge-success">{{ translate('Solved') }}</span>
                                    @endif
                                </td>
                                <td>{{ $ticket->user->name }}</td>
                                <td>{{ $ticket->user->email }}</td>
                                <td>{{ $ticket->getVendor()->name }}</td>
                                <td>{{ $ticket->getVendor()->shop->name }}</td>
                                <td>
                                    <span
                                        class="badge badge-inline badge-info">{{ translate(ucfirst(str_replace('_', ' ', $ticket->orderDetails()->first()->delivery_status))) }}</span>
                                </td>
                                <td>#{{ $ticket->orderDetails()->first()->order->id }}</td>
                                <td>{{ $ticket->orderDetails()->first()->product->name }}</td>
                                <td class="text-right">
                                    <a href="{{ route('support_ticket.admin_show', encrypt($ticket->id)) }}"
                                        class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                        title="{{ translate('View Details') }}">
                                        <i class="las la-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            <div class="clearfix">
                <div class="pull-right">
                    {{ $tickets->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('#search_status').select2({
                placeholder: "{{ __('Choose ticket status') }}"
            });

            $('#search_sub_order_status').select2({
                placeholder: "{{ __('Choose sub-order status') }}"
            });

            @if (is_null($search_status) === false && is_string($search_status) === false)
                const selectedOptions = @json($search_status->toArray());
                $('#search_status').val(selectedOptions).trigger('change');
            @endif

            @if (is_null($search_sub_order_status) === false && is_string($search_sub_order_status) === false)
                const selectedSubOrderStatusOptions = @json($search_sub_order_status->toArray());
                $('#search_sub_order_status').val(selectedSubOrderStatusOptions).trigger('change');
            @endif

            document.getElementById('sort_support').addEventListener('submit', function(event) {
                event.preventDefault();

                const search = document.getElementById('search').value.trim();
                updateUrl('search', search);
            });

            $("#search_status").on('change', function() {
                updateUrl('search_status', $(this).val());
            });

            $("#search_sub_order_status").on('change', function() {
                updateUrl('search_sub_order_status', $(this).val());
            });
        });

        function updateUrl(param, value) {
            const url = new URL(window.location.href);
            const params = new URLSearchParams(url.search);

            if (value.length > 0) {
                params.set(param, value);
            } else {
                params.delete(param);
            }

            url.search = params.toString();
            location.href = url.toString();
        }
    </script>
@endsection
