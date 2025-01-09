<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="navbarStyle.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <title>navbar</title>
</head>

<body>

  <?php
  // Ensure session is started
  if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
  }
  // Example: Assume USER_ROLE is stored in the session
  $userRole = $_SESSION['USER_ROLE'] ?? null; // Default to null if not set
  // Get the current script name (e.g., index.php)
  $currentPage = basename($_SERVER['PHP_SELF']);
  ?>

  <div class="navbar-wrapper">
    <nav class="navbar">
      <div class="data-container">
        <!-- <img src="https://i.ibb.co/kGbqdSB/NEW-2.png" class="Logo" /> -->
        <div id="menu-time" class="menu-data animate__animated">
          <a onclick="closebar()"><i id="close" class="size fa-solid fa-xmark"></i></a>
          <a href="index.php" class="<?= $currentPage === 'index.php' ? 'active' : '' ?>">Home</a>
          <a href="categories.php" class="<?= $currentPage === 'categories.php' ? 'active' : '' ?>">Categories</a>
          <a href="products.php" class="<?= $currentPage === 'products.php' ? 'active' : '' ?>">Products</a>
          <a href="suppliers.php" class="<?= $currentPage === 'suppliers.php' ? 'active' : '' ?>">Suppliers</a>
          <a href="product_unit.php" class="<?= $currentPage === 'product_unit.php' ? 'active' : '' ?>">Product Unit</a>
          <a href="carts.php" class="<?= $currentPage === 'carts.php' ? 'active' : '' ?>">Carts</a>

          <!-- Conditionally Render Users Link -->
          <?php if ($userRole !== 'KARYAWAN'): ?>
            <a href="users.php" class="<?= $currentPage === 'users.php' ? 'active' : '' ?>">Users</a>
          <?php endif; ?>

        </div>
        <a id="Menu-bar" onclick="menubar()"><i class="size-icon fa-solid fa-bars"></i></a>
      </div>
    </nav>
  </div>
  <script src="https://kit.fontawesome.com/7103fc097b.js" crossorigin="anonymous"></script>
  <script src="navbarScript.js"></script>
</body>

</html>