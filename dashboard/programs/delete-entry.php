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
            background-color: #f44336;
            color: white;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #e53935;
        }

        button[type="button"] {
            background-color: #9e9e9e;
            color: white;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s ease;
        }

        button[type="button"]:hover {
            background-color: #757575;
        }

        .button-group {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        body {
            overflow: hidden;
        }

        #modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            width: 300px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .modal-content h3 {
            font-size: 18px;
            color: #333;
        }

        .modal-content p {
            font-size: 14px;
            color: #f44336;
            font-weight: bold;
            margin: 15px 0;
        }

        .modal-actions {
            display: flex;
            justify-content: space-between;
        }

        .modal-actions button {
            width: 45%;
        }
    </style>
</head>
<body>

    <div id="body-container">
        <form id="delete-entry-form">
            <label for="program_id">Program ID</label>
            <input type="text" id="program_id" name="program_id" placeholder="Enter Program ID" required disabled>

            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" placeholder="Enter Full Name" required disabled>

            <label for="short_name">Short Name</label>
            <input type="text" id="short_name" name="short_name" placeholder="Enter Short Name" required disabled>

            <label for="college">College</label>
            <select id="college" name="college" required disabled>
                <option value="" disabled selected>Select College</option>
            </select>

            <label for="department">Department</label>
            <select id="department" name="department" required disabled>
                <option value="" disabled selected>Select Department</option>
            </select>

            <div class="button-group">
                <button type="submit">Save Changes</button>
                <button type="button" id="cancel-button">Cancel</button>
            </div>
        </form>
    </div>

    <div id="modal">
        <div class="modal-content">
            <h3>Confirm Changes</h3>
            <p class="modal-warning">This action is irreversible. Are you sure you want to delete this entry?</p>
            <div class="modal-actions">
                <button id="confirm-delete">Yes, Delete</button>
                <button id="cancel-delete">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('delete-entry-form').addEventListener('submit', function(event) {
            event.preventDefault();
            document.getElementById('modal').style.display = 'flex';
        });

        document.getElementById('cancel-delete').addEventListener('click', function() {
            document.getElementById('modal').style.display = 'none';
        });

        document.getElementById('confirm-delete').addEventListener('click', function() {
            const programId = document.getElementById('program_id').value;
            axios.post('delete-program.php', new URLSearchParams({
                'program_id': programId
            }), {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            })
            .then(function(response) {
                const data = response.data;
                if (data.status === 'success') {
                    alert(data.message);
                    window.location.href = '../dashboard-table.php';
                } else {
                    alert(data.message);
                }
            })
            .catch(function(error) {
                console.error('Error deleting entry:', error);
                alert('Error deleting the entry.');
            });
            document.getElementById('modal').style.display = 'none';
        });

        document.getElementById('cancel-button').addEventListener('click', function() {
            window.location.href = '../dashboard-table.php';
        });
    </script>

    <script src="process-edit.js"></script>
</body>
</html>
