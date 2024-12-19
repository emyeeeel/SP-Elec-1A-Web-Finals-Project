<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: ../login/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../assets/usjr.ico">
    <style>
        body, html {
            width: 100%;
            height: 100%; 
            margin: 0; 
            font-family: 'Arial', sans-serif; 
            display: flex; 
            flex-direction: column;
        }

        #header-container {
            height: 18%;
            background-color: #162C17;
            color: #E6E8EA;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        #header-container img {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        #left-header, #right-header {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        #left-header div {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }

        #left-header h1 {
            margin: 0;
            font-size: 30px;
            font-weight: bold;
        }

        #left-header p {
            margin: 0;
            font-size: 20px;
            font-weight: normal;
        }

        #logout-button {
            width: 40px;
            height: 40px;
            background-color: white;
            color: #2D582E;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            text-decoration: none;
        }

        #logout-button:hover {
            background-color: #FEC039;
            color: white;
        }

        #logout-button i {
            font-size: 20px;
        }

        #body-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-grow: 1;
            padding: 20px;
            background-color: white;
        }

        #category-container {
            width: 100%;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            gap: 20px;
        }

        #colleges, #departments, #programs, #students {
            width: 100%;
            max-width: 300px;
            height: 300px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            background-color: rgba(13, 62, 33, 0.68);
            overflow: hidden;
        }

        #colleges-img {
            width: 100%;
            height: 100%;
            background: linear-gradient(rgba(13, 62, 33, 0.68), rgba(13, 62, 33, 0.68)), 
                        url('../assets/usjr-main.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            transition: opacity 0.3s ease;
        }

        #departments-img {
            width: 100%;
            height: 100%;
            background: linear-gradient(rgba(13, 62, 33, 0.68), rgba(13, 62, 33, 0.68)), 
                        url('../assets/coli.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            transition: opacity 0.3s ease;
        }

        #programs-img {
            width: 100%;
            height: 100%;
            background: linear-gradient(rgba(13, 62, 33, 0.48), rgba(13, 62, 33, 0.48)), 
                        url('../assets/cover.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            transition: opacity 0.3s ease;
        }

        #students-img {
            width: 100%;
            height: 100%;
            background: linear-gradient(rgba(13, 62, 33, 0.48), rgba(13, 62, 33, 0.48)), 
                        url('../assets/library.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            transition: opacity 0.3s ease;
        }

        #colleges #colleges-text, #departments #departments-text,  #programs #programs-text, #students #students-text {
            width: 100%;
            height: 100%;
            position: absolute;
            display: flex;
            justify-content: center;
            align-items: center;
            color: rgba(255, 255, 255, 0.5);
            font-size: 35px;
            font-weight: 600;
            transition: opacity 0.3s ease;
        }

        #colleges:hover #colleges-img {
            opacity: 0;
        }

        #colleges:hover #colleges-text {
            opacity: .85;
            background-color: #FEC039;
            color: white;
        }

        #departments:hover #departments-img {
            opacity: 0;
        }

        #departments:hover #departments-text {
            opacity: .85;
            background-color: #FEC039;
            color: white;
        }

        #programs:hover #programs-img {
            opacity: 0;
        }

        #programs:hover #programs-text {
            opacity: .85;
            background-color: #FEC039;
            color: white;
        }

        #students:hover #students-img {
            opacity: 0;
        }

        #students:hover #students-text {
            opacity: .85;
            background-color: #FEC039;
            color: white;
        }

        #footer-container {
            padding: 0 20px;
            display: flex;
            justify-content: flex-start;
            align-items: center;
            height: 5%;
            background-color: #162C17;
            color: white;
            text-align: center;
            flex-shrink: 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div id="header-container">
        <div id="left-header"> 
            <img src="../assets/logo-main.svg" alt="USJR Logo Colored"> 
            <div>
                <h1>School Management System</h1>
                <p>Admin Dashboard</p>
            </div>
        </div>
        
        <div id="right-header"> 
            <p><?php echo 'Welcome, ' . $_SESSION['username'] . '!'; ?></p> 
            <a href="../login/login.php" id="logout-button">
                <i class="fas fa-sign-out-alt"></i> 
            </a>
        </div>
    </div>

    <div id="body-container">
        <div id="category-container">
            <div id="colleges">
                <div id="colleges-img"></div>
                <div id="colleges-text">
                    <p>Colleges</p>
                </div>
            </div>
            <div id="departments">
                <div id="departments-img"></div>
                <div id="departments-text">
                    <p>Departments</p>
                </div>
            </div>
            <div id="programs"> 
                <div id="programs-img"></div>
                <div id="programs-text">
                    <p>Programs</p>
                </div>
            </div>
            <div id="students">
                <div id="students-img"></div>
                <div id="students-text">
                    <p>Students</p>
                </div>
            </div>
        </div>
    </div>

    <div id="footer-container">
        <span>&copy; 2024 Catado, Mary Amiel Riva U.</span>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    
    const categories = document.querySelectorAll('#colleges, #departments, #programs, #students');
    
    categories.forEach(category => {
        category.addEventListener('click', function() {
            const categoryText = category.querySelector('div p').innerText;

            axios.get('../usjr-main.php', {
                params: { category: categoryText }
            })
            .then(function(response) {
                console.log(response.data);
                console.log('Session is set with category: ' + response.data.category);
                window.location.href = 'dashboard-table.php';
            })
            .catch(function(error) {
                console.error('There was an error setting the session!', error);
                alert('Error setting session.');
            });
        });
    });
    const logoutButton = document.getElementById('logout-button');

    logoutButton.addEventListener('click', function(e) {
        e.preventDefault();

        axios.get('../logout.php')
            .then(function(response) {
                if (response.data.status === 'success') {
                    console.log(response.data.message);
                    window.location.href = '../login/login.php';
                } else {
                    console.error('Logout failed:', response.data.message);
                    alert('Logout failed. Please try again.');
                }
            })
            .catch(function(error) {
                console.error('There was an error logging out:', error);
                alert('An error occurred while logging out.');
            });
    });
});
    </script>
</body>
</html>
