<?php
session_start();
require_once('../db_connection.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $_SESSION['category'] . ' Dashboard'; ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
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
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #left-header h1 {
            margin: 0;
            font-size: 30px;
            font-weight: bold;
        }

        a {
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

        a:hover {
            background-color: #FEC039;
            color: white;
        }

        a i {
            font-size: 20px;
        }

        #body-container {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            flex-grow: 1;
            padding: 10px;
            background-color: white;
        }

        #button-container {
            align-self: flex-start;
        }

        #button-container button {
            padding: 10px 15px;
            font-size: 14px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            margin: 0;
        }

        #table-container {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #162C17;
            color: white;
            font-size: 16px;
        }

        td {
            font-size: 14px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        button {
            padding: 6px 12px;
            margin: 0 5px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
            display: inline-block;
        }

        .edit-button {
            background-color: #4CAF50;
            color: white;
        }

        .delete-button {
            background-color: #f44336;
            color: white;
        }

        button:hover {
            opacity: 0.8;
        }

        td.actions {
            display: flex;
            gap: 10px;
            justify-content: center;
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
                <h1><?php echo $_SESSION['category'] . ' Master List'; ?></h1>
            </div>
        </div>
        
        <div id="right-header"> 
            <p><?php echo 'Welcome, ' . $_SESSION['username'] . '!'; ?></p> 
            <a href="dashboard.php" id="home-button">
                <i class="fas fa-home"></i> 
            </a>
            <a href="../login/login.php" id="logout-button">
                <i class="fas fa-sign-out-alt"></i> 
            </a>
        </div>
    </div>

    <div id="body-container">
        <div id="button-container">
            <button id="add-entry-btn"><?php echo 'Add ' . substr($_SESSION['category'], 0, -1) . ' Entry'; ?></button>
        </div>

        <div id="table-container"></div>
    </div>

    <div id="footer-container">
        <span>&copy; 2024 Catado, Mary Amiel Riva U.</span>
    </div>

    <script>
const titleheaders = {
    colleges: ["College ID", "Full Name", "Short Name"],
    departments: ["Department ID", "Full Name", "Short Name", "College"],
    programs: ["Program ID", "Full Name", "Short Name", "College", "Department"],
    students: ["Student ID", "First Name", "Last Name", "Middle Name", "Year", "College", "Program"]
};

axios.get('fetch_data.php')
    .then(function (response) {
        const data = response.data;
        console.log(data);

        const tableContainer = document.querySelector('#table-container');
        
        if (Array.isArray(data) && data.length > 0) {
            const table = document.createElement('table');
            table.style.width = '100%';

            const thead = document.createElement('thead');
            const headerRow = document.createElement('tr');
            const headers = Object.keys(data[0]);

            headers.forEach(header => {
                const th = document.createElement('th');
                th.textContent = header.charAt(0).toUpperCase() + header.slice(1);
                headerRow.appendChild(th);
            });

            const th = document.createElement('th');
            th.textContent = 'Actions';
            headerRow.appendChild(th);

            thead.appendChild(headerRow);
            table.appendChild(thead);

            const tbody = document.createElement('tbody');

            data.forEach(row => {
                const tr = document.createElement('tr');
                headers.forEach(header => {
                    const td = document.createElement('td');
                    td.textContent = row[header];
                    tr.appendChild(td);
                });

                const actionsTd = document.createElement('td');
                actionsTd.classList.add('actions');

                const editButton = document.createElement('button');
                editButton.textContent = 'Edit';
                editButton.classList.add('edit-button');
                editButton.onclick = function () {
                    let category = '<?php echo $_SESSION["category"]; ?>';
                    let url = category + '/edit-entry.php';

                    sessionStorage.setItem('objData', JSON.stringify(row));
                    window.location.href = url.toLowerCase();
                };
                actionsTd.appendChild(editButton);

                const deleteButton = document.createElement('button');
                deleteButton.textContent = 'Delete';
                deleteButton.classList.add('delete-button');
                deleteButton.onclick = function () {
                    let category = '<?php echo $_SESSION["category"]; ?>';
                    let url = category + '/delete-entry.php';

                    sessionStorage.setItem('objData', JSON.stringify(row));
                    window.location.href = url.toLowerCase();
                };
                actionsTd.appendChild(deleteButton);

                tr.appendChild(actionsTd);
                tbody.appendChild(tr);
            });

            table.appendChild(tbody);
            tableContainer.appendChild(table);

            const category = '<?php echo $_SESSION["category"]; ?>';
            const categoryHeaders = titleheaders[category.toLowerCase()];

            if (categoryHeaders) {
                const thElements = table.querySelectorAll('thead th');
                categoryHeaders.forEach((header, index) => {
                    if (thElements[index]) {
                        thElements[index].textContent = header;
                    }
                });
            }

        } else {
            tableContainer.innerHTML = '<p>No data available.</p>';
        }
    })
    .catch(function (error) {
        console.error('Error fetching data:', error);
    });

document.getElementById('add-entry-btn').addEventListener('click', function() {
    let category = '<?php echo $_SESSION["category"]; ?>';
    let url = category + '/add-entry.php';
    window.location.href = url.toLowerCase();
});
    </script>
</body>
</html>
