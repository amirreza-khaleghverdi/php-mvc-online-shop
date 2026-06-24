<?php if (!isset($_SESSION)) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Panel | OSI</title>
  <link rel="stylesheet" href="Templates/style.css">
</head>

<body>

<header class="header">
  <div class="logo">🛠️ OSI Admin</div>
  <nav class="nav">
    <a href="index.php?action=logout">🚪 Logout</a>
  </nav>
</header>

<section class="hero hero--small">
  <h1>📊 Admin Dashboard</h1>
</section>

<main class="account">

  <?php if (!empty($_SESSION['error'])): ?>
    <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
  <?php elseif (!empty($_SESSION['message'])): ?>
    <p class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
  <?php endif; ?>

  <div class="account-grid">

    <div class="card" onclick="location.href='index.php?action=admin_products'">
      <h2>📦 Products</h2>
      <p>Add, edit, delete products</p>
    </div>

    <div class="card" onclick="location.href='index.php?action=admin_customers'">
      <h2>👥 Customers</h2>
      <p>View and manage customers</p>
    </div>

    <div class="card" onclick="location.href='index.php?action=admin_couriers'">
      <h2>🚚 Couriers</h2>
      <p>View, add and delete couriers</p>
    </div>

    <div class="card" onclick="location.href='index.php?action=admin_orders'">
      <h2>🛒 Orders</h2>
      <p>View and update order status</p>
    </div>

    <div class="card" onclick="location.href='index.php?action=admin_inventory'">
      <h2>📋 Inventory</h2>
      <p>Manage stock levels per warehouse</p>
    </div>

    <div class="card" onclick="location.href='index.php?action=admin_shipments'">
      <h2>📦 Shipments</h2>
      <p>View and assign shipments to couriers</p>
    </div>

    <div class="card" onclick="location.href='index.php?action=admin_warehouses'">
      <h2>🏭 Warehouses</h2>
      <p>View, add and edit warehouses</p>
    </div>

    <div class="card" onclick="location.href='index.php?action=admin_delivery_status'">
      <h2>📍 Delivery Status History</h2>
      <p>View full delivery status history</p>
    </div>

  </div>

</main>

<footer>
  <p>© 2026 🛍️ OSI-Shop Designed by (Bakhshi-Khaleghverdi-Eyvazi) Alpha-version</p>
</footer>

</body>
</html>