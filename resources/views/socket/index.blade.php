<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="{{ mix('js/app.js') }}"></script>
</head>

<body>
    Hello world

    <script>
        window.Echo.channel('messages')
            .listen('.newMessage', (message) => {
                console.log(message);
            });
    </script>
</body>

</html>
