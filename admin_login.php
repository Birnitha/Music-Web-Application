<?php
session_start();

$host = "localhost"; 
$user = "root";
$password = "";
$db_name = "music1";

$conn = mysqli_connect($host, $user, $password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} 

$error = ""; // Initialize error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM admin_login WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['admin'] = $username;
        header("Location: admin_crud.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin Login - VibeCast</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #1f1f1f, #3a3a3a);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: #2c2c2c;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }

        .login-container h2 {
            margin-bottom: 25px;
            font-size: 28px;
            color: #f06292;
        }

        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0;
            border: none;
            border-radius: 8px;
            background: #444;
            color: #fff;
            font-size: 16px;
            box-sizing: border-box;
        }

        .login-container input[type="submit"] {
            background-color: #f06292;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 15px;
        }

        .login-container input[type="submit"]:hover {
            background-color: #d81b60;
        }

        .error-message {
            color: #ff4d4d;
            margin-top: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <form method="post">
            <input type="text" name="username" required placeholder="Enter username"><br>
            <input type="password" name="password" required placeholder="Enter password"><br>
            <input type="submit" name="submit" value="Login">
        </form>

        <?php if (!empty($error)) { echo "<div class='error-message'>$error</div>"; } ?>
    </div>
</body>
</html>
