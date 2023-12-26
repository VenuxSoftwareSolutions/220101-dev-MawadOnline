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
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Name') }}</th>
                                <th>{{ translate('Value type') }}</th>
                                <th>{{ translate('Values') }}</th>
                                @role('Super Admin')
                                    <th>{{ translate('Activated') }}</th>
                                @endrole
                                @can('enabling_product_attribute')
                                    <th>{{ translate('Activated') }}</th>
                                @endcan
                                <th class="text-right">{{ translate('Options') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attributes as $key => $attribute)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $attribute->name }}</td>
                                    <td>{{ ucfirst($attribute->type_value) }}</td>
                                    <td>
                                        @php
                                            $values_en = [];
                                            $values_ar = [];
                                            $values = [];
                                            foreach ($attribute->attribute_values as $key => $value) {
                                                switch ($value->lang) {
                                                    case 'en':
                                                        array_push($values_en, $value->value);
                                                        break;
                                                    case 'ar':
                                                        array_push($values_ar, $value->value);
                                                        break;
                                                    default:
                                                        array_push($values, $value->value);
                                                }
                                            }
                                        @endphp

                                        @if (count($values_en) > 0)
                                            @foreach ($values_en as $key => $value)
                                                @if ($key == 0)
                                                    English: <span class="badge badge-inline badge-md bg-soft-dark">{{ $value }}</span>
                                                @else
                                                    <span class="badge badge-inline badge-md bg-soft-dark">{{ $value }}</span>
                                                @endif

                                            @endforeach
                                        @endif

                                        @if (count($values_ar) > 0)
                                            @foreach ($values_ar as $key => $value)
                                                @if ($key == 0)
                                                    <br><br> Arabic: <span class="badge badge-inline badge-md bg-soft-dark">{{ $value }}</span>
                                                @else
                                                    <span class="badge badge-inline badge-md bg-soft-dark">{{ $value }}</span>
                                                @endif

                                            @endforeach
                                        @endif

                                        @if (count($values) > 0)
                                            @foreach ($values as $key => $value)
                                                <span class="badge badge-inline badge-md bg-soft-dark">{{ $value }}</span>
                                            @endforeach
                                        @endif

                                    </td>
                                    @can('enabling_product_attribute')
                                        <td>
                                            <label class="aiz-switch aiz-switch-success mb-0">
                                                <input class="activated" data-id="{{ $attribute->id }}" type="checkbox" @if($attribute->is_activated == 1) {{ "checked" }} @endif >
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                    @endcan
                                    <td class="text-right">
                                        @can('edit_product_attribute')
                                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                                href="{{ route('attributes.edit', ['id' => $attribute->id, 'lang' => env('DEFAULT_LANGUAGE')]) }}"
                                                title="{{ translate('Edit') }}">
                                                <i class="las la-edit"></i>
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="aiz-pagination">
                        {{ $attributes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $( document ).ready(function() {
            $(document).on('change', '.activated', function(){
                var message_confirm = '';
                var message_success = '';
                var message_btn = '';
                var status = true;

                if($(this).is(":checked") == true ){
                    message_confirm = 'Are you sure you want to enable this attribute ?';
                    message_success = 'Enabled successfully';
                    message_btn = "Enable";
                }else{
                    message_confirm = 'Are you sure you want to disable this attribute ?';
                    message_success = 'Disabled successfully';
                    message_btn = "Disable";
                    status = false;
                }

                var id = $(this).data('id');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var current = $(this);

                swal({
                    title: message_confirm,
                    type: "warning",
                    confirmButtonText: message_btn,
                    showCancelButton: true
                })
                .then((result) => {
                    if (result.value) {
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
                                swal(
                                    message_btn,
                                    message_success,
                                    'success'
                                )
                            }
                        })
                    } else if (result.dismiss === 'cancel') {
                        swal(
                            'Cancelled',
                            'Your deletion is undone',
                            'warning'
                        )
                        if(current.is(":checked") == true ){
                            current.prop("checked", false);
                        }else{
                            current.prop("checked", true);
                        }
                    }
                })
            })
        });
    </script>
@endsection
