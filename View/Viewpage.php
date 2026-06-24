<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?php echo htmlspecialchars($product['Title']); ?> | OSI</title>
  <link rel="stylesheet" href="Templates/style.css">
  <style>
    .detail-product {
      max-width: 450px;
      margin: 2rem auto;
    }
    .detail-product img {
      width: 100%;
      max-height: 300px;
      object-fit: cover;
      border-radius: 15px;
      margin-bottom: 15px;
    }
    .detail-product .btn {
      display: inline-block;
      margin: 8px 5px;
      background: #00d4ff;
      border: none;
      padding: 10px 20px;
      border-radius: 20px;
      font-weight: 600;
      cursor: pointer;
      transition: 0.3s;
      text-decoration: none;
      color: black;
    }
    .detail-product .btn:hover {
      background: white;
    }
    .detail-product .price {
      font-size: 28px;
      margin: 15px 0;
    }
    .error, .message {
      text-align: center;
      margin-top: 1rem;
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
    <a href="index.php?action=cart">🧺 Cart</a>
    <?php if (isset($_SESSION['username'])): ?>
      <a href="index.php?action=dashboard">👤 <?php echo htmlspecialchars($_SESSION['username']); ?></a>
      <a href="index.php?action=logout">🚪 Logout</a>
    <?php else: ?>
      <a href="index.php?action=login">👤 Login</a>
      <a href="index.php?action=register">📝 Register</a>
    <?php endif; ?>
  </nav>
</header>

<section class="hero hero--small">
  <h1><?php echo htmlspecialchars($product['Title']); ?></h1>
  <p>✨ Product Details</p>
</section>

<main>
  <?php if (!empty($_SESSION['error'])): ?>
    <p class="error" style="background:#ff4d4d; color:white; padding:8px 15px; border-radius:30px; display:inline-block; margin:1rem auto; text-align:center;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
  <?php elseif (!empty($_SESSION['message'])): ?>
    <p class="message" style="background:#00d4ff; color:black; padding:8px 15px; border-radius:30px; display:inline-block; margin:1rem auto; text-align:center;"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
  <?php endif; ?>

  <?php if (!empty($product)): ?>
    <div class="product detail-product">
      <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['Title']); ?>">
      <h3><?php echo htmlspecialchars($product['Title']); ?></h3>
      <p class="price">💲<?php echo number_format($product['Price'], 2); ?></p>
      <form action="index.php?action=add_to_cart" method="POST" style="display:inline;">
        <input type="hidden" name="product_id" value="<?php echo $product['ProductID']; ?>">
        <button type="submit" class="btn">🛒 Add to Cart</button>
      </form>
      <a href="index.php?action=homepage" class="btn">⬅️ Back to Shop</a>
    </div>
  <?php else: ?>
    <p style="text-align:center;">No product found.</p>
  <?php endif; ?>
</main>

<footer>
  <p>©️ 2026 🛍️ OSI-Shop Designed by (Bakhshi-Khaleghverdi-Eyvazi) Alpha-version</p>
</footer>

</body>
</html>