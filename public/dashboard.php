

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h2>Welcome to your Dashboard</h2>
    <div id="user-info"></div>

    <script>
        const token = localStorage.getItem('token');

        fetch('/api/dashboardAPI.php', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`,
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.user) {
                document.getElementById('user-info').innerHTML = `Welcome, ${data.user.name}`;
            } else {
                alert(data.message);
                window.location.href = 'login.php';
            }
        });
    </script>
</body>
</html>



