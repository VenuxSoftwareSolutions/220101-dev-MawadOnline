@extends('seller.layouts.app')

@section('panel_content')

<style>
/* Keep existing styles the same */
.pagination .active .page-link {
    background-color: #8f97ab !important;
}

/* ... rest of the existing styles ... */
</style>

<div class="aiz-titlebar mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-12">
            <h2 class="h3">{{ translate('Jobs History') }}</h2>
            <div class="row">
                <div class="col-md-8">
                    <p style="font-size: 16px;">{{ translate('Track and manage your background jobs. Monitor progress, review errors, and manage job executions efficiently.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <form class="" id="sort_jobs" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" id="search" name="search" 
                               @isset($search) value="{{ $search }}" @endisset 
                               placeholder="{{ translate('Search jobs') }}">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="dropdown mb-2 mb-md-0">
                    <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
                        {{translate('Bulk Action')}}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item confirm-alert" href="javascript:void(0)" 
                           data-target="#bulk-delete-modal">{{translate('Delete selection')}}</a>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr style="background-color: #f8f8f8;">
                            <th style="padding-left: 12px !important;">
                                <div class="form-group">
                                    <div class="aiz-checkbox-inline">
                                        <label class="aiz-checkbox">
                                            <input type="checkbox" class="check-all">
                                            <span class="aiz-square-check"></span>
                                        </label>
                                    </div>
                                </div>
                            </th>
                            <th>{{ translate('Job ID') }}</th>
                            <th>{{ translate('Product File') }}</th>
                            <th>{{ translate('Total Rows') }}</th>
                            <th>{{ translate('Creation Date') }}</th>
                            <th>{{ translate('Stage') }}</th>
                            <th>{{ translate('Progress') }}</th>
                            <th>{{ translate('Execution Error') }}</th>
                            <th>{{ translate('Error File') }}</th>
                            <th class="text-center">{{ translate('Actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($jobs as $job)
                            <tr>
                                <td>
                                    <div class="form-group d-inline-block">
                                        <label class="aiz-checkbox">
                                            <input type="checkbox" class="check-one" 
                                                   name="id[]" value="{{ $job->id }}">
                                            <span class="aiz-square-check"></span>
                                        </label>
                                    </div>
                                </td>
                              
                                <td class="text-monospace">{{ \Illuminate\Support\Str::substr($job->id, 0, 8) }}...{{\Illuminate\Support\Str::substr($job->id, -4) }}</td>
                    
                                <td>
                                    @if($job->vendor_products_file)
                                      <a href="{{ route('seller.bulk.jobs.download_product_file', $job->id) }}"
                                         class="text-reset"
                                         download>
                                        {{ basename($job->vendor_products_file) }}
                                      </a>
                                    @else
                                      -
                                    @endif
                                  </td>
                                  
                           
                                  
                                <td>{{ $job->total_rows }}</td>  
                                <td>{{ $job->created_at->format('d M Y H:i') }}</td>  
                                <td>
                                    <span class="badge 
                                        @if($job->stage == 'COMP') badge-success
                                        @elseif($job->stage == 'VENT') badge-warning
                                        @elseif($job->stage == 'VSUB') badge-info
                                        @elseif($job->stage == 'AIPROC') badge-primary
                                        @elseif($job->stage == 'AIDONE') badge-secondary
                                        @else badge-light @endif
                                        px-3 py-2 rounded-pill text-dark font-weight-semibold">

                                        @switch($job->stage)
                                            @case('VENT') Pending @break
                                            @case('VSUB') Submitted @break
                                            @case('AIPROC') AI Processing @break
                                            @case('AIDONE') AI Completed @break
                                            @case('COMP') Completed @break
                                            @default {{ ucfirst($job->stage) }}
                                        @endswitch
                                    </span>
                                </td>
                    
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar 
                                            @if($job->progress == 100) bg-success
                                            @elseif($job->progress > 75) bg-info
                                            @elseif($job->progress > 50) bg-primary
                                            @else bg-warning @endif" 
                                            role="progressbar" 
                                            style="width: {{ $job->progress }}%; min-width: 2em;"
                                            aria-valuenow="{{ $job->progress }}" 
                                            aria-valuemin="0" 
                                            aria-valuemax="100">
                                            {{ $job->progress }}%
                                        </div>
                                    </div>
                                </td>
                    
                                <td>
                                    @if($job->error_msg)
                                        <span class="text-danger">{{ Str::limit($job->error_msg, 30) }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($job->error_file)
                                      <a href="{{ route('seller.bulk.jobs.download_error_file', $job->id) }}"
                                         class="text-reset"
                                         download>
                                        {{ translate('Download Error File') }}
                                      </a>
                                    @else
                                      -
                                    @endif
                                  </td>
                                <td class="text-right remove-top-padding">
                                    <a href="#" class="btn btn-sm confirm-delete" 
                                       {{-- data-href="{{ route('bulk.jobs.destroy', $job->id) }}"   --}}
                                       title="{{ translate('Delete') }}">
                                        <img src="{{ asset('public/trash.svg') }}">
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="row">
                    <div class="col-6" style="padding-top: 11px !important;">
                        <p class="pagination-showin">
                            Showing {{ $jobs->firstItem() }} - {{ $jobs->lastItem() }} of {{ $jobs->total() }}
                        </p>
                    </div>
                    <div class="col-6">
                        <div class="pagination-container text-right" style="float: right;">
                            {{ $jobs->links('custom-pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Keep the existing modals, just update text if needed -->
    <div id="modal-info" class="modal fade">
        <!-- ... existing modal structure ... -->
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6" id="title-modal">{{translate('Delete Confirmation')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
               

                <div class="modal-body text-center">
                    <p class="mt-1 fs-14" id="text-modal">{{translate('Are you sure to delete this job?')}}</p>
                    <button type="button" class="btn btn-secondary rounded-0 mt-2" data-dismiss="modal" id="cancel_published">{{translate('Cancel')}}</button>
                    <button type="button" id="publish-link" class="btn btn-primary rounded-0 mt-2"></button>
                </div>
            </div>
        </div>

@endsection