<?php
   if(isset($message)){
      foreach($message as $message){
         echo '
         <div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
?>

<header class="header">

   <section class="flex">

      <a href="../admin/dashboard.php" class="logo" style="text-decoration: none;">Admin<span>HDS</span></a>

      <nav class="navbar" style="text-decoration: none;">
         <a style="text-decoration: none;" href="../admin/dashboard.php">thống kê</a>
         <!-- <a style="text-decoration: none;" href="../admin/nhacungcap.php">nhà cung cấp</a> -->
         <a style="text-decoration: none;" href="../admin/products.php">sản phẩm</a>
         <a style="text-decoration: none;" href="../admin/placed_orders.php">đặt hàng</a>
         <a style="text-decoration: none;" href="../admin/admin_accounts.php">quản trị viên</a>
         <a style="text-decoration: none;" href="../admin/users_accounts.php">khách hàng</a>
         <a style="text-decoration: none;" href="../admin/messages.php">tin nhắn</a>
         <a style="text-decoration: none;" href="../admin/admin_comment.php">bình luận</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `admins` WHERE ad_id = ?");
            $select_profile->execute([$admin_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <p><?= $fetch_profile['ad_name']; ?></p>
         <a href="../admin/profile.php?update=<?= $fetch_profile['ad_id']; ?>" class="btn btn-success "style="text-decoration: none; font-size: 1.7rem;">Cập nhật thông tin</a>
         <div class="flex-btn">
            <?php if($admin_id==1){ ?>
            <a href="../admin/register_admin.php" class="option-btn"style="text-decoration: none;">Đăng ký</a>
            <?php } ?>
            <a href="../admin/admin_login.php" class="option-btn"style="text-decoration: none;">Đăng nhập</a>
         </div>
         <a href="../components/admin_logout.php" class="delete-btn" style="text-decoration: none;" onclick="return confirm('logout from the website?');">đăng xuất</a> 
      </div>

   </section>

</header>