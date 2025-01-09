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
  <div class="navbar-wrapper">
    <nav class="navbar">
      <div class="data-container">
        <!-- <img src="https://i.ibb.co/kGbqdSB/NEW-2.png" class="Logo" /> -->
        <div id="menu-time" class="menu-data animate__animated">
          <a onclick="closebar()"><i id="close" class="size fa-solid fa-xmark"></i></a>
          <a href="index.php">Home</a>
          <a href="categories.php">Categories</a>
          <a href="products.php">Products</a>
          <a href="suppliers.php">Suppliers</a>
          <a href="product_unit.php">Product Unit</a>
          <a href="carts.php">Carts</a>
        </div>
        <a id="Menu-bar" onclick="menubar()"><i class="size-icon fa-solid fa-bars"></i></a>
      </div>
    </nav>
  </div>
  <script src="https://kit.fontawesome.com/7103fc097b.js" crossorigin="anonymous"></script>
  <script src="navbarScript.js"></script>
</body>

</html>