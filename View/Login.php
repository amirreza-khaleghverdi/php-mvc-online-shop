<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login | OSI</title>
  <link rel="stylesheet" href="Templates/style.css">
</head>

<body>

  <header class="header">
    <div class="logo">OurShopIust</div>
    <div class="search">
      <input type="text" placeholder="Search products...">
      <button>Search</button>
    </div>
    <nav class="nav">
      <a href="index.php">Home</a>
      <a href="index.php">Products</a>
    </nav>
  </header>

  <section class="hero hero--small">
    <h1>Login</h1>
    <p>Welcome back!</p>
  </section>

  <main class="account-single">
    <section class="card single-card">
      <h2 class="card-title">Sign In</h2>
      <p class="card-subtitle">Enter your credentials</p>

      <?php if (!empty($_SESSION['error'])): ?>
        <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
      <?php elseif (!empty($_SESSION['message'])): ?>
        <p class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
      <?php endif; ?>

      <form class="form" action="index.php?action=doLogin" method="POST">

        <label class="label" for="nationalId">National ID</label>
        <input class="input" id="nationalId" name="Nationalcode" type="text" placeholder="Identification number" required>

        <label class="label" for="password">Password</label>
        <input class="input" id="password" name="password" type="password" placeholder="At least 6 characters" minlength="6" required>

        <label class="label" for="role">Role</label>
        <select class="input select" id="role" name="role" required>
          <option value="" selected disabled>Select your role</option>
          <option value="customer">Customer</option>
          <option value="courier">Courier</option>
        </select>

        <button class="btn btn-primary" type="submit">Login</button>

        <p class="hint">
          Don't have an account?
          <a href="index.php?action=register"> sign up</a>
        </p>

      </form>
    </section>
  </main>

  <footer>
    <p>© 2026 OSI-Shop</p>
  </footer>

</body>
</html>