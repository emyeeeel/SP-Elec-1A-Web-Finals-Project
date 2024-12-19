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
        <form id="edit-program-form">
            <label for="program_id">Program ID</label>
            <input type="text" id="program_id" name="program_id" placeholder="Enter Program ID" required>

            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" placeholder="Enter Full Name" required>

            <label for="short_name">Short Name</label>
            <input type="text" id="short_name" name="short_name" placeholder="Enter Short Name">

            <label for="college">College</label>
            <select id="college" name="college" required>
                <option value="" disabled selected>Select College</option>
            </select>

            <label for="department">Department</label>
            <select id="department" name="department" required>
                <option value="" disabled selected>Select Department</option>
            </select>

            <div class="button-group">
                <button type="submit">Edit Program</button>
                <button type="button" id="cancel-button">Cancel</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('edit-program-form').addEventListener('submit', function(event) {
            event.preventDefault();
            
            const formData = new FormData(this);
            const dataObject = {};
            formData.forEach((value, key) => {
                dataObject[key] = value;
            });
            console.log(dataObject);

            const objData = JSON.parse(sessionStorage.getItem('objData'));

            if (objData && objData.progid) {
                dataObject['originalID'] = objData.progid;
            } else {
                console.error('Program data not found or progid is missing');
            }

            const collegeSelect = document.getElementById('college');
            const selectedCollege = collegeSelect.options[collegeSelect.selectedIndex].textContent;
            dataObject['college'] = selectedCollege;

            const departmentSelect = document.getElementById('department');
            const selectedProgram = departmentSelect.options[departmentSelect.selectedIndex].textContent;
            dataObject['department'] = selectedProgram;  

            console.log(dataObject);

            axios.post('update-program.php', dataObject)
                .then(function(response) {
                    console.log(response.data)
                    alert('Program updated successfully!');
                    window.location.href = '../dashboard-table.php';
                })
                .catch(function(error) {
                    console.error('Error submitting program:', error);
                    alert('Error updating the program.');
                });
        });

        document.getElementById('cancel-button').addEventListener('click', function() {
            window.location.href = '../dashboard-table.php';
        });
    </script>
    <script src="process-edit.js"></script>
</body>
</html>
