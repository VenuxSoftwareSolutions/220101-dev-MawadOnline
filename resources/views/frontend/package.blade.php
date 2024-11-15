@extends('frontend.layouts.app')

@section('content')
    <section class="py-4 py-lg-5">
        <div class="container d-flex justify-content-center">
            <div class="col-11 col-xl-10 p-0 fs-48 register-shop-title d-flex justify-content-start font-prompt pb-3 text-start">Become a Vendor</div>
        </div>
        <div class="container d-flex justify-content-center">
            <div class="col-11 col-xl-10 register-shop-style p-0">
                <!-- e-shop registration header -->
                <div class="col-12 register-shop-h d-flex justify-content-center">
                    <span class="register-shop-h-text fs-16 font-prompt text-center">Make sure that you have the following documents handy and ready to upload</span>
                </div>
                <!-- e-shop demanded uploads -->
                <div class="col-md-12 pt-5">
                    <div class="col-md-12 col-lg-4 float-left border-inside-right no-border-right-md">
                        <div class="col-12 d-flex justify-content-center">
                            <svg width="72" height="72" viewBox="0 0 72 72" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M54.421 64.8593C51.781 65.6393 48.6611 65.9993 45.0011 65.9993H27.0011C23.3411 65.9993 20.2211 65.6393 17.5811 64.8593C18.2411 57.0593 26.2511 50.9092 36.0011 50.9092C45.7511 50.9092 53.761 57.0593 54.421 64.8593Z" stroke="#8B8D98" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M45 6H27C12 6 6 12 6 27V45C6 56.34 9.42 62.55 17.58 64.86C18.24 57.06 26.25 50.9099 36 50.9099C45.75 50.9099 53.76 57.06 54.42 64.86C62.58 62.55 66 56.34 66 45V27C66 12 60 6 45 6ZM36 42.5099C30.06 42.5099 25.26 37.68 25.26 31.74C25.26 25.8 30.06 21 36 21C41.94 21 46.74 25.8 46.74 31.74C46.74 37.68 41.94 42.5099 36 42.5099Z" fill="white" stroke="#8B8D98" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M46.7398 31.7401C46.7398 37.6801 41.9398 42.5099 35.9998 42.5099C30.0598 42.5099 25.2598 37.6801 25.2598 31.7401C25.2598 25.8001 30.0598 21 35.9998 21C41.9398 21 46.7398 25.8001 46.7398 31.7401Z" stroke="#8B8D98" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="col-md-12 d-flex justify-content-center py-2">
                            <span class="register-shop-s-t fs-16 font-prompt-sb">UAE ID</span>
                        </div>
                        <div class="col-md-12 d-flex justify-content-center py-2">
                            <span class="register-shop-desc fs-16 font-prompt text-center">
                                Identification of the person who will manage your account.
                            </span>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-4 float-left border-inside-right no-border-right-md">
                        <div class="col-12 d-flex justify-content-center">
                            <svg width="72" height="72" viewBox="0 0 72 72" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.0293 33.6592V47.1292C9.0293 60.5992 14.4293 65.9992 27.8993 65.9992H44.0693C57.5393 65.9992 62.9393 60.5992 62.9393 47.1292V33.6592" stroke="#8B8D98" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M36.0014 36C41.4914 36 45.5414 31.53 45.0014 26.04L43.0214 6H29.0114L27.0014 26.04C26.4614 31.53 30.5114 36 36.0014 36Z" stroke="#8B8D98" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M54.9294 36C60.9894 36 65.4294 31.08 64.8294 25.05L63.9894 16.8C62.9094 9 59.9094 6 52.0494 6H42.8994L44.9994 27.03C45.5094 31.98 49.9794 36 54.9294 36Z" stroke="#8B8D98" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M16.9211 36C21.8711 36 26.3411 31.98 26.8211 27.03L27.4811 20.4L28.9211 6H19.7711C11.9111 6 8.91111 9 7.83111 16.8L7.02111 25.05C6.42111 31.08 10.8611 36 16.9211 36Z" stroke="#8B8D98" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M36 51C30.99 51 28.5 53.49 28.5 58.5V66H43.5V58.5C43.5 53.49 41.01 51 36 51Z" stroke="#8B8D98" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="col-md-12 d-flex justify-content-center py-2">
                            <span class="register-shop-s-t fs-16 font-prompt-sb">UAE Trade License </span>
                        </div>
                        <div class="col-md-12 d-flex justify-content-center py-2">
                            <span class="register-shop-desc fs-16 font-prompt text-center">
                                Your valid businesses license issued within the UAE.
                            </span>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-4 float-left">
                        <div class="col-12 d-flex justify-content-center">
                            <svg width="72" height="72" viewBox="0 0 72 72" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M39 27H21" stroke="#8B8D98" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M66.0006 32.9105V39.0906C66.0006 40.7406 64.6806 42.0905 63.0006 42.1505H57.1206C53.8806 42.1505 50.9107 39.7805 50.6407 36.5405C50.4607 34.6505 51.1806 32.8805 52.4406 31.6505C53.5506 30.5105 55.0806 29.8506 56.7606 29.8506H63.0006C64.6806 29.9106 66.0006 31.2605 66.0006 32.9105Z" stroke="#8B8D98" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M52.4399 31.65C51.1799 32.88 50.46 34.65 50.64 36.54C50.91 39.78 53.8799 42.15 57.1199 42.15H63V46.5C63 55.5 57 61.5 48 61.5H21C12 61.5 6 55.5 6 46.5V25.5C6 17.34 10.92 11.64 18.57 10.68C19.35 10.56 20.16 10.5 21 10.5H48C48.78 10.5 49.53 10.53 50.25 10.65C57.99 11.55 63 17.28 63 25.5V29.85H56.7599C55.0799 29.85 53.5499 30.51 52.4399 31.65Z" stroke="#8B8D98" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="col-md-12 d-flex justify-content-center py-2">
                            <span class="register-shop-s-t fs-16 font-prompt-sb">Bank Account Details</span>
                        </div>
                        <div class="col-md-12 d-flex justify-content-center py-2">
                            <span class="register-shop-desc fs-16 font-prompt text-center">
                                Your businesses bank account information for secure payout.
                            </span>
                        </div>
                    </div>
                </div>
                <!-- e-shop Register button -->
                <div class="col-md-12 d-flex justify-content-center py-4">
                    <form action="{{ route('shops.create') }}">
                    <button type="submit" class="btn btn-secondary-base register-shop-btn text-white border-radius-16 fs-16 font-prompt py-2">
                        Register your eShop
                    </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
