<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'task_buddy_db';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_name = $_POST['admin_name'] ?? '';
    $admin_password_plain = $_POST['admin_password'] ?? '';

    if (empty($admin_name) || empty($admin_password_plain)) {
        $message = 'Please provide both admin name and password.';
    } else {
        $conn = new mysqli($host, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $admin_password_hashed = password_hash($admin_password_plain, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO admins (admin_name, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $admin_name, $admin_password_hashed);

        if ($stmt->execute()) {
            $message = "Admin user '$admin_name' created successfully.";
        } else {
            $message = "Error creating admin user: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Create New Admin - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-6 bg-white rounded-lg shadow-md mt-10">
        <h1 class="text-2xl font-bold mb-6">Create New Admin User</h1>
        <?php if ($message): ?>
            <script>
                setTimeout(function() {
                    window.location.href = "admindashboard.php";
                }, 2000); // Redirect after 2 seconds
            </script>
            <div class="mb-4 p-3 rounded bg-<?php echo strpos($message, 'successfully') !== false ? 'green' : 'red'; ?>-100 text-<?php echo strpos($message, 'successfully') !== false ? 'green' : 'red'; ?>-700">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
<form method="POST" action="create_new_admin.php" class="space-y-4">
            <div>
                <label for="admin_name" class="block mb-1 font-semibold">Admin Name</label>
                <input type="text" id="admin_name" name="admin_name" class="w-full border border-gray-300 rounded px-3 py-2" required />
            </div>
            <div>
                <label for="admin_password" class="block mb-1 font-semibold">Password</label>
                <input type="password" id="admin_password" name="admin_password" class="w-full border border-gray-300 rounded px-3 py-2" required />
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">Create Admin</button>
        </form>
        <a href="admindashboard.php" class="inline-block mt-4 text-blue-600 hover:underline">Back to Dashboard</a>
    </div>
</body>
</html>