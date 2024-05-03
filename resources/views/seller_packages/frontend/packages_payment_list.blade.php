@extends('seller.layouts.app')
@section('panel_content')

    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Purchase Package List') }}</h1>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6">{{ translate('All Purchase Package') }}</h5>
            </div>
        </div>
        <div class="card-body">
            <table id="step1" class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th width="30%">{{ translate('Package')}}</th>
                        <th data-breakpoints="md">{{ translate('Package Price')}}</th>
                        <th data-breakpoints="md">{{ translate('Payment Type')}}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($seller_packages_payment as $key => $payment)
                        <tr>
                            <td>{{ ($key+1) + ($seller_packages_payment->currentPage() - 1) * $seller_packages_payment->perPage() }}</td>
                            <td>{{ $payment->seller_package->name ?? translate('Package Unavailable') }}</td>
                            <td>{{ $payment->seller_package->amount ?? translate('Package Unavailable') }}</td>
                            <td>
                                @if($payment->offline_payment == 1)
                                    {{ translate('Offline Payment') }}
                                @else
                                    {{ translate('Online Payment') }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $seller_packages_payment->links() }}
          	</div>
        </div>
    </div>

@endsection

@section('script')

    {{-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            if ({{Auth::user()->tour}} == true | {{Auth::user()->id}} != {{Auth::user()->owner_id}}) {
            return;
        }
        var tour_steps = [
            @foreach($tour_steps as $key => $step)
            {
                element: document.querySelector('#{{$step->element_id}}'),
                title: '{{$step->title}}',
                intro: "{{$step->description}}",
                position: 'right'
            },
            @endforeach
        ];

        let tour = introJs();
        let step_number = 0 ;
        tour.setOptions({
            steps: tour_steps ,
            doneLabel: 'Next', // Replace the "Done" button with "Next"
            exitOnEsc : false ,
            exitOnOverlayClick : false ,
            disableInteraction : true ,
            overlayOpacity : 0.4 ,
            showStepNumbers : true ,
            hidePrev : true ,
            showProgress :true ,
        });

            tour.onexit(function() {
                $.ajax({
                url: "{{ route('seller.tour') }}",
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' }, // Include CSRF token for Laravel
                success: function(response) {
                    // Handle success
                    console.log('User tour status updated successfully');
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error('Error updating user tour status:', error);
                }
            });
            setTimeout(function() {
                window.location.href = '{{ route("seller.dashboard") }}';
            }, 500);
            });

            tour.onbeforechange(function(targetElement) {
                step_number += 1 ;
                if (step_number == 3) {
                window.location.href = '{{ route("seller.staffs.index") }}';
                sleep(60000);
                }

                //tour.exit();
            });

        tour.start();
        tour.goToStepNumber(10);
        });
    </script> --}}
@endsection

