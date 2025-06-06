<?php
// register.php
session_start();
include('./includes/connection.php'); // Include your connection file

if (isset($_POST['register'])) {
    // Retrieve and sanitize form inputs
    $firstName        = $dsn->real_escape_string($_POST['firstName']);
    $lastName         = $dsn->real_escape_string($_POST['lastName']);
    $email            = $dsn->real_escape_string($_POST['email']);
    $phone            = $dsn->real_escape_string($_POST['phone']);
    $username         = $dsn->real_escape_string($_POST['username']);
    $password         = $_POST['password']; // Raw password (to be hashed)
    $confirm_password = $_POST['confirm_password'];
    $role             = $dsn->real_escape_string($_POST['role']);

    // Validate that the passwords match
    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if the username already exists
        $query  = "SELECT userId FROM users WHERE username = '$username' LIMIT 1";
        $result = $dsn->query($query);
        if ($result && $result->num_rows > 0) {
            $error = "Username already exists. Please choose another one.";
        } else {
            // Hash the password securely
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new user record into the database
            $query = "INSERT INTO users (firstName, lastName, email, phone, username, password, role)
                      VALUES ('$firstName', '$lastName', '$email', '$phone', '$username', '$hashed_password', '$role')";
            if ($dsn->query($query)) {
                $success = "User successfully registered.";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Ensure proper rendering and touch zooming on mobile devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Responsive Register</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: #f5f5f5;
        }
        .register-container {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin: 20px auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Center the form on the page -->
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="register-container">
                    <h2 class="text-center mb-4">Register</h2>
                    <?php if (isset($error)) : ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <?php if (isset($success)) : ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>
                    <form method="post" action="">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="firstName">First Name</label>
                                <input type="text" name="firstName" id="firstName" class="form-control" placeholder="First Name" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="lastName">Last Name</label>
                                <input type="text" name="lastName" id="lastName" class="form-control" placeholder="Last Name" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Email Address" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" name="phone" id="phone" class="form-control" placeholder="Phone Number" required>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username" class="form-control" placeholder="Choose a username" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="confirm_password">Confirm Password</label>
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="role">Select Role</label>
                            <select name="role" id="role" class="form-control">
                                <option value="user" selected>User</option>
                                <option value="admin">Admin</option>
                                <option value="editor">Editor</option>
                            </select>
                        </div>
                        <button type="submit" name="register" class="btn btn-primary btn-block">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Optional JavaScript dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Optional: Additional client-side interactivity
        document.querySelector('form').addEventListener('submit', function() {
            console.log('Registration form submitted');
        });
    </script>
</body>
</html>
