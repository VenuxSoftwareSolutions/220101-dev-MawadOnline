@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h6">{{ translate('Brand Information') }}</h5>
    </div>

    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-body p-0">
                <ul class="nav nav-tabs nav-fill border-light">
                    @foreach (get_all_active_language() as $key => $language)
                        <li class="nav-item">
                            <a class="nav-link text-reset @if ($language->code == $lang) active @else bg-soft-dark border-light border-left-0 @endif py-3"
                                href="{{ route('brands.edit', ['id' => $brand->id, 'lang' => $language->code]) }}">
                                <img src="{{ static_asset('assets/img/flags/' . $language->code . '.png') }}" height="11"
                                    class="mr-1">
                                <span>{{ $language->name }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
                <div class="form-group mb-3 text-right">
                    <a href="{{ route('brands.index') }}" class="btn btn-secondary">
                        <i class="las la-arrow-left"></i> {{ translate('Back to Brands') }}
                    </a>
                </div>
                <form class="p-4" action="{{ route('brands.update', $brand->id) }}" method="POST"
                    enctype="multipart/form-data">
                    <input name="_method" type="hidden" value="PATCH">
                    <input type="hidden" name="lang" value="{{ $lang }}">
                    @csrf
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">{{ translate('Name') }} <i
                                class="las la-language text-danger" title="{{ translate('Translatable') }}"></i></label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{ translate('Name') }}" id="name" name="name"
                                value="{{ $brand->getTranslation('name', $lang) }}" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="signinSrEmail">{{ translate('Logo') }}
                            <small>({{ translate('120x80') }})</small></label>
                        <div class="col-md-9">
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">
                                        {{ translate('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="logo" value="{{ $brand->logo }}" class="selected-files">
                            </div>
                            <div class="file-preview box sm">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label">{{ translate('Meta Title') }}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="meta_title" value="{{ $brand->meta_title }}"
                                placeholder="{{ translate('Meta Title') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label">{{ translate('Meta Description') }}</label>
                        <div class="col-sm-9">
                            <textarea name="meta_description" rows="8" class="form-control">{{ $brand->meta_description }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">{{ translate('Slug') }}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{ translate('Slug') }}" id="slug" name="slug"
                                value="{{ $brand->slug }}" class="form-control">
                        </div>
                    </div>
                    <input type="hidden" id="approved" name="approved" value="{{ $brand->approved }}">

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="status">{{ translate('Status') }}</label>
                        <div class="col-sm-9 d-flex align-items-center">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input type="checkbox" id="status" @if ($brand->approved == 1) checked @endif>
                                <span></span>
                            </label>
                            <span class="ml-2">
                                @if ($brand->approved == 1)
                                    <strong>{{ translate('Active') }}</strong>
                                @else
                                    <strong>{{ translate('Inactive') }}</strong>
                                @endif
                            </span>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="approved_at">{{ translate('Approved At') }}</label>
                        <div class="col-sm-9">
                            <input type="text" id="approved_at" name="approved_at"
                                value="{{ $brand->approved_at ?? translate('Not Approved Yet') }}" class="form-control"
                                disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="approved_by">{{ translate('Approved By') }}</label>
                        <div class="col-sm-9">
                            <input type="text" id="approved_by" name="approved_by"
                                value="{{ optional($brand->approvedUser)->name ?? translate('Not Approved Yet') }}"
                                class="form-control" disabled>
                        </div>
                    </div>

                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@section('modal')
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h6" id="confirmModalLabel">{{ translate('Confirmation') }}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body" id="confirmModalBody">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="button" class="btn btn-primary"
                        id="confirmationButton">{{ translate('Yes') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            let approvedVal = parseInt($('#approved').val()) || 0;
            $('#status').prop('checked', approvedVal === 1);

            $('#status').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#confirmModalLabel').text("{{ translate('Approve Brand') }}");
                    $('#confirmModalBody').text(
                        "{{ translate('Are you sure you want to approve this brand?') }}");
                    $('#confirmationButton').text("{{ translate('Yes, Approve') }}");
                    $('#confirmationButton').data('action', 'approve');
                    $('#confirmModal').modal('show');
                } else {
                    $('#confirmModalLabel').text("{{ translate('Disapprove Brand') }}");
                    $('#confirmModalBody').text(
                        "{{ translate('Are you sure you want to disapprove this brand?') }}");
                    $('#confirmationButton').text("{{ translate('Yes, Disapprove') }}");
                    $('#confirmationButton').data('action', 'disapprove');
                    $('#confirmModal').modal('show');
                }
            });

            $('#confirmationButton').on('click', function() {
                const action = $(this).data('action');
                if (action === 'approve') {
                    $('#approved').val(1);

                    let now = new Date().toLocaleString();
                    $('#approved_at').val(now);
                    $('#approved_by').val("{{ Auth::user()->name }}");
                } else {
                    $('#approved').val(0);
                    $('#approved_at').val("{{ translate('Not Approved Yet') }}");
                    $('#approved_by').val("{{ translate('Not Approved Yet') }}");
                }

                $('#confirmModal').modal('hide');
            });

            $('#confirmModal').on('hide.bs.modal', function(e) {
                let finalApproved = parseInt($('#approved').val()) || 0;
                $('#status').prop('checked', finalApproved === 1);
            });
        });
    </script>
@endsection
