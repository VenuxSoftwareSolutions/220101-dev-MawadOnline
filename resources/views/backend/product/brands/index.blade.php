@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="align-items-center">
		<h1 class="h3">{{translate('All Brands')}}</h1>
	</div>
</div>

<div class="row">
	<div class="@if(auth()->user()->can('add_brand')) col-lg-9 @else col-lg-12 @endif">
		<div class="card">
		    <div class="card-header row gutters-5">
				<div class="col text-center text-md-left">
					<h5 class="mb-md-0 h6">{{ translate('Brands') }}</h5>
				</div>
				<div class="col-md-4">
					<form class="" id="sort_brands" action="" method="GET">
						<div class="input-group input-group-sm">
					  		<input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type name & Enter') }}">
							  <select class="form-control ml-2" name="status" onchange="this.form.submit()">
                                <option value="">{{ translate('All Statuses') }}</option>
                                <option value="0" {{ $status_filter === '0' ? 'selected' : '' }}>
                                    {{ translate('Pending') }}
                                </option>
                                <option value="1" {{ $status_filter === '1' ? 'selected' : '' }}>
                                    {{ translate('Approved') }}
                                </option>
                                <option value="4" {{ $status_filter === '4' ? 'selected' : '' }}>
                                    {{ translate('Under Review') }}
                                </option>
                                <option value="2" {{ $status_filter === '2' ? 'selected' : '' }}>
                                    {{ translate('Revision Required') }}
                                </option>
                                <option value="3" {{ $status_filter === '3' ? 'selected' : '' }}>
                                    {{ translate('Rejected') }}
                                </option>
                            </select>
						</div>
					</form>
				</div>
		    </div>
		    <div class="card-body">
				<div class="table-responsive">

                    <table class="table aiz-table mb-0 text-nowrap">
						<thead>
							<tr>
								<th>#</th>
								<th>{{translate('Name')}}</th>
								<th>{{translate('Meta Title')}}</th>
								<th>{{translate('Meta Description')}}</th>
								<th>{{ translate('Status') }}</th>
								<th>{{translate('Logo')}}</th>
								<th class="text-right">{{translate('Options')}}</th>
							</tr>
						</thead>
						<tbody>
							@foreach($brands as $key => $brand)
								<tr>
									<td>{{ ($key+1) + ($brands->currentPage() - 1)*$brands->perPage() }}</td>
									<td>{{ $brand->getTranslation('name') }}</td>
									<td>{{ $brand->meta_title }}</td>
									<td>{{ $brand->meta_description }}</td>
									<td>
										@switch($brand->approved)
										@case(0)
											<span class="badge badge-primary width-badge"
												style="background-color: #2E294E;">{{ translate('Pending') }}</span>
										@break

										@case(1)
											<span class="badge badge-success width-badge">{{ translate('Approved') }}</span>
										@break

										@case(4)
											<span class="badge badge-info width-badge">{{ translate('Under Review') }}</span>
										@break

										@case(2)
											<span
												class="badge badge-warning width-badge">{{ translate('Revision Required') }}</span>
										@break

										@case(3)
											<span class="badge badge-danger width-badge">{{ translate('Rejected') }}</span>
										@break
									@endswitch
									</td>
									<td>
										<img src="{{ uploaded_asset($brand->logo) }}" alt="{{translate('Brand')}}" class="h-50px">
									</td>
									<td class="text-right">
										@can('edit_brand')
											<a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('brands.edit', ['id'=>$brand->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ translate('Edit') }}">
												<i class="las la-edit"></i>
											</a>
										@endcan
										@can('delete_brand')
											<a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('brands.destroy', $brand->id)}}" title="{{ translate('Delete') }}">
												<i class="las la-trash"></i>
											</a>
										@endcan
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
					<div class="aiz-pagination">
						{{ $brands->appends(request()->input())->links() }}
					</div>
				</div>
		    </div>
		</div>
	</div>
	@can('add_brand')
		<div class="col-md-3">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-0 h6">{{ translate('Add New Brand') }}</h5>
				</div>
				<div class="card-body">
					<form action="{{ route('brands.store') }}" method="POST">
						@csrf
						<div class="form-group mb-3">
							<label for="name">{{translate('Name')}}</label>
							<input type="text" placeholder="{{translate('Name')}}" name="name" class="form-control" required>
						</div>
						<div class="form-group mb-3">
							<label for="name">{{translate('Logo')}} <small>({{ translate('120x80') }})</small></label>
							<div class="input-group" data-toggle="aizuploader" data-type="image">
								<div class="input-group-prepend">
										<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
								</div>
								<div class="form-control file-amount">{{ translate('Choose File') }}</div>
								<input type="hidden" name="logo" class="selected-files">
							</div>
							<div class="file-preview box sm">
							</div>
						</div>
						<div class="form-group mb-3">
							<label for="name">{{translate('Meta Title')}}</label>
							<input type="text" class="form-control" name="meta_title" placeholder="{{translate('Meta Title')}}">
						</div>
						<div class="form-group mb-3">
							<label for="name">{{translate('Meta Description')}}</label>
							<textarea name="meta_description" rows="5" class="form-control"></textarea>
						</div>
						<div class="form-group mb-3 text-right">
							<button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	@endcan
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
<script type="text/javascript">
    function sort_brands(el){
        $('#sort_brands').submit();
    }
</script>
@endsection
