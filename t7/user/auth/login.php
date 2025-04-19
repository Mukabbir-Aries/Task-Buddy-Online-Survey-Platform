<?php
session_start(); // Start the session

$host = 'localhost';
$username = 'root'; // Default XAMPP username
$password = ''; // Default XAMPP password is empty
$dbname = 'task_buddy_db'; // Ensure this matches your database name

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $email;
            header("Location: ../../dashboard/dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Invalid password. Please try again.";
        }
    } else {
        $_SESSION['error'] = "No account found with that email address.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>TaskBuddy - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .login-card {
            background: white;
            border-radius: 1rem;
            overflow: hidden;
            width: 100%;
            max-width: 28rem;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
            transition: all 0.3s ease;
        }
        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem;
            text-align: center;
            color: white;
        }
        .gradient-bg h1 {
            font-size: 1.875rem; /* text-3xl */
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .gradient-bg p {
            color: #d8b4fe; /* text-purple-100 */
        }
        form {
            padding: 2rem;
        }
        .input-focus:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.2);
            outline: none;
        }
        .input-wrapper {
            position: relative;
            margin-bottom: 1.5rem;
        }
        .input-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            pointer-events: none;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.75rem 0.75rem 0.75rem 2.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }
        .password-toggle-btn {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            cursor: pointer;
            color: #9ca3af;
            padding: 0;
        }
        .password-toggle-btn:hover {
            color: #6b21a8;
        }
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .remember-me {
            display: flex;
            align-items: center;
        }
        .remember-me input[type="checkbox"] {
            height: 1rem;
            width: 1rem;
            accent-color: #7c3aed;
            border-radius: 0.25rem;
            border: 1px solid #d1d5db;
        }
        .remember-me label {
            margin-left: 0.5rem;
            font-size: 0.875rem;
            color: #374151;
        }
        .forgot-password {
            font-size: 0.875rem;
            color: #7c3aed;
            text-decoration: none;
        }
        .forgot-password:hover {
            color: #5b21b6;
        }
        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            width: 100%;
            cursor: pointer;
            transition: opacity 0.3s ease;
            border: none;
        }
        .btn-submit:hover {
            opacity: 0.9;
        }
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            color: #9ca3af;
            margin-bottom: 1.5rem;
        }
        .divider::before, .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #e5e7eb;
        }
        .divider::before {
            margin-right: 1rem;
        }
        .divider::after {
            margin-left: 1rem;
        }
        .social-login {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .social-login button {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 0;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            background: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .social-login button:hover {
            background-color: #f9fafb;
        }
        .social-login i {
            font-size: 1.25rem;
        }
        .sign-up-text {
            text-align: center;
            font-size: 0.875rem;
            color: #4b5563;
        }
        .sign-up-text a {
            color: #7c3aed;
            font-weight: 500;
            text-decoration: none;
        }
        .sign-up-text a:hover {
            color: #5b21b6;
        }
        .alert {
            margin-bottom: 1rem;
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            display: flex;
            align-items: center;
            color:rgb(18, 213, 47);
            background-color:rgb(230, 254, 226);
            border-left: 4px solidrgb(35, 255, 1);
        }
        .alert i {
            margin-right: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="gradient-bg">
                <h1>Welcome To Task Buddy</h1>
                <p>Sign in to access your account</p>
            </div>
            <form action="" method="POST" novalidate>
                <?php
                if (isset($_SESSION['registration_success'])) {
                    echo '<div class="alert">';
                    echo '<i class="fas fa-check-circle"></i>';
                    echo '<span>' . $_SESSION['registration_success'] . '</span>';
                    echo '</div>';
                    unset($_SESSION['registration_success']);
                }
                if (isset($_SESSION['error'])) {
                    echo '<div class="alert">';
                    echo '<i class="fas fa-exclamation-circle"></i>';
                    echo '<span>' . $_SESSION['error'] . '</span>';
                    echo '</div>';
                    unset($_SESSION['error']);
                }
                ?>
                <div class="input-wrapper">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" id="email" name="email" required placeholder="you@example.com" class="input-focus" />
                </div>
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="password" name="password" required placeholder="••••••••" class="input-focus" />
                    <button type="button" class="password-toggle-btn" aria-label="Toggle password visibility">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div class="remember-forgot">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember" />
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="forgot_password.php" class="forgot-password">Forgot password?</a>
                </div>
                <button type="submit" class="btn-submit">Sign In</button>
                <div class="divider">or continue with</div>
                <div class="social-login">
                    <button type="button" aria-label="Sign in with Google">
                        <i class="fab fa-google text-red-500"></i>
                    </button>
                    <button type="button" aria-label="Sign in with Facebook">
                        <i class="fab fa-facebook-f text-blue-600"></i>
                    </button>
                    <button type="button" aria-label="Sign in with Apple">
                        <i class="fab fa-apple text-gray-800"></i>
                    </button>
                </div>
                <div class="sign-up-text">
                    Don't have an account? <a href="register.php">Sign up</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Password visibility toggle
        const togglePasswordBtn = document.querySelector('.password-toggle-btn');
        const passwordInput = document.getElementById('password');
        const toggleIcon = togglePasswordBtn.querySelector('i');

        togglePasswordBtn.addEventListener('click', () => {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    </script>
</body>
</html>
