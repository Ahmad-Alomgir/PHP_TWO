<?php
include('config.php');
session_start(); // Ensure session is started

if (!isset($_SESSION['user_id'])) {
    echo "Please <a href='./login.php'>login</a> to access the BMI calculator.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $age = intval($_POST['age']); // Convert to integer
    $gender = $_POST['gender'];
    $height = floatval($_POST['height']); // Convert to float
    $weight = floatval($_POST['weight']); // Convert to float

    // Validate age
    if ($age <= 0) {
        die("Age must be a positive number.");
    }

    $bmi = $weight / (($height / 100) ** 2);

    // Insert into BMIUsers table
    $stmt = $conn->prepare("INSERT INTO BMIUsers (Name, Age, Gender) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $name, $age, $gender);
    $stmt->execute();
    $bmi_user_id = $stmt->insert_id;
    $stmt->close();

    // Insert into BMIRecords table
    $stmt = $conn->prepare("INSERT INTO BMIRecords (BMIUserID, Height, Weight, BMI) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iddd", $bmi_user_id, $height, $weight, $bmi);
    $stmt->execute();
    $stmt->close();

    // Redirect to the results page
    header("Location: ./bmi_results.php?id=" . $bmi_user_id);
    exit();
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
    <title>BMI Calculator</title>
</head>
<body class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white shadow-md rounded-lg p-8 w-full max-w-md">
        <h1 class="text-xl text-center font-bold mb-4">BMI Calculator</h1>
        <form method="POST" action="bmi_calculator.php">
            <input type="text" name="name" placeholder="Name" class="border p-2 mb-2 w-full rounded" required>
            <input type="number" name="age" placeholder="Age" class="border p-2 mb-2 w-full rounded" required min="1" step="1">
            <select name="gender" class="border rounded p-2 mb-2 w-full" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
            <input type="number" step="any" name="height" placeholder="Height in cm" class="border rounded p-2 mb-2 w-full" required min="1">
            <input type="number" step="any" name="weight" placeholder="Weight in kg" class="border rounded p-2 mb-2 w-full" required min="1">
            <div class="flex justify-center mt-4">
                <button type="submit" class="flex-shrink-0 bg-teal-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded">
                    Calculate BMI
                </button>
            </div>
        </form>

        <div class="flex justify-between mt-6">
            <a href="show_history.php" class="text-blue-500 hover:underline">Show History</a>
            <form method="POST" action="logout.php">
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold px-4 py-2 rounded focus:outline-none focus:shadow-outline">
                    Logout
                </button>
            </form>
        </div>
    </div>
</body>
</html>
