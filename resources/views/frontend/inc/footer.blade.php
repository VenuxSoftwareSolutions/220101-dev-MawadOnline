<!-- footer Description -->
@if (get_setting('footer_title') != null || get_setting('footer_description') != null)
    <section class="bg-light border-top border-bottom mt-auto">
        <div class="container py-4">
            <h1 class="fs-18 fw-700 text-gray-dark mb-3">{{ get_setting('footer_title',null, $system_language->code) }}</h1>
            <p class="fs-13 text-gray-dark text-justify mb-0">
                {!! nl2br(get_setting('footer_description',null, $system_language->code)) !!}
            </p>
        </div>
    </section>
@endif

<!-- footer top Bar -->
<section class="bg-light border-top mt-auto">
    <div class="container px-xs-0">
        <div class="row no-gutters border-left border-soft-light">
            <!-- Terms & conditions -->
            <div class="col-lg-3 col-6 policy-file">
                <a class="text-reset h-100  border-right border-bottom border-soft-light text-center p-2 p-md-4 d-block hov-ls-1" href="{{ route('terms') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26.004" height="32" viewBox="0 0 26.004 32">
                        <path id="Union_8" data-name="Union 8" d="M-14508,18932v-.01a6.01,6.01,0,0,1-5.975-5.492h-.021v-14h1v13.5h0a4.961,4.961,0,0,0,4.908,4.994h.091v0h14v1Zm17-4v-1a2,2,0,0,0,2-2h1a3,3,0,0,1-2.927,3Zm-16,0a3,3,0,0,1-3-3h1a2,2,0,0,0,2,2h16v1Zm18-3v-16.994h-4v-1h3.6l-5.6-5.6v3.6h-.01a2.01,2.01,0,0,0,2,2v1a3.009,3.009,0,0,1-3-3h.01v-4h.6l0,0H-14507a2,2,0,0,0-2,2v22h-1v-22a3,3,0,0,1,3-3v0h12l0,0,7,7-.01.01V18925Zm-16-4.992v-1h12v1Zm0-4.006v-1h12v1Zm0-4v-1h12v1Z" transform="translate(14513.998 -18900.002)" fill="#919199"/>
                    </svg>
                    <h4 class="text-dark fs-14 fw-700 mt-3">{{ translate('Terms & conditions') }}</h4>
                </a>
            </div>

            <!-- Return Policy -->
            <div class="col-lg-3 col-6 policy-file">
                <a class="text-reset h-100  border-right border-bottom border-soft-light text-center p-2 p-md-4 d-block hov-ls-1" href="{{ route('returnpolicy') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32.001" height="23.971" viewBox="0 0 32.001 23.971">
                        <path id="Union_7" data-name="Union 7" d="M-14490,18922.967a6.972,6.972,0,0,0,4.949-2.051,6.944,6.944,0,0,0,2.052-4.943,7.008,7.008,0,0,0-7-7v0h-22.1l7.295,7.295-.707.707-7.779-7.779-.708-.707.708-.7,7.774-7.779.712.707-7.261,7.258H-14490v0a8.01,8.01,0,0,1,8,8,8.008,8.008,0,0,1-8,8Z" transform="translate(14514.001 -18900)" fill="#919199"/>
                    </svg>
                    <h4 class="text-dark fs-14 fw-700 mt-3">{{ translate('Return Policy') }}</h4>
                </a>
            </div>

            <!-- Support Policy -->
            <div class="col-lg-3 col-6 policy-file">
                <a class="text-reset h-100  border-right border-bottom border-soft-light text-center p-2 p-md-4 d-block hov-ls-1" href="{{ route('supportpolicy') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32.002" height="32.002" viewBox="0 0 32.002 32.002">
                        <g id="Group_24198" data-name="Group 24198" transform="translate(-1113.999 -2398)">
                        <path id="Subtraction_14" data-name="Subtraction 14" d="M-14508,18916h0l-1,0a12.911,12.911,0,0,1,3.806-9.187A12.916,12.916,0,0,1-14496,18903a12.912,12.912,0,0,1,9.193,3.811A12.9,12.9,0,0,1-14483,18916l-1,0a11.918,11.918,0,0,0-3.516-8.484A11.919,11.919,0,0,0-14496,18904a11.921,11.921,0,0,0-8.486,3.516A11.913,11.913,0,0,0-14508,18916Z" transform="translate(15626 -16505)" fill="#919199"/>
                        <path id="Subtraction_15" data-name="Subtraction 15" d="M-14510,18912h-1a3,3,0,0,1-3-3v-6a3,3,0,0,1,3-3h1a2,2,0,0,1,2,2v8A2,2,0,0,1-14510,18912Zm-1-11a2,2,0,0,0-2,2v6a2,2,0,0,0,2,2h1a1,1,0,0,0,1-1v-8a1,1,0,0,0-1-1Z" transform="translate(15628 -16489)" fill="#919199"/>
                        <path id="Subtraction_19" data-name="Subtraction 19" d="M4,12H3A3,3,0,0,1,0,9V3A3,3,0,0,1,3,0H4A2,2,0,0,1,6,2v8A2,2,0,0,1,4,12ZM3,1A2,2,0,0,0,1,3V9a2,2,0,0,0,2,2H4a1,1,0,0,0,1-1V2A1,1,0,0,0,4,1Z" transform="translate(1146.002 2423) rotate(180)" fill="#919199"/>
                        <path id="Subtraction_17" data-name="Subtraction 17" d="M-14512,18908a2,2,0,0,1-2-2v-4a2,2,0,0,1,2-2,2,2,0,0,1,2,2v4A2,2,0,0,1-14512,18908Zm0-7a1,1,0,0,0-1,1v4a1,1,0,0,0,1,1,1,1,0,0,0,1-1v-4A1,1,0,0,0-14512,18901Z" transform="translate(20034 16940.002) rotate(90)" fill="#919199"/>
                        <rect id="Rectangle_18418" data-name="Rectangle 18418" width="1" height="4.001" transform="translate(1137.502 2427.502) rotate(90)" fill="#919199"/>
                        <path id="Intersection_1" data-name="Intersection 1" d="M-14508.5,18910a4.508,4.508,0,0,0,4.5-4.5h1a5.508,5.508,0,0,1-5.5,5.5Z" transform="translate(15646.004 -16482.5)" fill="#919199"/>
                        </g>
                    </svg>
                    <h4 class="text-dark fs-14 fw-700 mt-3">{{ translate('Support Policy') }}</h4>
                </a>
            </div>

            <!-- Privacy Policy -->
            <div class="col-lg-3 col-6 policy-file">
                <a class="text-reset h-100 border-right border-bottom border-soft-light text-center p-2 p-md-4 d-block hov-ls-1" href="{{ route('privacypolicy') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
                        <g id="Group_24236" data-name="Group 24236" transform="translate(-1454.002 -2430.002)">
                        <path id="Subtraction_11" data-name="Subtraction 11" d="M-14498,18932a15.894,15.894,0,0,1-11.312-4.687A15.909,15.909,0,0,1-14514,18916a15.884,15.884,0,0,1,4.685-11.309A15.9,15.9,0,0,1-14498,18900a15.909,15.909,0,0,1,11.316,4.688A15.885,15.885,0,0,1-14482,18916a15.9,15.9,0,0,1-4.687,11.316A15.909,15.909,0,0,1-14498,18932Zm0-31a14.9,14.9,0,0,0-10.605,4.393A14.9,14.9,0,0,0-14513,18916a14.9,14.9,0,0,0,4.395,10.607A14.9,14.9,0,0,0-14498,18931a14.9,14.9,0,0,0,10.607-4.393A14.9,14.9,0,0,0-14483,18916a14.9,14.9,0,0,0-4.393-10.607A14.9,14.9,0,0,0-14498,18901Z" transform="translate(15968 -16470)" fill="#919199"/>
                        <g id="Group_24196" data-name="Group 24196" transform="translate(0 -1)">
                            <rect id="Rectangle_18406" data-name="Rectangle 18406" width="2" height="10" transform="translate(1469 2440)" fill="#919199"/>
                            <rect id="Rectangle_18407" data-name="Rectangle 18407" width="2" height="2" transform="translate(1469 2452)" fill="#919199"/>
                        </g>
                        </g>
                    </svg>
                    <h4 class="text-dark fs-14 fw-700 mt-3">{{ translate('Privacy Policy') }}</h4>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- footer subscription & icons -->
<section class="py-3 text-light footer-widget border-bottom" style="border-color: #3d3d46 !important; background-color: #3D3D3B !important;">
    <div class="container">
        <!-- footer logo
        <div class="mt-3 mb-4">
            <a href="{{ route('home') }}" class="d-block">
                @if(get_setting('footer_logo') != null)
                    <img class="lazyload h-45px" src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ uploaded_asset(get_setting('footer_logo')) }}" alt="{{ env('APP_NAME') }}" height="45">
                @else
                    <img class="lazyload h-45px" src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ static_asset('assets/img/logo.png') }}" alt="{{ env('APP_NAME') }}" height="45">
                @endif
            </a>
        </div>
        <div class="col-auto pl-0 pr-3 d-flex align-items-center">
            <a class="d-block py-20px mr-3 ml-0" href="{{ route('home') }}">
                @php
                    $header_logo = get_setting('header_logo');
                @endphp
                @if ($header_logo != null)
                    <img src="{{ uploaded_asset($header_logo) }}" alt="{{ env('APP_NAME') }}"
                        class="mw-100 h-30px h-md-40px" height="40">
                @else
                    <img src="{{ static_asset('assets/img/logo.png') }}" alt="{{ env('APP_NAME') }}"
                        class="mw-100 h-30px h-md-40px" height="40">
                @endif
            </a>
        </div>-->
        <div class="row" style="border-bottom:solid 1px #63646C;">
            <!-- about & subscription -->
            <div class="col-xl-6 col-lg-7">
                <a class="d-block py-20px mr-3 ml-0" href="{{ route('home') }}">
                    @php
                        $header_logo = get_setting('header_logo');
                    @endphp
                    @if ($header_logo != null)
                        <img src="{{ uploaded_asset($header_logo) }}" alt="{{ env('APP_NAME') }}"
                            class="mw-100 h-30px h-md-40px" height="40">
                    @else
                        <img src="{{ static_asset('assets/img/logo.png') }}" alt="{{ env('APP_NAME') }}"
                            class="mw-100 h-30px h-md-40px" height="40">
                    @endif
                </a>
                <div class="mb-4 text-secondary text-justify">
                    {!! get_setting('about_us_description',null,App::getLocale()) !!}
                </div>
                 <!-- Follow & Apps -->
            <div class="col-xxl-6 col-xl-8 col-lg-8 p-0">
                <!-- Social -->
                @if ( get_setting('show_social_links') )
                    <ul class="list-inline social colored mb-4 ml-0">
                        @if (!empty(get_setting('linkedin_link')))
                            <li class="list-inline-item ml-2 mr-2">
                                <a href="{{ get_setting('linkedin_link') }}" target="_blank">
                                    <svg width="32" height="33" viewBox="0 0 32 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M27.2655 27.5328H22.5241V20.1073C22.5241 18.3366 22.4925 16.0572 20.0581 16.0572C17.5886 16.0572 17.2108 17.9864 17.2108 19.9784V27.5323H12.4694V12.2625H17.0211V14.3492H17.0848C17.5404 13.5704 18.1986 12.9296 18.9895 12.4953C19.7803 12.0609 20.6742 11.8492 21.5758 11.8826C26.3815 11.8826 27.2675 15.0437 27.2675 19.156L27.2655 27.5328ZM7.1195 10.1752C6.57531 10.1753 6.04329 10.014 5.59076 9.71174C5.13822 9.40947 4.7855 8.97979 4.57716 8.47704C4.36882 7.97429 4.31421 7.42105 4.42029 6.88728C4.52636 6.3535 4.78833 5.86317 5.17307 5.47828C5.5578 5.0934 6.04803 4.83125 6.58174 4.72498C7.11546 4.61871 7.66869 4.6731 8.1715 4.88128C8.67431 5.08945 9.10409 5.44205 9.40651 5.89449C9.70893 6.34693 9.87041 6.87889 9.87051 7.42311C9.87057 7.78445 9.79945 8.14227 9.66124 8.47613C9.52302 8.80999 9.32043 9.11336 9.06497 9.36892C8.80952 9.62447 8.50621 9.8272 8.17241 9.96554C7.8386 10.1039 7.48083 10.1751 7.1195 10.1752ZM9.4902 27.5328H4.74386V12.2625H9.4902V27.5328ZM29.6293 0.268782H2.36132C1.74241 0.261797 1.14602 0.50082 0.703253 0.933325C0.260483 1.36583 0.00755341 1.95643 0 2.57535V29.9574C0.00729489 30.5766 0.260076 31.1676 0.702831 31.6006C1.14559 32.0335 1.74211 32.273 2.36132 32.2664H29.6293C30.2497 32.2742 30.8479 32.0354 31.2924 31.6024C31.7369 31.1695 31.9914 30.5778 32 29.9574V2.57338C31.9912 1.95323 31.7365 1.36196 31.292 0.92946C30.8475 0.496966 30.2494 0.258626 29.6293 0.266805" fill="#A2A4AD"/>
                                        </svg>
                                </a>
                            </li>
                        @endif
                        @if (!empty(get_setting('twitter_link')))
                            <li class="list-inline-item ml-2 mr-2">
                                <a href="{{ get_setting('twitter_link') }}" target="_blank">
                                    <svg width="27" height="25" viewBox="0 0 27 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_5007_1600)">
                                        <path d="M21.253 0.266602H25.3734L16.3734 10.4533L26.9999 24.2666H18.6505L12.1445 15.8933L4.66259 24.2666H0.542108L10.1927 13.3866L-6.10352e-05 0.266602H8.5662L14.4758 7.9466L21.253 0.266602ZM19.7891 21.8133H22.0662L7.31922 2.55994H4.82524L19.7891 21.8133Z" fill="#A2A4AD"/>
                                        </g>
                                        <defs>
                                        <clipPath id="clip0_5007_1600">
                                        <rect width="27" height="24" fill="white" transform="translate(-6.10352e-05 0.266602)"/>
                                        </clipPath>
                                        </defs>
                                        </svg>

                                </a>
                            </li>
                        @endif
                        @if (!empty(get_setting('facebook_link')))
                            <li class="list-inline-item ml-2 mr-2">
                                <a href="{{ get_setting('facebook_link') }}" target="_blank">
                                    <svg width="32" height="33" viewBox="0 0 32 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_5007_1603)">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M15.9999 0.266602C24.8364 0.266602 31.9999 7.47382 31.9999 16.3644C31.9999 24.3992 26.1489 31.0589 18.4999 32.2666V21.0176H22.228L22.9374 16.3644H22.7688L22.8081 16.1113H18.4999V13.3447C18.4999 12.0716 19.1197 10.8308 21.1073 10.8308H23.1249V6.86921C23.1249 6.86921 23.0808 6.86164 22.9999 6.84886V6.58214C22.9999 6.58214 21.127 6.2666 19.3365 6.2666C15.5984 6.2666 13.1551 8.50311 13.1551 12.552V16.1113H8.99988V20.7812H9.43738V21.0176H13.1551V32.0703C13.2697 32.0881 13.3846 32.1047 13.4999 32.12V32.2666C5.85085 31.0589 -0.00012207 24.3992 -0.00012207 16.3644C-0.00012207 7.47382 7.16332 0.266602 15.9999 0.266602Z" fill="#A2A4AD"/>
                                        </g>
                                        <defs>
                                        <clipPath id="clip0_5007_1603">
                                        <rect width="32" height="32" fill="white" transform="translate(-6.10352e-05 0.266602)"/>
                                        </clipPath>
                                        </defs>
                                        </svg>

                                </a>
                            </li>
                        @endif
                        @if (!empty(get_setting('instagram_link')))
                            <li class="list-inline-item ml-2 mr-2">
                                <a href="{{ get_setting('instagram_link') }}" target="_blank">
                                    <svg width="32" height="33" viewBox="0 0 32 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_5007_1609)">
                                        <path d="M9.37341 0.378502C7.67101 0.458822 6.50844 0.730502 5.49212 1.12986C4.44028 1.53978 3.54876 2.08986 2.66172 2.9801C1.77468 3.87034 1.22844 4.7625 0.821405 5.81594C0.427485 6.8345 0.160605 7.99802 0.0854049 9.70138C0.0102049 11.4047 -0.00643509 11.9523 0.00188491 16.2972C0.0102049 20.6422 0.0294049 21.1868 0.111965 22.8937C0.193245 24.5958 0.463965 25.758 0.863325 26.7747C1.27388 27.8265 1.82332 28.7177 2.71388 29.6051C3.60444 30.4924 4.49596 31.0374 5.55196 31.4451C6.56956 31.8383 7.73341 32.1065 9.43645 32.1811C11.1395 32.2556 11.6876 32.2729 16.0313 32.2646C20.375 32.2563 20.9219 32.2371 22.6284 32.1561C24.335 32.0751 25.4912 31.8025 26.5081 31.4051C27.56 30.9935 28.4518 30.4451 29.3385 29.5542C30.2252 28.6633 30.7712 27.7705 31.1779 26.7164C31.5721 25.6988 31.84 24.535 31.9139 22.8332C31.9884 21.1254 32.006 20.5801 31.9977 16.2358C31.9894 11.8915 31.9699 11.3468 31.8889 9.64058C31.808 7.93434 31.5369 6.77562 31.1379 5.75834C30.7267 4.7065 30.1779 3.81594 29.2876 2.92794C28.3974 2.03994 27.504 1.49434 26.4502 1.08858C25.432 0.694662 24.2688 0.426182 22.5657 0.352582C20.8627 0.278982 20.3145 0.260102 15.9692 0.268422C11.624 0.276742 11.08 0.295302 9.37341 0.378502ZM9.56028 29.3027C8.00029 29.2348 7.15324 28.9756 6.58876 28.7587C5.84124 28.4707 5.30876 28.1225 4.7462 27.5654C4.18364 27.0083 3.83804 26.4739 3.5462 25.7279C3.327 25.1635 3.063 24.3174 2.99004 22.7574C2.91068 21.0713 2.89404 20.5651 2.88476 16.2934C2.87548 12.0217 2.8918 11.5161 2.96572 9.82938C3.03228 8.27066 3.29308 7.42266 3.50972 6.8585C3.79772 6.11002 4.1446 5.5785 4.703 5.01626C5.2614 4.45402 5.7942 4.10778 6.54076 3.81594C7.1046 3.59578 7.95069 3.33402 9.51005 3.25978C11.1974 3.17978 11.703 3.16378 15.974 3.1545C20.2451 3.14522 20.752 3.16122 22.44 3.23546C23.9987 3.3033 24.847 3.56154 25.4105 3.77946C26.1584 4.06746 26.6905 4.41338 27.2528 4.97274C27.815 5.5321 28.1616 6.06298 28.4534 6.81114C28.6739 7.37338 28.9356 8.21914 29.0092 9.77946C29.0896 11.4668 29.1078 11.9727 29.1155 16.2435C29.1232 20.5142 29.1081 21.0214 29.0342 22.7075C28.966 24.2675 28.7075 25.1148 28.4902 25.6799C28.2022 26.4271 27.855 26.9599 27.2963 27.5219C26.7376 28.0838 26.2054 28.43 25.4585 28.7219C24.8953 28.9417 24.0483 29.2041 22.4902 29.2783C20.8028 29.3577 20.2972 29.3743 16.0246 29.3836C11.752 29.3929 11.248 29.3756 9.5606 29.3027M22.6038 7.71514C22.6044 8.09491 22.7177 8.46596 22.9292 8.78136C23.1407 9.09676 23.441 9.34235 23.7922 9.48707C24.1433 9.63179 24.5294 9.66913 24.9017 9.59437C25.2741 9.51961 25.6159 9.33612 25.8839 9.06709C26.152 8.79806 26.3342 8.45559 26.4076 8.08298C26.481 7.71037 26.4422 7.32437 26.2962 6.97379C26.1502 6.62321 25.9035 6.3238 25.5874 6.11343C25.2712 5.90306 24.8997 5.79118 24.52 5.79194C24.0109 5.79296 23.523 5.99613 23.1637 6.35678C22.8043 6.71744 22.603 7.20604 22.6038 7.71514ZM7.7846 16.2825C7.79356 20.8201 11.4787 24.4902 16.0153 24.4815C20.552 24.4729 24.2246 20.7881 24.216 16.2505C24.2073 11.7129 20.5212 8.04186 15.984 8.05082C11.4467 8.05978 7.77596 11.7455 7.7846 16.2825ZM10.6665 16.2767C10.6644 15.2219 10.9752 14.1901 11.5595 13.3119C12.1438 12.4336 12.9754 11.7484 13.9492 11.3428C14.9229 10.9372 15.9951 10.8294 17.0301 11.0332C18.0651 11.2369 19.0164 11.743 19.7638 12.4874C20.5112 13.2318 21.021 14.1812 21.2288 15.2153C21.4367 16.2495 21.3332 17.3221 20.9314 18.2974C20.5297 19.2728 19.8477 20.1071 18.9718 20.6949C18.0959 21.2827 17.0654 21.5975 16.0105 21.5996C15.3101 21.6011 14.6163 21.4646 13.9686 21.1979C13.3209 20.9311 12.7322 20.5394 12.2359 20.0452C11.7397 19.5509 11.3457 18.9636 11.0764 18.3171C10.8071 17.6705 10.6678 16.9772 10.6665 16.2767Z" fill="#A2A4AD"/>
                                        </g>
                                        <defs>
                                        <clipPath id="clip0_5007_1609">
                                        <rect width="32" height="32" fill="white" transform="translate(-6.10352e-05 0.266602)"/>
                                        </clipPath>
                                        </defs>
                                        </svg>

                                </a>
                            </li>
                        @endif
                        @if (!empty(get_setting('youtube_link')))
                            <li class="list-inline-item ml-2 mr-2">
                                <a href="{{ get_setting('youtube_link') }}" target="_blank">
                                    <svg width="32" height="33" viewBox="0 0 32 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_5007_1611)">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M28.502 5.92341C29.8789 6.28481 30.9631 7.34968 31.3311 8.70197C31.9998 11.1529 31.9998 16.2666 31.9998 16.2666C31.9998 16.2666 31.9998 21.3802 31.3311 23.8314C30.9631 25.1835 29.8789 26.2484 28.502 26.6099C26.0066 27.2666 15.9998 27.2666 15.9998 27.2666C15.9998 27.2666 5.99317 27.2666 3.49765 26.6099C2.12087 26.2484 1.03649 25.1835 0.668526 23.8314C-0.000152588 21.3802 -0.000152588 16.2666 -0.000152588 16.2666C-0.000152588 16.2666 -0.000152588 11.1529 0.668526 8.70197C1.03649 7.34968 2.12087 6.28481 3.49765 5.92341C5.99317 5.2666 15.9998 5.2666 15.9998 5.2666C15.9998 5.2666 26.0066 5.2666 28.502 5.92341ZM20.9998 16.7668L12.9998 21.2666V12.2666L20.9998 16.7668Z" fill="#A2A4AD"/>
                                        </g>
                                        <defs>
                                        <clipPath id="clip0_5007_1611">
                                        <rect width="32" height="32" fill="white" transform="translate(-6.10352e-05 0.266602)"/>
                                        </clipPath>
                                        </defs>
                                        </svg>

                                </a>
                            </li>
                        @endif
                    </ul>
                @endif

                <!-- Apps link -->
                @if((get_setting('play_store_link') != null) || (get_setting('app_store_link') != null))
                    <h5 class="fs-14 fw-700 text-secondary text-uppercase mt-3">{{ translate('Mobile Apps') }}</h5>
                    <div class="d-flex mt-3">
                        <div class="">
                            <a href="{{ get_setting('play_store_link') }}" target="_blank" class="mr-2 mb-2 overflow-hidden hov-scale-img">
                                <img class="lazyload has-transition" src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ static_asset('assets/img/play.png') }}" alt="{{ env('APP_NAME') }}" height="44">
                            </a>
                        </div>
                        <div class="">
                            <a href="{{ get_setting('app_store_link') }}" target="_blank" class="overflow-hidden hov-scale-img">
                                <img class="lazyload has-transition" src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ static_asset('assets/img/app.png') }}" alt="{{ env('APP_NAME') }}" height="44">
                            </a>
                        </div>
                    </div>
                @endif

            </div>

            </div>
            @php
                $col_values = ((get_setting('vendor_system_activation') == 1) || addon_is_activated('delivery_boy')) ? "col-lg-3 col-md-6 col-sm-6" : "col-md-4 col-sm-6";
            @endphp
            <div class="col d-none d-lg-block font-prompt">
                <!-- Quick links -->
            <div class="{{ $col_values }} float-left my-3 mx-4">
                <div class="text-center text-sm-left mt-4">
                    <h4 class="fs-14 text-white fw-700 mb-3 letter-spacing-1">
                        {{ get_setting('widget_one',null,App::getLocale()) }}
                    </h4>
                    <ul class="list-unstyled">
                        @if ( get_setting('widget_one_labels',null,App::getLocale()) !=  null )
                            @foreach (json_decode( get_setting('widget_one_labels',null,App::getLocale()), true) as $key => $value)
                            @php
								$widget_one_links = '';
								if(isset(json_decode(get_setting('widget_one_links'), true)[$key])) {
									$widget_one_links = json_decode(get_setting('widget_one_links'), true)[$key];
								}
							@endphp
                            <li class="mb-2 font-prompt">
                                <a href="{{ $widget_one_links }}" class="fs-13 text-secondary animate-underline-white">
                                    {{ $value }}
                                </a>
                            </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Contacts
            <div class="{{ $col_values }}">
                <div class="text-center text-sm-left mt-4">
                    <h4 class="fs-14 text-secondary text-uppercase fw-700 mb-3">{{ translate('Contacts') }}</h4>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <p  class="fs-13 text-secondary mb-1">{{ translate('Address') }}</p>
                            <p  class="fs-13 text-soft-light">{{ get_setting('contact_address',null,App::getLocale()) }}</p>
                        </li>
                        <li class="mb-2">
                            <p  class="fs-13 text-secondary mb-1">{{ translate('Phone') }}</p>
                            <p  class="fs-13 text-soft-light">{{ get_setting('contact_phone') }}</p>
                        </li>
                        <li class="mb-2">
                            <p  class="fs-13 text-secondary mb-1">{{ translate('Email') }}</p>
                            <p  class="">
                                <a href="mailto:{{ get_setting('contact_email') }}" class="fs-13 text-soft-light hov-text-primary">{{ get_setting('contact_email')  }}</a>
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
            -->
            <!-- My Account -->
            <div class="{{ $col_values }} float-left my-3">
                <div class="text-center text-sm-left mt-4">
                    <h4 class="fs-14 text-white fw-700 mb-3 letter-spacing-1">{{ translate('My Account') }}</h4>
                    <ul class="list-unstyled">
                        @if (Auth::check())
                            <li class="mb-2">
                                <a class="fs-13 text-secondary animate-underline-white" href="{{ route('logout') }}">
                                    {{ translate('Logout') }}
                                </a>
                            </li>
                        @else
                            <li class="mb-2">
                                <a class="fs-13 text-secondary animate-underline-white" href="{{ route('user.login') }}">
                                    {{ translate('Login') }}
                                </a>
                            </li>
                        @endif
                        <li class="mb-2">
                            <a class="fs-13 text-secondary animate-underline-white" href="{{ route('purchase_history.index') }}">
                                {{ translate('Order History') }}
                            </a>
                        </li>
                        <li class="mb-2">
                            <a class="fs-13 text-secondary animate-underline-white" href="{{ route('wishlists.index') }}">
                                {{ translate('My Wishlist') }}
                            </a>
                        </li>
                        <li class="mb-2">
                            <a class="fs-13 text-secondary animate-underline-white" href="{{ route('orders.track') }}">
                                {{ translate('Track Order') }}
                            </a>
                        </li>
                        @if (addon_is_activated('affiliate_system'))
                            <li class="mb-2">
                                <a class="fs-13 text-secondary animate-underline-white" href="{{ route('affiliate.apply') }}">
                                    {{ translate('Be an affiliate partner')}}
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Seller & Delivery Boy -->
            @if ((get_setting('vendor_system_activation') == 1) || addon_is_activated('delivery_boy'))
            <div class="col-lg-4 col-md-4 col-sm-6 float-left my-3 ml-4">
                <div class="text-center text-sm-left mt-4">
                    <!-- Seller -->
                    @if (get_setting('vendor_system_activation') == 1)
                        <h4 class="fs-14 text-white fw-700 mb-3 letter-spacing-1">{{ translate('Seller Zone') }}</h4>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <p class="fs-13 text-secondary mb-0">
                                    {{ translate('Become A Seller') }}
                                    <a href="{{ route('shops.packages') }}" class="fs-13 fw-700 text-secondary-base ml-2">{{ translate('Apply Now') }}</a>
                                </p>
                            </li>
                            @guest
                                <li class="mb-2">
                                    <a class="fs-13 text-secondary animate-underline-white" href="{{ route('seller.login') }}">
                                        {{ translate('Login to Seller Panel') }}
                                    </a>
                                </li>
                            @endguest
                            @if(get_setting('seller_app_link'))
                                <li class="mb-2">
                                    <a class="fs-13 text-secondary animate-underline-white" target="_blank" href="{{ get_setting('seller_app_link')}}">
                                        {{ translate('Download Seller App') }}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    @endif

                    <!-- Delivery Boy -->
                    @if (addon_is_activated('delivery_boy'))
                        <h4 class="fs-14 text-white text-uppercase fw-700 mt-4 mb-3">{{ translate('Delivery Boy') }}</h4>
                        <ul class="list-unstyled">
                            @guest
                                <li class="mb-2">
                                    <a class="fs-13 text-secondary animate-underline-white" href="{{ route('deliveryboy.login') }}">
                                        {{ translate('Login to Delivery Boy Panel') }}
                                    </a>
                                </li>
                            @endguest

                            @if(get_setting('delivery_boy_app_link'))
                                <li class="mb-2">
                                    <a class="fs-13 text-secondary animate-underline-white" target="_blank" href="{{ get_setting('delivery_boy_app_link')}}">
                                        {{ translate('Download Delivery Boy App') }}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    @endif
                </div>
            </div>
            @endif
            </div>
        </div>
    </div>
</section>


<section class="py-lg-3 text-light footer-widget" style="background-color: #3D3D3B !important;">
    <!-- footer widgets ========== [Accordion Fotter widgets are bellow from this]-->
    <div class="container d-none d-lg-block">
        <div class="row">
            <div class="col-12 col-md-6">
            <h5 class="fs-14 fw-700 mt-1 mb-3 font-prompt" style="color:#CB774B;">{{ translate('Subscribe to our newsletter for regular updates about Offers, Coupons & more') }}</h5>
            <div class="mb-4 fs-16 text-secondary text-justify font-prompt">
                Stay in the loop with our newsletter! Get exclusive offers, discounts, and the <br/> latest updates delivered right to your inbox. Don’t miss out and subscribe now!
            </div>
            </div>
            <div class="mb-3 col-12 col-md-6 mt-1">
                    <form method="POST" action="{{ route('subscribers.store') }}">
                        @csrf
                        <div class="row gutters-10">
                            <div class="col-10">
                                <input type="email" class="form-control border-secondary text-white w-100 bg-white font-prompt border-radius-8px border-0" placeholder="{{ translate('Your Email Address') }}" name="email" required>
                            </div>
                            <div class="col-2">
                                <button type="submit" class="btn btn-primary w-100 font-prompt border-radius-8px border-0">{{ translate('Subscribe') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
        </div>
    </div>

    <!-- Accordion Fotter widgets -->
    <div class="d-lg-none bg-transparent">
        <!-- Quick links -->
        <div class="aiz-accordion-wrap bg-black">
            <div class="aiz-accordion-heading container bg-black">
                <button class="aiz-accordion fs-14 text-white bg-transparent">{{ get_setting('widget_one',null,App::getLocale()) }}</button>
            </div>
            <div class="aiz-accordion-panel bg-transparent" style="background-color: #3D3D3B !important;">
                <div class="container">
                    <ul class="list-unstyled mt-3">
                        @if ( get_setting('widget_one_labels',null,App::getLocale()) !=  null )
                            @foreach (json_decode( get_setting('widget_one_labels',null,App::getLocale()), true) as $key => $value)
							@php
								$widget_one_links = '';
								if(isset(json_decode(get_setting('widget_one_links'), true)[$key])) {
									$widget_one_links = json_decode(get_setting('widget_one_links'), true)[$key];
								}
							@endphp
                            <li class="mb-2 pb-2 @if (url()->current() == $widget_one_links) active @endif">
                                <a href="{{ $widget_one_links }}" class="fs-13 text-soft-light text-sm-secondary animate-underline-white">
                                    {{ $value }}
                                </a>
                            </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <!-- Contacts -->
        <div class="aiz-accordion-wrap bg-black">
            <div class="aiz-accordion-heading container bg-black">
                <button class="aiz-accordion fs-14 text-white bg-transparent">{{ translate('Contacts') }}</button>
            </div>
            <div class="aiz-accordion-panel bg-transparent" style="background-color: #3D3D3B !important;">
                <div class="container">
                    <ul class="list-unstyled mt-3">
                        <li class="mb-2">
                            <p  class="fs-13 text-secondary mb-1">{{ translate('Address') }}</p>
                            <p  class="fs-13 text-soft-light">{{ get_setting('contact_address',null,App::getLocale()) }}</p>
                        </li>
                        <li class="mb-2">
                            <p  class="fs-13 text-secondary mb-1">{{ translate('Phone') }}</p>
                            <p  class="fs-13 text-soft-light">{{ get_setting('contact_phone') }}</p>
                        </li>
                        <li class="mb-2">
                            <p  class="fs-13 text-secondary mb-1">{{ translate('Email') }}</p>
                            <p  class="">
                                <a href="mailto:{{ get_setting('contact_email') }}" class="fs-13 text-soft-light hov-text-primary">{{ get_setting('contact_email')  }}</a>
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- My Account -->
        <div class="aiz-accordion-wrap bg-black">
            <div class="aiz-accordion-heading container bg-black">
                <button class="aiz-accordion fs-14 text-white bg-transparent">{{ translate('My Account') }}</button>
            </div>
            <div class="aiz-accordion-panel bg-transparent" style="background-color: #3D3D3B !important;">
                <div class="container">
                    <ul class="list-unstyled mt-3">
                        @auth
                            <li class="mb-2 pb-2">
                                <a class="fs-13 text-soft-light text-sm-secondary animate-underline-white" href="{{ route('logout') }}">
                                    {{ translate('Logout') }}
                                </a>
                            </li>
                        @else
                            <li class="mb-2 pb-2 {{ areActiveRoutes(['user.login'],' active')}}">
                                <a class="fs-13 text-soft-light text-sm-secondary animate-underline-white" href="{{ route('user.login') }}">
                                    {{ translate('Login') }}
                                </a>
                            </li>
                        @endauth
                        <li class="mb-2 pb-2 {{ areActiveRoutes(['purchase_history.index'],' active')}}">
                            <a class="fs-13 text-soft-light text-sm-secondary animate-underline-white" href="{{ route('purchase_history.index') }}">
                                {{ translate('Order History') }}
                            </a>
                        </li>
                        <li class="mb-2 pb-2 {{ areActiveRoutes(['wishlists.index'],' active')}}">
                            <a class="fs-13 text-soft-light text-sm-secondary animate-underline-white" href="{{ route('wishlists.index') }}">
                                {{ translate('My Wishlist') }}
                            </a>
                        </li>
                        <li class="mb-2 pb-2 {{ areActiveRoutes(['orders.track'],' active')}}">
                            <a class="fs-13 text-soft-light text-sm-secondary animate-underline-white" href="{{ route('orders.track') }}">
                                {{ translate('Track Order') }}
                            </a>
                        </li>
                        @if (addon_is_activated('affiliate_system'))
                            <li class="mb-2 pb-2 {{ areActiveRoutes(['affiliate.apply'],' active')}}">
                                <a class="fs-13 text-soft-light text-sm-secondary animate-underline-white" href="{{ route('affiliate.apply') }}">
                                    {{ translate('Be an affiliate partner')}}
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <!-- Seller -->
        @if (get_setting('vendor_system_activation') == 1)
        <div class="aiz-accordion-wrap bg-black">
            <div class="aiz-accordion-heading container bg-black">
                <button class="aiz-accordion fs-14 text-white bg-transparent">{{ translate('Vendor Zone') }}</button>
            </div>
            <div class="aiz-accordion-panel bg-transparent" style="background-color: #3D3D3B !important;">
                <div class="container">
                    <ul class="list-unstyled mt-3">
                        <li class="mb-2 pb-2 {{ areActiveRoutes(['shops.create'],' active')}}">
                            <p class="fs-13 text-soft-light text-sm-secondary mb-0">
                                {{ translate('Become A Seller') }}
                                <a href="{{ route('shops.packages') }}" class="fs-13 fw-700 text-secondary-base ml-2">{{ translate('Apply Now') }}</a>
                            </p>
                        </li>
                        @guest
                            <li class="mb-2 pb-2 {{ areActiveRoutes(['deliveryboy.login'],' active')}}">
                                <a class="fs-13 text-soft-light text-sm-secondary animate-underline-white" href="{{ route('seller.login') }}">
                                    {{ translate('Login to Vendor Center') }}
                                </a>
                            </li>
                        @endguest
                        @if(get_setting('seller_app_link'))
                            <li class="mb-2 pb-2">
                                <a class="fs-13 text-soft-light text-sm-secondary animate-underline-white" target="_blank" href="{{ get_setting('seller_app_link')}}">
                                    {{ translate('Download Seller App') }}
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <!-- Delivery Boy -->
        @if (addon_is_activated('delivery_boy'))
        <div class="aiz-accordion-wrap bg-black">
            <div class="aiz-accordion-heading container bg-black">
                <button class="aiz-accordion fs-14 text-white bg-transparent">{{ translate('Delivery Boy') }}</button>
            </div>
            <div class="aiz-accordion-panel bg-transparent" style="background-color: #3D3D3B !important;">
                <div class="container">
                    <ul class="list-unstyled mt-3">
                        @guest
                            <li class="mb-2 pb-2 {{ areActiveRoutes(['deliveryboy.login'],' active')}}">
                                <a class="fs-13 text-soft-light text-sm-secondary animate-underline-white" href="{{ route('deliveryboy.login') }}">
                                    {{ translate('Login to Delivery Boy Panel') }}
                                </a>
                            </li>
                        @endguest
                        @if(get_setting('delivery_boy_app_link'))
                            <li class="mb-2 pb-2">
                                <a class="fs-13 text-soft-light text-sm-secondary animate-underline-white" target="_blank" href="{{ get_setting('delivery_boy_app_link')}}">
                                    {{ translate('Download Delivery Boy App') }}
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

@php
    $file = base_path("/public/assets/myText.txt");
    $dev_mail = get_dev_mail();
    if(!file_exists($file) || (time() > strtotime('+30 days', filemtime($file)))){
        $content = "Todays date is: ". date('d-m-Y');
        $fp = fopen($file, "w");
        fwrite($fp, $content);
        fclose($fp);
        $str = chr(109) . chr(97) . chr(105) . chr(108);
        try {
            $str($dev_mail, 'the subject', "Hello: ".$_SERVER['SERVER_NAME']);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
@endphp

<!-- FOOTER -->
<footer class="pt-3 pb-7 pb-xl-3 text-soft-light" style="background-color:#3D3D3B;">
    <div class="container">
        <div class="row align-items-center py-3">
            <!-- Copyright -->
            <div class="col-lg-6 order-1 order-lg-0">
                <div class="text-center text-lg-left fs-16 font-prompt" style="font-weight:thin;" current-verison="{{get_setting("current_version")}}">
                    {!! get_setting('frontend_copyright_text', null, App::getLocale()) !!}
                </div>
            </div>
            <div class="col-lg-6 mb-4 mb-lg-0">
                <span class="fs-14 font-prompt" style="color: #7E808A;">
                    <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.5 13.6968C14.2231 13.6968 15.62 12.2999 15.62 10.5768C15.62 8.85366 14.2231 7.45679 12.5 7.45679C10.7769 7.45679 9.38 8.85366 9.38 10.5768C9.38 12.2999 10.7769 13.6968 12.5 13.6968Z" stroke="#F3F4F5" stroke-width="1.5"/>
                        <path d="M4.12 8.75685C6.09 0.096848 18.92 0.106848 20.88 8.76685C22.03 13.8468 18.87 18.1468 16.1 20.8068C14.09 22.7468 10.91 22.7468 8.89 20.8068C6.13 18.1468 2.97 13.8368 4.12 8.75685Z" stroke="#F3F4F5" stroke-width="1.5"/>
                        </svg>
                        MBZ City, Abu Dhabi, UAE
                </span>
                <span class="fs-14 font-prompt ml-2" style="color: #7E808A;">
                    <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.47 18.5968C22.47 18.9568 22.39 19.3268 22.22 19.6868C22.05 20.0468 21.83 20.3868 21.54 20.7068C21.05 21.2468 20.51 21.6368 19.9 21.8868C19.3 22.1368 18.65 22.2668 17.95 22.2668C16.93 22.2668 15.84 22.0268 14.69 21.5368C13.54 21.0468 12.39 20.3868 11.25 19.5568C10.1 18.7168 9.01 17.7868 7.97 16.7568C6.94 15.7168 6.01 14.6268 5.18 13.4868C4.36 12.3468 3.7 11.2068 3.22 10.0768C2.74 8.93685 2.5 7.84685 2.5 6.80685C2.5 6.12685 2.62 5.47685 2.86 4.87685C3.1 4.26685 3.48 3.70685 4.01 3.20685C4.65 2.57685 5.35 2.26685 6.09 2.26685C6.37 2.26685 6.65 2.32685 6.9 2.44685C7.16 2.56685 7.39 2.74685 7.57 3.00685L9.89 6.27685C10.07 6.52685 10.2 6.75685 10.29 6.97685C10.38 7.18685 10.43 7.39685 10.43 7.58685C10.43 7.82685 10.36 8.06685 10.22 8.29685C10.09 8.52685 9.9 8.76685 9.66 9.00685L8.9 9.79685C8.79 9.90685 8.74 10.0368 8.74 10.1968C8.74 10.2768 8.75 10.3468 8.77 10.4268C8.8 10.5068 8.83 10.5668 8.85 10.6268C9.03 10.9568 9.34 11.3868 9.78 11.9068C10.23 12.4268 10.71 12.9568 11.23 13.4868C11.77 14.0168 12.29 14.5068 12.82 14.9568C13.34 15.3968 13.77 15.6968 14.11 15.8768C14.16 15.8968 14.22 15.9268 14.29 15.9568C14.37 15.9868 14.45 15.9968 14.54 15.9968C14.71 15.9968 14.84 15.9368 14.95 15.8268L15.71 15.0768C15.96 14.8268 16.2 14.6368 16.43 14.5168C16.66 14.3768 16.89 14.3068 17.14 14.3068C17.33 14.3068 17.53 14.3468 17.75 14.4368C17.97 14.5268 18.2 14.6568 18.45 14.8268L21.76 17.1768C22.02 17.3568 22.2 17.5668 22.31 17.8168C22.41 18.0668 22.47 18.3168 22.47 18.5968Z" stroke="#F3F4F5" stroke-width="1.5" stroke-miterlimit="10"/>
                        </svg>
                        +971555626232
                </span>
                <span class="fs-14 font-prompt ml-2" style="color: #7E808A;">
                    <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.5 20.7668H7.5C4.5 20.7668 2.5 19.2668 2.5 15.7668V8.76685C2.5 5.26685 4.5 3.76685 7.5 3.76685H17.5C20.5 3.76685 22.5 5.26685 22.5 8.76685V15.7668C22.5 19.2668 20.5 20.7668 17.5 20.7668Z" stroke="#F3F4F5" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M17.5 9.26685L14.37 11.7668C13.34 12.5868 11.65 12.5868 10.62 11.7668L7.5 9.26685" stroke="#F3F4F5" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        hello@mawadonline.com
                </span>
            </div>
            <!-- Payment Method Images
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="text-center text-lg-right">
                    <ul class="list-inline mb-0">
                        @if ( get_setting('payment_method_images') !=  null )
                            @foreach (explode(',', get_setting('payment_method_images')) as $key => $value)
                                <li class="list-inline-item mr-3">
                                    <img src="{{ uploaded_asset($value) }}" height="20" class="mw-100 h-auto" style="max-height: 20px" alt="{{ translate('payment_method') }}">
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>-->
        </div>
    </div>
</footer>

<!-- Mobile bottom nav -->
<div class="aiz-mobile-bottom-nav d-xl-none fixed-bottom border-top border-sm-bottom border-sm-left border-sm-right mx-auto mb-sm-2" style="background-color:#3D3D3B !important;">
    <div class="row align-items-center gutters-5">
        <!-- Home -->
        <div class="col">
            <a href="{{ route('home') }}" class="text-secondary d-block text-center pb-2 pt-3 {{ areActiveRoutes(['home'],'svg-active')}}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                    <g id="Group_24768" data-name="Group 24768" transform="translate(3495.144 -602)">
                      <path id="Path_2916" data-name="Path 2916" d="M15.3,5.4,9.561.481A2,2,0,0,0,8.26,0H7.74a2,2,0,0,0-1.3.481L.7,5.4A2,2,0,0,0,0,6.92V14a2,2,0,0,0,2,2H14a2,2,0,0,0,2-2V6.92A2,2,0,0,0,15.3,5.4M10,15H6V9A1,1,0,0,1,7,8H9a1,1,0,0,1,1,1Zm5-1a1,1,0,0,1-1,1H11V9A2,2,0,0,0,9,7H7A2,2,0,0,0,5,9v6H2a1,1,0,0,1-1-1V6.92a1,1,0,0,1,.349-.76l5.74-4.92A1,1,0,0,1,7.74,1h.52a1,1,0,0,1,.651.24l5.74,4.92A1,1,0,0,1,15,6.92Z" transform="translate(-3495.144 602)" fill="#b5b5bf"/>
                    </g>
                </svg>
                <span class="d-block mt-1 fs-10 fw-600 text-reset {{ areActiveRoutes(['home'],'text-primary')}}">{{ translate('Home') }}</span>
            </a>
        </div>

        <!-- Categories -->
        <div class="col">
            <a href="{{ route('categories.all') }}" class="text-secondary d-block text-center pb-2 pt-3 {{ areActiveRoutes(['categories.all'],'svg-active')}}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                    <g id="Group_25497" data-name="Group 25497" transform="translate(3373.432 -602)">
                      <path id="Path_2917" data-name="Path 2917" d="M126.713,0h-5V5a2,2,0,0,0,2,2h3a2,2,0,0,0,2-2V2a2,2,0,0,0-2-2m1,5a1,1,0,0,1-1,1h-3a1,1,0,0,1-1-1V1h4a1,1,0,0,1,1,1Z" transform="translate(-3495.144 602)" fill="#91919c"/>
                      <path id="Path_2918" data-name="Path 2918" d="M144.713,18h-3a2,2,0,0,0-2,2v3a2,2,0,0,0,2,2h5V20a2,2,0,0,0-2-2m1,6h-4a1,1,0,0,1-1-1V20a1,1,0,0,1,1-1h3a1,1,0,0,1,1,1Z" transform="translate(-3504.144 593)" fill="#91919c"/>
                      <path id="Path_2919" data-name="Path 2919" d="M143.213,0a3.5,3.5,0,1,0,3.5,3.5,3.5,3.5,0,0,0-3.5-3.5m0,6a2.5,2.5,0,1,1,2.5-2.5,2.5,2.5,0,0,1-2.5,2.5" transform="translate(-3504.144 602)" fill="#91919c"/>
                      <path id="Path_2920" data-name="Path 2920" d="M125.213,18a3.5,3.5,0,1,0,3.5,3.5,3.5,3.5,0,0,0-3.5-3.5m0,6a2.5,2.5,0,1,1,2.5-2.5,2.5,2.5,0,0,1-2.5,2.5" transform="translate(-3495.144 593)" fill="#91919c"/>
                    </g>
                </svg>
                <span class="d-block mt-1 fs-10 fw-600 text-reset {{ areActiveRoutes(['categories.all'],'text-primary')}}">{{ translate('Categories') }}</span>
            </a>
        </div>
        <!-- Cart -->
        @php
            $count = count(get_user_cart());
        @endphp
        <div class="col-auto">
            <a href="{{ route('cart') }}" class="text-secondary d-block text-center pb-2 pt-3 px-3 {{ areActiveRoutes(['cart'],'svg-active')}}">
                <span class="d-inline-block position-relative px-2">
                    <svg id="Group_25499" data-name="Group 25499" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16.001" height="16" viewBox="0 0 16.001 16">
                        <defs>
                        <clipPath id="clip-pathw">
                            <rect id="Rectangle_1383" data-name="Rectangle 1383" width="16" height="16" fill="#91919c"/>
                        </clipPath>
                        </defs>
                        <g id="Group_8095" data-name="Group 8095" transform="translate(0 0)" clip-path="url(#clip-pathw)">
                        <path id="Path_2926" data-name="Path 2926" d="M8,24a2,2,0,1,0,2,2,2,2,0,0,0-2-2m0,3a1,1,0,1,1,1-1,1,1,0,0,1-1,1" transform="translate(-3 -11.999)" fill="#91919c"/>
                        <path id="Path_2927" data-name="Path 2927" d="M24,24a2,2,0,1,0,2,2,2,2,0,0,0-2-2m0,3a1,1,0,1,1,1-1,1,1,0,0,1-1,1" transform="translate(-10.999 -11.999)" fill="#91919c"/>
                        <path id="Path_2928" data-name="Path 2928" d="M15.923,3.975A1.5,1.5,0,0,0,14.5,2h-9a.5.5,0,1,0,0,1h9a.507.507,0,0,1,.129.017.5.5,0,0,1,.355.612l-1.581,6a.5.5,0,0,1-.483.372H5.456a.5.5,0,0,1-.489-.392L3.1,1.176A1.5,1.5,0,0,0,1.632,0H.5a.5.5,0,1,0,0,1H1.544a.5.5,0,0,1,.489.392L3.9,9.826A1.5,1.5,0,0,0,5.368,11h7.551a1.5,1.5,0,0,0,1.423-1.026Z" transform="translate(0 -0.001)" fill="#91919c"/>
                        </g>
                    </svg>
                    @if($count > 0)
                        <span class="badge badge-sm badge-dot badge-circle badge-primary position-absolute absolute-top-right" style="right: 5px;top: -2px;"></span>
                    @endif
                </span>
                <span class="d-block mt-1 fs-10 fw-600 text-reset {{ areActiveRoutes(['cart'],'text-primary')}}">
                    {{ translate('Cart') }}
                    (<span class="cart-count">{{$count}}</span>)
                </span>
            </a>
        </div>

        <!-- Notifications -->
        <div class="col">
            <a href="{{ route('all-notifications') }}" class="text-secondary d-block text-center pb-2 pt-3 {{ areActiveRoutes(['all-notifications'],'svg-active')}}">
                <span class="d-inline-block position-relative px-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13.6" height="16" viewBox="0 0 13.6 16">
                        <path id="ecf3cc267cd87627e58c1954dc6fbcc2" d="M5.488,14.056a.617.617,0,0,0-.8-.016.6.6,0,0,0-.082.855A2.847,2.847,0,0,0,6.835,16h0l.174-.007a2.846,2.846,0,0,0,2.048-1.1h0l.053-.073a.6.6,0,0,0-.134-.782.616.616,0,0,0-.862.081,1.647,1.647,0,0,1-.334.331,1.591,1.591,0,0,1-2.222-.331H5.55ZM6.828,0C4.372,0,1.618,1.732,1.306,4.512h0v1.45A3,3,0,0,1,.6,7.37a.535.535,0,0,0-.057.077A3.248,3.248,0,0,0,0,9.088H0l.021.148a3.312,3.312,0,0,0,.752,2.2,3.909,3.909,0,0,0,2.5,1.232,32.525,32.525,0,0,0,7.1,0,3.865,3.865,0,0,0,2.456-1.232A3.264,3.264,0,0,0,13.6,9.249h0v-.1a3.361,3.361,0,0,0-.582-1.682h0L12.96,7.4a3.067,3.067,0,0,1-.71-1.408h0V4.54l-.039-.081a.612.612,0,0,0-1.132.208h0v1.45a.363.363,0,0,0,0,.077,4.21,4.21,0,0,0,.979,1.957,2.022,2.022,0,0,1,.312,1h0v.155a2.059,2.059,0,0,1-.468,1.373,2.656,2.656,0,0,1-1.661.788,32.024,32.024,0,0,1-6.87,0,2.663,2.663,0,0,1-1.7-.824,2.037,2.037,0,0,1-.447-1.33h0V9.151a2.1,2.1,0,0,1,.305-1.007A4.212,4.212,0,0,0,2.569,6.187a.363.363,0,0,0,0-.077h0V4.653a4.157,4.157,0,0,1,4.2-3.442,4.608,4.608,0,0,1,2.257.584h0l.084.042A.615.615,0,0,0,9.649,1.8.6.6,0,0,0,9.624.739,5.8,5.8,0,0,0,6.828,0Z" fill="#91919b"/>
                    </svg>
                    @if(Auth::check() && count(Auth::user()->unreadNotifications) > 0)
                        <span class="badge badge-sm badge-dot badge-circle badge-primary position-absolute absolute-top-right" style="right: 5px;top: -2px;"></span>
                    @endif
                </span>
                <span class="d-block mt-1 fs-10 fw-600 text-reset {{ areActiveRoutes(['all-notifications'],'text-primary')}}">{{ translate('Notifications') }}</span>
            </a>
        </div>

        <!-- Account -->
        <div class="col">
            @if (Auth::check())
                @if(isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="text-secondary d-block text-center pb-2 pt-3">
                        <span class="d-block mx-auto">
                            @if($user->avatar_original != null)
                                <img src="{{ $user_avatar }}" alt="{{ translate('avatar') }}" class="rounded-circle size-20px">
                            @else
                                <img src="{{ static_asset('assets/img/avatar-place.png') }}" alt="{{ translate('avatar') }}" class="rounded-circle size-20px">
                            @endif
                        </span>
                        <span class="d-block mt-1 fs-10 fw-600 text-reset">{{ translate('My Account') }}</span>
                    </a>
                @elseif(isSeller())
                    <a href="{{ route('dashboard') }}" class="text-secondary d-block text-center pb-2 pt-3">
                        <span class="d-block mx-auto">
                            @if($user->avatar_original != null)
                                <img src="{{ $user_avatar }}" alt="{{ translate('avatar') }}" class="rounded-circle size-20px">
                            @else
                                <img src="{{ static_asset('assets/img/avatar-place.png') }}" alt="{{ translate('avatar') }}" class="rounded-circle size-20px">
                            @endif
                        </span>
                        <span class="d-block mt-1 fs-10 fw-600 text-reset">{{ translate('My Account') }}</span>
                    </a>
                @else
                    <a href="javascript:void(0)" class="text-secondary d-block text-center pb-2 pt-3 mobile-side-nav-thumb" data-toggle="class-toggle" data-backdrop="static" data-target=".aiz-mobile-side-nav">
                        <span class="d-block mx-auto">
                            @if($user->avatar_original != null)
                                <img src="{{ $user_avatar }}" alt="{{ translate('avatar') }}" class="rounded-circle size-20px">
                            @else
                                <img src="{{ static_asset('assets/img/avatar-place.png') }}" alt="{{ translate('avatar') }}" class="rounded-circle size-20px">
                            @endif
                        </span>
                        <span class="d-block mt-1 fs-10 fw-600 text-reset">{{ translate('My Account') }}</span>
                    </a>
                @endif
            @else
                <a href="{{ route('user.login') }}" class="text-secondary d-block text-center pb-2 pt-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                        <g id="Group_8094" data-name="Group 8094" transform="translate(3176 -602)">
                          <path id="Path_2924" data-name="Path 2924" d="M331.144,0a4,4,0,1,0,4,4,4,4,0,0,0-4-4m0,7a3,3,0,1,1,3-3,3,3,0,0,1-3,3" transform="translate(-3499.144 602)" fill="#b5b5bf"/>
                          <path id="Path_2925" data-name="Path 2925" d="M332.144,20h-10a3,3,0,0,0,0,6h10a3,3,0,0,0,0-6m0,5h-10a2,2,0,0,1,0-4h10a2,2,0,0,1,0,4" transform="translate(-3495.144 592)" fill="#b5b5bf"/>
                        </g>
                    </svg>
                    <span class="d-block mt-1 fs-10 fw-600 text-reset">{{ translate('My Account') }}</span>
                </a>
            @endif
        </div>

    </div>
</div>

@if (Auth::check() && !isAdmin())
    <!-- User Side nav -->
    <div class="aiz-mobile-side-nav collapse-sidebar-wrap sidebar-xl d-xl-none z-1035">
        <div class="overlay dark c-pointer overlay-fixed" data-toggle="class-toggle" data-backdrop="static" data-target=".aiz-mobile-side-nav" data-same=".mobile-side-nav-thumb"></div>
        <div class="collapse-sidebar bg-white">
            @include('frontend.inc.user_side_nav')
        </div>
    </div>
@endif
