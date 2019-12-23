<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="cekovic">
    <meta name="description" content="Bekleme Ekrani">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="http://stats.pusher.com">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" rel="stylesheet" media>
    <style>
        @keyframes rotating {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
        .rotating {
            font-size: 24px;
            animation: rotating 1.5s linear infinite;
        }
    </style>
    @yield('styles')
</head>
<body>
    <div id="app">
        @if (Route::current()->getName() == '')
        <button v-if="! notify" class="btn btn-sm btn-danger" @click="playSound(true)">
            <i class="fas fa-volume-mute"></i>
        </button>
        <button v-else class="btn btn-sm btn-success" @click="playSound(false)">
            <i class="fas fa-volume-up"></i>
        </button>
        @endif
        @yield('content')
        <div class="container">
            <div class="col-md-12" style="margin-top: 20px">
                <hr />
            </div>

            <div class="col-md-12">
                <div class="text-center">
                    Built with ❤️ by <a href="https://instagram.com/ihfkaya" rel="noopener" target="_blank">Cekovic</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
</body>
</html>
