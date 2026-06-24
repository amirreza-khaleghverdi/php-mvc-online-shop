<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Manage Orders | OSI Admin</title>
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
  <h1>🛒 Manage Orders</h1>
  <p>View and update order status</p>
</section>

<main>

  <?php if (!empty($_SESSION['error'])): ?>
    <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
  <?php elseif (!empty($_SESSION['message'])): ?>
    <p class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
  <?php endif; ?>

  <div class="cart-wrapper">

    <h2 class="card-title">📋 All Orders</h2>

    <?php if (!empty($orders)): ?>
      <table class="cart-table">
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Total</th>
            <th>Date</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($orders as $order): ?>
            <tr>
              <form action="index.php?action=admin_update_order_status&id=<?php echo $order['id']; ?>" method="POST">
                <td>#<?php echo htmlspecialchars($order['id']); ?></td>
                <td><?php echo htmlspecialchars($order['FirstName'] . ' ' . $order['LastName']); ?></td>
                <td>💲<?php echo number_format($order['total'], 2); ?></td>
                <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                <td>
                  <select class="input select" name="status" required>
                    <option value="pending"   <?php echo $order['status'] === 'pending'   ? 'selected' : ''; ?>>⏳ Pending</option>
                    <option value="paid"      <?php echo $order['status'] === 'paid'      ? 'selected' : ''; ?>>💳 Paid</option>
                    <option value="shipped"   <?php echo $order['status'] === 'shipped'   ? 'selected' : ''; ?>>🚚 Shipped</option>
                    <option value="completed" <?php echo $order['status'] === 'completed' ? 'selected' : ''; ?>>✅ Completed</option>
                    <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>❌ Cancelled</option>
                  </select>
                </td>
                <td>
                  <button type="submit" class="btn">💾 Save</button>
                </td>
              </form>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No orders found.</p>
    <?php endif; ?>

  </div>
</main>

<footer>
  <p>© 2026 🛍️ OSI-Shop Designed by (Bakhshi-Khaleghverdi-Eyvazi) Alpha-version</p>
</footer>

</body>
</html>