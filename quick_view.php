<?php

include 'components/connect.php';
include 'components/cmt.php';

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
   <title>quick view</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'components/user_header.php'; ?>

   <section class="quick-view">

      <h1 class="heading">Chi tiết sản phẩm</h1>

      <?php
      $products_id = $_GET['products_id'];
      $select_products = $conn->prepare("SELECT * FROM `products` WHERE products_id = ?");
      $select_products->execute([$products_id]);
      if ($select_products->rowCount() > 0) {
         while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
      ?>

            <form action="" method="post" class="box">
               <input type="hidden" name="products_id" value="<?= $fetch_product['products_id']; ?>">
               <input type="hidden" name="products_name" value="<?= $fetch_product['products_name']; ?>">
               <input type="hidden" name="products_price" value="<?= $fetch_product['products_price']; ?>">
               <input type="hidden" name="products_image" value="<?= $fetch_product['image_01']; ?>">
               <div class="row">
                  <div class="image-container">
                     <div class="main-image">
                        <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
                     </div>
                     <div class="sub-image">
                        <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
                        <img src="uploaded_img/<?= $fetch_product['image_02']; ?>" alt="">
                        <img src="uploaded_img/<?= $fetch_product['image_03']; ?>" alt="">
                     </div>
                  </div>
                  <div class="content">
                     <div class="name"><?= $fetch_product['products_name']; ?></div>
                     <div class="flex">
                        <div class="price"><?=number_format($fetch_product['products_price']); ?><span> VNĐ</span></div>
                        <input type="number" name="products_quantity" class="qty" min="1" max="10" onkeypress="if(this.value.length == 2) return false;" value="1">
                     </div>
                     <div class="details"><?= $fetch_product['products_details']; ?></div>

                     <div class="flex-btn">
                        <input type="submit" value="thêm vào giỏ hàng" class="btn" name="add_to_cart">
                        <input class="option-btn" type="submit" name="add_to_wishlist" value="yêu thích">
                     </div>
                  </div>
               </div>

            </form>

            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
            <script>
               $(document).ready(function() {
                  $("#binhluan").load("comment.php", {
                     products_id: <?= $products_id ?>
                  });
               });
            </script>
            <div class="row1" id="binhluan">

            </div>
      <?php
         }
      } else {
         echo '<p class="empty">no products added yet!</p>';
      }
      ?>

   </section>


   <?php include 'components/footer.php'; ?>

   <script src="js/script.js"></script>

</body>

</html>