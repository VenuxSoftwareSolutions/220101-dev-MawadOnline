<!-- resources/views/admin/subscriptions/index.blade.php -->

@extends('backend.layouts.app')
@section('css')
 <style>
.dropdown-menu {
    min-width: 17rem !important;
}
 </style>
@endsection
@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">Subscriptions</h1>
        </div>
    </div>
</div>
<div class="card">

    <div class="card-header row gutters-5">
        <div class="col">
            <h5 class="mb-md-0 h6">{{ __('messages.vendors') }}</h5>
        </div>
    </div>

    <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @elseif(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        <table id="myTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Stripe ID</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subscriptions as $subscription)
                    <tr>
                        <td>{{ $subscription->id }}</td>
                        <td>{{ $subscription->user->name }}</td>
                        @php
                        $pauseCollection = json_decode($subscription->pause_collection, true);
                        $isPaused = is_array($pauseCollection) && isset($pauseCollection['behavior']) && $pauseCollection['behavior'] === 'keep_as_draft';
                        $isPastDue = $subscription->stripe_status === 'past_due'; // Assuming the status is stored in `stripe_status`
                         @endphp
                        <td>{{ $subscription->stripe_id }}</td>
                        @if ($isPaused)
                            <td>paused</td>
                         @else
                         <td>{{ $subscription->stripe_status }}</td>

                        @endif

                        <td>
                            @if( $subscription->stripe_status != "canceled")
                            @php
                            $pauseCollection = json_decode($subscription->pause_collection, true);
                            $isPaused = is_array($pauseCollection) && isset($pauseCollection['behavior']) && $pauseCollection['behavior'] === 'keep_as_draft';
                            @endphp
                            <!-- Options column -->
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm btn-circle btn-soft-primary btn-icon dropdown-toggle no-arrow" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="las la-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">


                                    @if(!$isPaused)
                                        <form action="{{ route('admin.subscriptions.pause', $subscription->id) }}" method="POST" class="dropdown-item">
                                            @csrf
                                            <button type="submit" class="btn">{{ __('Pause Subscription Immediately') }}</button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.subscriptions.unpause', $subscription->id) }}" method="POST" class="dropdown-item">
                                            @csrf
                                            <button type="submit" class="btn">{{ __('Unpause Subscription') }}</button>
                                        </form>

                                        <form action="{{ route('admin.subscriptions.cancel', $subscription->id) }}" method="POST" class="dropdown-item">
                                            @csrf
                                            <button type="submit" class="btn">{{ __('Cancel Subscription') }}</button>
                                        </form>
                                    @endif

                                    @if($isPastDue)
                                        <form action="{{ route('admin.subscriptions.retry', $subscription->stripe_id) }}" method="POST" class="dropdown-item">
                                            @csrf
                                            <button type="submit" class="btn">{{ __('Retry Payment') }}</button>
                                        </form>
                                    @endif
                                    <!-- Refund Option -->
                                <form action="{{ route('admin.subscriptions.refund', $subscription->stripe_id) }}" method="POST" class="dropdown-item">
                                    @csrf
                                    <button type="submit" class="btn">{{ __('Refund Last Payment') }}</button>
                                </form>

                                </div>
                            </div>

                            @endif
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script>
    $('#myTable').DataTable({
        "order": false
    });
</script>
@endsection
