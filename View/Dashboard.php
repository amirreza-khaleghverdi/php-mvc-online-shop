<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard | OSI</title>
  <link rel="stylesheet" href="Templates/style.css">
  <style>
    .dashboard-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }
    .stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 20px;
      margin-bottom: 40px;
    }
    .stats .card {
      background: rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      padding: 20px;
      text-align: center;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
      transition: 0.3s;
    }
    .stats .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
    }
    .stats .card h3 {
      font-size: 18px;
      margin-bottom: 10px;
      font-weight: 600;
    }
    .stats .card p {
      font-size: 28px;
      font-weight: 700;
      color: #00d4ff;
    }
    .section-box {
      background: rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      padding: 20px;
      margin-bottom: 30px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }
    .section-box h3 {
      font-size: 22px;
      margin-bottom: 20px;
      border-left: 4px solid #00d4ff;
      padding-left: 15px;
    }
    .cart-table {
      width: 100%;
      border-collapse: collapse;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 16px;
      overflow: hidden;
      color: white;
    }
    .cart-table th {
      background: rgba(0, 0, 0, 0.35);
      padding: 12px;
      font-weight: 700;
    }
    .cart-table td {
      padding: 12px;
      text-align: center;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    .cart-table tr:hover {
      background: rgba(0, 212, 255, 0.15);
    }
    .btn {
      background: #00d4ff;
      border: none;
      padding: 8px 18px;
      border-radius: 20px;
      font-weight: 600;
      cursor: pointer;
      transition: 0.3s;
      text-decoration: none;
      color: black;
      display: inline-block;
      font-size: 14px;
    }
    .btn:hover {
      background: white;
      transform: translateY(-2px);
    }
    .error, .message {
      text-align: center;
      margin-bottom: 20px;
    }
    ul {
      list-style: none;
      padding: 0;
    }
    ul li {
      background: rgba(255, 255, 255, 0.05);
      margin-bottom: 8px;
      padding: 10px 15px;
      border-radius: 12px;
      font-size: 14px;
    }
  </style>
</head>
<body>

<header class="header">
  <div class="logo">🛍️ OSI</div>
  <div class="search">
    <input type="text" placeholder="🔍 Search products...">
    <button>🔎 Search</button>
  </div>
  <nav class="nav">
    <a href="index.php?action=homepage">🏠 Home</a>
    <a href="index.php?action=homepage">🛒 Products</a>
    <a href="index.php?action=cart">🧺 Cart</a>
    <a href="index.php?action=logout">🚪 Logout</a>
  </nav>
</header>

<section class="hero hero--small">
  <h1>👤 Dashboard</h1>
  <p>Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
</section>

<main>
  <div class="dashboard-container">

    <?php if (!empty($_SESSION['error'])): ?>
      <p class="error" style="background:#ff4d4d; color:white; padding:8px 15px; border-radius:30px; display:inline-block; width:100%; text-align:center;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php elseif (!empty($_SESSION['message'])): ?>
      <p class="message" style="background:#00d4ff; color:black; padding:8px 15px; border-radius:30px; display:inline-block; width:100%; text-align:center;"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
    <?php endif; ?>

    <!-- Stats Cards -->
    <section class="stats">
      <div class="card">
        <h3>Total Orders</h3>
        <p><?php echo $order_stats['total_orders']; ?></p>
      </div>
      <div class="card">
        <h3>✅ Completed</h3>
        <p><?php echo $order_stats[
          'completed']; ?></p>
      </div>
      <div class="card">
        <h3>⏳ Pending</h3>
        <p><?php echo $order_stats['pending']; ?></p>
      </div>
      <div class="card">
        <h3>❌ Cancelled</h3>
        <p><?php echo $order_stats['cancelled']; ?></p>
      </div>
    </section>

    <!-- Orders Table -->
    <section class="section-box">
      <h3>📦 Latest Orders</h3>
      <table class="cart-table">
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Total</th>
            <th>Status</th>
            <th>Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($orders)): ?>
            <?php foreach ($orders as $order): ?>
              <tr>
                <td>#<?php echo htmlspecialchars($order['id']); ?></td>
                <td>💲<?php echo htmlspecialchars($order['total']); ?></td>
                <td><?php echo htmlspecialchars($order['status']); ?></td>
                <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                <td>
                  <a href="index.php?action=view_order_details&order_id=<?php echo $order['id']; ?>" class="btn">
                    👁️ View
                  </a>
                 </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5">No orders found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </section>

    <!-- Notifications -->
    <section class="section-box">
      <h3>🔔 Notifications</h3>
      <?php if (!empty($notifications)): ?>
        <ul>
          <?php foreach ($notifications as $note): ?>
            <li><?php echo htmlspecialchars($note); ?></li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p>No new notifications.</p>
      <?php endif; ?>
    </section>

  </div>
</main>

<footer>
  <p>©️ 2026 🛍️ OSI-Shop Designed by (Bakhshi-Khaleghverdi-Eyvazi) Alpha-version</p>
</footer>

</body>
</html>