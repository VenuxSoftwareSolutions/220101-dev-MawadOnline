@extends('seller.layouts.app')

@section('panel_content')

<style>
.pagination .active .page-link {
    background-color: #8f97ab !important;
}
.pagination .page-link:hover{
    background-color: #8f97ab !important;
}

.pagination-showin{
    Weight:400;
    size: 16px;
    line-height: 24px;
    color: #808080;
}

thead tr{
    height: 53px !important;
    padding: 0 !important;
    margin: 0 !important;
}


.aiz-table th {
    padding: 0 !important;
    vertical-align: middle !important;
}

.remove-top-padding {
    padding-top: 0 !important;
}

</style>

<div class="aiz-titlebar mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-12">
            <h2 class="h3">{{ translate('Smart Bulk Upload History') }}</h2>
            <div class="row">
                <div class="col-md-8">
                    <p style="font-size: 16px;">
                        {{ translate('Track and manage your Smart Bulk Upload jobs. Monitor the status of your product uploads, review any errors, and ensure efficient bulk product management.') }}
                    </p>
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
                    <button type="button"  id="delete-selected-btn" data-toggle="modal" data-target="#confirm-bulk-delete-modal">

                        <a class="dropdown-item confirm-alert" href="javascript:void(0)" 
                          >{{translate('Delete selection')}}</a>

                    </button>
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
                                            <input type="checkbox" class="check-all" >
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
                                            <input type="checkbox" class="job-checkbox" value="{{ $job->id }}">

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
                                    <div class="progress w-100" style="height: 20px;">
                                      <div
                                        id="progress-bar-{{ $job->id }}"
                                        class="progress-bar {{ $job->progress == 100
                                             ? 'bg-success'
                                             : ($job->progress > 75
                                                ? 'bg-info'
                                                : ($job->progress > 50
                                                   ? 'bg-primary'
                                                   : 'bg-warning')) }}"
                                        role="progressbar"
                                        style="width: {{ $job->progress }}%; min-width: 2em;"
                                        aria-valuenow="{{ $job->progress }}"
                                        aria-valuemin="0"
                                        aria-valuemax="100"
                                        data-progress-url="{{ route('seller.bulk.jobs.progress', $job->id) }}"
                                      >
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
                                  <td>
                                    <a href="#" class="btn btn-sm confirm-delete"
                                        data-href="{{ route('seller.bulk.jobs.destroy', $job->id) }}"
                                        data-toggle="modal"
                                        data-target="#confirm-delete-modal"
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

    <div class="modal fade" id="confirm-delete-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h6">{{translate('Confirmation')}}</h5>
                    <button type="button" class="close" data-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>{{translate('Do you really want to delete this job?')}}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Cancel')}}</button>
                    <form id="delete-form" method="POST">
                        @csrf
                        @method('DELETE')
                    </form>                    
                    <button type="button" id="confirmation" class="btn btn-primary">{{translate('Proceed!')}}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirm-bulk-delete-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h6">{{translate('Confirmation')}}</h5>
                    <button type="button" class="close" data-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>{{translate('Do you really want to delete selected jobs?')}}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Cancel')}}</button>
                    <form id="bulk-delete-form" method="POST" action="{{ route('seller.bulk.jobs.bulkDestroy') }}">
                        @csrf
                        @method('DELETE')
                    </form>                    
                    <button type="button" id="confirm-bulk-delete" class="btn btn-primary">{{translate('Proceed!')}}</button>
                </div>
            </div>
        </div>
    </div>
    
     
@endsection
@section('script')

    <script type="text/javascript">

        document.addEventListener('DOMContentLoaded',function() {
            const bars = Array.from(
                document.querySelectorAll('[id^="progress-bar-"]')
            );

            function refreshBar(el) {
                const url = el.dataset.progressUrl;
                if (!url) return;

                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res => {
                    if (!res.ok) throw new Error(res.status + ' ' + res.statusText);
                    return res.json();
                })
                .then(json => {
                    const p = parseInt(json.progress, 10);
                    if (isNaN(p)) return;

                    el.style.setProperty('width', p + '%', 'important');

                    el.textContent = p + '%';

                    el.setAttribute('aria-valuenow', p);

                    el.classList.remove('bg-success','bg-info','bg-primary','bg-warning');
                    if      (p === 100) el.classList.add('bg-success');
                    else if (p > 75)    el.classList.add('bg-info');
                    else if (p > 50)    el.classList.add('bg-primary');
                    else                el.classList.add('bg-warning');
                })
                .catch(err => console.error('refreshBar error for', url, err));
            }
            
            


            bars.forEach(refreshBar);
            setInterval(() => bars.forEach(refreshBar), 60_000);
        });
    let deleteUrl = null;

    $(document).on('click', '.confirm-delete', function (e) {
        e.preventDefault();
        deleteUrl = $(this).data('href');
        console.log('Set delete URL:', deleteUrl);
    });

    $('#confirmation').on('click', function () {
        console.log('Deleting from:', deleteUrl);
        if (deleteUrl) {
            const form = $('#delete-form');
            form.attr('action', deleteUrl);
            form.submit();
        }
    });
    
    $(document).ready(function () {
    $('#delete-selected-btn').on('click', function () {
        
        const selectedIds = $('.job-checkbox:checked').map(function () {
            return $(this).val();
        }).get();

        if (selectedIds.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Jobs Selected',
                text: 'Please select at least one job to delete.',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });

            $('#confirm-bulk-delete-modal').modal('hide');
            return;
        }


        $('#bulk-job-ids').remove(); 
        const hiddenInputs = selectedIds.map(id => `<input type="hidden" name="job_ids[]" value="${id}">`);
        $('#bulk-delete-form').append(hiddenInputs.join(''));
    });

    $('#confirm-bulk-delete').on('click', function () {
        $('#bulk-delete-form').attr('action', "{{ route('seller.bulk.jobs.bulkDestroy') }}").submit();
    });
});


    </script>
    
@endsection