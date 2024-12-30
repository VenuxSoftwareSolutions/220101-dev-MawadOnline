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

                <div class="compare-container">
                    <!-- The dynamic content will be rendered here -->
                </div>
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

        if (!compareData) {
            console.log('No compare data found in local storage.');
            return;
        }

        fetch('/compare/local', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ compareData })
        })
        .then(response => response.json())
        .then(data => {
            if (data.html) {
                document.querySelector('.compare-container').innerHTML = data.html;
            } else {
                console.error('Failed to load compare data.');
            }
        })
        .catch(error => console.error('Error fetching compare data:', error));
    });

</script>   
@endsection
