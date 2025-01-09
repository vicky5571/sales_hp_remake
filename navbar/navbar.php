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
  $firstName = $_SESSION['first_name'] ?? 'Vicky';
  $lastName = $_SESSION['last_name'] ?? 'Galih';
  $email = $_SESSION['email'] ?? 'example@example.com';
  $userRole = $_SESSION['user_role'] ?? null; // Default to null if not set

  // Get the current script name (e.g., index.php)
  $currentPage = basename($_SERVER['PHP_SELF']);

  // Map active page names to display text
  $pageNames = [
    'index.php' => 'Home',
    'categories.php' => 'Categories',
    'products.php' => 'Products',
    'suppliers.php' => 'Suppliers',
    'product_unit.php' => 'Product Unit',
    'carts.php' => 'Carts',
    'users.php' => 'Users',
  ];

  // Get the display name for the current page
  $activePageName = $pageNames[$currentPage] ?? 'Page';
  ?>



  <div class="navbar-wrapper">
    <nav class="navbar">
      <div class="data-container">
        <!-- <img src="https://i.ibb.co/kGbqdSB/NEW-2.png" class="Logo" /> -->

        <img src="img/jv8-light.jpg" class="Logo" style="border-radius: 50%;" />
        <!-- Add the big text showing the active page -->
        <div class="active-page-title">
          <p style="font-size: 3rem; padding-left: 20px; font-weight: bolder; margin-right: 20px; !important"> <?= htmlspecialchars($activePageName); ?></p>
        </div>

        <div id="menu-time" class="menu-data animate__animated" style="margin-left:300px">
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
            <a href="transactions.php" class="<?= $currentPage === 'transactions.php' ? 'active' : '' ?>">Transactions</a>
          <?php endif; ?>

          |

        </div>
        <!-- Profile Dropdown -->
        <div class="profile-container">
          <i class="fa-solid fa-user ms-3" onclick="toggleDropdown()"></i>
          <div class="profile-dropdown" id="profileDropdown">
            <p><strong><?= htmlspecialchars($firstName) . ' ' . htmlspecialchars($lastName); ?></strong></p>
            <p><?= htmlspecialchars($email); ?></p>
            <p><?= htmlspecialchars($userRole); ?></p>
            <form action="logout.php" method="post" style="margin-top: 10px;">
              <button type="submit" style="
      background-color: #ff4d4d; 
      color: white; 
      border: none; 
      padding: 8px 12px; 
      border-radius: 4px; 
      cursor: pointer;
      font-size: 0.9rem;">Logout</button>
            </form>
          </div>
        </div>
        <a id="Menu-bar" onclick="menubar()"><i class="size-icon fa-solid fa-bars"></i></a>
      </div>

    </nav>
  </div>
  <script src="https://kit.fontawesome.com/7103fc097b.js" crossorigin="anonymous"></script>
  <script src="navbarScript.js"></script>
  <script>
    function toggleDropdown() {
      const dropdown = document.getElementById('profileDropdown');
      dropdown.classList.toggle('show');
    }
  </script>
</body>

</html>