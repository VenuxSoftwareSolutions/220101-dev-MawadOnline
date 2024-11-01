@extends('seller.layouts.app')
@push('styles')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">

@endpush
@section('panel_content')

    <div class="aiz-titlebar mt-2 mb-4">
      <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Bulk Products Upload') }}</h1>
        </div>
      </div>
    </div>

    <div class="card">
         <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Product Category') }}</h5>
                        <h6 class="float-right fs-13 mb-0">
                            {{ translate('Select Main') }}
                            <span class="position-relative main-category-info-icon">
                                <i class="las la-question-circle fs-18 text-info"></i>
                                <span class="main-category-info bg-soft-info p-2 position-absolute d-none border">{{ translate('This will be used for commission based calculations and homepage category wise product Show.') }}</span>
                            </span>

                        </h6>
                    </div> 
        <div class="card-body">

            <div class="tree_main">
                <input type="hidden" id="selected_parent_id" name="parent_id" value="">
                <input type="text" id="search_input" class="form-control" placeholder="{{ translate('Search') }}">
                <div class="h-300px overflow-auto c-scrollbar-light">

                    <div id="jstree"></div>


                </div>
            </div>

            <div class="float-right">
            <a href="#">
                <button id="download_button" disabled type="button" class="btn btn-primary mt-2 ">{{ translate('Download File') }}</button>
                <span class="d-block " id="message-category"></span>
            </a>
            
            </div>

            {{-- <table class="table aiz-table mb-0" style="font-size:14px; background-color: #cce5ff; border-color: #b8daff">
                <tr>
                    <td>{{ translate('1. Download the skeleton file and fill it with data.')}}:</td>
                </tr>
                <tr >
                    <td>{{ translate('2. You can download the example file to understand how the data must be filled.')}}:</td>
                </tr>
                <tr>
                    <td>{{ translate('3. Once you have downloaded and filled the skeleton file, upload it in the form below and submit.')}}:</td>
                </tr>
                <tr>
                    <td>{{ translate('4. After uploading products you need to edit them and set products images and choices.')}}</td>
                </tr>
            </table>
            <a href="{{ static_asset('download/product_bulk_upload.xlsx') }}" download><button class="btn btn-primary mt-2">{{ translate('Download CSV') }}</button></a>
        </div> --}}
    </div>

    
    <div class="card">
        <div class="card-header">
            <div class="col text-center text-md-left">
                <h5 class="mb-md-0 h6">{{ translate('Upload EXCEL File') }}</h5>
            </div>
        </div>
        <div class="card-body">
            <form  class="form-horizontal" action="{{ route('seller.bulk_product_upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <label class="col-md-2 col-form-label">{{ translate('EXCEL') }}</label>
                    <div class="col-sm-10">
                        <div class="custom-file">
    						<label class="custom-file-label">
    							<input type="file" name="bulk_file" class="custom-file-input" required>
    							<span class="custom-file-name">{{ translate('Choose File')}}</span>
    						</label>
    					</div>
                        @error('bulk_file')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary">{{translate('Upload CSV')}}</button>
                </div>
            </form>
        </div>
    </div>



    <div class="card">
        <div class="card-header">
            <div class="col text-center text-md-left">
                <h5 class="mb-md-0 h6">{{ translate('List of uploaded files') }}</h5>
            </div>
        </div>
        <div class="card-body">
            <table id="step3" class="table {{-- aiz-table --}} mb-0">
                <thead>
                    <tr>
                        <th class="custom-th">{{ __('File name') }}</th>
                        <th class="custom-th">{{ __('Submission date') }}</th>
                        <th class="custom-th">{{ __('Status') }}</th>
                        <th class="custom-th">{{ __('Extension') }}</th>
                        <th class="custom-th">{{ __('Size') }}</th>
                </thead>
                
            </table>
        </div>
    </div>

@endsection

@section('script')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
<script src="{{ static_asset('assets/js/filter-multi-select-bundle.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.min.js"></script>
<script>
$(function() {
        $('#jstree').jstree({
            'core': {
                'data': {
                    "url": "{{ route('seller.categories.jstree') }}",
                    "data": function(node) {
                        return {
                            "id": node.id
                        };
                    },
                    "dataType": "json"
                },
                'check_callback': true,
                'themes': {
                    'responsive': false
                }
            },
            "plugins": ["wholerow", "search"] // Include the search plugin here
        }).on("changed.jstree", function(e, data) {
            if (data && data.selected && data.selected.length) {
                    var selectedId = data.selected[0]; // Get the ID of the first selected node

                    // Check if the selected node has children
                    var node = $('#jstree').jstree(true).get_node(selectedId);
                    if (node.parent != '#' && node.parent != 1) {
                        $('#message-category').text("");
                        $('#message-category').css({'color': 'green', 'margin-right': '7px'});
                        $("#download_button").prop("disabled", false);

                        // The node does not have children, proceed with your logic
                        $('#selected_parent_id').val(selectedId); // Update hidden input with selected ID
                        AIZ.plugins.bootstrapSelect('refresh');
                        // Call your functions to load attributes
                    } else {
                        $("#download_button").prop("disabled", true);

                        // The node has children, maybe clear selection or handle differently
                        $('#message-category').text("Please select a sub category!");
                        $('#message-category').css({'color': 'red', 'margin-right': '7px'});
                        $('#check_selected_parent_id').val(-1);                
                        AIZ.plugins.bootstrapSelect('refresh');
                        // Optionally, clear selection here if needed
                        // $('#jstree').jstree(true).deselect_node(selectedId);
                    }
                }
        });
    });


    var to = false;
    $('#search_input').keyup(function() {
        if (to) {
            clearTimeout(to);
        }
        to = setTimeout(function() {
            var v = $('#search_input').val();
            if (v === "") {
                lastSearchTerm = null;
                    // Explicitly reset the URL for the initial data load
                $('#jstree').jstree(true).settings.core.data.url = "{{ route('seller.categories.jstree') }}";

                $('#jstree').jstree(true).settings.core.data.data = function(node) {
                    return {
                        "id": node.id
                    };
                },
                $('#jstree').jstree(false, true).refresh(); // Refresh the tree to load initial data
            } else {
                lastSearchTerm = v;
                $.ajax({
                    url: "{{ route('seller.categories.jstreeSearch') }}", // Your actual API endpoint
                    type: 'GET', // Or 'POST', depending on your API
                    dataType: 'json', // Expected data format from API
                    data: {
                        searchTerm: v // Send the search term to your API
                    },
                    success: function(response) {
                        //console.log(response);
                        // Assuming 'response' contains the data to update the jstree
                        // You will need to process 'response' to fit your jstree's data format

                        // Example: clear the existing jstree and populate with new data
                        $('#jstree').jstree(true).settings.core.data = response;
                        $('#jstree').jstree(true).refresh();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error during search API call:", status, error);
                    }
                });
            }
        }, 250);
    });

    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    $("#download_button").click(function() {
        var selectedId = $('#selected_parent_id').val();
        $.ajax({
            url: "{{ route('seller.products.bulk_upload.download_file') }}", // Replace with your server URL
            method: 'POST',
            data:{
                selectedId:selectedId,
                _token: csrfToken // Include CSRF token in the request data
            },
            xhrFields: {
                responseType: 'blob'
            },
            success: function(response, status, xhr) {
                var filename = "";                   
                var disposition = xhr.getResponseHeader('Content-Disposition');

                if (disposition && disposition.indexOf('attachment') !== -1) {
                    var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                    var matches = filenameRegex.exec(disposition);
                    if (matches !== null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                }

                var type = xhr.getResponseHeader('Content-Type');

                var blob = new Blob([response], { type: type });

                if (typeof window.navigator.msSaveBlob !== 'undefined') {
                    window.navigator.msSaveBlob(blob, filename);
                } else {
                    var URL = window.URL || window.webkitURL;
                    var downloadUrl = URL.createObjectURL(blob);

                    if (filename) {
                        var a = document.createElement("a");

                        if (typeof a.download === 'undefined') {
                            window.location = downloadUrl;
                        } else {
                            a.href = downloadUrl;
                            a.download = filename;
                            document.body.appendChild(a);
                            a.click();
                        }
                    } else {
                        window.location = downloadUrl;
                    }

                    setTimeout(function () { URL.revokeObjectURL(downloadUrl); }, 100);
                }
            },
            error: function() {
                alert('Failed to generate and download the file.');
            }
        });
    });
</script>


<script>
    $(document).ready(function() {
        $('#step3').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{ route('seller.product_bulk_upload.getFiles') }}", // Replace 'your-route-name' with the actual route name
            "columns": [
                { "data": "filename" },
                { "data": "created_at" },
                { 
                        "data": "status",
                        "render": function(data, type, row) {
                            if (data === 'processing') {
                                return '<span class="badge badge-primary" style="display:inline">Processing</span>';
                            } else if (data === 'failed') {
                                return '<span class="badge badge-danger" style="display:inline">Failed</span>';
                            }else{
                                return'<span class="badge badge-success" style="display:inline">success</span>';
                            }
                        }
                    },
                { "data": "extension" },
                { "data": "size" }
            ],
            "order": [[1, "desc"]], // Sort by first column in ascending order
            "dom": 'Bfrtip', // Add buttons to the layout
            "language": {
                "search": "", // Remove the label text for search input
                "searchPlaceholder": "{{ __('stock.search_records') }}" // Custom search placeholder text
            }
        });
    });
</script>
@endsection
