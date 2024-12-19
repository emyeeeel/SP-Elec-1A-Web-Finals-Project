<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $_SESSION['category'] . ' - Edit Entry'; ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="icon" type="image/x-icon" href="../../assets/usjr.ico">
    <style>
        body, html {
            width: 100%;
            height: 100%;
            margin: 0;
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f4f4f9;
        }

        #body-container {
            padding: 40px;
            background-color: white;
            border: 2px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-size: 14px;
            font-weight: bold;
        }

        input, select, button {
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="text"], input[type="number"], select {
            background-color: #f9f9f9;
        }

        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        button[type="button"] {
            background-color: #f44336;
            color: white;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s ease;
        }

        button[type="button"]:hover {
            background-color: #e53935;
        }

        .button-group {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        body {
            overflow: hidden;
        }
    </style>
</head>
<body>

    <div id="body-container">
        <form id="edit-college-form">
            <label for="college_id">College ID</label>
            <input type="text" id="college_id" name="college_id" placeholder="Enter College ID" required>

            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" placeholder="Enter Full Name" required>

            <label for="short_name">Short Name</label>
            <input type="text" id="short_name" name="short_name" placeholder="Enter Short Name">

            <div class="button-group">
                <button type="submit">Edit College</button>
                <button type="button" id="cancel-button">Cancel</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('edit-college-form').addEventListener('submit', function(event) {
            event.preventDefault();
            const objData = JSON.parse(sessionStorage.getItem('objData'));
            const dataObject = {
                college_id: document.getElementById('college_id').value,
                full_name: document.getElementById('full_name').value,
                short_name: document.getElementById('short_name').value
            };
            dataObject.originalID = objData.collid;
            console.log(dataObject);
            axios.post('update-college.php', dataObject, {
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(function(response) {
                alert('College updated successfully!');
                window.location.href = '../dashboard-table.php';
            })
            .catch(function(error) {
                console.error('Error editing college:', error);
                alert('Error updating the college.');
            });
        });

        document.getElementById('cancel-button').addEventListener('click', function() {
            window.location.href = '../dashboard-table.php';
        });
    </script>
    <script src="process.js"></script>
</body>
</html>
