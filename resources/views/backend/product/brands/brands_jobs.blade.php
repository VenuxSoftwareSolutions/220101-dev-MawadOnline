@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="d-flex align-items-center justify-content-between">
        <h1 class="h3">{{ translate('Brands by Jobs') }}</h1>
        <a href="{{ route('brands.index') }}" class="btn btn-sm btn-light">
            <i class="las la-arrow-left"></i> {{ translate('Back') }}
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <!-- Header with search -->
            <div class="card-header row gutters-5">
                <div class="col text-center text-md-left">
                    <h5 class="mb-md-0 h6">{{ translate('Brands by Jobs') }}</h5>
                </div>
                <div class="col-md-4">
                    <form id="sort_jobs" action="" method="GET">
                        <div class="input-group input-group-sm">
                            <input type="text"
                                   class="form-control"
                                   id="search"
                                   name="search"
                                   @isset($sort_search) value="{{ $sort_search }}" @endisset
                                   placeholder="{{ translate('Type brand or vendor name & Enter') }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    {{ translate('Search') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Table -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table aiz-table mb-0 text-nowrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Brand Name') }}</th>
                                <th>{{ translate('Vendor Name') }}</th>
                                <th>{{ translate('Vendor Business Name') }}</th>
                                <th>{{ translate('Vendor Org File') }}</th>
                                <th>{{ translate('Created At') }}</th>
                                <th class="text-right">{{ translate('Options') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jobs as $key => $job)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @php
                                            // Collect all associated brand names
                                            $brandNames = $job->brands->pluck('name')->toArray();
                                        @endphp
                                        {{ implode(', ', $brandNames) }}
                                    </td>
                                    <td>
                                        {{  $job->vendor_name }}
                                    </td>
                                    <td>
                                        {{$job->vendor_business_name }}
                                    </td>
                                    <td>
                                        {{ $job->vendor_org_file }}
                                    </td>
                                    <td>
                                        {{ $job->created_at->format('Y-m-d H:i:s') }}
                                    </td>
                                    <td class="text-right">
                                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" 
                                           {{-- href="{{ route('jobs.show', $job->id) }}"  --}}
                                           title="{{ translate('View Details') }}">
                                            <i class="las la-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
