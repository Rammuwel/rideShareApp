<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Document</title>
    @vite('resources/js/app.js')
    <style>
        body {
            background-color: gray;
        }

        .container {
            width: 80%;
            margin: auto;
        }

        .header {
            padding: 10px;
            text-align: center;
            background-color: white;
            font-size: 16px;
            width: 100%;
        }

        .header span {
            font-weight: bold;
        }

        .search-show {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 10px;
            color: #fff;
        }

        .show-ride-container {
            display: flex;
            flex-direction: column;
            gap: 5px;
            align-items: center;
            margin-top: 20px;
        }

        .card {
            width: 380px;
            background-color: white;
            padding: 10px;
            border-radius: 10px;
            box-sizing: border-box;
            box-shadow: 0px 2px 20px rgba(0, 0, 0, 0.2);
        }

        .cart-header {
            display: flex;
            justify-content: space-between;
            font-size: 16px;

        }

        .cart-header .counter {
            background: green;
            color: white;
            padding: 5px;
            border-radius: 50px;
            box-shadow: 0px 2px 20px rgba(0, 0, 0, 0.2);
        }

        .cart-header .cancel {
            color: red;
            font-weight: bold;
            cursor: pointer;
        }

        .cart-address {
            font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <p>Dear partner <span>{{ $user->name }}</span> you are online</p>
        </div>

        <div class="show-ride-container">


        </div>
        <div class="search-show">
            <p>Searching for ride.....</p>
        </div>
    </div>
</body>



<script type="module">
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.querySelector('.show-ride-container');

        window.Echo.private('drivers')
            .listen('.trip.created', (e) => {

                const rideCard = document.createElement('div');
                rideCard.className = 'card';

                let timeLeft = 30;

                rideCard.innerHTML = `
                    <div class="cart-header">
                        <div class="counter">${timeLeft}s</div>
                    </div>
                    <div class="cart-address">
                        <p>from: <span>${e.trip.origin_name}</span></p>
                        <p>to: <span>${e.trip.destination_name}</span></p>
                    </div>
                    <div style="display: flex; gap: 10px; margin-top: 10px;">
                        <button class="btn-cancel" style="flex: 1; padding: 10px; background: #dc3545; color: white; border: none; border-radius: 5px; cursor: pointer;">
                            Cancel
                        </button>
                        <button class="btn-accept" style="flex: 2; padding: 10px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer;">
                            Accept Ride
                        </button>
                    </div>
                `;

                // Add to container
                container.prepend(rideCard);

                // --- Logic for this specific card ---
                const counterDisplay = rideCard.querySelector('.counter');

                // 1. Timer logic
                const timer = setInterval(() => {
                    timeLeft--;
                    counterDisplay.innerText = `${timeLeft}s`;

                    if (timeLeft <= 0) {
                        clearInterval(timer);
                        rideCard.remove(); // Auto-remove after 30s
                    }
                }, 1000);

                // 2. Cancel button logic
                rideCard.querySelector('.btn-cancel').addEventListener('click', () => {
                    clearInterval(timer);
                    rideCard.remove();
                });

                // 3. Accept button logic
                let accept_btn = rideCard.querySelector('.btn-accept');
                accept_btn.addEventListener('click', async () => {
                    clearInterval(timer);
                    let tripId = e.trip.id
                    const formData = {
                        'driver_location': {
                            'lag': '23.5678738',
                            'lat': '21.568666'
                        }
                    }
                    accept_btn.disabled = true;
                    accept_btn.innerText = "Processing...";

                    try {
                        const url = "{{ route('post.trip.accept', ':tripId') }}".replace(':tripId', tripId);
                        const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'content-type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Authorization': "Bearer " + localStorage.getItem('token')
                            },
                            body: JSON.stringify(formData)
                        });

                        if (response.ok) {
                            const trip = await response.json();
                            window.location.href = "{{ url('/trip/deriver') }}/" + e.trip.id;

                        } else {
                            alert('Error creating trip. Check console.');
                            accept_btn.disabled = false;
                            accept_btn.innerText = "Accept";
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        accept_btn.disabled = false;
                    }
                });

            });
    });
</script>



</html>