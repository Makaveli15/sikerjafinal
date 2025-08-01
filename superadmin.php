<?php
session_start();
include 'config/koneksi.php';

$errors = [];

if (isset($_POST['login_superadmin'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $cek = mysqli_query($host, "SELECT * FROM superadmin WHERE username='$username'");
    $data = mysqli_fetch_assoc($cek);

    if ($data && password_verify($password, $data['password'])) {
        $_SESSION['superadmin'] = $data;
        header("Location: superadmin_dashboard.php");
        exit;
    } else {
        $errors[] = "Username atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login Superadmin - SIMONEV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #2c3e50, #4ca1af);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: #fff;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 420px;
        }

        .login-card h4 {
            font-weight: 700;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .form-control {
            border-radius: 8px;
        }

        .btn-primary {
            border-radius: 8px;
            font-weight: 600;
            transition: background 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .login-icon {
            font-size: 3rem;
            color: #4ca1af;
            margin-bottom: 15px;
        }

        .alert {
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
    <div class="login-card text-center">
        <div class="login-icon">
            <i class="bi bi-shield-lock-fill"></i>
        </div>
        <h4>Login Superadmin SIMONEV</h4>

        <?php if (!empty($errors)) : ?>
            <div class="alert alert-danger mt-2"><?= implode("<br>", $errors); ?></div>
        <?php endif; ?>

        <form method="post" class="text-start mt-3">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required autofocus>
            </div>
            <div class="mb-4">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" name="login_superadmin" class="btn btn-primary w-100">
                <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
            </button>
        </form>
    </div>
</body>

</html>