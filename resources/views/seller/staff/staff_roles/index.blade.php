@extends('seller.layouts.app')

@section('panel_content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3">{{translate('All Role')}}</h1>
		</div>

            <div class="col-md-6 text-md-right">
                <a href="{{ route('seller.roles.create') }}" class="btn btn-circle btn-info">
                    <span>{{translate('Add New Role')}}</span>
                </a>
            </div>

	</div>
</div>
{{-- <div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Add New Permission')}}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('seller.roles.permission') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="name">{{translate('Name')}}</label>
                        <input type="text" id="name" name="name" placeholder="{{ translate('Permission') }}" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="name">{{translate('Parent')}}</label>
                        <input type="text" id="parent" name="parent" placeholder="{{ translate('Parent') }}" class="form-control" required>
                    </div>
                    <div class="form-group mb-3 text-right">
                        <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> --}}

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Roles')}}</h5>
    </div>
    <div class="card-body">
        <table class="table aiz-table">
            <thead>
                <tr>
                    <th width="10%">#</th>
                    <th width="25%">{{translate('Name')}}</th>
                    <th>{{translate('Description')}}</th>
                    <th class="text-right" width="10%">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $key => $role)
                    <tr>
                        <td>{{ ($key+1) + ($roles->currentPage() - 1)*$roles->perPage() }}</td>
                        <td>{{ $role->name}}</td>
                        <td>{{ $role->description}}</td>
                        <td class="text-right">

                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('seller.roles.edit', ['id'=>$role->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ translate('Edit') }}">
                                    @if($role->id != 1 && $role->created_by != 1 )
                                    <i class="las la-edit"></i>
                                    @else
                                    <i class="la la-list-alt"></i>
                                    @endif
                                </a>

                            @if($role->id != 1 && $role->created_by != 1 )
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('seller.roles.destroy', $role->id)}}" title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $roles->appends(request()->input())->links() }}
        </div>
    </div>
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection
