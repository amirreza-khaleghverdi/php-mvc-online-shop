<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Manage Couriers | OSI Admin</title>
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
  <h1>🚚 Manage Couriers</h1>
  <p>Edit or remove courier accounts</p>
</section>

<main>

  <?php if (!empty($_SESSION['error'])): ?>
    <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
  <?php elseif (!empty($_SESSION['message'])): ?>
    <p class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
  <?php endif; ?>

  <div class="cart-wrapper">

    <h2 class="card-title">📋 All Couriers</h2>

    <?php if (!empty($couriers)): ?>
      <table class="cart-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Contact Number</th>
            <th>National Code</th>
            <th>Vehicle Type</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($couriers as $courier): ?>
            <tr>
              <form action="index.php?action=admin_update_courier&id=<?php echo $courier['CourierID']; ?>" method="POST">
                <td>#<?php echo htmlspecialchars($courier['CourierID']); ?></td>
                <td><input class="input" type="text" name="FirstName" value="<?php echo htmlspecialchars($courier['FirstName']); ?>" required></td>
                <td><input class="input" type="text" name="LastName" value="<?php echo htmlspecialchars($courier['LastName']); ?>" required></td>
                <td><input class="input" type="text" name="ContactNumber" value="<?php echo htmlspecialchars($courier['ContactNumber']); ?>" required></td>
                <td><input class="input" type="text" name="NationalCode" value="<?php echo htmlspecialchars($courier['NationalCode'] ?? ''); ?>" required></td>
                <td>
                  <select class="input select" name="VehicleType" required>
                    <option value="motorcycle" <?php echo $courier['VehicleType'] === 'motorcycle' ? 'selected' : ''; ?>>🏍️ Motorcycle</option>
                    <option value="car" <?php echo $courier['VehicleType'] === 'car' ? 'selected' : ''; ?>>🚗 Car</option>
                    <option value="van" <?php echo $courier['VehicleType'] === 'van' ? 'selected' : ''; ?>>🚐 Van</option>
                    <option value="truck" <?php echo $courier['VehicleType'] === 'truck' ? 'selected' : ''; ?>>🚚 Truck</option>
                  </select>
                </td>
                <td>
                  <button type="submit" class="btn">💾 Save</button>
              </form>
                  <a href="index.php?action=admin_delete_courier&id=<?php echo $courier['CourierID']; ?>" class="btn">🗑️ Delete</a>
                </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No couriers found.</p>
    <?php endif; ?>

  </div>
</main>

<footer>
  <p>© 2026 🛍️ OSI-Shop Designed by (Bakhshi-Khaleghverdi-Eyvazi) Alpha-version</p>
</footer>

</body>
</html>