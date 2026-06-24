<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register | OSI</title>
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
    <h1>Register</h1>
    <p>Create your account</p>
  </section>

  <main class="account-single">
    <section class="card single-card">
      <h2 class="card-title">Create Account</h2>
      <p class="card-subtitle">Fill in your details</p>

      <?php if (!empty($_SESSION['error'])): ?>
        <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
      <?php elseif (!empty($_SESSION['message'])): ?>
        <p class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
      <?php endif; ?>

      <form class="form" action="index.php?action=doRegister" method="POST">

        <div class="row">
          <div>
            <label class="label" for="firstName">First Name</label>
            <input class="input" id="firstName" name="Firstname" type="text" placeholder="Ali" required>
          </div>
          <div>
            <label class="label" for="lastName">Last Name</label>
            <input class="input" id="lastName" name="Lastname" type="text" placeholder="Sharifi" required>
          </div>
        </div>

        <label class="label" for="registerEmail">Email</label>
        <input class="input" id="registerEmail" name="email" type="email" placeholder="Alisharifi@OSI.com" required>

        <div>
          <label class="label" for="phone">Phone Number</label>
          <input class="input" id="phone" name="Phonenumber" type="tel" placeholder="+98 0912 1234 567" required>
        </div>

        <label class="label" for="nationalId">National ID</label>
        <input class="input" id="nationalId" name="Nationalcode" type="text" placeholder="Identification number" required>

        <label class="label" for="role">Role</label>
        <select class="input select" id="role" name="role" required>
          <option value="" selected disabled>Select a role</option>
          <option value="customer">Customer</option>
          <option value="courier">Courier</option>
        </select>

        <div class="row">
          <div>
            <label class="label" for="registerPassword">Password</label>
            <input class="input" id="registerPassword" name="password" type="password" placeholder="At least 6 characters" minlength="6" required>
          </div>
          <div>
            <label class="label" for="confirmPassword">Confirm Password</label>
            <input class="input" id="confirmPassword" name="confirmPassword" type="password" placeholder="Repeat password" minlength="6" required>
          </div>
        </div>

        <button class="btn btn-primary" type="submit">Register</button>

        <p class="hint">
          Already have an account?
          <a href="index.php?action=login">Login</a>
        </p>

      </form>
    </section>
  </main>

  <footer>
    <p>© 2026 OSI-Shop</p>
  </footer>

</body>
</html>