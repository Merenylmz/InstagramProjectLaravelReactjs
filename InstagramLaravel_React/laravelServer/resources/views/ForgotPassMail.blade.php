<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css"/>
</head>
<body>
    <h1>We Changing your password</h1>
    <p>Please click link, then enter new password</p>
    <br>
    <a href="http://localhost:8000/api/newpassword?={{$token}}" class="btn btn-primary">Şifreyi Değiştir</a>
</body>
</html>