@extends('seller.layouts.app')

@section('panel_content')
<style>
    #modal-title {
        font-weight: 600;
        font-size: 32px;
        line-height: 40px;
    }
    #modal-content {
        font-weight: 400;
        font-size: 16px;
        line-height: 24px;

    }
</style>
    <!-- delete Modal -->
<div id="welcome-modal" class="modal fade">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content"  style="border-radius: 20px !important;">
            <div class="modal-body text-center">
                <img class="mt-4" src="{{asset('public/images/Welcome.svg')}}" alt="Welcome Image">
                <h4 id="modal-title" class="mt-5">Welcome to MawadOnline</h4>
                <p id="modal-content">Thank you for joining us on this journey! Start by adding your products and organizing your inventory. Get ready to manage your offerings effectively.</p>

                <div class="mt-3">
                <button type="button" class="btn btn-light mr-2" data-dismiss="modal">Skip</button>
                    <button type="button" class="btn btn-secondary" onclick="start_tour()">Start Tour</button>
                </div>
            </div>
        </div>
    </div>
</div><!-- /.modal -->



@endsection

@section('script')

<script type="text/javascript">
    @if(Auth::user()->first_login)
    $(window).on('load', function() {
        $('#welcome-modal').modal('show');
    });
    @endif
</script>
    <script type="text/javascript">
        function start_tour() {
            // Reload the page
            location.reload();
        }
    </script>
@endsection
