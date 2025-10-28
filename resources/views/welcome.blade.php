<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f8f9fa;
        }
        .redirect-message {
            text-align: center;
            padding: 20px;
        }
    </style>
    <script>
        // Автоматический редирект на логин
        setTimeout(function() {
            window.location.href = '/login';
        }, 1000);
    </script>
</head>
<body>
<div class="redirect-message">
    <h2>Перенаправление на страницу входа...</h2>
    <p>Если редирект не произошел, <a href="/login">нажмите сюда</a></p>
</div>
</body>
</html>
