<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Buyer requests</title>
</head>
<body>

    {{auth()->user()->username}}
    <table border="1" width="100%">
        <thead>
            <tr>
                <th style="text-align: left">Id</th>
                <th style="text-align: left;">status</th>
                <th style="text-align: left;">type</th>
                <th style="text-align: left;">service</th>
                <th style="text-align: left;">city</th>
                <th style="text-align: left;">date</th>
                <th style="text-align: left;">image</th>
                <th style="text-align: left;">description</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($buyer_requests as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->status }}</td>
                    <td>{{ $order->type }}</td>
                    <td>{{ $order->service->name }}</td>
                    <td>{{ $order->city->name }}</td>
                    <td>{{ $order->date }}</td>
                    <td>{{ $order->image }}</td>
                    <td>{{ $order->description }}</td>
                    <td><a href="/pusher/{{ $order->id }}/details">تفاصيل</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script src="{{ mix('js/app.js') }}"></script>

    <script>
        Echo.channel('buyer-requests')
            .listen('BuyerRequestUpdates', (e) => {
                window.location.replace("/pusher")
            });

        Echo.join(`provider-online`)
            .here((users) => {
                console.log(users);
            })
            .joining((user) => {
                alert('تم تسجيل دخول ' + user.name)
            })
            .leaving((user) => {
                alert('تم تسجيل خروج ' + user.name)
            });
    </script>
</body>
</html>
