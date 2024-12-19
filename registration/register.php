<?php
session_start();
require_once('../db_connection.php');

if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
    header("Location: ../dashboard/dashboard.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="icon" type="image/x-icon" href="../assets/usjr.ico">
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body, html {
            width: 100%;
            height: 100%;
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f7f7f7;
        }

        #main-container {
            width: 28%;
            height: 85%;
            border: 5px solid #162C17;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        #main-container img {
            width: 200px;
            height: 200px;
            object-fit: contain;
            margin-bottom: 20px;
        }

        #form-container {
            width: 100%;
            padding: 10px;
        }

        form {
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        .form-label {
            margin-bottom: 6px;
            font-weight: 600;
            color: #162C17;
        }

        input[type="text"], input[type="password"] {
            padding: 0 12px;
            width: 100%;
            height: 38px;
            border: 1px solid #ccc;
            font-size: 0.875rem;
            line-height: 22px;
            margin-top: 5px;
            margin-bottom: 10px;
            border-radius: 3px;
        }

        #buttons-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            width: 100%;
            margin-top: 20px;
        }

        button {
            padding: 10px 0;
            background: transparent;
            color: #162C17;
            border: 1px solid #162C17;
            font-size: 1rem;
            line-height: 22px;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            width: 40%;
            border-radius: 3px;
            transition: background-color 0.3s, color 0.3s;
        }

        button:hover {
            background: #162C17;
            color: white;
        }

        #form-verify-password {
            width: 100%;
            margin-bottom: 15px;
        }

        #form-verify-password .form-label {
            margin-bottom: 6px;
        }

        #form-verify-password input {
            padding: 0 12px;
            width: 100%;
            height: 38px;
            border: 1px solid #ccc;
            font-size: 0.875rem;
            line-height: 22px;
            margin-top: 5px;
            margin-bottom: 10px;
            border-radius: 3px;
        }

        .login-link {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .login-link p {
            font-size: 0.875rem;
            color: #162C17;
        }

        .login-link a {
            color: #162C17;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s;
        }

        .login-link a:hover {
            color: #0d1e0d;
        }

    </style>
</head>
<body>
    <div id="main-container">
        <img src="../assets/logo-green.png" alt="USJR Logo Green"> 
        <div id="form-container">
            <form id="registration-form" method="POST" action="">
                <div id="form-username">
                    <label class="form-label username" for="username">Username</label>
                    <input type="text" name="username" id="username" required>
                </div>
                <div id="form-password">
                    <label class="form-label password" for="password">Password</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div id="form-verify-password">
                    <label class="form-label verify-password" for="verify-password">Verify Password</label>
                    <input type="password" name="verify-password" id="verify-password" required>
                </div>
                <div id="buttons-container">
                    <button type="submit">Login</button>
                    <button type="reset">Clear</button>
                </div>
                <div class="login-link">
                    <p>Already have an account? <a href="../login/login.php">Login here</a></p>
                </div>
            </form>
        </div>
    </div>
    <script src="process-registration.js"></script>
</body> 
</html>
