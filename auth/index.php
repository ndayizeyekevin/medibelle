<?php
session_start();
include('../includes/connection.php'); // Database connection

if (isset($_POST['login'])) {
    $username = $dsn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT userId, username, password, role, account_status FROM users WHERE username = '$username' LIMIT 1";
    $result = $dsn->query($query);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            if ($user['account_status'] != 1) {
                $error = "Your access has been revoked.";
            } else {
                $_SESSION['userId']   = $user['userId'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role']     = $user['role'];

                $_SESSION['redirect_to'] = ($user['role'] === 'admin' || $user['role'] === 'custom') ? "../staff/" : "./index.php";
                echo "<script>window.location.href = './?auth=success';</script>";
                exit();
            }
        } else {
            $error = "Invalid username/password.";
        }
    } else {
        $error = "Invalid username/password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="icon" href="../images/icon.png" type="image/x-icon">
    <link href="/cosmeticsstore/assets/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="/cosmeticsstore/css/style.css">
    <link rel="stylesheet" href="/cosmeticsstore/assets/fontawesome/css/all.min.css">

    <style>
        body {
            background: #f5f5f5;
        }

        .login-container {
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            margin-top: 60px;
        }

        /* Spinner Loader */
        #loader {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: white;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 1;
            transition: opacity 0.6s ease;
        }

        @keyframes bounceIn {
            0% { transform: scale(0.5); opacity: 0; }
            60% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(1); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="login-container shadow-lg">
                <img src="/cosmeticsstore/images/logo.png" alt="Logo" class="logo img-fluid" width="300">
                <h2 class="text-center mb-4">Staff Login</h2>

                <?php if (isset($error)) : ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="post" action="" class="staff-form">
                    <div class="form-group mb-3">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" class="form-control" placeholder="Enter username" required>
                    </div>

                    <div class="form-group mb-3 position-relative">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control pe-5" placeholder="Enter password" required>
                        
                        <span class="position-absolute top-50 end-0 translate-middle-y me-3 pt-4" onclick="togglePassword()" style="cursor: pointer;">
                            <i id="eyeIcon" class="fa-solid fa-eye-slash"></i>
                        </span>
                    </div>

                    <div class="text-center">
                        <button type="submit" name="login" class="btn btn-primary btn-block px-5 w-100">Login</button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    <a href="../">← Back to Home</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Show loader if redirected after successful login -->
<?php if (isset($_GET['auth']) && $_GET['auth'] === 'success' && isset($_SESSION['redirect_to'])): ?>
    <div id="loader">
        <img src="/cosmeticsstore/images/icon.png" alt="Logo" width="100" style="animation: bounceIn 1s ease;">
        <i class="fa fa-spinner fa-spin fa-3x text-primary mt-4"></i>
        <p class="mt-3 text-primary fs-4" style="animation: fadeInUp 1.2s ease;">Logging in, please wait...</p>
    </div>
    <script>
        setTimeout(() => {
            window.location.href = "<?php echo $_SESSION['redirect_to']; ?>";
        }, 1200);
    </script>
<?php endif; ?>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById("password");
        const eyeIcon = document.getElementById("eyeIcon");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eyeIcon.classList.remove("fa-eye-slash");
            eyeIcon.classList.add("fa-eye");
        } else {
            passwordInput.type = "password";
            eyeIcon.classList.remove("fa-eye");
            eyeIcon.classList.add("fa-eye-slash");
        }
    }
</script>

</body>
</html>
