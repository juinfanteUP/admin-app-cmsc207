@extends('layout.layout')
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
            </div>
        </div>
    </div>

    <div id="loader">
        <div class="loading">Loading&#8230;</div>
    </div>
    
    <script src="{{ asset('assets/js/app.js') }}"></script>

@endsection
