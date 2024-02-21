@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Vendor Status History for ') }} {{ $vendor_email }}</h1>

        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table  id="myTable" {{-- class="table aiz-table mb-0" --}}>
            <thead>
                <tr>
                    <th>{{__('messages.Status')}}</th>
                    <th>{{__('messages.Reason')}}</th>
                    <th>{{ __('messages.Suspension Reason') }}</th>
                    <th>{{ __('messages.Details') }}</th>
                    <th>{{ __('messages.Date') }}</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($history as $item)
                    <tr>
                        <td>{{ $item['status'] }}</td>
                        <td>{{ $item['reason'] ?? '' }}</td>
                        <td>{{ $item['suspension_reason'] ?? '' }}</td>
                        <td>{{ $item['details'] ?? '' }}</td>
                        <td>{{ \Carbon\Carbon::parse($item['created_at'])->toDateTimeString() }}</td>
                    </tr>
                    @endforeach

                </tbody>

        </table>
    </div>
</div>
@endsection
@section('script')
  <!-- DataTables Buttons extension -->
  <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

  <script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            "order": [[4, "desc"]], // Sort by first column in descending order
            "dom": 'Bfrtip', // Add buttons to the layout
            "buttons":   [{
                extend: 'excelHtml5',
                text: '{{__("stock.Export to Excel")}}',

            }]
        });

    });
</script>
@endsection
