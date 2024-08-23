@extends('seller.layouts.app')

@section('panel_content')
    <div class="coming-soon-container">
        <img src="../public/assets/img/Coming_Soon_Illustration1.svg" alt="Coming Soon Illustration">
        <h1 style="font-weight: 600;font-size: 32px;line-height: 40px;" class="mt-5">Coming Soon...</h1>
        <p style="font-weight: 400;font-size: 16px;line-height: 24px;">Exciting updates are on the horizon! We're gearing up to unveil powerful new features designed to supercharge
            your selling experience. Stay tuned for a smoother, more intuitive dashboard.</p>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('startTourButton').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default anchor click behavior
        localStorage.setItem('guide_tour', '0'); // Set local storage as required
        window.location.href = '{{ route("seller.dashboard") }}'; // Redirect to the dashboard
    });
    if (localStorage.getItem('guide_tour') != '0') {
        if ({{Auth::user()->tour}} == true | {{Auth::user()->id}} != {{Auth::user()->owner_id}} ) {
            return;
        }
    }
            var stepValue = {{ $step }};

            var tour_steps = [
            @foreach($tour_steps as $key => $step)
            {
                element: document.querySelector('#{{$step->element_id}}'),
                title: '{{$step->getTranslation('title')}}',
                intro: "{{$step->getTranslation('description')}}",
                position: '{{ $step->getTranslation('lang') === 'en' ? 'right' : 'left' }}'
            },
            @endforeach
        ];
        var lang = '{{$tour_steps[0]->getTranslation('lang')}}';
        let tour = introJs();
        let step_number = 0 ;
        tour.setOptions({
            steps: tour_steps ,
            nextLabel: lang == 'en' ? 'Next' : 'التالي',
            prevLabel: lang == 'en' ? 'Back' : 'رجوع',
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
                    data: {
                        _token: '{{ csrf_token() }}'
                    }, // Include CSRF token for Laravel
                    success: function(response) {
                        // Handle success


                        console.log('User tour status updated successfully');
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error('Error updating user tour status:', error);
                    }
                });
                localStorage.setItem('guide_tour', '1'); // Set local storage as required

                setTimeout(function() {
                    window.location.href = '{{ route('seller.dashboard') }}';
                }, 500);
            });

            tour.onbeforechange(function(targetElement) {

                if (this._direction === 'backward') {
                if (stepValue == 3) {
                        window.location.href = '{{ route('catalog.search_page') }}';
                    } else if (stepValue == 12) {
                        window.location.href = '{{ route('seller.lease.index') }}';
                    } else {
                        window.location.href = '{{ route('seller.stock.operation.report') }}';
                    }
                    sleep(60000);
                }

                step_number += 1;
                if (step_number == 3) {
                    if (stepValue == 3) {
                        window.location.href = '{{ route('seller.stocks.index') }}';
                    } else if (stepValue == 12) {
                        window.location.href = '{{ route('seller.support_ticket.index') }}';
                    } else {
                        window.location.href = '{{ route('seller.seller_packages_list') }}';
                    }
                    sleep(60000);
                }

                //tour.exit();
            });

            tour.start();
            if (stepValue == 3) {
                tour.goToStepNumber(4);
            } else if (stepValue == 12) {
                tour.goToStepNumber(12);
            } else {
                tour.goToStepNumber(8);
            }

        });
    </script>
@endsection
