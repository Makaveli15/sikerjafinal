<?php
session_start();
include 'config/koneksi.php';

$errors = [];

if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $query = mysqli_query($host, "SELECT * FROM users WHERE username='$username'");

  if ($query && mysqli_num_rows($query) === 1) {
    $user = mysqli_fetch_assoc($query);

    if ($user['status'] !== 'aktif') {
      $errors[] = "Akun Anda telah dinonaktifkan.";
    } elseif (password_verify($password, $user['password'])) {
      $_SESSION['user'] = $user;

      if (password_verify('BPS5305', $user['password'])) {
        $_SESSION['force_change_password'] = true;
      }

      if ($user['role'] === 'Kepala Kantor' || $user['role'] === 'Kepala Sub Bagian Umum') {
        header("Location: admin/dashboard_kepala.php");
      } else {
        header("Location: admin/dashboard.php");
      }
      exit;
    } else {
      $errors[] = "Password salah.";
    }
  } else {
    $errors[] = "Username tidak ditemukan.";
  }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Login - SIKERJA BPS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    body {
      background: linear-gradient(135deg, #005DAB, #007DAD);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .login-container {
      max-width: 420px;
      width: 100%;
      background-color: rgba(255, 255, 255, 0.95);
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
      backdrop-filter: blur(4px);
    }

    .logo {
      width: 90px;
      display: block;
      margin: 0 auto 15px auto;
    }

    .form-title {
      text-align: center;
      font-weight: 700;
      color: #005DAB;
      margin-bottom: 10px;
      letter-spacing: 1px;
    }

    .form-subtitle {
      text-align: center;
      font-size: 0.95rem;
      color: #555;
      margin-bottom: 20px;
    }

    .form-control:focus {
      border-color: #005DAB;
      box-shadow: 0 0 0 0.2rem rgba(0, 93, 171, 0.25);
    }

    .btn-primary {
      background-color: #005DAB;
      border: none;
      font-weight: 500;
      transition: 0.3s ease;
    }

    .btn-primary:hover {
      background-color: #004d8a;
    }

    .password-wrapper {
      position: relative;
    }

    .toggle-password {
      position: absolute;
      top: 50%;
      right: 12px;
      transform: translateY(-50%);
      cursor: pointer;
      font-size: 1.2rem;
      color: #888;
    }

    .toggle-password:hover {
      color: #000;
    }
  </style>
</head>

<body>
  <div class="login-container">

    <h4 class="form-title">SIKERJA</h4>
    <img src="assets/img/bpsss.png" alt="Logo BPS" class="logo">
    <div class="form-subtitle">Sistem Informasi Monitoring Kinerja Kinerja BPS Kefamenanu</div>

    <?php if (!empty($errors)) : ?>
      <div class="alert alert-danger"><?= implode("<br>", $errors); ?></div>
    <?php endif; ?>

    <form method="post">
      <div class="mb-3">
        <label>Username</label>
        <input type="text" name="username" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Password</label>
        <div class="password-wrapper">
          <input type="password" name="password" id="password" class="form-control" required>
          <i class="bi bi-eye toggle-password" id="togglePassword" onclick="togglePassword()"></i>
        </div>
      </div>
      <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
    </form>
  </div>

  <script>
    function togglePassword() {
      const passwordInput = document.getElementById("password");
      const toggleIcon = document.getElementById("togglePassword");

      if (passwordInput.type === "password") {
        passwordInput.type = "text";
        toggleIcon.classList.remove("bi-eye");
        toggleIcon.classList.add("bi-eye-slash");
      } else {
        passwordInput.type = "password";
        toggleIcon.classList.remove("bi-eye-slash");
        toggleIcon.classList.add("bi-eye");
      }
    }
  </script>
</body>

</html>