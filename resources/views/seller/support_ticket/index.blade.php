@extends('seller.layouts.app')

@section('panel_content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Support Tickets') }}</h1>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Tickets') }}</h5>
            <div class="row" style="gap: 5px;">
                <div class="col px-0">
                    <div class="input-group input-group-sm">
                        <select id="tickets_status" class="select2 form-control" multiple="multiple">
                            <option value="pending"
                                @isset($ticket_status) @if ($ticket_status == 'pending') selected @endif @endisset>
                                {{ __('Pending') }}</option>
                            <option value="resolved"
                                @isset($ticket_status) @if ($ticket_status == 'resolved') selected @endif @endisset>
                                {{ __('Resolved') }}</option>
                            <option value="submitted"
                                @isset($ticket_status) @if ($ticket_status == 'submitted') selected @endif @endisset>
                                {{ __('Submitted') }}</option>
                            <option value="under review"
                                @isset($ticket_status) @if ($ticket_status == 'under review') selected @endif @endisset>
                                {{ __('Under Review') }}</option>
                            <option value="rejected"
                                @isset($ticket_status) @if ($ticket_status == 'rejected') selected @endif @endisset>
                                {{ __('Rejected') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col px-0">
                    <div class="input-group input-group-sm">
                        <select id="sub_order_status" class="select2 form-control" multiple="multiple">
                            <option value="pending"
                                @isset($sub_order_status) @if ($sub_order_status == 'pending') selected @endif @endisset>
                                {{ translate('Pending') }}</option>
                            <option value="confirmed"
                                @isset($sub_order_status) @if ($sub_order_status == 'confirmed') selected @endif @endisset>
                                {{ translate('Confirmed') }}</option>
                            <option value="on_the_way"
                                @isset($sub_order_status) @if ($sub_order_status == 'on_the_way') selected @endif @endisset>
                                {{ translate('On The Way') }}</option>
                            <option value="delivered"
                                @isset($sub_order_status) @if ($sub_order_status == 'delivered') selected @endif @endisset>
                                {{ translate('Delivered') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col d-none">
                    <div id="step2" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#ticket_modal">
                        <i class="las la-plus la-1x text-white"></i> {{ translate('Create ticket') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table id="step1" class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th data-breakpoints="lg">{{ translate('Ticket ID') }}</th>
                        <th data-breakpoints="lg">{{ translate('Sending Date') }}</th>
                        <th>{{ translate('Subject') }}</th>
                        <th>{{ translate('Status') }}</th>
                        <th data-breakpoints="lg">{{ translate('Options') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tickets as $key => $ticket)
                        <tr>
                            <td>#{{ $ticket->code }}</td>
                            <td>{{ $ticket->created_at }}</td>
                            <td>{{ $ticket->subject }}</td>
                            <td>
                                @if (str()->lower($ticket->status) === 'pending')
                                    <span class="badge badge-inline badge-danger">{{ translate('Pending') }}</span>
                                @elseif ($ticket->status == 'Submitted')
                                    <span class="badge badge-inline badge-secondary">{{ translate('Submitted') }}</span>
                                @elseif (str()->lower($ticket->status) === 'resolved')
                                    <span class="badge badge-inline badge-success">{{ translate('Resolved') }}</span>
                                @elseif ($ticket->status == 'Under Review')
                                    <span class="badge badge-inline badge-warning">{{ translate('Under Review') }}</span>
                                @elseif (str()->lower($ticket->status) === 'rejected')
                                    <span class="badge badge-inline badge-info">{{ translate('Rejected') }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('seller.support_ticket.show', encrypt($ticket->id)) }}"
                                    class="btn btn-styled btn-link py-1 px-0 icon-anim text-underline--none">
                                    {{ translate('View Details') }}
                                    <i class="la la-angle-right text-sm"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $tickets->links() }}
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <div class="modal fade" id="ticket_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title strong-600 heading-5">{{ translate('Create a Ticket') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body px-3 pt-3">
                    <form class="" action="{{ route('seller.support_ticket.store') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-2">
                                <label>{{ translate('order') }}</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" class="form-control mb-3" placeholder="{{ translate('Subject') }}"
                                    name="subject" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label>{{ translate('Subject') }}</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" class="form-control mb-3" placeholder="{{ translate('Subject') }}"
                                    name="subject" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <label>{{ translate('Provide a detailed description') }}</label>
                            </div>
                            <div class="col-md-10">
                                <textarea type="text" class="form-control mb-3" rows="3" name="details"
                                    placeholder="{{ translate('Type your reply') }}"
                                    data-buttons="bold,underline,italic,|,ul,ol,|,paragraph,|,undo,redo" required></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">{{ translate('Photo') }}</label>
                            <div class="col-md-10">
                                <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            {{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="attachments" class="selected-files">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>
                        <div class="text-right mt-4">
                            <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">{{ translate('cancel') }}</button>
                            <button type="submit" class="btn btn-primary">{{ translate('Send Ticket') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ static_asset('assets/js/helpers.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $('#tickets_status').select2({
                placeholder: "{{ __('Choose ticket status') }}"
            });

            $('#sub_order_status').select2({
                placeholder: "{{ __('Choose sub-order status') }}"
            });

            @if (is_null($ticket_status) === false && is_string($ticket_status) === false)
                const selectedTicketsOptions = @json($ticket_status->toArray());
                $('#tickets_status').val(selectedTicketsOptions).trigger('change');
            @endif

            @if (is_null($sub_order_status) === false && is_string($sub_order_status) === false)
                const selectedSubOrderStatusOptions = @json($sub_order_status->toArray());
                $('#sub_order_status').val(selectedSubOrderStatusOptions).trigger('change');
            @endif

            $("#tickets_status").on('change', function() {
                updateUrl('ticket_status', $(this).val());
            });

            $("#sub_order_status").on('change', function() {
                updateUrl('sub_order_status', $(this).val());
            });

            document.getElementById('startTourButton').addEventListener('click', function(event) {
                event.preventDefault();
                localStorage.setItem('guide_tour', '0');
                window.location.href = '{{ route('seller.dashboard') }}';
            });

            if (localStorage.getItem('guide_tour') != '0') {
                if ({{ Auth::user()->tour }} == true | {{ Auth::user()->id }} != {{ Auth::user()->owner_id }}) {
                    return;
                }
            }

            let tour_steps = [
                @foreach ($tour_steps as $key => $step)
                    {
                        element: document.querySelector('#{{ $step->element_id }}'),
                        title: '{{ $step->getTranslation('title') }}',
                        intro: "{{ $step->getTranslation('description') }}",
                        position: '{{ $step->getTranslation('lang') === 'en' ? 'right' : 'left' }}'
                    },
                @endforeach
            ];
            let lang = '{{ $tour_steps[0]->getTranslation('lang') }}';
            let tour = introJs();
            let step_number = 0;
            tour.setOptions({
                steps: tour_steps,
                nextLabel: lang == 'en' ? 'Next' : 'التالي',
                prevLabel: lang == 'en' ? 'Back' : 'رجوع',
                exitOnEsc: false,
                exitOnOverlayClick: false,
                disableInteraction: true,
                overlayOpacity: 0.4,
                showStepNumbers: true,
                hidePrev: true,
                showProgress: true,
            });


            tour.onexit(function() {
                $.ajax({
                    url: "{{ route('seller.tour') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log('User tour status updated successfully');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating user tour status:', error);
                    }
                });
                localStorage.setItem('guide_tour', '1');
                setTimeout(function() {
                    window.location.href = '{{ route('seller.dashboard') }}';
                }, 500);
            });

            tour.onbeforechange(function(targetElement) {
                if (this._direction === 'backward') {
                    window.location.href = '{{ route('seller.sales.index') }}';
                    sleep(60000);
                }
                step_number += 1;
                if (step_number == 3) {
                    window.location.href = '{{ route('seller.profile.index') }}';
                    sleep(60000);
                }
            });

            tour.start();
            tour.goToStepNumber(13);
        });
    </script>
@endsection
