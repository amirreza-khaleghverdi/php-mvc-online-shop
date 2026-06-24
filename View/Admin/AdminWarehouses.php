<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Manage Warehouses | OSI Admin</title>
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
  <h1>🏭 Manage Warehouses</h1>
  <p>Add and edit warehouse locations</p>
</section>

<main>

  <?php if (!empty($_SESSION['error'])): ?>
    <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
  <?php elseif (!empty($_SESSION['message'])): ?>
    <p class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
  <?php endif; ?>

  <div class="cart-wrapper">

    <!-- Add New Warehouse -->
    <h2 class="card-title">➕ Add New Warehouse</h2>
    <form class="form" action="index.php?action=admin_add_warehouse" method="POST">

      <div class="row">
        <div>
          <label class="label" for="Name">Name</label>
          <input class="input" type="text" id="Name" name="Name" placeholder="Warehouse name" required>
        </div>
        <div>
          <label class="label" for="Capacity">Capacity</label>
          <input class="input" type="number" id="Capacity" name="Capacity" placeholder="Max capacity" min="1" required>
        </div>
      </div>

      <label class="label" for="Address">Address</label>
      <input class="input" type="text" id="Address" name="Address" placeholder="Full address" required>

      <button class="btn btn-primary" type="submit">➕ Add Warehouse</button>

    </form>

    <!-- Warehouses List -->
    <h2 class="card-title" style="margin-top:2rem;">📋 All Warehouses</h2>

    <?php if (!empty($warehouses)): ?>
      <table class="cart-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Address</th>
            <th>Capacity</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($warehouses as $warehouse): ?>
            <tr>
              <form action="index.php?action=admin_update_warehouse&id=<?php echo $warehouse['WarehouseID']; ?>" method="POST">
                <td>#<?php echo htmlspecialchars($warehouse['WarehouseID']); ?></td>
                <td>
                  <input class="input" type="text" name="Name"
                         value="<?php echo htmlspecialchars($warehouse['Name']); ?>" required>
                </td>
                <td>
                  <input class="input" type="text" name="Address"
                         value="<?php echo htmlspecialchars($warehouse['Address']); ?>" required>
                </td>
                <td>
                  <input class="input" type="number" name="Capacity"
                         value="<?php echo htmlspecialchars($warehouse['Capacity']); ?>"
                         min="1" required style="width:80px;">
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
      <p>No warehouses found.</p>
    <?php endif; ?>

  </div>
</main>

<footer>
  <p>© 2026 🛍️ OSI-Shop Designed by (Bakhshi-Khaleghverdi-Eyvazi) Alpha-version</p>
</footer>

</body>
</html>