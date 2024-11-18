<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('images/capresoca.jpg') }}" type="image/jpg">
    <link href="{{asset('css/custom_style.css')}}" rel="stylesheet">
    <title>Capresoca</title>
</head>
<body>
<div class="container-1">
    <div class="boo-wrapper">
        <div class="boo">
            {{--<div class="face"></div>--}}
            <img class="face-2" src="{{asset('images/search-problem.png')}}" alt="not file">
        </div>
        <div class="shadow-1"></div>
        <h1>El archivo no existe (404).</h1>
        <p>
            <a onclick="cerar()" style="text-decoration: none; color: inherit; cursor: pointer">Regresar a la página.</a>
        </p>
    </div>
</div>
<script type="text/javascript">
    function cerar () {window.close()}
</script>
</body>
</html>