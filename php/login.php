<?php
session_start();
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT AppUserID, Password FROM AppUsers WHERE Username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            header("Location: ./bmi_calculator.php");
            exit();
        } else {
            header("Location: login.php?error=invalid_password");
            exit();
        }
    } else {
        header("Location: login.php?error=no_user");
        exit();
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
    <title>Login</title>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            const error = urlParams.get('error');

            if (error === 'no_user') {
                alert('No user found.');
            } else if (error === 'invalid_password') {
                alert('Invalid password.');
            }
        });
    </script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">Login</h1>

        <form method="POST" class="space-y-4">
            <input type="text" name="username" placeholder="Username" class="border border-gray-300 p-2 w-full rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            <input type="password" name="password" placeholder="Password" class="border border-gray-300 p-2 w-full rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            <div class="flex justify-center">
                <button type="submit" class="bg-red-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg w-full transition-colors duration-300">
                    Login
                </button>
            </div>
            <p class="text-center mt-4">Don't have an account? <a href="./register.php" class="text-blue-500 hover:underline">Sign Up</a></p>
        </form>
    </div>
</body>
</html>
