@extends('backend.layouts.app')
@section('css')
{{-- <link rel="stylesheet" type="text/css" href="{{static_asset('assets/css/summernote.css')}}"> --}}
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<style>
    .note-modal .note-group-select-from-files {
    display: block !important;
}
</style>
@endsection
@php
use Carbon\Carbon;
@endphp
@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">Suspend Vendor</h1>

        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">



        <form  id="rejectionForm" enctype="multipart/form-data" method="POST" action="{{ route('vendors.suspend',$user->id) }}">
            @csrf
            <div class="form-group">
                <label for="reason">Select Suspension Reason:</label>
                <select class="form-control" id="reason" name="reason" required>
                    <option value="">Select a reason</option>
                    <option value="Fraud">Fraud</option>
                    <option value="Violation of Policies">Violation of Policies</option>
                    <option value="Non-compliance">Non-compliance</option>
                    <option value="Legal Issues">Legal Issues</option>
                    <option value="Non-payment">Non-payment</option>
                    <option value="IT Security Concerns">IT Security Concerns</option>
                </select>
            </div>
            <div class="form-group">
                <label for="reason-title">Reason Title:</label>
                <input type="text" class="form-control" id="reason-title" name="reason_title" required>
            </div>
            <div class="form-group">
                <label for="reason_details">Reason Details:</label>
                <textarea class="form-control" name="reason_details" id="editor" rows="3"></textarea>
            </div>
            <div class="form-group">
                <button type="submit" id="submitRejection" class="btn btn-danger">Submit</button>
                <button  type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('sellers.index') }}'" data-dismiss="modal">Cancel</button>
            </div>
        </form>


    </div>
</div>
@endsection
@section('script')

{{-- <script src={{static_asset('assets/js/editor/summernote/summernote.js')}}></script>
<script src={{static_asset('assets/js/editor/summernote/summernote.custom.js')}}></script> --}}
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {

        $('#editor').summernote({
            placeholder: 'Type your text here...',
                        height: 300,
                        callbacks: {
                onImageUpload: function(files) {
                    uploadImage(files[0]);
                },
                onMediaDelete : function(image) {
                    deleteImage(image[0].src);
                }
            }

        })
        function uploadImage(file) {
            var formData = new FormData();
            formData.append('image', file);
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: '{{route("upload.image")}}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#editor').summernote('editor.insertImage', response.imageUrl);
                },
                error: function(xhr, status, error) {
                    // Display validation errors using Toastr
                    var errors = xhr.responseJSON.errors;
                    if (errors) {
                        $.each(errors, function(key, value) {
                            toastr.error(value);
                        });
                    } else {
                        toastr.error("An error occurred while uploading the image.");
                    }
                }
            });
        }
        function deleteImage(imageSrc) {

            $.ajax({
                url: '{{ route("delete.image") }}',
                method: 'DELETE',
                data: { src: imageSrc },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log('Image deleted successfully');
                },
                error: function(xhr, status, error) {
                    console.error('Failed to delete image:', error);
                }
            });
        }



    })
</script>
<script>
    document.getElementById('submitRejection').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default form submission
 // Check if the Suspension Reason and Reason Title fields are not empty
 var suspensionReason = document.getElementById('reason').value.trim();
        var reasonTitle = document.getElementById('reason-title').value.trim();
        var reasonDetails = document.getElementById('editor').value.trim();

        if (!suspensionReason) {
            // If Suspension Reason is empty, show an error message
            Swal.fire({
                title: 'Error!',
                text: 'Please select a Suspension Reason.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return; // Exit the function without submitting the form
        }

        if (!reasonTitle) {
            // If Reason Title is empty, show an error message
            Swal.fire({
                title: 'Error!',
                text: 'Please enter a Reason Title.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return; // Exit the function without submitting the form
        }

        if (!reasonDetails) {
            // If Reason Details is empty, show an error message
            Swal.fire({
                title: 'Error!',
                text: 'Please provide Reason Details.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return; // Exit the function without submitting the form
        }

        // Use SweetAlert for confirmation
        Swal.fire({
            title: 'Are you sure?',
            text: 'You are about to reject. This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, reject it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // If user confirms, submit the form
                document.getElementById('rejectionForm').submit();
            }
        });
    });
</script>


@endsection
