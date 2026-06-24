<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Confirm Order | OSI</title>
  <link rel="stylesheet" href="Templates/style.css">
</head>

<body>

<header class="header">
  <div class="logo">🛍️ OSI</div>
  <div class="search">
    <input type="text" placeholder="Search products...">
    <button>Search</button>
  </div>
  <nav class="nav">
    <a href="index.php?action=homepage">🏠 Home</a>
    <a href="index.php?action=cart">🧺 Cart</a>
    <a href="index.php?action=dashboard">👤 Dashboard</a>
    <a href="index.php?action=logout">🚪 Logout</a>
  </nav>
</header>

<section class="hero hero--small">
  <h1>📦 Order Summary</h1>
  <p>Please review your order before confirming</p>
</section>

<main class="account-single">

  <?php if (!empty($_SESSION['error'])): ?>
    <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
  <?php elseif (!empty($_SESSION['message'])): ?>
    <p class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
  <?php endif; ?>

  <div class="single-card card">

    <h2 class="card-title">🛍️ Order #<?php echo htmlspecialchars($order_id); ?></h2>

    <?php if (!empty($order_items)): ?>
      <table class="cart-table">
        <thead>
          <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($order_items as $item): ?>
            <tr>
              <td><?php echo htmlspecialchars($item['Title']); ?></td>
              <td><?php echo $item['quantity']; ?></td>
              <td>💲<?php echo number_format($item['Price'], 2); ?></td>
              <td>💲<?php echo number_format($item['total'], 2); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="3"><strong>Grand Total:</strong></td>
            <td><strong>💲<?php echo number_format($grand_total, 2); ?></strong></td>
          </tr>
        </tfoot>
      </table>
    <?php endif; ?>

    <p style="margin-top:1.5rem; text-align:center;">
      <strong>Are you sure you want to place this order?</strong>
    </p>

    <div class="payment-actions" style="margin-top:1rem;">

      <form action="index.php?action=confirm_order" method="POST" style="display:inline;">
        <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
        <button type="submit" class="pay-btn">✅ Yes, Confirm Order</button>
      </form>

      <form action="index.php?action=cancel_order" method="POST" style="display:inline;">
        <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
        <button type="submit" class="continue-btn">❌ No, Cancel</button>
      </form>

    </div>

  </div>
</main>

<footer>
  <p>© 2026 🛍️ OSI-Shop Designed by (Bakhshi-Khaleghverdi-Eyvazi) Alpha-version</p>
</footer>

</body>
</html>