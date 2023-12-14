@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="align-items-center">
            <h1 class="h3">{{ translate('Edit unit') }}</h1>
        </div>
    </div>

    <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Edit unit') }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('units.update', $unit->id) }}" method="POST">
                            <input name="_method" type="hidden" value="PATCH">
                            @csrf
                            <div class="row">
                                <div class="form-group mb-3 col-6">
                                    <label for="name">{{ translate('Name in english') }}</label>
                                    <input type="text" id="name" name="name_english" class="form-control" value="{{ $unit->getTranslation('name','en',false) }}" required>
                                </div>
                                <div class="form-group mb-3 col-6">
                                    <label for="name">{{ translate('Name in arabic') }}</label>
                                    <input type="text"  id="name" name="name_arabic" class="form-control" value="{{ $unit->getTranslation('name','ar',false) }}" required>
                                </div>
                            </div>
                            <div class="form-group mb-3 text-center">
                                <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </div>
@endsection
