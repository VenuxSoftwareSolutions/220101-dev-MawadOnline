@extends('backend.layouts.app')
@section('css')
<!-- Include MultiSelect CSS -->
<link rel="stylesheet" href="https://cdn.rawgit.com/nobleclem/jQuery-MultiSelect/master/jquery.multiselect.css">
@endsection
@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Vendors Status History Report') }}</h1>

        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <!-- Report Filters -->
        <div class="row mb-4">
            <div class="col-md-6">
                <label for="vendorSelect">Vendor:</label>
                <select id="vendorSelect" class="form-control myMultiselect" multiple>
                    @foreach ($vendors as $vendor)
                        <option value="{{ $vendor->id }}">{{ $vendor->id }} - {{ $vendor->business_information ? $vendor->business_information->trade_name : '' }} - {{ $vendor->name }}</option>
                    @endforeach
                </select>

            </div>
        </div>
        <table  id="myTable" {{-- class="table aiz-table mb-0" --}}>
            <thead>
                <tr>
                    <th>Vendor Business Name</th>
                    <th>Vendor Display Name</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Suspension Reason</th>
                    <th>Suspension Reason Title</th>
                    <th>Suspension Reason Details</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($vendorsStatusHistory as $vendorStatusHistory )
                    <tr>
                        <td>{{ $vendorStatusHistory->vendor->business_information ?  $vendorStatusHistory->vendor->business_information->trade_name :"" }}</td>
                        <td>{{ $vendorStatusHistory->vendor->name}}</td>
                        <td>{{ $vendorStatusHistory->status}}</td>
                        <td>{{ $vendorStatusHistory->created_at->format('jS F Y, H:i')}}</td>
                        @if ($vendorStatusHistory->status == "Suspended")
                            <td>{{ $vendorStatusHistory->reason}}</td>
                            <td>{{ $vendorStatusHistory->suspension_reason}}</td>
                        @else
                            <td></td>
                            <td></td>
                        @endif


                        <td> @if ($vendorStatusHistory->details)
                            <button type="button" class="btn btn-info" data-toggle="modal" >
                                View Description
                            </button>

                        @endif</td>
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
    <!-- Include MultiSelect JS -->
    <script src="https://cdn.rawgit.com/nobleclem/jQuery-MultiSelect/master/jquery.multiselect.js"></script>
  <script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            "order": [[4, "desc"]], // Sort by first column in descending order
            "dom": 'Bfrtip', // Add buttons to the layout
            "buttons":   [{
                extend: 'excelHtml5',
                text: '{{__("stock.Export to Excel")}}',

            }],

        });
        $('.myMultiselect').multiselect({
            columns: 1,
            placeholder: 'Select Options',
            selectAll: true,
            search: true
        });
         // Apply filter based on the selected options in the multi-select dropdown
         $('#vendorSelect').on('change', function() {
            var selectedValues = $(this).val();
            table.column(1).search(selectedValues.join('|'), true, false).draw();
        });

    });
</script>
@endsection
