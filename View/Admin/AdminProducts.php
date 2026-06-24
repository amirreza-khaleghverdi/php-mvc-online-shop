<?php if (!isset($_SESSION)) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Manage Products | OSI Admin</title>
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
  <h1>📦 Manage Products</h1>
  <p>Add, edit or remove products</p>
</section>

<main>

  <?php if (!empty($_SESSION['error'])): ?>
    <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
  <?php elseif (!empty($_SESSION['message'])): ?>
    <p class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
  <?php endif; ?>

  <div class="cart-wrapper">

    <!-- Add New Product -->
    <h2 class="card-title">➕ Add New Product</h2>

    <form class="form" action="index.php?action=admin_add_product" method="POST">

      <div class="row">
        <div>
          <label class="label" for="Title">Title</label>
          <input class="input" id="Title" name="Title" type="text" placeholder="Product name" required>
        </div>
        <div>
          <label class="label" for="Price">Price</label>
          <input class="input" id="Price" name="Price" type="number" step="0.01" placeholder="0.00" required>
        </div>
      </div>

      <div class="row">
        <div>
          <label class="label" for="SKU">SKU</label>
          <input class="input" id="SKU" name="SKU" type="text" placeholder="Unique SKU code" required>
        </div>
        <div>
          <label class="label" for="weight">Weight (kg)</label>
          <input class="input" id="weight" name="weight" type="number" step="0.01" placeholder="0.00" required>
        </div>
      </div>

      <label class="label" for="image_url">Image URL</label>
      <input class="input" id="image_url" name="image_url" type="text" placeholder="https://..." required>

      <label class="label" for="Descriptions">Description</label>
      <textarea class="input textarea" id="Descriptions" name="Descriptions" placeholder="Product description..." required></textarea>

      <button class="btn btn-primary" type="submit">➕ Add Product</button>

    </form>

    <!-- Products List -->
    <h2 class="card-title" style="margin-top:2rem;">📋 All Products</h2>

    <?php if (!empty($products)): ?>
      <table class="cart-table">
        <thead>
          <tr>
            <th>Image</th>
            <th>Title</th>
            <th>Price</th>
            <th>SKU</th>
            <th>Weight</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($products as $product): ?>
            <tr>
              <td>
                <img src="<?php echo htmlspecialchars($product['image_url']); ?>"
                     alt="<?php echo htmlspecialchars($product['Title']); ?>"
                     style="width:50px; height:50px; object-fit:cover; border-radius:6px;">
              </td>
              <td><?php echo htmlspecialchars($product['Title']); ?></td>
              <td>💲<?php echo number_format($product['Price'], 2); ?></td>
              <td><?php echo htmlspecialchars($product['SKU']); ?></td>
              <td><?php echo htmlspecialchars($product['weight']); ?> kg</td>
              <td>
                <a href="index.php?action=admin_edit_product&id=<?php echo $product['ProductID']; ?>" class="btn">✏️ Edit</a>
                <a href="index.php?action=admin_delete_product&id=<?php echo $product['ProductID']; ?>" class="btn">🗑️ Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No products found.</p>
    <?php endif; ?>

  </div>
</main>

<footer>
  <p>© 2026 🛍️ OSI-Shop Designed by (Bakhshi-Khaleghverdi-Eyvazi) Alpha-version</p>
</footer>

</body>
</html>