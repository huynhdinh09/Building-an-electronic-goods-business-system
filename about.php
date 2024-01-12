<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
};

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Giới thiệu</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'components/user_header.php'; ?>

   <section class="about">

      <div class="row">

         <div class="image">
            <img src="images/aboutusss.png" alt="">
         </div>

         <div class="content">
            <h3 style="color: white;">Thông tin về HuynhDinhStore</h3>
            <p style="color: white;">HuynhDinhStore specializes in trading all kinds of technology products,
               because these products are used more and more by people.
               With the family tradition of trading small and medium technology products,
               HuynhDinhStore appeared. In the era of technological development,
               this business will help business people earn a lot of income.
               People have more choices to trust when shopping.</p>
            <p style="color: white;">HuynhDinhStore chuyên kinh doanh các loại sản phẩm công nghệ,
               vì các sản phẩm này ngày càng được mọi người sử dụng nhiều hơn.
               Với truyền thống gia đình kinh doanh sản phẩm công nghệ vừa và nhỏ,
               nên mới có sự xuất hiện của HuynhDinhStore. Trong thời đại công nghệ phát triển,
               việc kinh doanh này sẽ giúp người kinh doanh kiếm được nhiều thu nhập.
               Mọi người có thêm sự lựa chọn đang tin cậy để mua sắm.</p>

            <a href="contact.php" class="btn">Liên hệ chúng tôi</a>
         </div>

      </div>

   </section>

   <section class="reviews">

      <h1 class="heading">Nhân viên tiêu biểu</h1>

      <div class="swiper reviews-slider">

         <div class="swiper-wrapper">
            <?php

            $select_admin = $conn->prepare("SELECT * FROM `admins`");
            $select_admin->execute();
            if ($select_admin->rowCount() > 0) {
               while ($fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC)) {


            ?>
                  <div class="swiper-slide slide">
                     <div>
                        <img src="images/<?= $fetch_admin['ad_image']; ?>" alt="">
                     </div>
                     <p><?= $fetch_admin['ad_about']; ?></p>
                     <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                     </div>
                     <h3><?= $fetch_admin['ad_name']; ?></h3>
                  </div>

            <?php
               }
            } else {
               echo '';
            }
            ?>

            <!-- <div class="swiper-slide slide">
         <img src="images/kimduong33.jpg" alt="">
         <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quia tempore distinctio hic, iusto adipisci a rerum nemo perspiciatis fugiat sapiente.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>john deo</h3>
      </div>

      <div class="swiper-slide slide">
         <img src="images/tuancuong.png" alt="">
         <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quia tempore distinctio hic, iusto adipisci a rerum nemo perspiciatis fugiat sapiente.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>john deo</h3>
      </div>

       <div class="swiper-slide slide">
         <img src="images/duongduc.jpg" alt="">
         <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quia tempore distinctio hic, iusto adipisci a rerum nemo perspiciatis fugiat sapiente.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>john deo</h3>
      </div> -->

            <!-- <div class="swiper-slide slide">
         <img src="images/pic-5.png" alt="">
         <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quia tempore distinctio hic, iusto adipisci a rerum nemo perspiciatis fugiat sapiente.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>john deo</h3>
      </div>

      <div class="swiper-slide slide">
         <img src="images/pic-6.png" alt="">
         <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quia tempore distinctio hic, iusto adipisci a rerum nemo perspiciatis fugiat sapiente.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>john deo</h3>
      </div> -->

         </div>

         <div class="swiper-pagination"></div>

      </div>

   </section>









   <?php include 'components/footer.php'; ?>

   <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

   <script src="js/script.js"></script>

   <script>
      var swiper = new Swiper(".reviews-slider", {
         loop: true,
         spaceBetween: 20,
         pagination: {
            el: ".swiper-pagination",
            clickable: true,
         },
         breakpoints: {
            0: {
               slidesPerView: 1,
            },
            768: {
               slidesPerView: 2,
            },
            991: {
               slidesPerView: 3,
            },
         },
      });
   </script>

</body>

</html>