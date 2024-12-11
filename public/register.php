<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>
    <form id="register-form">
        <input type="text" id="name" placeholder="Name" required><br>
        <input type="email" id="email" placeholder="Email" required autocomplete="current-email"><br>
        <input type="password" id="password" placeholder="Password" required autocomplete="current-password"><br>
        <button type="submit">Register</button>
    </form>

    <script>
        document.getElementById('register-form').addEventListener('submit', function (e) {
            e.preventDefault();
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            fetch('../api/registerAPI.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ name: name, email: email, password: password })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.message === 'User registered successfully') {
                    window.location.href = 'login.php';
                }
            });
        });
    </script>
</body>
</html>
