@extends('backend.layouts.app')

@section('content')
    <div class="card">
        <form class="" action="" id="sort_orders" method="GET">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-md-0 h6">{{ translate('All refunds details') }}</h5>
                </div>
                <a href="{{url()->previous()}}" class="">
                    <h5 class="mb-md-0 h6">{{ translate('previous') }}</h5>
                </a>
            </div>

            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>{{ translate('Order Code') }}</th>
                            <th data-breakpoints="md">{{ translate('Customer') }}</th>
                            <th data-breakpoints="md">{{ translate('Seller') }}</th>
                            <th data-breakpoints="md">{{ translate('Amount') }}</th>
                            <th data-breakpoints="md">{{ translate('detail ID') }}</th>
                            <th data-breakpoints="md">{{ translate('detail Status') }}</th>
                            <th data-breakpoints="md">{{ translate('Error Description') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($details as $key => $detail)
                            <tr>
                                <td>
                                    {{ $detail->refund->orderDetail->order->code }}

                                </td>
                                <td>
                                    {{ $detail->refund->user->name }}
                                </td>
                                <td>
                                    {{ $detail->refund->seller->name }}
                                </td>
                                <td>
                                    {{ single_price($detail->amount) }}
                                </td>
                                <td>
                                    {{ $detail->payment_refund_id }}
                                </td>
                                <td>
                                    @if ($detail->refund_status== "succeeded")
                                    <span class="badge badge-inline badge-success">{{$detail->refund_status}}</span>
                                    @elseif($detail->refund_status == "failed")
                                    <span class="badge badge-inline badge-danger">{{$detail->refund_status}}</span>
                                    @elseif($detail->refund_status == "requires_action")
                                    <span class="badge badge-inline badge-warning">{{$detail->refund_status}}</span>
                                    @elseif($detail->refund_status == "pending")
                                    <span class="badge badge-inline badge-info">{{$detail->refund_status}}</span>
                                    @elseif($detail->refund_status == "canceled")
                                    <span class="badge badge-inline badge-primary">{{$detail->refund_status}}</span>
                                    @endif
                                </td>
                                <td>
                                    {{$detail->description_error ? $detail->description_error : "-" }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="aiz-pagination">
                    {{ $details->appends(request()->input())->links() }}
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
