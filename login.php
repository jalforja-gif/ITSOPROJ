<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "start_ml.php";
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description"
      content="ITSO System login page – securely access your dashboard and manage applications.">
      <meta name="description" content="ITSO System – online tracking and management for applications.">
  <title>Login | ITSO System</title>
  <link rel="icon" type="image/png" href="assets/ITSO.png">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="assets/css/login_signup.css">

  <link rel="manifest" href="/ITSO_System/manifest.json">
  <meta name="theme-color" content="#0f172a">

</head>
<body>
  <div class="wrapper">
    <div class="info-text login">
      <img src="assets/imgs/itsolog.png" alt="ITSO Logo">
    </div>

    <div class="form-box login">
      <h2>Welcome Back</h2>
      <p class="subtitle">Sign in to continue to ITSO System</p>
      <form action="process_login.php" method="POST">
        <div class="input-box">
          <i class='bx bxs-user'></i>
          <input type="text" name="username" autocomplete="off" required>
          <label>Username</label>
        </div>
        <div class="input-box">
          
          <input type="password" id="password" name="password" required>
          <label>Password</label>
          <i class='bx bx-show toggle-password' onclick="togglePassword()"></i>
        </div>
        <button type="submit" class="btn">Login</button>
        <div class="logreg-link">
          <p>Don’t have an account? <a href="register.php">Sign up</a></p>
        </div>
      </form>
    </div>
  </div>

  <script>
    function togglePassword() {
      const password = document.getElementById("password");
      const toggleIcon = document.querySelector(".toggle-password");
      if (password.type === "password") {
        password.type = "text";
        toggleIcon.classList.replace("bx-show", "bx-hide");
      } else {
        password.type = "password";
        toggleIcon.classList.replace("bx-hide", "bx-show");
      }
    }

    if ('serviceWorker' in navigator) {
  window.addEventListener('load', function() {
    navigator.serviceWorker.register('service-worker.js')
      .then(function(registration) {
        console.log('ServiceWorker registered');
      })
      .catch(function(error) {
        console.log('ServiceWorker failed:', error);
      });
  });
}
  </script>
</body>
</html>
