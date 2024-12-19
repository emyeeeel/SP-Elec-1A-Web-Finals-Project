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
        <form id="edit-entry-form">
            <label for="student_id">Student ID</label>
            <input type="text" id="student_id" name="student_id" placeholder="Enter Student ID" required>

            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" placeholder="Enter First Name" required>

            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" placeholder="Enter Last Name" required>

            <label for="middle_name">Middle Name</label>
            <input type="text" id="middle_name" name="middle_name" placeholder="Enter Middle Name" required>

            <label for="college">College</label>
            <select id="college" name="college" required>
                <option value="" disabled selected>Select College</option>
            </select>

            <label for="program">Program</label>
            <select id="program" name="program" required>
                <option value="" disabled selected>Select Program</option>
            </select>

            <label for="year">Year</label>
            <input type="number" id="year" name="year" placeholder="Enter Year" required>

            <div class="button-group">
                <button type="submit">Edit Entry</button>
                <button type="button" id="cancel-button">Cancel</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('edit-entry-form').addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(this);
            const dataObject = {};
            formData.forEach((value, key) => {
                dataObject[key] = value;
            });
            const objData = JSON.parse(sessionStorage.getItem('objData'));

            if (objData && objData.studid) {
                dataObject['originalID'] = objData.studid;
            } else {
                console.error('Student data not found or studid is missing');
            }

            const collegeSelect = document.getElementById('college');
            const selectedCollege = collegeSelect.options[collegeSelect.selectedIndex].textContent;
            dataObject['college'] = selectedCollege;

            const programSelect = document.getElementById('program');
            const selectedProgram = programSelect.options[programSelect.selectedIndex].textContent;
            dataObject['program'] = selectedProgram;
            
            console.log(dataObject);
            axios.post('update-entry.php', dataObject)
                .then(function(response) {
                    console.log(response.data)
                    alert('Entry updated successfully!');
                    window.location.href = '../dashboard-table.php';
                })
                .catch(function(error) {
                    console.error('Error updating entry:', error);
                    alert('Error updating the entry.');
                });
        });

        document.getElementById('cancel-button').addEventListener('click', function() {
            window.location.href = '../dashboard-table.php';
        });
    </script>
    <script src="process-edit.js"></script>
</body>
</html>
