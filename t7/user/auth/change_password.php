<?php
session_start(); // Start the session
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to view this page.");
}

// Database connection using PDO
$servername = "localhost"; // Change if necessary
$username = "root"; // Change if necessary
$password = ""; // Change if necessary
$dbname = "task_buddy_db"; // Updated database name

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Handle form submission for changing password
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Check if new passwords match
    if ($newPassword !== $confirmPassword) {
        $_SESSION['error'] = "New passwords do not match!";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Fetch the current user's password from the database
    $userId = $_SESSION['user_id'];
    $sql = "SELECT password FROM users WHERE id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify the current password
    if (password_verify($currentPassword, $user['password'])) {
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the password in the database
        $sqlUpdate = "UPDATE users SET password = :password WHERE id = :user_id";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bindParam(':password', $hashedPassword);
        $stmtUpdate->bindParam(':user_id', $userId);

        if ($stmtUpdate->execute()) {
            $_SESSION['success'] = "Password changed successfully!";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $_SESSION['error'] = "Error changing password: " . $stmtUpdate->errorInfo()[2];
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    } else {
        $_SESSION['error'] = "Current password is incorrect!";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard - Task Buddy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
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
        .alert {
            margin-bottom: 1rem;
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            display: flex;
            align-items: center;
            font-weight: 600;
        }
        .alert-success {
            color: #065f46;
            background-color: #d1fae5;
            border-left: 4px solid #10b981;
        }
        .alert-error {
            color: #b91c1c;
            background-color: #fee2e2;
            border-left: 4px solid #b91c1c;
        }
        .alert i {
            margin-right: 0.5rem;
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
                        <a href="../../dashboard/dashboard.php" class="sidebar-link flex items-center gap-3 p-3 rounded-lg bg-blue-700">
                            <i class="fas fa-home"></i>
                            <span class="font-medium">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="../../surveys/survey.php" class="sidebar-link flex items-center gap-3 p-3 rounded-lg hover:bg-blue-700">
                            <i class="fas fa-poll"></i>
                            <span class="font-medium">Start Survey</span>
                        </a>
                    </li>
                    <li>
                        <a href="../../withdrawals/withdraw.php" class="sidebar-link flex items-center gap-3 p-3 rounded-lg hover:bg-blue-700">
                            <i class="fas fa-money-bill-wave"></i>
                            <span class="font-medium">Withdraw Money</span>
                        </a>
                    </li>
                    <li>
                        <a href="../../withdrawals/withdraw_history.php" class="sidebar-link flex items-center gap-3 p-3 rounded-lg hover:bg-blue-700">
                            <i class="fas fa-history"></i>
                            <span class="font-medium">Withdraw History</span>
                        </a>
                    </li>
                    <li>
                        <a href="../../tickets/open_ticket.php" class="sidebar-link flex items-center gap-3 p-3 rounded-lg hover:bg-blue-700">
                            <i class="fas fa-ticket-alt"></i>
                            <span class="font-medium">Open Ticket</span>
                        </a>
                    </li>
                    <li>
                        <a href="../../tickets/ticket_history.php" class="sidebar-link flex items-center gap-3 p-3 rounded-lg hover:bg-blue-700">
                            <i class="fas fa-list"></i>
                            <span class="font-medium">Ticket History</span>
                        </a>
                    </li>
                    <li>
                        <a href="../change_password.php" class="sidebar-link flex items-center gap-3 p-3 rounded-lg bg-blue-700">
                            <i class="fas fa-key"></i>
                            <span class="font-medium">Change Password</span>
                        </a>
                    </li>
                    <li>
                        <a href="../auth/logout.php" class="sidebar-link flex items-center gap-3 p-3 rounded-lg hover:bg-red-600">
                            <i class="fas fa-sign-out-alt"></i>
                            <span class="font-medium">Logout</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <main class="flex-1 p-6">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-2xl font-semibold text-gray-800">Change Password</h1>
                <div class="flex items-center gap-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bell"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path></svg>
                </div>
            </div>

            <div class="bg-white rounded-md shadow-md p-6 w-full max-w-md mx-auto">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success" id="messageBox">
                        <i class="fas fa-check-circle"></i>
                        <span><?= $_SESSION['success'] ?></span>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-error" id="messageBox">
                        <i class="fas fa-exclamation-circle"></i>
                        <span><?= $_SESSION['error'] ?></span>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>
                <form class="space-y-4" method="POST" action="">
                    <div>
                        <label for="currentPassword" class="block text-gray-700 text-sm font-bold mb-2">Current Password <span class="text-red-500">*</span></label>
                        <input type="password" id="currentPassword" name="currentPassword" placeholder="Current Password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div>
                        <label for="password" class="block text-gray-700 text-sm font-bold mb-2">New Password <span class="text-red-500">*</span></label>
                        <input type="password" id="password" name="password" placeholder="New Password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div>
                        <label for="confirmPassword" class="block text-gray-700 text-sm font-bold mb-2">Confirm New Password <span class="text-red-500">*</span></label>
                        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm New Password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-blue-700 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                        Submit
                    </button>
                </form>
            </div>
        </main>
    </div>
    <footer class="bg-gray-200 text-gray-700 py-4 text-center mt-8">
        © 2025 Task Buddy. All Rights Reserved.
    </footer>
    <script>
        // Hide message box after 5 seconds
        window.addEventListener('DOMContentLoaded', () => {
            const messageBox = document.getElementById('messageBox');
            if (messageBox) {
                setTimeout(() => {
                    messageBox.style.transition = 'opacity 0.5s ease';
                    messageBox.style.opacity = '0';
                    setTimeout(() => messageBox.remove(), 500);
                }, 5000);
            }
        });
    </script>
</body>
</html>
