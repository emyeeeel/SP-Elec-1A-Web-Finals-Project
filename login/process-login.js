document.getElementById('login-form').addEventListener('submit', function (e) {
    e.preventDefault(); 
    
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    const data = {
        username: username,
        pass: password
    };

    axios.post('verify-login.php', JSON.stringify(data), {
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (response.data.success) {
            alert('Login successful!');
            document.getElementById('login-form').reset();
            window.location.href = "../dashboard/dashboard.php";  
        } else {
            alert(response.data.message);
        }
    })
    .catch(error => {
        alert('An error occurred!');
        console.error(error);
    });
});
