<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <link rel=stylesheet href="{{ mix('css/app.css') }}" />

        <title>{{env('APP_NAME')}}</title><style>body{font-family:'Nunito',sans-serif}</style>

        <script>
            window.Laravel = {!! json_encode(['csrfToken' => csrf_token()]) !!};
        </script>
    </head>
    <body class="antialiased">
    <div id=app class=wrapper>

        <header id=appHeader class=app-header>
            @if(Auth::check())
            <app-header :user="{{Auth::user()}}"></app-header>
            @else
            <app-header :user="false"></app-header>
            @endif
        </header>



{{--       <main id=appMain class=app-main>--}}
{{--            <router-view></router-view>--}}
{{--       </main>--}}
{{--        <footer id=appFooter class=app-footer>--}}
{{--            <app-footer></app-footer>--}}
{{--        </footer>--}}


    </div> <script src="{{ mix('js/app.js') }}"></script>
    </body>

    <script>
        import HeaderComponent from "../js/components/HeaderComponent";
        export default {
            components: {HeaderComponent}
        }
    </script>
</html>
