<?php
session_start();
require_once 'config.php';

$errors = [];
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validation
    if (empty($username)) {
        $errors['username'] = 'Username or email is required';
    }

    if (empty($password)) {
        $errors['password'] = 'Password is required';
    }

    if (empty($errors)) {
        // Check if user exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: dashboard.php');
            exit;
        } else {
            $errors['general'] = 'Invalid username/email or password';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In - Facebook</title>
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

        .form-group {
            margin-bottom: 15px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #dddfe2;
            border-radius: 6px;
            font-size: 17px;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #1877f2;
            outline: none;
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
            text-align: center;
        }

        .forgot-password {
            text-align: center;
            margin: 15px 0;
        }

        .forgot-password a {
            color: #1877f2;
            text-decoration: none;
            font-size: 14px;
        }

        .forgot-password a:hover {
            text-decoration: underline;
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

        .signup-link {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #dadde1;
        }

        .signup-link a {
            color: #1877f2;
            text-decoration: none;
            font-weight: bold;
        }

        .signup-link a:hover {
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
            <?php if (!empty($errors['general'])): ?>
                <div class="error"><?php echo $errors['general']; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <input type="text" name="username" placeholder="Email or username" 
                           value="<?php echo htmlspecialchars($username); ?>" required>
                    <?php if (!empty($errors['username'])): ?>
                        <span class="error"><?php echo $errors['username']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <input type="password" name="password" placeholder="Password" required>
                    <?php if (!empty($errors['password'])): ?>
                        <span class="error"><?php echo $errors['password']; ?></span>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-primary">Log In</button>
            </form>

            <div class="forgot-password">
                <a href="#">Forgotten password?</a>
            </div>

            <div class="divider"><span>or</span></div>

            <button type="button" class="btn btn-google" onclick="simulateGoogleLogin()">
                Login with Google
            </button>

            <div class="signup-link">
                <a href="signup.php">Create new account</a>
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