@extends('seller.layouts.app')

@section('panel_content')
    <div class="coming-soon-container">
        <img src="../public/assets/img/Coming_Soon_Illustration1.svg" alt="Coming Soon Illustration">
        <h1>Coming Soon...</h1>
        <p>Exciting updates are on the horizon! We're gearing up to unveil powerful new features designed to supercharge
            your selling experience. Stay tuned for a smoother, more intuitive dashboard.</p>
        <input type="email" class="email-input" placeholder="Enter your email">
        <button class="notify-btn">Notify Me</button>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if ({{ Auth::user()->tour }} == true | {{ Auth::user()->id }} != {{ Auth::user()->owner_id }}) {
                return;
            }
            var stepValue = {{ $step }};

            var tour_steps = [
                @foreach ($tour_steps as $key => $step)
                    {
                        element: document.querySelector('#{{ $step->element_id }}'),
                        title: '{{ $step->title }}',
                        intro: "{{ $step->description }}",
                        position: 'right'
                    },
                @endforeach
            ];

            let tour = introJs();
            let step_number = 0;
            tour.setOptions({
                steps: tour_steps,
                doneLabel: 'Next', // Replace the "Done" button with "Next"
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
