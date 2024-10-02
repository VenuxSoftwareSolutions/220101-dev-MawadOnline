@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="h3">{{translate('All attributes')}}</h1>
        </div>
        <div class="col text-right">
            <a href="{{ route('attributes.create') }}" class="btn btn-circle btn-info">
                <span>{{translate('Add New attribute')}}</span>
            </a>
        </div>
    </div>
</div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Attributes') }}</h5>
                </div>
                <div class="card-body">
                    <table id="attributesTable" class="table  mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Name') }}</th>
                                <th>{{ translate('Value type') }}</th>
                                <th>{{ translate('Values') }}</th>
                                @auth
                                    @if (auth()->user()->hasRole('Super Admin') || auth()->user()->hasPermissionTo('enabling_product_attribute'))
                                        <th>{{ translate('Activated') }}</th>
                                    @endif
                                @endauth
                                <th class="text-right">{{ translate('Options') }}</th>
                            </tr>
                        </thead>
                        
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-info" class="modal fade">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6" id="title-modal">{{translate('Delete Confirmation')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <input type="hidden" id="status">
                <input type="hidden" id="attribute_id">
                <div class="modal-body text-center">
                    <p class="mt-1 fs-14" id="text-modal">{{translate('Are you sure to delete this?')}}</p>
                    <button type="button" class="btn btn-secondary rounded-0 mt-2" data-dismiss="modal" id="cancel_published">{{translate('Cancel')}}</button>
                    <button type="button" id="publish-link" class="btn btn-primary rounded-0 mt-2"></button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-success" class="modal fade">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6" id="title-modal-success">{{translate('Delete Confirmation')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <input type="hidden" id="status">
                <input type="hidden" id="product_id">
                <div class="modal-body text-center">
                    <p class="mt-1 fs-14" id="text-modal-success">{{translate('Are you sure to delete this?')}}</p>
                    <button type="button" class="btn btn-secondary rounded-0 mt-2" data-dismiss="modal">{{translate('OK')}}</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')

@section('script')
<script>
    $(document).ready(function() {
        // Initialize DataTable with server-side processing
        $('#attributesTable').DataTable({
            "processing": true,  // Show processing indicator
            "serverSide": true,   // Enable server-side processing
            "paging": true,       // Enable pagination
            "ordering": true,     // Enable column ordering
            "info": true,         // Show table information
            "autoWidth": false,   // Disable auto width for better responsiveness
            "pageLength": 10,     // Number of entries per page
            "ajax": {
                "url": "{{ route('attributes.data') }}", // URL to fetch data
                "type": "POST",                         // Use POST for server-side request
                "data": function (d) {
                    d._token = "{{ csrf_token() }}";    // Pass the CSRF token with each request
                }
            },
            "columns": [
                { "data": "id" },
                { "data": "name" },
                { "data": "type_value" },
                { "data": "attribute_values" },
                { "data": "is_activated", "orderable": false },
                { "data": "options", "orderable": false, "searchable": false }
            ]
        });
        $(document).on('change', '.activated', function(){
                var message_confirm = '';
                var message_success = '';
                var message_btn = '';
                var status = true;
                var id = $(this).data('id');

                if($(this).is(":checked") == true ){
                    var published = 1;
                    message_confirm = 'Are you sure you want to enable this attribute ?';
                    message_success = 'Enabled successfully';
                    message_btn = "Enable";
                }else{
                    var published = 0;
                    message_confirm = 'Are you sure you want to disable this attribute ?';
                    message_success = 'Disabled successfully';
                    message_btn = "Disable";
                    status = false;
                }

                if(id != undefined){
                    $("#title-modal").text('{{ translate("Enable Attribute") }}');
                    $("#text-modal").text(message_confirm);
                    $("#publish-link").text(message_btn);
                    $("#status").val(published);
                    $("#attribute_id").val(id);

                    $("#modal-info").modal('show')
                } 
            })

            $('body').on('click', '#cancel_published', function(){
                $('#modal-info').modal('hide');
                var id = $("#attribute_id").val();
                var published = $("#status").val();

                if((id != '') && (id != undefined)){
                    if(published == 0){
                        $('#' + id).prop('checked', true)
                    }else{
                        $('#' + id).prop('checked', false)
                    }
                }

                $("#attribute_id").val('');
                $("#status").val('');
            })

            $('body').on('click', '#publish-link', function(){
                $("#modal-info").modal('hide');
                var id = $("#attribute_id").val();
                var status = $("#status").val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                if(status == 1){
                    var message_success = "Enabled successfully";
                    var message_icon = "Enabled"
                }else{
                    var message_success = "Disabled successfully";
                    var message_icon = "Disabled"
                }

                if((id != '') && (id != undefined)){
                    $.ajax({
                        url: "{{ route('attributes.activated') }}",
                        type: "POST",
                        data: {
                            status: status,
                            id: id
                        },
                        cache: false,
                        dataType: 'JSON',
                        success: function(dataResult) {
                            $("#title-modal-success").text(message_icon);
                            $("#text-modal-success").text(message_success);

                            $("#modal-success").modal('show')
                        }
                    })
                }
            });
    });
</script>

@endsection

    
