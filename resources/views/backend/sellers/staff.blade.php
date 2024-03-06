@extends('backend.layouts.app')
@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">Staff of {{ $seller->name }}</h1>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <table  class="tableStaff" id="myTable" {{-- class="table aiz-table mb-0" --}}>
            <thead>
                <tr>
                    <th>{{__('Email Address')}}</th>
                    <th>{{__('Approval')}}</th>

                </tr>
                </thead>
                <tbody>
                    @foreach($staff as $item)
                    <tr>
                        <td>{{ $item->email }}</td>
                        <td>
                            <!-- Approval status column with toggle switch -->
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input id="vendor-checkbox-{{ $item->id }}" type="checkbox" class="approval-checkbox" data-vendor-id="{{ $item->id }}" <?php if($item->status == 'Enabled') echo "checked";?> onchange="updateSettings(this, 'vendor_approval', {{ $item->id }})">
                                <span class="slider round"></span>
                            </label>
                        </td>

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
        $('.tableStaff').DataTable({
            "order": [[0, "asc"]], // Sort by first column in descending order

        });
    });
</script>
<script type="text/javascript">
    // JavaScript function
    function updateSettings(el, type, vendorId) {
        // Determine the value based on whether the checkbox is checked or not
        var value = $(el).is(':checked') ? 'Enabled' : 'Disabled';

        // Send a POST request to update the vendor's approval status

        $.post("{{ route('vendors.approve', ':id') }}".replace(':id', vendorId), {_token:'{{ csrf_token() }}', type:type, value:value}, function(data){
            // Handle the response from the server
            if(data.success){
                AIZ.plugins.notify('success', '{{ translate('Vendor approval status updated successfully') }}');
                $('#status-' + vendorId).text(data.status); // Update the status cell with the new status

            }
            else{
                AIZ.plugins.notify('danger', 'Something went wrong');
            }
        });
    }

</script>
@endsection
