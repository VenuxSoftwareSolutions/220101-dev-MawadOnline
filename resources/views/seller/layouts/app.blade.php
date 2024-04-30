<!doctype html>
@if(\App\Models\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1)
<html dir="rtl" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@else
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@endif
<head>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="app-url" content="{{ getBaseURL() }}">
	<meta name="file-base-url" content="{{ getFileBaseURL() }}">

	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Favicon -->
	<link rel="icon" href="{{ uploaded_asset(get_setting('site_icon')) }}">
	<title>{{ get_setting('website_name').' | '.get_setting('site_motto') }}</title>

	<!-- google font -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">

	<!-- aiz core css -->
	<link rel="stylesheet" href="{{ static_asset('assets/css/vendors.css') }}">
    @if(\App\Models\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1)
    <link rel="stylesheet" href="{{ static_asset('assets/css/bootstrap-rtl.min.css') }}">
    @endif
	<link rel="stylesheet" href="{{ static_asset('assets/css/aiz-seller.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css" integrity="sha512-In/+MILhf6UMDJU4ZhDL0R0fEpsp4D3Le23m6+ujDWXwl3whwpucJG1PEmI3B07nyJx+875ccs+yX2CqQJUxUw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="{{ static_asset('assets/css/countrySelect.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/introjs.min.css" rel="stylesheet">

    @stack('styles')
    <style>
        /* Override Dropify's default message font size */
        .dropify-wrapper .dropify-message p {
            font-size: 13px !important; /* Adjust the font size as needed */
        }

        .country-select {
            width: 100%;
        }

        .country-select.inside input, .country-select.inside input[type=text] {
            width: 100%;
            height: calc(1.3125rem + 1.2rem + 2px);
            border: 1px solid #e4e5eb;
            color: #898b92;

        }

        .icon-delete-pricing{
            display: flex;
            justify-content: flex-end;
            margin-bottom: 14px;
        }
        /* .fa-regular{
            color:red
        } */

        .div-btn{
            display: flex;
            width: 100%;
            justify-content: center;
        }

        #bloc_pricing_configuration_variant{
            width: 94%;
            margin-left: 19px;
            margin-top: 21px;
        }

        .bloc_pricing_configuration_variant{
            width: 94%;
            margin-left: 19px;
            margin-top: 21px;
        }

        #bloc_sample_pricing_configuration_variant{
            width: 94%;
            margin-left: 19px;
            margin-top: 21px;
        }

        .bloc_sample_pricing_configuration_variant{
            width: 94%;
            margin-left: 19px;
            margin-top: 21px;
        }

        #general_attributes{
            width: 100%;
        }

        .font-size-icon{
            font-size: 23px;
        }

        .container-img{
            position: relative;
        }

        .icon-delete-image{
            position: absolute;
            color: red;
            top: 0;
            right: -11px;
        }

        .icon-delete-image:hover{
            cursor: pointer;
        }
    </style>

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Include MultiSelect CSS -->
    <link rel="stylesheet" href="https://cdn.rawgit.com/nobleclem/jQuery-MultiSelect/master/jquery.multiselect.css">
    <link rel="stylesheet" href="{{ static_asset('assets/css/filter_multi_select.css') }}">
    <style>
        body {
            font-size: 12px;
        }
        #map{
            width: 100%;
            height: 250px;
        }
        #edit_map{
            width: 100%;
            height: 250px;
        }
        .pac-container{
            z-index: 100000;
        }

        .plus, .minus {
            display: inline-block;
            background-repeat: no-repeat;
            background-size: 16px 16px !important;
            width: 16px;
            height: 16px;
        }

        .plus {
            background-image: url(https://img.icons8.com/android/24/plus.png);
        }

        .minus {
            background-image: url(https://img.icons8.com/material-rounded/24/minus.png);
        }

        .square-variant{
            margin-right: 10px;
            color: black;
        }

        ul {
            list-style: none;
            padding: 0px 0px 0px 20px;
        }

        ul.inner_ul li:before {
            content: "├";
            font-size: 18px;
            margin-left: -11px;
            margin-top: -5px;
            vertical-align: middle;
            float: left;
            width: 8px;
            color: #41424e;
        }

        ul.inner_ul li:last-child:before {
            content: "└";
        }

        .inner_ul {
            padding: 0px 0px 0px 35px;
        }

        .width-badge{
            width: 100%;
        }

        .ms-options-wrap > .ms-options {
            position: absolute;
            left: 0;
            width: 247%;
            margin-top: 1px;
            margin-bottom: 20px;
            background: white;
            z-index: 2000;
            border: 1px solid #aaa;
            overflow: auto;
            visibility: hidden;
        }

        .bloc-default-shipping-style{
            border: 1px solid gainsboro;
            border-radius: 5px;
            padding: 15px 26px;
        }

    </style>
    <style>

.coming-soon-container {
        text-align: center;
        padding: 50px;
        background-color: #f7f8fa; /* Adjust the background color if needed */
    }



    .coming-soon-container img {
        max-width: 100%;
        height: auto;
    }

    .coming-soon-container h1 {
        font-weight: 700;
        font-size: 2.5em; /* Adjusted size for visibility */
        color: #333; /* Adjusted color for contrast */
        margin-bottom: 0.5em; /* Spacing adjusted */
    }

    .coming-soon-container p {
        color: #666; /* Adjusted color for contrast */
        font-size: 1em; /* Adjusted size for readability */
        margin-bottom: 2em; /* Spacing adjusted */
    }
    .email-input {
        padding: 15px;
        margin-right: 10px; /* Space between input and button */
        border: 1px solid #ccc;
        border-radius: 5px;
        width: 300px; /* Fixed width for the input */
    }

    .notify-btn {
        padding: 15px 25px;
        background-color: #A2B8C6; /* Button color reference */
        border: none;
        border-radius: 5px;
        cursor: pointer;
        color: white;
        font-size: 1em;
        /* Adding hover effect for the button */
        transition: background-color 0.3s ease;
    }

    .notify-btn:hover {
        background-color: #8a9ba8; /* Slightly darker shade on hover */
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .coming-soon-container img {
            max-width: 70%; /* Larger image on smaller screens */
        }

        .email-input {
            width: auto; /* Full width on small screens */
            margin: 0 0 1em 0; /* Stack input above button */
        }

        .notify-btn {
            width: auto; /* Full width on small screens */
        }
    }
    </style>
	<script>
    	var AIZ = AIZ || {};
        AIZ.local = {
            nothing_selected: '{!! translate('Nothing selected', null, true) !!}',
            nothing_found: '{!! translate('Nothing found', null, true) !!}',
            choose_file: '{{ translate('Choose file') }}',
            file_selected: '{{ translate('File selected') }}',
            files_selected: '{{ translate('Files selected') }}',
            add_more_files: '{{ translate('Add more files') }}',
            adding_more_files: '{{ translate('Adding more files') }}',
            drop_files_here_paste_or: '{{ translate('Drop files here, paste or') }}',
            browse: '{{ translate('Browse') }}',
            upload_complete: '{{ translate('Upload complete') }}',
            upload_paused: '{{ translate('Upload paused') }}',
            resume_upload: '{{ translate('Resume upload') }}',
            pause_upload: '{{ translate('Pause upload') }}',
            retry_upload: '{{ translate('Retry upload') }}',
            cancel_upload: '{{ translate('Cancel upload') }}',
            uploading: '{{ translate('Uploading') }}',
            processing: '{{ translate('Processing') }}',
            complete: '{{ translate('Complete') }}',
            file: '{{ translate('File') }}',
            files: '{{ translate('Files') }}',
        }
	</script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
</head>
<body class="">

	<div class="aiz-main-wrapper">
        @include('seller.inc.seller_sidenav')
		<div class="aiz-content-wrapper">
            @include('seller.inc.seller_nav')
			<div class="aiz-main-content">
				<div class="px-15px px-lg-25px">
                    @yield('panel_content')
				</div>
				<div class="bg-white text-center py-3 px-15px px-lg-25px mt-auto border-sm-top">
					<p class="mb-0">&copy; {{ get_setting('site_name') }} v{{ get_setting('current_version') }}</p>
				</div>
			</div><!-- .aiz-main-content -->
		</div><!-- .aiz-content-wrapper -->
	</div><!-- .aiz-main-wrapper -->

    @yield('modal')


	<script src="{{ static_asset('assets/js/vendors.js') }}" ></script>
	<script src="{{ static_asset('assets/js/aiz-core.js') }}" ></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <!-- Select extension -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @yield('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
    <!-- Include MultiSelect JS -->
    <script src="https://cdn.rawgit.com/nobleclem/jQuery-MultiSelect/master/jquery.multiselect.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/intro.min.js"></script>

    <script type="text/javascript">
	    @foreach (session('flash_notification', collect())->toArray() as $message)
	        AIZ.plugins.notify('{{ $message['level'] }}', '{{ $message['message'] }}');
	    @endforeach

        $('.dropdown-menu a[data-toggle="tab"]').click(function(e) {
            e.stopPropagation()
            $(this).tab('show')
        })

        if ($('#lang-change').length > 0) {
            $('#lang-change .dropdown-menu a').each(function() {
                $(this).on('click', function(e){
                    e.preventDefault();
                    var $this = $(this);
                    var locale = $this.data('flag');
                    $.post('{{ route('language.change') }}',{_token:'{{ csrf_token() }}', locale:locale}, function(data){
                        location.reload();
                    });

                });
            });
        }
        function menuSearch(){
			var filter, item;
			filter = $("#menu-search").val().toUpperCase();
			items = $("#main-menu").find("a");
			items = items.filter(function(i,item){
				if($(item).find(".aiz-side-nav-text")[0].innerText.toUpperCase().indexOf(filter) > -1 && $(item).attr('href') !== '#'){
					return item;
				}
			});

			if(filter !== ''){
				$("#main-menu").addClass('d-none');
				$("#search-menu").html('')
				if(items.length > 0){
					for (i = 0; i < items.length; i++) {
						const text = $(items[i]).find(".aiz-side-nav-text")[0].innerText;
						const link = $(items[i]).attr('href');
						 $("#search-menu").append(`<li class="aiz-side-nav-item"><a href="${link}" class="aiz-side-nav-link"><i class="las la-ellipsis-h aiz-side-nav-icon"></i><span>${text}</span></a></li`);
					}
				}else{
					$("#search-menu").html(`<li class="aiz-side-nav-item"><span	class="text-center text-muted d-block">{{ translate('Nothing Found') }}</span></li>`);
				}
			}else{
				$("#main-menu").removeClass('d-none');
				$("#search-menu").html('')
			}
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>

</body>
</html>
