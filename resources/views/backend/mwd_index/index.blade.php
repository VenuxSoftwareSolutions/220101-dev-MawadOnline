@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3">{{translate('All Leaf Categories')}}</h1>
		</div>
	</div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Leaf Categories')}}</h5>
        <form method="GET">
            <input
                name="search"
                class="form-control"
                @if(is_null($search) === false) value="{{ $search }}" @endif
                type="search"
                placeholder="{{ __("Type & Press Enter") }}"
            />
        </form>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th data-breakpoints="lg" width="10%">#</th>
                    <th>{{translate('Name')}}</th>
                    <th width="10%" class="text-right">{{translate('Selected')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $key => $category)
                    <tr>
                        <td>{{ $key + $categories->firstItem() }}</td>
                        <td>{{ $category->path_name }}</td>
                        <td>
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input value="{{ $category->id }}" class="selectedCategory__clz" type="checkbox" @if($category->selected == 1) checked @endif>
                                <span class=""> </span>
                            </label>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="row">
            <div class="col-6" style="padding-top: 11px; !important">
                <p class="pagination-showin">
                    {{ __("product.showing_items_pagination", [
                            "first" => $categories->firstItem(),
                            "last" => $categories->lastItem(),
                            "total" => $categories->total()
                        ]) }}
                </p>
            </div>
            <div class="col-6">
                <div class="pagination-container float-right">
                    {{ $categories->links('custom-pagination') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section("script")
    <script>
        $(document).ready(function() {
            $(document).on("change", ".selectedCategory__clz", function() {
                let selected = this.checked;
                let category_id = $(this).val();
                let token = $('meta[name="csrf-token"]').attr('content');

                fetch("{{ route('admin.mawad.index.select.category') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": token
                    },
                    body: JSON.stringify({
                        category_id,
                        selected
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.error === false) {
                        AIZ.plugins.notify('success', data.message);
                    }
                })
                .catch(error => {
                    AIZ.plugins.notify('danger', error.message);

                    location.reload();
                });
            });
        });
</script>
@endsection
