<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
        <script src="https://cdn.tailwindcss.com"></script>

        
    </head>
    <body >
     <div class="p-4"> 
         <div id="principal"> 
             <principal></principal>
         </div>
     </div>
 
        <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
