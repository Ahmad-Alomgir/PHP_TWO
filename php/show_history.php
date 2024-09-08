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
        BMIRecords.country,
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
            background-color: #f3f4f6; /* Light gray background for the body */
        }
        h1 {
            font-size: 1.75rem;
            color: #1a202c; /* Darker color for the title */
        }
        table {
            border-collapse: collapse;
        }
        table th, table td {
            padding: 16px;
        }
        table th {
            background-color: #f9fafb; /* Light gray for table header */
            color: #374151; /* Darker gray for header text */
        }
        table tbody tr:nth-child(even) {
            background-color: #f1f5f9; /* Alternate row background color */
        }
        table tbody tr:hover {
            background-color: #e2e8f0; /* Hover effect on table rows */
        }
        .container {
            max-width: 800px; /* Width control for the container */
        }
        .btn-primary {
            background-color: #2563eb;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #1d4ed8;
        }
        .btn-danger {
            background-color: #ef4444;
            transition: background-color 0.3s ease;
        }
        .btn-danger:hover {
            background-color: #dc2626;
        }
    </style>
    <title>User BMI History</title>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6 mt-8 bg-white shadow-lg rounded-lg">
        <h1 class="text-2xl font-bold text-center mb-6">User BMI History</h1>

        <?php if ($bmi_records): ?>
            <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden shadow-md">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="text-left text-sm font-semibold text-gray-600">User Name</th>
                        <th class="text-left text-sm font-semibold text-gray-600">Age</th>
                        <th class="text-left text-sm font-semibold text-gray-600">Gender</th>
                        <th class="text-left text-sm font-semibold text-gray-600">Height (cm)</th>
                        <th class="text-left text-sm font-semibold text-gray-600">Weight (kg)</th>
                        <th class="text-left text-sm font-semibold text-gray-600">BMI</th>
                        <th class="text-left text-sm font-semibold text-gray-600">Country</th>
                        <th class="text-left text-sm font-semibold text-gray-600">Recorded At</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-300">
                    <?php foreach ($bmi_records as $record): ?>
                        <tr class="hover:bg-gray-100 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800"><?php echo htmlspecialchars($record['UserName']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo htmlspecialchars($record['Age']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo htmlspecialchars($record['Gender']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo htmlspecialchars($record['Height']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo htmlspecialchars($record['Weight']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-bold"><?php echo htmlspecialchars(number_format($record['BMI'], 2)); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo htmlspecialchars($record['country']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo htmlspecialchars($record['RecordedAt']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center text-gray-600 mt-4">No BMI records found.</p>
        <?php endif; ?>

        <div class="flex justify-between mt-8">
            <a href="bmi_calculator.php" class="btn-primary text-white font-bold py-2 px-4 rounded shadow-md hover:shadow-lg focus:outline-none focus:shadow-outline">
                Go back to Calculator
            </a>
            <form method="POST" action="logout.php">
                <button type="submit" class="btn-danger text-white font-bold py-2 px-4 rounded shadow-md hover:shadow-lg focus:outline-none focus:shadow-outline">
                    Logout
                </button>
            </form>
        </div>
    </div>
</body>
</html>
