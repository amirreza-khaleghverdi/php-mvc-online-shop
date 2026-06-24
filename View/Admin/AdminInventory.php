<?php if (!isset($_SESSION)) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Manage Inventory | OSI Admin</title>
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
  <h1>📋 Manage Inventory</h1>
  <p>Update stock levels per warehouse</p>
</section>

<main>

  <?php if (!empty($_SESSION['error'])): ?>
    <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
  <?php elseif (!empty($_SESSION['message'])): ?>
    <p class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
  <?php endif; ?>

  <div class="cart-wrapper">

    <h2 class="card-title">📋 All Inventory</h2>

    <?php if (!empty($inventory)): ?>
      <table class="cart-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Product</th>
            <th>Warehouse</th>
            <th>Available Stock</th>
            <th>Reserved Stock</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($inventory as $item): ?>
            <tr>
              <form action="index.php?action=admin_update_inventory&id=<?php echo $item['InventoryID']; ?>" method="POST">
                <td>#<?php echo htmlspecialchars($item['InventoryID']); ?></td>
                <td><?php echo htmlspecialchars($item['Title']); ?></td>
                <td><?php echo htmlspecialchars($item['Warehouse']); ?></td>
                <td>
                  <input class="input" type="number" name="AvailableStock"
                         value="<?php echo htmlspecialchars($item['AvailableStock']); ?>"
                         min="0" required style="width:80px;">
                </td>
                <td>
                  <input class="input" type="number" name="ReservedStock"
                         value="<?php echo htmlspecialchars($item['ReservedStock']); ?>"
                         min="0" required style="width:80px;">
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
      <p>No inventory found.</p>
    <?php endif; ?>

  </div>
</main>

<footer>
  <p>© 2026 🛍️ OSI-Shop Designed by (Bakhshi-Khaleghverdi-Eyvazi) Alpha-version</p>
</footer>

</body>
</html>