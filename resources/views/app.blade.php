<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1" name="viewport">
        <title>UrgeWrite</title>
        @vite(["resources/css/app.css", "resources/js/app.js"])
    </head>

    <body>
        <div class="min-h-screen bg-gray-200 flex flex-col items-center py-4 px-4" id="app"></div>
    </body>

</html>
