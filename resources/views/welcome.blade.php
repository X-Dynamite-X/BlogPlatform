<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @vite(['resources/js/app.js','resources/css/app.css'])
    </head>

    <body>
        <div id="app" class=""></div>
    </body>
</html>
