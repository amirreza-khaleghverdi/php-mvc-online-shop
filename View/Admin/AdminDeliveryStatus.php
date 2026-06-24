<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Delivery Status History | OSI Admin</title>
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
  <h1>📍 Delivery Status History</h1>
  <p>Full log of all shipment status updates</p>
</section>

<main>

  <?php if (!empty($_SESSION['error'])): ?>
    <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
  <?php elseif (!empty($_SESSION['message'])): ?>
    <p class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
  <?php endif; ?>

  <div class="cart-wrapper">

    <h2 class="card-title">📋 All Status Updates</h2>

    <?php if (!empty($history)): ?>
      <table class="cart-table">
        <thead>
          <tr>
            <th>Status ID</th>
            <th>Shipment ID</th>
            <th>Tracking Code</th>
            <th>Status</th>
            <th>Location</th>
            <th>Time</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($history as $record): ?>
            <tr>
              <td>#<?php echo htmlspecialchars($record['StatusID']); ?></td>
              <td>#<?php echo htmlspecialchars($record['ShipmentID']); ?></td>
              <td><?php echo htmlspecialchars($record['TrakingCode']); ?></td>
              <td><?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $record['StatusMessage']))); ?></td>
              <td><?php echo htmlspecialchars($record['Location']); ?></td>
              <td><?php echo htmlspecialchars($record['StatusTime']); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No delivery status history found.</p>
    <?php endif; ?>

  </div>
</main>

<footer>
  <p>© 2026 🛍️ OSI-Shop Designed by (Bakhshi-Khaleghverdi-Eyvazi) Alpha-version</p>
</footer>

</body>
</html>