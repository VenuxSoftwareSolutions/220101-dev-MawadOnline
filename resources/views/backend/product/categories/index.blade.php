@extends('backend.layouts.app')

@section('content')

<style>
    td.details-control {
        background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAABGdBTUEAANbY1E9YMgAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAMDSURBVHjarFXdS5NRGH/eufyK2ZZWvqKiyZQQpQ9hNKVIHZLzpq7Ey8SbQhG66R+IPqCuCqKLrsK8kbCMhcOwNdimglZ24WSVHyxzVjqZQ9+P0/Mcz1upE7vwjB/nnOfjt3Oej/NKkHqYEGmIA4h0saahITYQiljr2x2lFHszIgthQeQgDgpSEGQJRByxikgiVARLdSoiy0QcRVR2dHRc8fv9nmg0OqvrukagNclIRzbCNjPFwbiATlWAcPT39z9VFGWD7TJIRzZkK3y2kEriSvmyLJ+LRCIfySmpJZk3Nsiuf+pmLaGLrDnYxLonO9mr7wMsoSY4MdmSD/oeExySJBJAsSoOBoN3HQ5H07KyDI+/PoI3S0M8OGTEpM1I0VR7uA6ull6D3PQ8CAQCHqfTeQPFMxRXI5O2rq6uhvb29k4NNOlO+DYMx4bRH386gv0DXYeZ5AxE1iJw4Ug9FBcWl8VisYnR0dFZSpJJEB5qbW29JEmS6d2SD3wxH2gaUmsqqLoG3roh8NYO8T1mB1TUjf0Yg7f4p+TT1tZ2WdzSbBBml5eXn6SAeqKvQVWRTFdBUdFZVf9kjuRch4QKknu+ebi8oqKCfLMpjmZRtOlWqzWXlFPxKXRQ8LISBFyBLaXgq/fz2ek9y+fPq1/4bLFYrEYDmLfXD8WMTrazsv4OVVN5qtaVjc0ywWsbOrPRTvF4/JfNZsuTM2SYW53nKT01cJrP4y3j3NjYi7xDQU4Bl6PvT9FFmkn05Vo4HJ4gpSvfxeO2GS+VJ8AYioghnZDWjXIjl09PT38gDjIxCFd6enr6sCz05sJmqLJWcSIOdDzRV8nBsy5kdosdWorcVEp6b2/vc9HfSppxh1AoFHe73faSopKyM3k1EF4J49XnttSizvgOqm3VcKvmJsjZMoyMjAxibz9Bjph4LFK33mJykT2YfMgaXrrY8Wd2Voo4/6Ke3Xt/n0UT0e2tl2+03n49Dlm7vTg7nq+FhYV5g4jWez1f//vAZgj9+l4PrLTfn4DfAgwAXP8AAdHdgRsAAAAASUVORK5CYII=') no-repeat center left;
        cursor: pointer;
        background-origin: content-box;
    }

    tr.details td.details-control {
        background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAABGdBTUEAANbY1E9YMgAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAALbSURBVHjarFVNTFNBEJ4tFVuw0gaj1Jhe5OdgJV5Iaw+CGAMKIdET4Sgh4SIHDcYLN07oiZOGqyFcjOECISEhwRpoGxLExAMVAi9NadIgWNLW8t7bdWa7L0Ap6oFtvsy+3Zlvd2ZnpnYoP2yICsQFRKWa0zARhwhdzXmpob3km6k1J8KFuIyoVqSgyLKIDOIAkUcYCFHuVkTmQFxF3BoYGHgWDodnk8mkxjk3CTSnNdojHaXrULanyOhW1xGB6enpD7quH4ozBu2RDukqmxOkTLlU5/V6721sbHwjI57Pi9z8vNgdHhY7PT0i1d0tdl+8FNmZGcGzWUlMumSDttcUB2PqAShWvuXl5bFAINDB9/chMzEBuYWF4rGMgsSACQECTRyhENQMDkJFbS0sLS3NhkKh16i1TXG1XtIzNDT0oL+//zmYJtt7Mwa5xc+Al0AmDlwKJKMfrunaNhibm+Bsa4MbPt/NdDq9GovFNHokmyKs6e3tfcIYs+XDYciGvwA3TQnT5EXJaW6qdQ65lRU8dBHIpq+v76ny0m4RVjU2Nt4h7w7m5qRhKY4OOALp0mhqaiLbKoqjXSVtpdvtrqXNfDwuldE3qMcblBs/WlulLGxtSelyudxWAZQmtkx90zTgb8M0DPlQTNeLaYJuH68UWU6ZTGbP4/FcqfB64XciIZ/2e/CuSixVCMeErAJvnfxG25+qikybqsvc+vr6qrx+e7uKkXEEQ8WNcxXPorx0v10SxuPxNeIgLovw1+Tk5EfKZ3dHBzj8/qIxt0gUkUWMh1TW14Pn8SNKIz41NfVJ1bd+IrGj0ejblpaWhwVNA210FA6iEfSPFV203EZnq5ubwTcyAs6GBohEInPBYPAVbmiKtHzpHabTIvnuvVjr6hIx/20R9fvF185OkRgfF4WdndLSq7NK77yag/OsjnOqfaVSqYRFRPN/tS/2nw32otov/KvBsvP+C/gjwAC23ACdhngbNwAAAABJRU5ErkJggg==') no-repeat center left;
        background-origin: content-box;
    }

    td.details-control.level-1 {
        padding-left: 30px;
    }

    tr.details td.details-control.level-1 {
        padding-left: 30px;
    }

    td.details-control.level-2 {
        padding-left: 50px;
    }

    tr.details td.details-control.level-2 {
        padding-left: 50px;
    }
</style>
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('All Categories')}}</h1>
        </div>
        @can('add_product_category')
        <div class="col-md-6 text-md-right">
            <a href="{{ route('categories.create') }}" class="btn btn-circle btn-info">
                <span>{{translate('Add New category')}}</span>
            </a>
        </div>
        @endcan
    </div>
</div>
<div class="card">
    <div class="card-header d-block d-md-flex">
        <h5 class="mb-0 h6">{{ translate('Categories') }}</h5>
    </div>
    <div class="card-body">
        <table id="example" class="display" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th></th>
                    <th>{{translate('Name')}}</th>
                    <th data-breakpoints="lg">{{ translate('Parent Category') }}</th>
                    <th data-breakpoints="lg">{{ translate('Order Level') }}</th>
                    <th data-breakpoints="lg">{{ translate('Level') }}</th>
                    <!--<th data-breakpoints="lg">{{translate('Banner')}}</th>
                    <th data-breakpoints="lg">{{translate('Icon')}}</th>-->
                    <th data-breakpoints="lg">{{translate('Thumbnail')}}</th>
                    <th data-breakpoints="lg">{{translate('Featured')}}</th>
                    <th data-breakpoints="lg">{{translate('Commission')}}</th>
                    <th width="10%" class="text-right">{{translate('Options')}}</th>
                </tr>
            </thead>
        </table>



    </div>
</div>
@endsection


@section('modal')
@include('modals.delete_modal')
@endsection


@section('script')
<script src="{{asset('/public/js/tree.js') }}"></script>
<script>
    var baseUrl = "{{ asset('/public') }}";

    var columns = [{
            title: '',
            target: 0,
            className: 'treegrid-control',
            data: function(item) {
                if (item.children.length > 0) {
                    return '<span>+<\/span>';
                }
                return '';
            }
        },
        {
            title: '{{translate('Name')}}',
            target: 1,
            data: function(item) {
                return item.name;
            }
        },
        {
            title: '{{ translate('Parent Category') }}',
            target: 2,
            data: function(item) {
                return item.parentName || '—';
            }
        },
        {
            title: '{{translate('Order Level')}}',
            target: 3,
            data: function(item) {
                return item.order_level;
            }
        },
        {
            title: '{{translate('Level')}}',
            target: 4,
            data: function(item) {
                return item.cat_level;
            }
        },
        {
            title: '{{translate('Thumbnail')}}',
            target: 5,
            data: function(item) {
                if (item.thumbnail_image) {
                    // Return an image tag with the cover image URL
                    return `<img src="${baseUrl + '/'+ item.thumbnail_image}" alt="Cover Image" class="h-50px">`;
                } else {
                    // Fallback text if no cover image is available
                    return '—';
                }
            }
        },
        {
            title: '{{translate('Featured')}}',
            target: 6,
            data: function(item) {
                var checkedStatus = item.featured == 1 ? "checked" : "";
                
                // Return the checkbox HTML, modifying the value attribute to use the category ID from the row data
                // Note: You need to ensure that 'full.id' or the correct property that holds the category ID is accessible here
                return `<label class="aiz-switch aiz-switch-success mb-0">
                            <input type="checkbox" onchange="update_featured(this)" value="${item.id}" ${checkedStatus}>
                            <span></span>
                        </label>`;
            }
            
        },
        {
            title: '{{translate('Commission')}}',
            target: 7,
            data: function(item) {
                return item.commision_rate;
            }
        },
        {
           title: '{{translate('Options')}}', // This column does not correspond to a single data field
           data: function(item) {
                var editButton = item.can_edit ? `<a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="${item.edit_url}" title="Edit"><i class="las la-edit"></i></a>` : '';
                var deleteButton = item.can_delete ? `<a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="${item.delete_url}" title="Delete"><i class="las la-trash"></i></a>` : '';

                return `<div class="text-right">${editButton} ${deleteButton}</div>`;
            }
        }
    ];
    $(document).ready(function() {
        var table = $('#example').DataTable({
            "columnDefs": [
                { "orderable": false, "targets": '_all' } // Disables sorting on all columns
            ],
            paging: false,
            processing: true, // Enable processing indicator
            serverSide: true, // Enable server-side processing
            ajax: '{{ route('categories.fetchCategories') }}', // Specify the URL to fetch data
            'columns': columns,
            'treeGrid': {
                'left': 10,
                'expandIcon': '<span>+</span>',
                'collapseIcon': '<span>-</span>'
            }
        });
    });



    function update_featured(el){
        if(el.checked){
            var status = 1;
        }
        else{
            var status = 0;
        }
        $.post('{{ route('categories.featured') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
            if(data == 1){
                AIZ.plugins.notify('success', '{{ translate('Featured categories updated successfully') }}');
            }
            else{
                AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
            }
        });
    }


    $(document).on('click', '.confirm-delete', function(e) {
        e.preventDefault();
        var url = $(this).data("href");
        $("#delete-modal").modal("show");
        $("#delete-link").attr("href", url);
    });
</script>




@endsection