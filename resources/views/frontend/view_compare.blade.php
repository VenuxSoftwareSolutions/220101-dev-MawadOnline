@extends('frontend.layouts.app')

@section('content')
    <section class="mb-4 mt-3">
        <div class="container text-left">
            <div class="bg-white shadow-sm rounded py-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="fw-700 text-dark">{{ translate('Compare Products') }}</h2>
                    <a href="{{ route('compare.reset') }}" class="btn btn-soft-primary btn-sm fw-600"
                        onclick="clearLocalStorage()">
                        {{ translate('Reset Compare List') }}
                    </a>
                </div>
                @if (!$compareList)
                    <div class="compare-container position-relative">
                        <div class="c-preloader text-center absolute-center">
                            <i class="las la-spinner la-spin la-3x opacity-70"></i>
                        </div>
                    </div>
                @endif
                <div class="compare-content d-none">
                    @if (Auth::check())
                        @include('frontend.partials.compare_table', ['compareData' => $compareList])
                    @else
                        {{-- Compare data will be loaded dynamically for guests --}}
                    @endif
                </div>
            </div>
    </section>

    <div id="delete-confirmation-modal" class="modal fade">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6">{{ translate('Delete Confirmation') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body text-center">
                    <p class="mt-1 fs-14">{{ translate('Are you sure you want to delete this item?') }}</p>
                    <button type="button" class="btn btn-secondary rounded-0 mt-2" data-dismiss="modal">
                        {{ translate('Cancel') }}
                    </button>
                    <button type="button" class="btn btn-danger rounded-0 mt-2" id="confirm-delete-btn">
                        {{ translate('Delete') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const compareData = localStorage.getItem('compare');
            const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
            const spinner = document.querySelector('.c-preloader');
            const contentContainer = document.querySelector('.compare-content');
            const compareContainer = document.querySelector('.compare-container');

            // Helper function to show "No items" message
            const showNoItemsMessage = () => {
                compareContainer.innerHTML = `
            <h4 class="fw-600 text-primary mb-3 text-center">
                {{ translate('No items in the compare list') }}
            </h4>
        `;
                spinner.classList.add('d-none');
                contentContainer.classList.add('d-none');
            };

            if (!isLoggedIn) {
                if (!compareData) {
                    showNoItemsMessage();
                    return;
                }

                fetch('/compare/local', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            compareData
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.html) {
                            compareContainer.innerHTML = data.html;
                            spinner.classList.add('d-none');
                            contentContainer.classList.remove('d-none');
                        } else {
                            console.warn('No data returned from the server.');
                            showNoItemsMessage();
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching compare data:', error);
                        showNoItemsMessage();
                    });
            } else {
                
                spinner.classList.add('d-none');
                contentContainer.classList.remove('d-none');
                
            ddd}
        });
    </script>
@endsection
