<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <title>Shopping Cart | OSI</title>
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
    <a href="index.php">🏠 Home</a>
    <a href="index.php">🛒 Products</a>
    <a href="index.php?action=dashboard">Dashboard</a>
  </nav>
</header>

<section class="hero hero--small">
  <h1>🧺 Shopping Cart</h1>
  <p>Review your items</p>
</section>

<main class="cart-page">
  <div class="cart-wrapper">

    <?php if (!empty($_SESSION['error'])): ?>
      <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php elseif (!empty($_SESSION['message'])): ?>
      <p class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
    <?php endif; ?>

    <?php if (!empty($cart_items)): ?>

        <form action="index.php?action=update_cart" method="POST">
        <table class="cart-table">
          <thead>
            <tr>
              <th>Product</th>
              <th>Price</th>
              <th>Quantity</th>
              <th>Total</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php 
              $grandTotal = 0;
              foreach ($cart_items as $item): 
                $grandTotal += $item['total'];
            ?>
            <tr>
              <td><?php echo htmlspecialchars($item['Title']); ?></td>
              <td>💲<?php echo number_format($item['Price'], 2); ?></td>
              <td>
                <input type="number" name="quantities[<?php echo $item['id']; ?>]"
                       value="<?php echo $item['quantity']; ?>" min="1" class="input" style="width:60px;">
              </td>
              <td>💲<?php echo number_format($item['total'], 2); ?></td>
              <td>
              <a href="index.php?action=remove_item&cart_item_id=<?php echo $item['id']; ?>" class="btn">🗑️ Remove</a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="3"><strong>Grand Total:</strong></td>
              <td><strong>💲<?php echo number_format($grandTotal, 2); ?></strong></td>
              <td></td>
            </tr>
          </tfoot>
        </table>

        <div class="payment-actions">
          <button type="submit" class="btn">🔄 Update Cart</button>
          <a href="index.php?action=homepage" class="continue-btn">← Continue Shopping</a>
          <a href="index.php?action=show_checkout" class="pay-btn">💳 Pay Now</a>
        </div>

      </form>

    <?php else: ?>
      <p>Your cart is empty. <a href="index.php?action=homepage">Start shopping!</a></p>
    <?php endif; ?>

  </div>
</main>

<footer>
  <p>© 2026 🛍️ OSI-Shop Designed by (Bakhshi-Khaleghverdi-Eyvazi) Alpha-version</p>
</footer>

</body>
</html>