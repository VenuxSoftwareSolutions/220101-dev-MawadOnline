@extends('seller.layouts.app')

@section('panel_content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Role Information')}}</h5>
</div>


<div class="col-lg-12 mx-auto">
    <div class="card">
        <div class="card-body p-0">
            <ul class="nav nav-tabs nav-fill border-light">
                @foreach (get_all_active_language() as $key => $language)
                    <li class="nav-item">
                        <a class="nav-link text-reset @if ($language->code == $lang) active @else bg-soft-dark border-light border-left-0 @endif py-3" href="{{ route('seller.roles.edit', ['id'=>$role->id, 'lang'=> $language->code] ) }}">
                            <img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="11" class="mr-1">
                            <span>{{$language->name}}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
            <form class="p-4" action="{{ route('seller.roles.update', $role->id) }}" method="POST">
                <input name="_method" type="hidden" value="PATCH">
                <input type="hidden" name="lang" value="{{ $lang }}">
            	   @csrf
                <div class="form-group row">
                    <label class="col-md-3 col-from-label" for="name">{{translate('Name')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                    <div class="col-md-9">
                        @php $roleForTranslation = \App\Models\Role::where('id',$role->id)->first(); @endphp
                        <input type="text" placeholder="{{translate('Name')}}" id="name" name="name" class="form-control"
                        value="{{ $roleForTranslation->getTranslation('name', $lang) }}" required
                        @if ($role->created_by == 1)
                            disabled
                        @endif >
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-from-label" for="name">{{translate('Description')}}</label>
                    <div class="col-md-9">
                        <textarea placeholder="{{translate('Description')}}" id="description" name="description" class="form-control"
                        @if ($role->created_by == 1)
                            disabled
                        @endif  >{{$role->description}}</textarea>
                    </div>
                </div>
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Permissions') }}</h5>
                </div>
                <br>
                @php
                    $user = Auth::user();
                    $user_role= \App\Models\Staff::where('user_id',$user->id)->first()->role;
                    $permission_groups=$user_role->permissions->groupBy('section');
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
                            <li class="list-group-item bg-light d-flex " aria-current="true">
                                <div class="mr-4">{{ translate(Str::headline($permission_group[0]['section'])) }}</div>

                                <!-- Add checkbox for selecting/deselecting all permissions in this group -->
                                <label class="aiz-switch aiz-switch-success">
                                    <input type="checkbox" class="form-control demo-sw select-all-permissions" data-group="{{ $permission_group[0]['section'] }}"
                                    @if ($role->created_by == 1)
                                                            disabled
                                                        @endif>
                                    <span class="slider round"></span>
                                </label>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    @foreach ($permission_group as $key => $permission)
                                        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                                            <div class="p-2 border mt-1 mb-2">
                                                <label class="control-label d-flex">{{ translate(Str::headline($permission->name))}}</label>
                                                <label class="aiz-switch aiz-switch-success">
                                                    <input type="checkbox" name="permissions[]" class="form-control demo-sw {{$permission_group[0]['section']}}" value="{{ $permission->id }}"
                                                        @if ($role->hasPermissionTo($permission->name))
                                                            checked
                                                        @endif
                                                        @if ($role->created_by == 1)
                                                            disabled
                                                        @endif >
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
                @if ($role->seller_id != 1)
                <div class="form-group mb-3 mt-3 text-right">
                    <button type="submit" class="btn btn-primary">{{translate('Update')}}</button>
                </div>
                @endif
            </form>
        </div>

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
