<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Informe Autorizador</title>
</head>
<style>
    body{
        font-family: 'Helvetica', sans-serif;
    }
    thead{
        background: rgb(71,164,245);
    }
    th{
        height: 50px;
        font-weight: bold;
    }
</style>
<body>
<div>
    <table>
        <thead style="background: #47a4f5 ;">
        <tr>
            <th>#</th>
            <th>AUTORIZADOR</th>
            <th>CANTIDAD</th>
        </tr>
        </thead>
        <tbody>
        @foreach($autorizadores as $key => $autor)
            <tr>
                <td>{{++$key}}</td>
                <td>{{$autor['autorizador']}}</td>
                <td>{{$autor['cantidad']}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>