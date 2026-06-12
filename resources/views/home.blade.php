<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>rideshare</title>
    @vite('resources/js/app.js')


    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: gray;
        }

        .container {
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;


        }

        .card {
            background-color: white;
            padding: 10px;
            border-radius: 10px;
        }

        .inpute-field {
            padding: 5px;
            display: flex;
            justify-content: space-between;
        }

        .inpute-field label {
            font-size: 16px;
            font-weight: bold;
            margin-right: 3px;
        }

        .inpute-field select {
            padding: 5px 10px;
            min-width: 220px;

        }

        button {
            float: right;
            padding: 5px 10px;
            cursor: pointer;
        }

        .profile {
            position: absolute;
            right: 10%;
            top: 10%;

        }

        .profile h3 {
            color: lightgreen;
            cursor: pointer;
            margin: 0;
        }

        .profile ul {

            gap: 5px;
            padding: 10px;
            border-radius: 5px;
            background-color: white;
            display: none;
            margin: 0;
        }


        .profile:hover ul {
            display: flex;
            flex-direction: column;
        }

        .profile li {
            list-style: none;
            font-size: 16px;
            padding: 5px;
            background: gray;
            border-radius: 3px;

        }

        .profile li a {
            text-decoration: none;
            color: white;
        }

        .profile ul li:last-child {
            font-weight: bold;
        }

        .profile ul li:nth-child(3) {
            background: white;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2 style="color: white; margin: 0;">SharerideApp</h2>
        <p style="color: white; margin: 5px;">select you address to make your trip</p>
        <div class="profile">
            <h3>{{ $userName }} >> </h3>
            <ul>
                <li><a href="#">View Profile</a></li>
                @if(!$isDriver)
                <li><a href="{{ route('driver.login') }}">Login as Driver</a></li>
                @else
                <li><a href="{{ route('driver.home') }}">Driver dashboard</a></li>
                @endif
                <li>
                    <hr>
                </li>
                <li><a href="#">
                        << Logout</a>
                </li>
            </ul>
        </div>
        <!-- ... inside your <div class="card"> ... -->
        <div class="card">
            <form id="tripForm">
                @csrf
                <div class="inpute-field">
                    <label for="origin_name">From:</label>
                    <select name="origin_name" id="origin_name">
                        <option value="Indore">Indore</option>
                        <option value="Bhopal">Bhopal</option>
                        <option value="Ujjain">Ujjain</option>
                    </select>
                </div>
                <div class="inpute-field">
                    <label for="destination_name">To:</label>
                    <select name="destination_name" id="destination_name">
                        <option value="Bhopal">Bhopal</option>
                        <option value="Indore">Indore</option>
                        <option value="Mumbai">Mumbai</option>
                    </select>
                </div>
                <!-- Hidden coordinates for your validation -->
                <input type="hidden" name="origin" value='{"lat": 22.7, "lng": 75.8}'>
                <input type="hidden" name="destination" value='{"lat": 23.2, "lng": 77.4}'>

                <button type="submit" id="submitBtn">Make Your Trip</button>
            </form>

            <!-- Status area that appears after clicking button -->
            <div id="statusArea" style="display:none; margin-top:15px; text-align:center;">
                <p style="color: green; font-weight: bold;">Broadcast sent! Waiting for a driver...</p>
                <div class="loader"></div>
            </div>
        </div>



    </div>

    <script type="module">
        document.getElementById('tripForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const token = localStorage.getItem('token');
            const btn = document.getElementById('submitBtn');
            const statusArea = document.getElementById('statusArea');
            const formData = new FormData(e.target);

            btn.disabled = true;
            btn.innerText = "Processing...";

            try {
                const response = await fetch("{{ route('post.trip.create') }}", {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Authorization': "Bearer " + token
                    },
                    body: formData
                });

                if (response.ok) {
                    const data = await response.json();
                    const tripId = data.id; // Ensure your backend returns { trip: { id: 1 } }




                    e.target.style.display = 'none';
                    statusArea.style.display = 'block';

                    // 1. Create the timeout logic
                    const waitingTimeout = setTimeout(() => {
                        alert("Your trip not accepted by any driver, try again.");
                        e.target.style.display = 'block';
                        statusArea.style.display = 'none';
                        btn.disabled = false;
                        btn.innerText = "Make Your Trip";
                        // Leave the channel if failed
                        window.Echo.leave(`trip.${tripId}`);
                    }, 60000);

                   

                   


                    window.Echo.private(`trip.${tripId}`)
                        .listen('.trip.accepted', (e) => {
                            
                            clearTimeout(waitingTimeout);

                            alert(`Your trip accepted by ${e.trip.driver.user.name}`);
                            window.location.href = "{{ url('/trip') }}/" + tripId;
                        });

                } else {
                    alert('Error creating trip.');
                    btn.disabled = false;
                }
            } catch (error) {
                console.error('Error:', error);
                btn.disabled = false;
            }
        });
    </script>

</body>

</html>