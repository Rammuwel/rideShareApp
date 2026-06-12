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
        max-width: 380px;
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
    button,
    select {
        font-size: 18px;
        border: 1px solid gray;
        box-sizing: border-box;
        padding: 10px;
        width: 100%;
    }

    select:focus,
    input:focus {
        outline: none;
    }

    .input-group-login {
        display: flex;
        align-items: center;
    }

    .input-group-login input {
        width: 70%;

        border-top-right-radius: 5px;
        border-bottom-right-radius: 5px;
    }

    .input-group-login select {
        width: 30%;
        font-size: 17px;
        border-top-left-radius: 5px;
        border-bottom-left-radius: 5px;
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

    #form-login {
        display: block;
    }

    #verify-form {
        display: none;
    }

    #error {
        color: red;
        text-align: center;
        padding: 2px;
        border-radius: 5px;
        position: absolute;
        top: 20%;
        margin: auto;
    }
</style>

<body>

    <div class="container">
        <p id="error"></p>

        <div class="from-container" id="form-login">
            <div class="loginFormContainer">
                <div class="login-content">
                    <h2>Welcom to, RideShareApp</h2>
                    <p>Enter mobile number for login...</p>
                </div>
                <form id="loginForm" method="POST">
                    <div class="input-group-login">
                        <select id="country">
                            <option value="+91" selected>(IN)+91</option>
                            <option value="+1">🇺🇸 +1</option>
                            <option value="+44">🇬🇧 +44</option>
                            <option value="+971">🇦🇪 +971</option>
                            <option value="+61">🇦🇺 +61</option>
                        </select>
                        <input type="text" id="number" maxlength="10" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                    </div>
                    <button>Login</button>
                </form>
            </div>
        </div>
        <div class="from-container" id="verify-form">
            <div class="loginFormContainer">
                <div class="login-content">
                    <h2>Verify for login</h2>
                    <p id="opt-msm"></p>
                </div>
                <form method="POST" id="verifyForm">
                    <div class="input-group input-otp">
                        <input type="text" maxlength="1" inputmode="numeric" id="one-time-code">
                        <input type="text" maxlength="1" inputmode="numeric">
                        <input type="text" maxlength="1" inputmode="numeric">
                        <input type="text" maxlength="1" inputmode="numeric">
                        <input type="text" maxlength="1" inputmode="numeric">
                        <input type="text" maxlength="1" inputmode="numeric">
                    </div>
                    <input type="hidden" name="mobile" value="" id="mobile">
                    <button type="submit">Veryfy OTP</button>
                </form>
            </div>
        </div>
    </div>

</body>
<script>
    const form_login = document.getElementById('form-login');
    const verify_form = document.getElementById('verify-form');

    document.getElementById('loginForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        let country = (document.getElementById('country').value).trim();
        let number = (document.getElementById('number').value).replace(/\s+/g, '');

        // let mobile = country + number


        try {
            let response = await fetch("{{ route('post.login') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    country,
                    number
                })
            });

            let data = await response.json();



            if (response.ok) {

                form_login.style.display = 'none';
                verify_form.style.display = 'block';
                document.getElementById('mobile').value = data.mobile;

                document.getElementById('opt-msm').innerText = data.message;
                setTimeout(() => {
                    document.getElementById('one-time-code').focus();
                }, 2000)

            } else {
                document.getElementById('error').innerText = data.errors.number;
                setTimeout(() => {
                    document.getElementById('error').innerText = "";
                }, 3000);
            }

        } catch (err) {
            console.log("error:", "your request is rejected")
        }
    });


    //otp
    let inputotps = document.querySelectorAll('.input-otp input');
    inputotps.forEach(element => {
        element.addEventListener('input', function(e) {
            element.value = element.value.replace(/[^0-9]/g, '');
            let next = element.nextElementSibling;
            if (element.value && next) {
                next.focus();
            }
        });

        element.addEventListener('keydown', function(e) {
            let prev = element.previousElementSibling;

            if (e.key === "Backspace") {
                if (!element.value && prev) {
                    prev.focus();
                }
            }
        });
    });


    document.getElementById('verifyForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const otp = Array.from(inputotps).map((element) => (element.value).replace(/\s+/g, '')).join('');
        const mobile = (document.getElementById('mobile').value).trim();

        //  Get the Sanctum CSRF Cookie
        //await fetch("/sanctum/csrf-cookie");

        try {



            let response = await fetch("{{route('post.login.verify')}}", {
                method: 'POST',
                headers: {
                    "content-type": "application/json",
                    'accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    mobile: mobile,
                    'login_code': otp
                })
            });


            let data = await response.json();

            if (response.ok) {
                localStorage.setItem('token', data.token);
                document.location.href = "{{ route('home') }}"
            } else {
                document.getElementById('error').innerText = data.errors.number;
                setTimeout(() => {
                    document.getElementById('error').innerText = "";
                }, 3000);
            }

        } catch (error) {
            console.error("error:", error.message);
        }


    });
</script>

</html>