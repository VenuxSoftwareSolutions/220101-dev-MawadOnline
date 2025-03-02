@extends('backend.layouts.app')

@section('content')
    <div class="card">
        <form class="" action="" id="sort_orders" method="GET">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-md-0 h6">{{ translate('All Refunds') }}</h5>
                </div>
            </div>

            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>{{ translate('Order Code') }}</th>
                            <th data-breakpoints="md">{{ translate('Customer') }}</th>
                            <th data-breakpoints="md">{{ translate('Seller') }}</th>
                            <th data-breakpoints="md">{{ translate('Amount') }}</th>
                            <th data-breakpoints="md">{{ translate('Refund ID') }}</th>
                            <th data-breakpoints="md">{{ translate('Refund Status') }}</th>
                            <th data-breakpoints="md">{{ translate('Error Description') }}</th>
                            <th data-breakpoints="md">{{ translate('Executed n times') }}</th>
                            <th class="text-right" width="15%">{{ translate('options') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($refunds as $key => $refund)
                            <tr>
                                <td>
                                    {{ $refund->orderDetail->order->code }}

                                </td>
                                <td>
                                    {{ $refund->user->name }}
                                </td>
                                <td>
                                    {{ $refund->seller->name }}
                                </td>
                                <td>
                                    {{ single_price($refund->amount) }}
                                </td>
                                <td>
                                    {{ $refund->payment_refund_id }}
                                </td>
                                <td>
                                    @if ($refund->refund_status== "succeeded")
                                    <span class="badge badge-inline badge-success">{{$refund->refund_status}}</span>
                                    @elseif($refund->refund_status == "failed")
                                    <span class="badge badge-inline badge-danger">{{$refund->refund_status}}</span>
                                    @elseif($refund->refund_status == "requires_action")
                                    <span class="badge badge-inline badge-warning">{{$refund->refund_status}}</span>
                                    @elseif($refund->refund_status == "pending")
                                    <span class="badge badge-inline badge-info">{{$refund->refund_status}}</span>
                                    @elseif($refund->refund_status == "canceled")
                                    <span class="badge badge-inline badge-primary">{{$refund->refund_status}}</span>
                                    @endif
                                </td>
                                <td>
                                    {{$refund->description_error ? $refund->description_error : "-" }}
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-info text-dark">{{$refund->refundHistories()->count()}}</span>
                                </td>
                                <td class="text-right">
                                    @if($refund->refund_status == "failed")
                                    <a class="btn btn-soft-primary  btn-sm"
                                        href="{{route('refunds.execute',$refund->id)}}" title="{{ translate('re-execute') }}">
                                        {{ translate('re-execute') }}
                                    </a>
                                    @endif
                                    @if($refund->refundHistories()->exists())
                                    <a href="{{route('refunds.details', $refund->id)}}" class="btn btn-soft-primary btn-icon btn-circle btn-sm">
                                        <i class="las la-eye"></i>
                                    </a>
                                    @endif

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="aiz-pagination">
                    {{ $refunds->appends(request()->input())->links() }}
                </div>

            </div>
        </form>
    </div>
@endsection

@section('modal')

@endsection

@section('script')
    <script type="text/javascript">
        $(document).on("change", ".check-all", function() {
            if (this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.check-one:checkbox').each(function() {
                    this.checked = false;
                });
            }

        });

    </script>
@endsection
