<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Buyer requests</title>
</head>

<body>

    <div>
        id: {{ $order->id }}
        <br>
        status: {{ $order->status }}
        <br>
        type: {{ $order->type }}
        <br>
        service: {{ $order->service->name }}
        <br>
        city: {{ $order->city->name }}
        <br>
        date: {{ $order->date }}
        <br>
        image: {{ $order->image }}
        <br>
        description: {{ $order->description }}
    </div>

    <form action="/pusher/{{ $order->id }}/accept" method="POST">
        @csrf
        <button>Accept</button>
    </form>
    <form action="/pusher/{{ $order->id }}/reject" method="POST">
        @csrf
        <button>Reject</button>
    </form>

    <script src="{{ mix('js/app.js') }}"></script>

    <script>
        Echo.channel('buyer-request.{{ $order->id }}')
            .listen('BuyerRequestAccepted', (e) => {
                window.location.replace("/pusher")
            });
    </script>
</body>

</html>
