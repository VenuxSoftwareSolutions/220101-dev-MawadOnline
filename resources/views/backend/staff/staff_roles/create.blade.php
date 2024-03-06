@extends('backend.layouts.app')

@section('content')

<div class="col-lg-12 mx-auto">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Role Information')}}</h5>
        </div>
        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-md-3 col-from-label" for="name">{{translate('Name')}}</label>
                    <div class="col-md-9">
                        <input type="text" placeholder="{{translate('Name')}}" id="name" name="name" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-from-label" for="description">{{translate('Description')}}</label>
                    <div class="col-md-9">
                        <textarea placeholder="{{translate('Description')}}" id="description" name="description" class="form-control" ></textarea>
                    </div>
                </div>
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Permissions') }}</h5>
                </div>
                <br>
                @php
                    $permission_groups =  \App\Models\Permission::where('section', 'not like', 'seller_%')->get()->groupBy('section');
                    $addons = array("offline_payment", "club_point", "pos_system", "paytm", "seller_subscription", "otp_system", "refund_request", "affiliate_system", "african_pg", "delivery_boy", "auction", "wholesale");
                @endphp
                @foreach ($permission_groups as $key => $permission_group)
                    @php
                        $show_permission_group = true;

                        if(in_array($permission_group[0]['section'], $addons)){

                            if (addon_is_activated($permission_group[0]['section']) == false) {
                                $show_permission_group = false;
                            }
                        }
                    @endphp
                    @if($show_permission_group)
                        <ul class="list-group mb-4">
                            <li class="list-group-item bg-light d-flex" aria-current="true">
                                <div class="mr-4">{{ translate(Str::headline($permission_group[0]['section'])) }}</div>
                                <!-- Add checkbox for selecting/deselecting all permissions in this group -->
                                <label class="aiz-switch aiz-switch-success">
                                <input type="checkbox" class="form-control demo-sw select-all-permissions" data-group="{{ $permission_group[0]['section'] }}" >
                                <span class="slider round"></span>
                            </label>                            <li class="list-group-item">
                                <div class="row">
                                    @foreach ($permission_group as $key => $permission)
                                        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                                            <div class="p-2 border mt-1 mb-2">
                                                <label class="control-label d-flex">{{ translate(Str::headline($permission->name)) }}</label>
                                                <label class="aiz-switch aiz-switch-success">
                                                    <input type="checkbox" name="permissions[]" class="form-control demo-sw {{ $permission_group[0]['section'] }}" value="{{ $permission->id }}">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </li>
                        </ul>
                    @endif
                @endforeach

                <div class="form-group mb-3 mt-3 text-right">
                    <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
@section('script')
<script>
   $(document).ready(function() {
       // Handle click event for select all checkboxes
       $(document).on('click', '.select-all-permissions', function() {
           var group = $(this).data('group');
           $('.'+group).prop('checked',$(this).prop('checked'));
       });
   });
</script>
@endsection
