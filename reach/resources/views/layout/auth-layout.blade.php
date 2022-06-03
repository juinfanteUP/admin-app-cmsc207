<!doctype html>
<html lang="en">
    <head>     
        <title>Reach App</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    
        <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}">
        <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet" type="text/css" />
        <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
       
    </head>

    <body>     
        
        <div id="app" >
            @yield('content')
        </div>

        <div id="loader">
            <div class="loading">Loading&#8230;</div>
        </div>

        <script src="{{ asset('assets/js/auth.js') }}"></script>

    </body>
      
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  
</html>


