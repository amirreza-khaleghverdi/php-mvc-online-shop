<?php if (!isset($_SESSION)) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Courier Dashboard | OSI</title>
  <link rel="stylesheet" href="Templates/style.css">
</head>

<body>

<header class="header">
  <div class="logo">🛍️ OSI</div>
  <div class="search">
    <input type="text" placeholder="🔍 Search...">
    <button>🔎 Search</button>
  </div>
  <nav class="nav">
    <a href="index.php?action=courier_dashboard">🚚 My Shipments</a>
    <a href="index.php?action=logout">🚪 Logout</a>
  </nav>
</header>

<section class="hero hero--small">
  <h1>🚚 Courier Dashboard</h1>
  <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
</section>

<main>

  <?php if (!empty($_SESSION['error'])): ?>
    <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
  <?php elseif (!empty($_SESSION['message'])): ?>
    <p class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
  <?php endif; ?>

  <div class="cart-wrapper">
    <h2>📦 Assigned Shipments</h2>

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
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($shipments as $shipment): 
            $finalStatuses = ['delivered', 'failed_attempt'];
            $isFinal = in_array($shipment['StatusMessage'], $finalStatuses);

            $statusOrder = [
                'picked_up'         => 1,
                'in_transit'        => 2,
                'sorting_center'    => 3,
                'out_for_delivery'  => 4,
                'delivered'         => 5,
                'failed_attempt'    => 5
            ];

            $currentOrder = $statusOrder[$shipment['StatusMessage']] ?? 0;

            $allStatuses = [
                'picked_up', 'in_transit', 'sorting_center',
                'out_for_delivery', 'delivered', 'failed_attempt'
            ];

            $availableStatuses = array_filter($allStatuses, function($s) use ($statusOrder, $currentOrder) {
                return $statusOrder[$s] > $currentOrder;
            });
          ?>
          <tr>
            <td>#<?php echo htmlspecialchars($shipment['ShipmentID']); ?></td>
            <td>#<?php echo htmlspecialchars($shipment['OrderID']); ?></td>
            <td><?php echo htmlspecialchars($shipment['TrakingCode']); ?></td>
            <td><?php echo htmlspecialchars($shipment['ShippingDate']); ?></td>
            <td>
              <span class="status">
                <?php echo htmlspecialchars($shipment['StatusMessage'] ?? 'not set'); ?>
              </span>
            </td>
            <td><?php echo htmlspecialchars($shipment['Location'] ?? '-'); ?></td>
            <td>
              <?php if ($isFinal): ?>
                <span><strong>✅ Finalized</strong></span>
              <?php else: ?>
                <form action="index.php?action=update_shipment_status" method="POST">
                  <input type="hidden" name="shipment_id" value="<?php echo $shipment['ShipmentID']; ?>">

                  <select name="new_status" class="input" style="width:150px;">
                    <?php foreach ($availableStatuses as $status): ?>
                      <option value="<?php echo $status; ?>">
                        <?php echo ucfirst(str_replace('_', ' ', $status)); ?>
                      </option>
                    <?php endforeach; ?>
                  </select>

                  <input class="input" type="text" name="location"
                         placeholder="Current location" style="width:130px;" required>

                  <button type="submit" class="btn">🔄 Update</button>
                </form>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

    <?php else: ?>
      <p>No shipments assigned to you yet.</p>
    <?php endif; ?>

  </div>
</main>

<footer>
  <p>© 2026 🛍️ OSI-Shop Designed by (Bakhshi-Khaleghverdi-Eyvazi) Alpha-version</p>
</footer>

</body>
</html>