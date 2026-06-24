<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Product | OSI Admin</title>
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
  <h1>✏️ Edit Product</h1>
  <p><?php echo htmlspecialchars($product['Title']); ?></p>
</section>

<main class="account-single">

  <?php if (!empty($_SESSION['error'])): ?>
    <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
  <?php endif; ?>

  <section class="card single-card">
    <h2 class="card-title">Edit Product Details</h2>

    <?php if (!empty($product)): ?>
    <form class="form" action="index.php?action=admin_update_product&id=<?php echo $product['ProductID']; ?>" method="POST">

      <div class="row">
        <div>
          <label class="label" for="Title">Title</label>
          <input class="input" id="Title" name="Title" type="text" value="<?php echo htmlspecialchars($product['Title']); ?>" required>
        </div>
        <div>
          <label class="label" for="Price">Price</label>
          <input class="input" id="Price" name="Price" type="number" step="0.01" value="<?php echo htmlspecialchars($product['Price']); ?>" required>
        </div>
      </div>

      <div class="row">
        <div>
          <label class="label" for="SKU">SKU</label>
          <input class="input" id="SKU" name="SKU" type="text" value="<?php echo htmlspecialchars($product['SKU']); ?>" required>
        </div>
        <div>
          <label class="label" for="weight">Weight (kg)</label>
          <input class="input" id="weight" name="weight" type="number" step="0.01" value="<?php echo htmlspecialchars($product['weight']); ?>" required>
        </div>
      </div>

      <label class="label" for="image_url">Image URL</label>
      <input class="input" id="image_url" name="image_url" type="text" value="<?php echo htmlspecialchars($product['image_url']); ?>" required>

      <label class="label" for="Descriptions">Description</label>
      <textarea class="input textarea" id="Descriptions" name="Descriptions" required><?php echo htmlspecialchars($product['Descriptions']); ?></textarea>

      <div class="payment-actions" style="margin-top:1rem;">
        <a href="index.php?action=admin_products" class="continue-btn">← Cancel</a>
        <button type="submit" class="btn btn-primary">💾 Save Changes</button>
      </div>

    </form>
    <?php else: ?>
      <p>Product not found.</p>
    <?php endif; ?>

  </section>
</main>

<footer>
  <p>© 2026 🛍️ OSI-Shop Designed by (Bakhshi-Khaleghverdi-Eyvazi) Alpha-version</p>
</footer>

</body>
</html>