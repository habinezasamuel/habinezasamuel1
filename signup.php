<?php
session_start();
require_once 'config.php';

$errors = [];
$username = $email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    if (empty($username)) {
        $errors['username'] = 'Username is required';
    } elseif (strlen($username) < 3) {
        $errors['username'] = 'Username must be at least 3 characters';
    }

    if (empty($email)) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address';
    }

    if (empty($password)) {
        $errors['password'] = 'Password is required';
    } elseif (strlen($password) < 6) {
        $errors['password'] = 'Password must be at least 6 characters';
    }

    if ($password !== $confirm_password) {
        $errors['confirm_password'] = 'Passwords do not match';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->rowCount() > 0) {
            $errors['general'] = 'Username or email already exists';
        }
    }

    
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        
        if ($stmt->execute([$username, $email, $hashed_password])) {
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['username'] = $username;
            header('Location: dashboard.php');
            exit;
        } else {
            $errors['general'] = 'Registration failed. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Facebook</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Helvetica, Arial, sans-serif;
        }

        body {
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            max-width: 400px;
            width: 100%;
            padding: 20px;
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo h1 {
            color: #1877f2;
            font-size: 3rem;
            font-weight: bold;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .1), 0 8px 16px rgba(0, 0, 0, .1);
            width: 100%;
        }

        .card h2 {
            text-align: center;
            color: #1c1e21;
            margin-bottom: 20px;
            font-size: 1.2rem;
        }

        .form-group {
            margin-bottom: 15px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #dddfe2;
            border-radius: 6px;
            font-size: 17px;
            background-color: #f5f6f7;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #1877f2;
            outline: none;
            background-color: white;
        }

        .btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 20px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-primary {
            background-color: #1877f2;
            color: white;
        }

        .btn-primary:hover {
            background-color: #166fe5;
        }

        .btn-google {
            background-color: #ffffff;
            color: #757575;
            border: 1px solid #dadce0;
            margin-top: 15px;
        }

        .btn-google:hover {
            background-color: #f8f9fa;
        }

        .error {
            color: #f02849;
            font-size: 13px;
            margin-top: 5px;
            display: block;
        }

        .success {
            color: #42b72a;
            font-size: 13px;
            margin-top: 5px;
            display: block;
        }

        .divider {
            text-align: center;
            margin: 20px 0;
            color: #8a8d91;
            position: relative;
        }

        .divider::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #dadde1;
        }

        .divider span {
            background: white;
            padding: 0 15px;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #dadde1;
        }

        .login-link a {
            color: #1877f2;
            text-decoration: none;
            font-weight: bold;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <h1>facebook</h1>
        </div>
        
        <div class="card">
            <h2>Create a New Account</h2>
            
            <?php if (!empty($errors['general'])): ?>
                <div class="error"><?php echo $errors['general']; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <input type="text" name="username" placeholder="Username" 
                           value="<?php echo htmlspecialchars($username); ?>" required>
                    <?php if (!empty($errors['username'])): ?>
                        <span class="error"><?php echo $errors['username']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <input type="email" name="email" placeholder="Email address" 
                           value="<?php echo htmlspecialchars($email); ?>" required>
                    <?php if (!empty($errors['email'])): ?>
                        <span class="error"><?php echo $errors['email']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <input type="password" name="password" placeholder="Password" required>
                    <?php if (!empty($errors['password'])): ?>
                        <span class="error"><?php echo $errors['password']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                    <?php if (!empty($errors['confirm_password'])): ?>
                        <span class="error"><?php echo $errors['confirm_password']; ?></span>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-primary">Sign Up</button>
            </form>

            <div class="divider"><span>or</span></div>

            <button type="button" class="btn btn-google" onclick="simulateGoogleLogin()">
                Login with Google
            </button>

            <div class="login-link">
                <a href="login.php">Already have an account?</a>
            </div>
        </div>
    </div>

    <script>
        function simulateGoogleLogin() {
            alert('Google login simulation: Redirecting to dashboard...');
            window.location.href = 'dashboard.php?google_login=true';
        }
    </script>
</body>
</html>