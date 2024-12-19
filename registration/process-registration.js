document.getElementById('registration-form').addEventListener('submit', function (e) {
    e.preventDefault();
    
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const verifyPassword = document.getElementById('verify-password').value;

    if (password !== verifyPassword) {
        alert("Passwords do not match!");
        return;
    }

    const data = {
        username: username,
        pass: password
    };

    console.log(data);

    axios.post('process-register.php', JSON.stringify(data), {
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (response.data.success) {
            alert('Registration successful!');
            document.getElementById('registration-form').reset();
        } else {
            alert(response.data.message);
        }
    })
    .catch(error => {
        alert('An error occurred!');
        console.error(error);
    });
});
