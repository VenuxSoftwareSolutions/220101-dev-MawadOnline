<!doctype html>
@if (\App\Models\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1)
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
    <link rel="apple-touch-icon" href="{{ uploaded_asset(get_setting('site_icon')) }}">
    <title>{{ get_setting('website_name') . ' | ' . get_setting('site_motto') }}</title>

    <!-- google font -->
    {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700"> --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">

    <!-- aiz core css -->
    <link rel="stylesheet" href="{{ static_asset('assets/css/vendors.css') }}">
    <link rel="stylesheet" href="{{ static_asset('assets/css/tagify.css') }}">


    @if (\App\Models\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1)
        <link rel="stylesheet" href="{{ static_asset('assets/css/bootstrap-rtl.min.css') }}">
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css" integrity="sha512-In/+MILhf6UMDJU4ZhDL0R0fEpsp4D3Le23m6+ujDWXwl3whwpucJG1PEmI3B07nyJx+875ccs+yX2CqQJUxUw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ static_asset('assets/css/aiz-core.css?v=') }}{{ rand(1000,9999) }}">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ static_asset('assets/css/countrySelect.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
     <!-- Font Awesome CSS -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
     <!-- DataTables CSS -->
     <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
     <!-- DataTables Buttons CSS -->
     <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
      <!-- Select2 CSS -->
     <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Override Dropify's default message font size */
        .dropify-wrapper .dropify-message p {
            font-size: 13px !important; /* Adjust the font size as needed */
        }
        .font-size-icon{
            font-size: 21px;
            margin-top: 29px;
            color: black;
            border: solid 1px;
            border-radius: 50%;
            padding: 5px;
        }

        .style-img{
            border-radius: 5%;
            box-shadow: 5px 5px 5px gainsboro;
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
        .fa-regular{
            color:red
        }

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

        .width-badge{
            width: 100%;
        }

    </style>
    <style>
        :root {
            --blue: #3390f3;
            --hov-blue: #1f6dc2;
            --soft-blue: #f1fafd;

            /* --primary: #009ef7;
            --hov-primary: #008cdd;
            --soft-primary: rgb(0, 158, 247, 0.15);
            --success: #19c553;
            --hov-success: #16a846;
            --soft-success:  rgb(25, 197, 83, 0.15);
            --info: #8f60ee;
            --hov-info: #714cbd;
            --soft-info: rgb(143, 96, 238, 0.15);
            --warning: #ffc700;
            --soft-warning: rgb(255, 199, 0, 0.15);
            --danger: #F0416C;
            --soft-danger: rgb(240, 65, 108, 0.15);

            --secondary-base: #f1416c;
            --hov-secondary-base: #c73459;
            --soft-secondary-base: rgb(241, 65, 108, 0.15); */
        }
        body {
            font-size: 12px;
            font-family: 'Public Sans', sans-serif;
        }
        /* .bootstrap-select .btn,
        .btn:not(.btn-circle),
        .form-control,
        .input-group-text,
        .custom-file-label, .custom-file-label::after {
            border-radius: 0;
        } */
        .border-gray {
            border-color: #e4e5eb !important;
        }
        .card {
            border-radius: 8px;
            background: #fff;
            border: 1px solid #f1f1f4;
            box-shadow: 0px 6px 14px rgba(35, 39, 52, 0.04);
        }
        .form-control {
            border: 1px solid #e4e5eb;
        }
        .aiz-color-input{
            border-top-left-radius: 4px !important;
            border-bottom-left-radius: 4px !important;
        }
        .form-control.file-amount{
            border-top-right-radius: 4px !important;
            border-bottom-right-radius: 4px !important;
        }

        .color-input{
            display: inline-block !important;
            width: 92%;
        }

        .tagify-label{
            display: block !important;
        }

        .tagify-input{
            width: 100%;
        }

        .add:hover{
            cursor: pointer;
        }

        .trash:hover{
            cursor: pointer;
        }

        .nonClickableSelect {
            pointer-events: none;
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

    <link rel="stylesheet" href="{{ static_asset('assets/css/filter_multi_select.css') }}">

</head>

<body class="">

    <div class="aiz-main-wrapper">
        @include('backend.inc.admin_sidenav')
        <div class="aiz-content-wrapper">
            @include('backend.inc.admin_nav')
            <div class="aiz-main-content">
                <div class="px-15px px-lg-25px">
                    @yield('content')
                </div>
                <div class="bg-white text-center py-3 px-15px px-lg-25px mt-auto">
                    <p class="mb-0">&copy; {{ get_setting('site_name') }} v{{ get_setting('current_version') }}</p>
                </div>
            </div><!-- .aiz-main-content -->
        </div><!-- .aiz-content-wrapper -->
    </div><!-- .aiz-main-wrapper -->

    @yield('modal')


    <script src="{{ static_asset('assets/js/vendors.js') }}"></script>
    <script src="{{ static_asset('assets/js/aiz-core.js?v=') }}{{ rand(1000,9999) }}"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <!-- Select extension -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @yield('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>


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
                $(this).on('click', function(e) {
                    e.preventDefault();
                    var $this = $(this);
                    var locale = $this.data('flag');
                    $.post('{{ route('language.change') }}', {
                        _token: '{{ csrf_token() }}',
                        locale: locale
                    }, function(data) {
                        location.reload();
                    });

                });
            });
        }

        function menuSearch() {
            var filter, item;
            filter = $("#menu-search").val().toUpperCase();
            items = $("#main-menu").find("a");
            items = items.filter(function(i, item) {
                if ($(item).find(".aiz-side-nav-text")[0].innerText.toUpperCase().indexOf(filter) > -1 && $(item)
                    .attr('href') !== '#') {
                    return item;
                }
            });

            if (filter !== '') {
                $("#main-menu").addClass('d-none');
                $("#search-menu").html('')
                if (items.length > 0) {
                    for (i = 0; i < items.length; i++) {
                        const text = $(items[i]).find(".aiz-side-nav-text")[0].innerText;
                        const link = $(items[i]).attr('href');
                        $("#search-menu").append(
                            `<li class="aiz-side-nav-item"><a href="${link}" class="aiz-side-nav-link"><i class="las la-ellipsis-h aiz-side-nav-icon"></i><span>${text}</span></a></li`
                            );
                    }
                } else {
                    $("#search-menu").html(
                        `<li class="aiz-side-nav-item"><span	class="text-center text-muted d-block">{{ translate('Nothing Found') }}</span></li>`
                        );
                }
            } else {
                $("#main-menu").removeClass('d-none');
                $("#search-menu").html('')
            }
        }
    </script>

</body>

</html>
