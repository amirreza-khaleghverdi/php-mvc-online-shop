<?php if (!isset($_SESSION)) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Login | OSI</title>
  <link rel="stylesheet" href="Templates/style.css">
</head>

<body>

<header class="header">
  <div class="logo">🛠️ OSI Admin</div>
  <nav class="nav">
    <a href="index.php?action=homepage">🏠 Home</a>
  </nav>
</header>

<section class="hero hero--small">
  <h1>🔐 Admin Login</h1>
  <p>Restricted area</p>
</section>

<main class="account-single">
  <section class="card single-card">
    <h2 class="card-title">Admin Access</h2>
    <p class="card-subtitle">Enter your credentials</p>

    <?php if (!empty($_SESSION['error'])): ?>
      <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php elseif (!empty($_SESSION['message'])): ?>
      <p class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
    <?php endif; ?>

    <form class="form" action="index.php?action=doAdminLogin" method="POST">

      <label class="label" for="admin_name">Name</label>
      <input class="input" id="admin_name" name="admin_name" type="text" placeholder="Admin name" required>

      <label class="label" for="passkey">Passkey</label>
      <input class="input" id="passkey" name="passkey" type="password" placeholder="Enter passkey" required>

      <button class="btn btn-primary" type="submit">🔐 Login</button>

    </form>
  </section>
</main>

<footer>
  <p>© 2026 🛍️ OSI-Shop Designed by (Bakhshi-Khaleghverdi-Eyvazi) Alpha-version</p>
</footer>

</body>
</html>