<?php
// Start the session
session_start();

// Ensure that the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Please <a href='./login.php'>login</a> to access this page.";
    exit();
}

// Include database configuration
include('config.php');

// Fetch the user ID from the URL
$bmi_user_id = intval($_GET['id']);

// SQL query to fetch BMI records and user details
$sql = "
    SELECT BMIRecords.*, BMIUsers.Name 
    FROM BMIRecords
    INNER JOIN BMIUsers ON BMIRecords.BMIUserID = BMIUsers.BMIUserID
    WHERE BMIRecords.BMIUserID = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $bmi_user_id);
$stmt->execute();
$result = $stmt->get_result();
$bmi_record = $result->fetch_assoc();

$stmt->close();
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
    <title>BMI Results</title>
</head>
<body>
<div class="container mx-auto p-5">
    <h1 class="text-xl text-center font-bold mb-4">BMI Results</h1>
    <?php if ($bmi_record): ?>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Height</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Weight</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">BMI</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recorded At</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($bmi_record['Name']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($bmi_record['Height']); ?> cm</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($bmi_record['Weight']); ?> kg</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($bmi_record['BMI']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($bmi_record['RecordedAt']); ?></td>
                </tr>
            </tbody>
        </table>
    <?php else: ?>
        <p>No BMI record found.</p>
    <?php endif; ?>

    <div class="flex justify-between mt-4">
        <a href="bmi_calculator.php" class="text-blue-500 hover:underline">Go back to Calculator</a>
        <form method="POST" action="./logout.php">
            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold px-4 py-2 rounded focus:outline-none focus:shadow-outline">
                Logout
            </button>
        </form>
    </div>
</div>
</body>
</html>
