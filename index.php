<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("location: dashboard.php");
    exit;
}

require_once 'db_connect.php';

$page_title = "Admin Login";
$error = "";

// ... (আপনার বাকি PHP কোড এখানে অপরিবর্তিত থাকবে)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty(trim($username)) || empty(trim($password))) {
        $error = "Please enter username and password.";
    } else {
        $sql = "SELECT id, username, password FROM admins WHERE username = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $param_username);
            $param_username = $username;
            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    $stmt->bind_result($id, $db_username, $hashed_password);
                    if ($stmt->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                            $_SESSION['loggedin'] = true;
                            $_SESSION['id'] = $id;
                            $_SESSION['username'] = $db_username;
                            header("location: dashboard.php");
                            exit;
                        } else {
                            $error = "Invalid username or password.";
                        }
                    }
                } else {
                    $error = "Invalid username or password.";
                }
            } else {
                $error = "Oops! Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            /* একটি হালকা এবং পরিচ্ছন্ন ব্যাকগ্রাউন্ড রঙ */
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .login-box {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            padding: 3rem;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            text-align: center;
        }
        .login-box .logo {
            width: 70px;
            height: 70px;
            margin-bottom: 1.5rem;
        }
        .login-box h3 {
            font-weight: 600;
            color: #333;
        }
        .login-box p {
            color: #777;
        }
        .form-control:focus {
            border-color: #0056b3;
            box-shadow: 0 0 0 0.2rem rgba(0, 86, 179, 0.25);
        }
        .btn-submit {
            background-color: #003366;
            color: white;
            padding: 10px 0;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .btn-submit:hover {
            background-color: #002244;
            color: white;
        }
    </style>
</head>
<body>

<div class="login-box">
    <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEgWm1YtqoFKO3q8KsKwfTAglbLRITblydJvQi8z7K8kMlxgpIrLCtismpvm-VJbsQGDJ34Lpl2ZD-X52ukZTXJqHHpwO0aCjqyzdYA_KfRjOl5gSUVGbYXTZ4SlplOZ4V4BBKsHlZ9zEWQ/s1600/NUB-Logo1-for-banner.png" alt="Logo" class="logo">
    <h3>University Management System From Group F</h3>
    <p class="mb-4">Admin Login Panel</p>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger p-2"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
            <label for="username">Username</label>
        </div>
        <div class="form-floating mb-4">
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
            <label for="password">Password</label>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-submit">Login</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>