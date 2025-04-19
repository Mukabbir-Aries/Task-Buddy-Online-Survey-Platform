<?php
$host = 'localhost';
$dbname = 'task_buddy_db'; // Replace with your actual database name
$username = 'root'; // Default XAMPP username
$password = ''; // Default XAMPP password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}

// Fetch surveys from the database
$query = "SELECT * FROM surveys";
$stmt = $pdo->prepare($query);
$stmt->execute();
$surveys = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey - Task Buddy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .sidebar-link {
            transition: all 0.3s ease;
        }
        .sidebar-link:hover {
            transform: translateX(5px);
        }
        .card {
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="bg-blue-800 text-white w-64 p-6 shadow-lg">
            <div class="logo text-2xl font-bold mb-8">
                <span class="text-yellow-400">Task</span>Buddy
            </div>
            <nav>
                <ul class="space-y-3">
                    <li>
                        <a href="../dashboard/dashboard.php" class="sidebar-link flex items-center gap-3 p-3 rounded-lg bg-blue-700">
                            <i class="fas fa-home"></i>
                            <span class="font-medium">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="../surveys/survey.php" class="sidebar-link flex items-center gap-3 p-3 rounded-lg hover:bg-blue-700">
                            <i class="fas fa-poll"></i>
                            <span class="font-medium">Start Survey</span>
                        </a>
                    </li>
                    <li>
                        <a href="../withdrawals/withdraw.php" class="sidebar-link flex items-center gap-3 p-3 rounded-lg hover:bg-blue-700">
                            <i class="fas fa-money-bill-wave"></i>
                            <span class="font-medium">Withdraw Money</span>
                        </a>
                    </li>
                    <li>
                        <a href="../withdrawals/withdraw_history.php" class="sidebar-link flex items-center gap-3 p-3 rounded-lg hover:bg-blue-700">
                            <i class="fas fa-history"></i>
                            <span class="font-medium">Withdraw History</span>
                        </a>
                    </li>
                    <li>
                        <a href="../tickets/open_ticket.php" class="sidebar-link flex items-center gap-3 p-3 rounded-lg hover:bg-blue-700">
                            <i class="fas fa-ticket-alt"></i>
                            <span class="font-medium">Open Ticket</span>
                        </a>
                    </li>
                    <li>
                        <a href="../tickets/ticket_history.php" class="sidebar-link flex items-center gap-3 p-3 rounded-lg hover:bg-blue-700">
                            <i class="fas fa-list"></i>
                            <span class="font-medium">Ticket History</span>
                        </a>
                    </li>
                    <li>
                        <a href="../main.html/auth/change_password.php" class="sidebar-link flex items-center gap-3 p-3 rounded-lg hover:bg-blue-700">
                            <i class="fas fa-key"></i>
                            <span class="font-medium">Change Password</span>
                        </a>
                    </li>
                    <li>
                        <a href="../main.html/auth/logout.php" class="sidebar-link flex items-center gap-3 p-3 rounded-lg hover:bg-red-600">
                            <i class="fas fa-sign-out-alt"></i>
                            <span class="font-medium">Logout</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <main class="flex-1 p-6 overflow-y-auto">
            <h1 class="text-2xl font-semibold text-gray-800">Available Tasks</h1>
            <section class="mt-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($surveys as $survey): ?>
                        <div class="bg-white rounded-lg shadow-md p-6 flex flex-col justify-between">
                            <h2 class="font-semibold"><?php echo htmlspecialchars($survey['title']); ?></h2>
                            <p><?php echo htmlspecialchars($survey['description']); ?></p>
                            <p class="text-green-500">Reward: $<?php echo number_format($survey['reward'], 2); ?></p>
<button class="bg-yellow-500 text-blue-700 py-2 px-4 rounded-md mt-4" onclick="window.location.href='participate_survey.php?id=<?php echo $survey['id']; ?>'">Start Task</button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </main>
    </div>

    <div id="taskModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg p-6 w-1/3">
            <h2 class="text-xl font-semibold mb-4">Start Task</h2>
            <p>Are you sure you want to start this task?</p>
            <div class="flex justify-end mt-4">
                <button class="bg-blue-500 text-white px-4 py-2 rounded-md" onclick="startTask()">Yes</button>
                <button class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md ml-2" onclick="closeModal()">No</button>
            </div>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('taskModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('taskModal').classList.add('hidden');
        }

        function startTask() {
            // Logic to start the task goes here
            closeModal();
        }
    </script>
</body>
</html>
