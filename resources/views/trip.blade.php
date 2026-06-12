<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <title>Active Trip #{{ $trip->id }}</title>
    @vite('resources/js/app.js')
    <style>
        body {
            font-family: sans-serif;
            background: #f4f4f9;
            margin: 0;
            padding: 20px;
        }

        .trip-container {
            max-width: 500px;
            margin: auto;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .map-placeholder {
            width: 100%;
            height: 250px;
            background: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
        }

        .details {
            padding: 20px;
        }

        .address-box {
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
            padding-left: 15px;
        }

        .label {
            font-size: 12px;
            color: #888;
            text-transform: uppercase;
        }

        .address-text {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .action-btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            background: #007bff;
            color: white;
        }
    </style>
</head>

<body>
    <div id="trip-app" class="container">
        <div id="status-card" class="card shadow-lg p-4">
            <!-- Default State: Driver is on the way -->
            <div id="on-the-way-section" @if($trip->is_started) style="display:none;" @endif>
                <h2 class="text-info">Driver is on the way!</h2>
                <p>Your driver <strong>{{ $trip->driver->user->name }}</strong> has accepted the ride.</p>

            </div>

            <!-- Trip Started State -->
            <div id="trip-started-section" @if(!$trip->is_started)style="display:none;" @endif>
                <h2 class="text-success">Trip in Progress</h2>
                <p>You are now heading to: <strong>{{ $trip->destination_name }}</strong></p>
                <div class="progress mt-3">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: 100%"></div>
                </div>
            </div>
            <input type="hidden" id="tripId" value="{{ $trip->id }}">
        </div>
    </div>

    <script type="module">
        document.addEventListener('DOMContentLoaded', function() {
            let tripId = document.getElementById('tripId').value;

            window.Echo.private(`trip.${tripId}`)
                .listen('.trip.started', (e) => {

                    document.getElementById('on-the-way-section').style.display = 'none';
                    document.getElementById('trip-started-section').style.display = 'block';

                });

            window.Echo.private(`trip.${tripId}`)
                .listen('.trip.ended', (e) => {
                    alert('Trip Completed! Redirecting...');
                    window.location.href = '/';
                });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

</body>

</html>