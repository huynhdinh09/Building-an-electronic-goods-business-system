<?php
if (isset($message)) {
   foreach ($message as $message) {
      echo '
         <div class="message">
            <span>' . $message . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
   }
}
?>

<header class="header">

   <section class="flex">

      <a href="home.php" style="text-decoration: none;" class="logo">HuynhDinh<span>Store</span></a>

      <nav class="navbar">
         <a style="text-decoration: none;" href="home.php">Trang chủ</a>
         <a style="text-decoration: none;" href="about.php">Giới thiệu</a>
         <a style="text-decoration: none;" href="orders.php">Đơn hàng</a>
         <a style="text-decoration: none;" href="shop.php">Cửa hàng</a>
         <a style="text-decoration: none;" href="contact.php">Gớp ý</a>
      </nav>

      <div class="icons">
         <?php
         $count_wishlist_items = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
         $count_wishlist_items->execute([$user_id]);
         $total_wishlist_counts = $count_wishlist_items->rowCount();

         $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $count_cart_items->execute([$user_id]);
         $total_cart_counts = $count_cart_items->rowCount();
         ?>
         <div id="menu-btn" class="fas fa-bars"></div>
         <a href="search_page.php"><i class="fas fa-search"></i></a>
         <a style="text-decoration: none;" href="wishlist.php"><i class="fas fa-heart"></i><span>(<?= $total_wishlist_counts; ?>)</span></a>
         <a style="text-decoration: none;" href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $total_cart_counts; ?>)</span></a>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="profile">
         <?php
         $select_profile = $conn->prepare("SELECT * FROM `users` WHERE u_id = ?");
         $select_profile->execute([$user_id]);
         if ($select_profile->rowCount() > 0) {
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
            <p><?= $fetch_profile["u_name"]; ?></p>
            <a href="profile_user.php?update=<?= $fetch_profile['u_id']; ?>" class="btn btn-success" style="text-decoration: none; font-size: 1.7rem;">Cập nhật</a>
            <div class="flex-btn">
               <a style="text-decoration: none;" href="user_register.php" class="option-btn">Đăng ký</a>
               <a style="text-decoration: none;" href="user_login.php" class="option-btn">Đăng nhập</a>
            </div>
            <a style="text-decoration: none;" href="components/user_logout.php" class="delete-btn" onclick="return confirm('logout from the website?');">Đăng xuất</a>
         <?php
         } else {
         ?>
            <p>Khách hàng vui long đăng nhập hoặc đăng ký!</p>
            <div class="flex-btn">
               <a href="user_register.php" class="option-btn">Đăng ký</a>
               <a href="user_login.php" class="option-btn">Đăng nhập</a>
            </div>
         <?php
         }
         ?>


      </div>

   </section>

</header>