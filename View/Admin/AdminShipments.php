<?php if (!isset($_SESSION)) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Manage Shipments | OSI Admin</title>
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
  <h1>📦 Manage Shipments</h1>
  <p>View, add, assign and update shipments</p>
</section>

<main>

  <?php if (!empty($_SESSION['error'])): ?>
    <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
  <?php elseif (!empty($_SESSION['message'])): ?>
    <p class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
  <?php endif; ?>

  <div class="cart-wrapper">

    <!-- Add New Shipment -->
    <h2 class="card-title">➕ Add New Shipment</h2>
    <form class="form" action="index.php?action=admin_add_shipment" method="POST">

      <div class="row">
        <div>
          <label class="label" for="order_id">Order ID</label>
          <input class="input" type="number" id="order_id" name="order_id" placeholder="Order ID" required>
        </div>
        <div>
          <label class="label" for="tracking_code">Tracking Code</label>
          <input class="input" type="text" id="tracking_code" name="tracking_code" placeholder="TRK123456" required>
        </div>
      </div>

      <label class="label" for="courier_id">Assign Courier</label>
      <select class="input select" id="courier_id" name="courier_id" required>
        <option value="" disabled selected>Select a courier</option>
        <?php if (!empty($couriers)): ?>
          <?php foreach ($couriers as $courier): ?>
            <option value="<?php echo $courier['CourierID']; ?>">
              <?php echo htmlspecialchars($courier['FirstName'] . ' ' . $courier['LastName']); ?>
              — <?php echo htmlspecialchars($courier['VehicleType']); ?>
            </option>
          <?php endforeach; ?>
        <?php endif; ?>
      </select>

      <button class="btn btn-primary" type="submit">➕ Add Shipment</button>

    </form>

    <!-- Shipments List -->
    <h2 class="card-title" style="margin-top:2rem;">📋 All Shipments</h2>

    <?php if (!empty($shipments)): ?>
      <table class="cart-table">
        <thead>
          <tr>
            <th>Shipment ID</th>
            <th>Order ID</th>
            <th>Tracking Code</th>
            <th>Shipping Date</th>
            <th>Current Status</th>
            <th>Location</th>
            <th>Assign Courier</th>
            <th>Update Status</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($shipments as $shipment): ?>
            <tr>
              <td>#<?php echo htmlspecialchars($shipment['ShipmentID']); ?></td>
              <td>#<?php echo htmlspecialchars($shipment['OrderID']); ?></td>
              <td><?php echo htmlspecialchars($shipment['TrakingCode']); ?></td>
              <td><?php echo htmlspecialchars($shipment['ShippingDate']); ?></td>
              <td><?php echo htmlspecialchars($shipment['StatusMessage'] ?? 'not set'); ?></td>
              <td><?php echo htmlspecialchars($shipment['Location'] ?? '-'); ?></td>

              <!-- Assign Courier -->
              <td>
                <form action="index.php?action=admin_assign_courier&id=<?php echo $shipment['ShipmentID']; ?>" method="POST">
                  <select class="input select" name="courier_id" required>
                    <?php if (!empty($couriers)): ?>
                      <?php foreach ($couriers as $courier): ?>
                        <option value="<?php echo $courier['CourierID']; ?>"
                          <?php echo $courier['CourierID'] == $shipment['CourierID'] ? 'selected' : ''; ?>>
                          <?php echo htmlspecialchars($courier['FirstName'] . ' ' . $courier['LastName']); ?>
                        </option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                  <button type="submit" class="btn">💾 Assign</button>
                </form>
              </td>

              <!-- Update Status -->
              <td>
                <form action="index.php?action=admin_update_shipment_status" method="POST">
                  <input type="hidden" name="shipment_id" value="<?php echo $shipment['ShipmentID']; ?>">
                  <select name="new_status" class="input" style="width:140px;">
                    <option value="picked_up">📦 Picked Up</option>
                    <option value="in_transit">🚚 In Transit</option>
                    <option value="sorting_center">🏭 Sorting Center</option>
                    <option value="out_for_delivery">🛵 Out for Delivery</option>
                    <option value="delivered">✅ Delivered</option>
                    <option value="failed_attempt">❌ Failed Attempt</option>
                  </select>
                  <input class="input" type="text" name="location"
                         placeholder="Location" style="width:120px;" required>
                  <button type="submit" class="btn">🔄 Update</button>
                </form>
              </td>

            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No shipments found.</p>
    <?php endif; ?>

  </div>
</main>

<footer>
  <p>© 2026 🛍️ OSI-Shop Designed by (Bakhshi-Khaleghverdi-Eyvazi) Alpha-version</p>
</footer>

</body>
</html>