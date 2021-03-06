<html>
    <head>
        <title>Dzienniczek leków - @yield('title')</title>
    
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script> 
        
        <script src="{{ asset('./js/app.js')}}"></script>

        <link href="{{ asset('./css/default/app.css') }}" rel="stylesheet"> 
    </head>
    <body>


        <div id='body_register'>
            <div id="menu">
                <div class="menu">
                    <a class="menu" href="{{url('/Main')}}">GŁÓWNA STRONA</a>
                </div>
                <div class="menu">
                    <a class="menu" href="{{url('/Produkt/Search')}}">WYSZUKAJ</a>
                </div>
                <div class="menu">
                    <a class="menu" href="{{url('/Produkt/Add')}}">DODAJ NOWY PRODUKT</a>
                </div>
                <div class="menu">
                    <a class="menu" href="{{url('/Produkt/Edit')}}">USTAWIENIA KONTA</a>
                </div>
                <div class="menu">
                    <a class="menu" href="{{url('/User/Logout')}}">WYLOGUJ</a>
                </div>
            </div>
            <div id="top_main">
              
              @yield('content')
              
            </div>
        <br>

            
        </div>
    </body>
</html>