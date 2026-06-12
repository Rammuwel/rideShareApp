<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sharride-login</title>
 

</head>

<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        background: gainsboro;
        margin: 0;
    }

    .container {
        width: 100vw;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;

    }

    .from-container {
        width: 90%;
        max-width: 480px;
        background: white;
        border-radius: 10px;
        padding: 10px;
        box-shadow: 0px 2px 20px rgb(0, 0, 0, 0.2);
    }

    .loginFormContainer {
        display: block;
        width: 100%;
        padding: 20px;

        box-sizing: border-box;

    }

    .login-content {
        text-align: center;
        /* margin: 0; */
        padding-bottom: 5px;
    }

    .login-content h2 {
        color: black;
        margin: 3px;
        font-size: 24px;
    }

    .login-content p {
        color: blue;
        font-style: italic;
        font-weight: bold;
        margin-top: 5px;
        font-size: 14px;
    }

    input,
    button{
        font-size: 18px;
        border: 1px solid gray;
        box-sizing: border-box;
        padding: 10px;
        width: 100%;
    }

   
    input:focus {
        outline: none;
    }

    .input-group-login {
        display: flex;
        flex-direction: column;
        gap: 10px;
       
    }

    .input-group-login input {
        width: 100%;
        border-radius: 5px;
    }

   

    button {
        margin-top: 20px;
        background: lightblue;
        cursor: pointer;
        border: none;
        border-radius: 5px;
        transition: all 0.3s ease-in-out;
        box-shadow: 0px 1px 8px rgb(0, 0, 0, 0.5);

    }
    label{
        display: block;
        font-size: 16px;
        font-weight: bold;
        margin: 4px 0;
    }

    button:hover {
        background: gray;
        color: white;
    }

    .input-group {
        display: flex;
        gap: 2px;

    }

    .input-group input {
        text-align: center;
        border-radius: 5px;

    }




    #error {
        color: red;
        text-align: center;
        padding: 2px;
        border-radius: 5px;
        position: absolute;
        top: 2%;
        margin: auto;
    }
</style>

<body>

    <div class="container">
        <p id="error"></p>

        <div class="from-container" id="form-login">
            <div class="loginFormContainer">
                <div class="login-content">
                    <h2>Wecome, As a Shareride Partner</h2>
                    <p>Fill the driver and vahical details</p>
                </div>
                <form id="loginForm" method="POST">
                    <div class="input-group-login">
                        <div>
                            <label for="name">Driver's Name:</label>
                            <input type="text" name="name" id="name" placeholder="Enter your name here">
                        </div>
                        <div>
                            <label for="license_number">License Number :</label>
                            <input type="text" name="license_number" id="license_number" placeholder="Enter your license number here">
                        </div>
                        <div>
                            <label for="vehicle_model">Vhicle Model:</label>
                            <input type="text" name="vehicle_model" id="vehicle_model" placeholder="Enter your vehicle model here">
                        </div>
                        <div>
                            <label for="vahical_plate">Vhicle Model:</label>
                            <input type="text" name="vahical_plate" id="vahical_plate" placeholder="Enter your vehicle plate number here">
                        </div>
                    </div>
                    <button>Submit Details</button>
                </form>
            </div>
        </div>
    </div>

</body>
<script>
    


    document.getElementById('loginForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        let vahical_plate = (document.getElementById('vahical_plate').value).trim();
        let vehicle_model = (document.getElementById('vehicle_model').value).trim();
        let license_number = (document.getElementById('license_number').value).trim();
        let name = (document.getElementById('name').value).trim();
        let token = localStorage.getItem('token');
      
      
       

        try {
            let response = await fetch("{{ route('post.driver.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Authorization': "Bearer " + token

                },
                body: JSON.stringify({
                     name,
                     license_number,
                     vahical_plate,
                     vehicle_model
                })
            });

            let data = await response.json();
            
            if (response.ok) {
                console.log(data);
            } else {
                document.getElementById('error').innerText = data.message;
                setTimeout(() => {
                    document.getElementById('error').innerText = "";
                }, 3000);
            }

        } catch (err) {
            console.log("error:", "your request is rejected")
        }
    });
</script>

</html>