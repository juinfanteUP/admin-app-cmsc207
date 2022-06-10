@extends('layout.main-layout')
@section('content')

    <div id="app" class="chat-bg">
        <div class="layout-wrapper d-lg-flex">

            <!-- Navbar -->
            @include('navbar.menu')


            <!-- Client list -->
            <div id="inner-navbar" class="chat-leftsidebar">
                <div class="tab-content">
                    @include('navbar.clients')           
                </div>
            </div>


            <!-- Pages -->
            <div class="w-100">
                @include('pages.home')
                @include('pages.chat')
                @include('pages.widget')
                @include('pages.multichat')
                @include('pages.messagehistory')
                @include('pages.clientBan')
                @include('pages.clientconfig')
                @include('pages.clienthistory')
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/app.js') }}"></script>

@endsection
