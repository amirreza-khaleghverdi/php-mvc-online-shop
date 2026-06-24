<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Manage Customers | OSI Admin</title>
  <link rel="stylesheet" href="Templates/style.css">
</head>

<body>

<header class="header">
  <div class="logo">🛠️ OSI Admin</div>
  <nav class="nav">
    <a href="index.php?action=admin">📊 Dashboard</a>
    <a href="index.php?action=admin_logout">🚪 Logout</a>
  </nav>
</header>

<section class="hero hero--small">
  <h1>👥 Manage Customers</h1>
  <p>Edit or remove customer accounts</p>
</section>

<main>

  <?php if (!empty($_SESSION['error'])): ?>
    <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
  <?php elseif (!empty($_SESSION['message'])): ?>
    <p class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
  <?php endif; ?>

  <div class="cart-wrapper">

    <h2 class="card-title">📋 All Customers</h2>

    <?php if (!empty($customers)): ?>
      <table class="cart-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>National Code</th>
            <th>Phone Number</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($customers as $customer): ?>
            <tr>
              <form action="index.php?action=admin_update_customer&id=<?php echo $customer['CustomerID']; ?>" method="POST">
                <td>#<?php echo htmlspecialchars($customer['CustomerID']); ?></td>
                <td><input class="input" type="text" name="FirstName" value="<?php echo htmlspecialchars($customer['FirstName']); ?>" required></td>
                <td><input class="input" type="text" name="LastName" value="<?php echo htmlspecialchars($customer['LastName']); ?>" required></td>
                <td><input class="input" type="email" name="Email" value="<?php echo htmlspecialchars($customer['Email'] ?? ''); ?>"></td>
                <td><?php echo htmlspecialchars($customer['NationalCode']); ?></td>
                <td><input class="input" type="text" name="PhoneNumber" value="<?php echo htmlspecialchars($customer['PhoneNumber']); ?>" required></td>
                <td>
                  <button type="submit" class="btn">💾 Save</button>
              </form>
                  <a href="index.php?action=admin_delete_customer&id=<?php echo $customer['CustomerID']; ?>" class="btn">🗑️ Delete</a>
                </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No customers found.</p>
    <?php endif; ?>

  </div>
</main>

<footer>
  <p>© 2026 🛍️ OSI-Shop Designed by (Bakhshi-Khaleghverdi-Eyvazi) Alpha-version</p>
</footer>

</body>
</html>