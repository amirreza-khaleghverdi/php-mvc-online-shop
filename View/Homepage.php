<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OSI - shop</title>

  <link rel="stylesheet" href="Templates/style.css">
  <style>

  .product img {
      width: 100%;
      height: 220px;
      object-fit: cover;
      border-radius: 8px;
      display: block;
  }

    .error {
      background: #ff4d4d;
      color: white;
      padding: 8px 15px;
      border-radius: 30px;
      display: inline-block;
      margin-top: 15px;
      font-weight: bold;
    }
    .message {
      background: #00d4ff;
      color: black;
      padding: 8px 15px;
      border-radius: 30px;
      display: inline-block;
      margin-top: 15px;
      font-weight: bold;
    }

    .view-link {
      display: inline-block;
      margin-top: 8px;
      font-size: 13px;
      color: #ccc;
      text-decoration: none;
      transition: 0.3s;
    }
    .view-link:hover {
      color: #00d4ff;
      text-decoration: underline;
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
    <a href="index.php">🏠 Home</a>
    <a href="index.php?action=cart">🛒 Cart</a>
    <?php if (isset($_SESSION['username'])): ?>
      <a href="index.php?action=dashboard">📊 Dashboard</a>
      <a href="index.php?action=logout">🚪 Logout</a>
    <?php else: ?>
      <a href="index.php?action=login">🔐 Login</a>
      <a href="index.php?action=register">📝 Register</a>
    <?php endif; ?>
  </nav>
</header>

<section class="hero">
  <h1>🛍️ OurShopIust</h1>
  <p>✨ Best Products in OSI-Shop</p>
  <?php if (isset($_SESSION['username'])): ?>
    <h2 style="font-size: 1.2rem; margin-top: 10px;">Wellcome <?php echo htmlspecialchars($_SESSION['username']); ?> 🙌</h2>
  <?php endif; ?>
  <?php if (!empty($_SESSION['error'])): ?>
    <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
  <?php elseif (!empty($_SESSION['message'])): ?>
    <p class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
  <?php endif; ?>
</section>

<section class="products">
  <?php if (!empty($products)):
    foreach ($products as $product): ?>
      <div class="product">
        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['Title']); ?>">
        <h3><?php echo htmlspecialchars($product['Title']); ?></h3>
        <p class="price">💲<?php echo number_format($product['Price'], 2); ?></p>
        <form action="index.php?action=add_to_cart" method="post">
          <input type="hidden" name="product_id" value="<?php echo $product['ProductID']; ?>">
          <button type="submit">🛒 Add to Cart</button>
        </form>
        <a href="index.php?action=view&product_id=<?php echo $product['ProductID']; ?>" class="view-link">View details</a>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p style="text-align: center; grid-column: 1/-1;">there isnt product</p>
  <?php endif; ?>
</section>

<footer>
  <p>©️ 2026 🛍️ OSI-Shop Designed by (Bakhshi-Khaleghverdi-Eyvazi) Alpha-version</p>
</footer>

</body>
</html>