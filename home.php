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
   <title>Trang chủ</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'components/user_header.php'; ?>

   <div class="home-bg">

      <section class="home">

         <div class="swiper home-slider">

            <div class="swiper-wrapper">

               <div class="swiper-slide slide">
                  <div class="image">
                     <img src="images/iPhone 14-128gb1.png" alt="">
                  </div>
                  <div class="content">
                     <span>Lên đến 50%</span>
                     <h3>điện thoại</h3>
                     <a href="shop.php" class="btn">Cửa hàng</a>
                  </div>
               </div>

               <div class="swiper-slide slide">
                  <div class="image">
                     <img src="images/home-img-2.png" alt="">
                  </div>
                  <div class="content">
                     <span>Lên đến 50%</span>
                     <h3>Laptop</h3>
                     <a href="shop.php" class="btn">Cửa hàng</a>
                  </div>
               </div>

               <div class="swiper-slide slide">
                  <div class="image">
                     <img src="images/tainghe-sony-inzone1.png" alt="">
                  </div>
                  <div class="content">
                     <span>Lên đến 50%</span>
                     <h3>Tai nghe</h3>
                     <a href="shop.php" class="btn">Cửa hàng</a>
                  </div>
               </div>

            </div>

            <div class="swiper-pagination"></div>

         </div>

      </section>

   </div>

   <section class="category">

      <h1 class="heading">Danh mục sản phẩm</h1>

      <div class="swiper category-slider">

         <div class="swiper-wrapper">

            <a href="category.php?category=laptop" class="swiper-slide slide">
               <img src="images/icon-1.png" alt="">
               <h3>Laptop</h3>
            </a>

            <a href="category.php?category=tivi" class="swiper-slide slide">
               <img src="images/icon-2.png" alt="">
               <h3>Tivi</h3>
            </a>

            <a href="category.php?category=chuột" class="swiper-slide slide">
               <img src="images/icon-4.png" alt="">
               <h3>Chuột</h3>
            </a>

            <a href="category.php?category=smartphone" class="swiper-slide slide">
               <img src="images/icon-7.png" alt="">
               <h3>Smartphone</h3>
            </a>

            <a href="category.php?category=keyboard" class="swiper-slide slide">
               <img src="images/icon-9.png" alt="">
               <h3>Bàn phím</h3>
            </a>

            <a href="category.php?category=tai nghe" class="swiper-slide slide">
               <img src="images/icon-8.png" alt="">
               <h3>Tai nghe</h3>
            </a>

         </div>

         <div class="swiper-pagination"></div>

      </div>

   </section>

   <section class="home-products">

      <h1 class="heading">Sản phẩm mới nhất</h1>

      <div class="swiper products-slider">

         <div class="swiper-wrapper">

            <?php
            $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6");
            $select_products->execute();
            if ($select_products->rowCount() > 0) {
               while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
            ?>
                  <form action="" method="post" class="swiper-slide slide">
                     <input type="hidden" name="products_id" value="<?= $fetch_product['products_id']; ?>">
                     <input type="hidden" name="products_name" value="<?= $fetch_product['products_name']; ?>">
                     <input type="hidden" name="products_price" value="<?= $fetch_product['products_price']; ?>">
                     <input type="hidden" name="products_image" value="<?= $fetch_product['image_01']; ?>">
                     <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
                     <a href="quick_view.php?products_id=<?= $fetch_product['products_id']; ?>" class="fas fa-eye"></a>
                     <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
                     <div class="product-form">
                        <div class="name"><?= $fetch_product['products_name']; ?></div>
                        <div class="flex">
                           <div class="price"><?=number_format($fetch_product['products_price']); ?><span> VNĐ</span></div>
                           <input type="number" name="products_quantity" class="qty" min="1" max="10" onkeypress="if(this.value.length == 2) return false;" value="1">
                        </div>
                        <div class="btn-addcart">
                           <input type="submit" value="thêm vào giỏ hàng" class="btn" name="add_to_cart">
                        </div>
                     </div>
                  </form>
            <?php
               }
            } else {
               echo '<p class="empty">no products added yet!</p>';
            }
            ?>

         </div>

         <div class="swiper-pagination"></div>

      </div>

   </section>

   <?php include 'components/footer.php'; ?>

   <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

   <script src="js/script.js"></script>

   <script>
      var swiper = new Swiper(".home-slider", {
         loop: true,
         spaceBetween: 20,
         pagination: {
            el: ".swiper-pagination",
            clickable: true,
         },
      });

      var swiper = new Swiper(".category-slider", {
         loop: true,
         spaceBetween: 20,
         pagination: {
            el: ".swiper-pagination",
            clickable: true,
         },
         breakpoints: {
            0: {
               slidesPerView: 2,
            },
            650: {
               slidesPerView: 3,
            },
            768: {
               slidesPerView: 4,
            },
            1024: {
               slidesPerView: 5,
            },
         },
      });

      var swiper = new Swiper(".products-slider", {
         loop: true,
         spaceBetween: 20,
         pagination: {
            el: ".swiper-pagination",
            clickable: true,
         },
         breakpoints: {
            550: {
               slidesPerView: 2,
            },
            768: {
               slidesPerView: 2,
            },
            1024: {
               slidesPerView: 3,
            },
         },
      });
   </script>

</body>

</html>