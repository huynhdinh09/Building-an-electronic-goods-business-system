<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
};

include 'components/wishlist_cart.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>cửa hàng</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'components/user_header.php'; ?>

   <section class="products">

      <h1 class="heading">Sản phảm</h1>
      <section class="search-form" style="width:1060px; margin-left:50px">
         <form action="" method="post">
            <input type="text"  name="search_box" placeholder="Tìm kiếm..." maxlength="100" class="box" required>
            <button type="submit" class="fas fa-search" name="search_btn"></button>
         </form>
      </section>

      <div class="box-container">
         <?php
         if (isset($_POST['search_box']) or isset($_POST['search_btn'])) {
            $search_box = $_POST['search_box'];
            $select_products = $conn->prepare("SELECT * FROM `products` WHERE products_name LIKE '%{$search_box}%' order by products_id desc");
            $select_products->execute();
            if ($select_products->rowCount() > 0) {
               while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
         ?>
                  <form action="" method="post" class="box">
                     <input type="hidden" name="products_id" value="<?= $fetch_product['products_id']; ?>">
                     <input type="hidden" name="products_name" value="<?= $fetch_product['products_name']; ?>">
                     <input type="hidden" name="products_price" value="<?= $fetch_product['products_price']; ?>">
                     <input type="hidden" name="products_image" value="<?= $fetch_product['image_01']; ?>">
                     <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
                     <a href="quick_view.php?products_id=<?= $fetch_product['products_id']; ?>" class="fas fa-eye"></a>
                     <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
                     <div class="name"><?= $fetch_product['products_name']; ?></div>
                     <div class="flex">
                        <div class="price"><?= number_format($fetch_product['products_price']); ?><span> VNĐ</span></div>
                        <input type="number" name="products_quantity" class="qty" min="1" max="10" onkeypress="if(this.value.length == 2) return false;" value="1">
                     </div>
                     <div class="btn-addcart">
                        <input type="submit" value="thêm vào giỏ hàng" class="btn" name="add_to_cart">
                     </div>
                  </form>
               <?php
               }
            }
         } else {
            $select_products = $conn->prepare("SELECT * FROM `products`");
            $select_products->execute();
            if ($select_products->rowCount() > 0) {
               while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
               ?>
                  <form action="" method="post" class="box">
                     <input type="hidden" name="products_id" value="<?= $fetch_product['products_id']; ?>">
                     <input type="hidden" name="products_name" value="<?= $fetch_product['products_name']; ?>">
                     <input type="hidden" name="products_price" value="<?= $fetch_product['products_price']; ?>">
                     <input type="hidden" name="products_image" value="<?= $fetch_product['image_01']; ?>">
                     <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
                     <a href="quick_view.php?products_id=<?= $fetch_product['products_id']; ?>" class="fas fa-eye"></a>
                     <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
                     <div class="name"><?= $fetch_product['products_name']; ?></div>
                     <div class="flex">
                        <div class="price"><?= number_format($fetch_product['products_price']); ?><span> VNĐ</span></div>
                        <input type="number" name="products_quantity" class="qty" min="1" max="10" onkeypress="if(this.value.length == 2) return false;" value="1">
                     </div>
                     <div class="btn-addcart">
                        <input type="submit" value="thêm vào giỏ hàng" class="btn" name="add_to_cart">
                     </div>
                  </form>
         <?php
               }
            }
         }
         ?>

      </div>

   </section>

   <?php include 'components/footer.php'; ?>

   <script src="js/script.js"></script>

</body>

</html>