<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>Active Ride</title>
    @vite('resources/js/app.js')
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; margin: 0; }
        .map-section { height: 40vh; background: #ddd; display: flex; align-items: center; justify-content: center; color: #666; font-weight: bold; }
        .info-card { 
            background: white; 
            margin-top: -20px; 
            border-radius: 20px 20px 0 0; 
            padding: 20px; 
            box-shadow: 0 -5px 15px rgba(0,0,0,0.1);
            min-height: 60vh;
        }
        .status-badge { background: #e8f5e9; color: #2e7d32; padding: 5px 12px; border-radius: 15px; font-size: 12px; font-weight: bold; }
        .address-group { margin: 20px 0; border-left: 2px solid #007bff; padding-left: 15px; }
        .label { color: #888; font-size: 12px; text-transform: uppercase; }
        .address { font-size: 16px; font-weight: 600; margin-bottom: 10px; }
        .btn-complete { 
            width: 100%; padding: 15px; background: #28a745; color: white; border: none; 
            border-radius: 10px; font-size: 18px; font-weight: bold; cursor: pointer;
            margin-top: 20px;
        }
        .btn-complete:disabled { background: #ccc; }
    </style>
</head>
<body>

   <div class="container mt-5">
    <div class="card shadow-sm p-8">
         
        <h3>Trip #{{ $trip->id }} Control Panel</h3>
        <hr>

        <!-- UI for "Picking up Passenger" -->
        <div id="pickup-controls" @if($trip->is_started != false) style="display:none;" @endif>
            <p class="lead">Navigate to pickup: <strong>{{ $trip->origin_name }}</strong></p>
            <button class="btn btn-primary w-100 py-3" onclick="updateTripStatus('started')">
                Passenger Picked Up (Start Trip)
            </button>
        </div>

        <!-- UI for "Heading to Destination" -->
        <div id="dropoff-controls" @if($trip->is_started != true) style="display:none;" @endif>
            <p class="lead">Passenger onboard. Heading to: <strong>{{ $trip->destination_name }}</strong></p>
            <button class="btn btn-success w-100 py-3" onclick="updateTripStatus('completed')">
                Arrived at Destination (Complete Trip)
            </button>
        </div>
    </div>
</div>

<script>
    async function updateTripStatus(status) {
        if(status === 'started'){
        try {
            const response = await fetch(`/api/trip/{{ $trip->id }}/start`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Authorization': "Bearer " + localStorage.getItem('token')
                },
                body: {}
            });

            if (response.ok) {
                location.reload(); 
            }
        } catch (error) {
            console.error('Error updating status:', error);
        }
       }
        if(status === 'completed'){
        try {
            const response = await fetch(`/api/trip/{{ $trip->id }}/end`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Authorization': "Bearer " + localStorage.getItem('token')
                },
                body: {}
            });

            if (response.ok) {
                window.location.href = '/driver/home';
            }
        } catch (error) {
            console.error('Error updating status:', error);
        }
       }
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
