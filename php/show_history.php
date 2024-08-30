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

// SQL query to fetch all users and their BMI records
$sql = "
    SELECT 
        BMIUsers.Name AS UserName,
        BMIUsers.Age,
        BMIUsers.Gender,
        BMIRecords.Height,
        BMIRecords.Weight,
        BMIRecords.BMI,
        BMIRecords.RecordedAt
    FROM BMIUsers
    LEFT JOIN BMIRecords ON BMIUsers.BMIUserID = BMIRecords.BMIUserID
    ORDER BY BMIRecords.RecordedAt DESC
";

$result = $conn->query($sql);
$bmi_records = $result->fetch_all(MYSQLI_ASSOC);
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
    <title>Show History</title>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-5 max-w-4xl bg-white shadow-md rounded-lg">
        <h1 class="text-xl font-bold text-center mb-4">User BMI History</h1>

        <?php if ($bmi_records): ?>
            <table class="min-w-full divide-y divide-gray-200 mb-6">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Age</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Height</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Weight</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">BMI</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recorded At</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($bmi_records as $record): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($record['UserName']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($record['Age']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($record['Gender']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($record['Height']); ?> cm</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($record['Weight']); ?> kg</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars(number_format($record['BMI'], 2)); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($record['RecordedAt']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center text-gray-500 mt-4">No BMI records found.</p>
        <?php endif; ?>

        <div class="flex justify-between mt-4">
            <a href="bmi_calculator.php" class="text-blue-500 hover:underline">Go back to Calculator</a>
            <form method="POST" action="logout.php">
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold px-4 py-2 rounded focus:outline-none focus:shadow-outline">
                    Logout
                </button>
            </form>
        </div>
    </div>
</body>
</html>
