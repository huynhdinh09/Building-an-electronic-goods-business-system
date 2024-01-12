<?php
session_start();
include '../components/connect.php';

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Thống kê</title>
   <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
   <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
   <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
   <link rel="stylesheet" href="../css/admin_style.css">

</head>

<body>

   <?php include '../components/admin_header.php'; ?>

   <section class="dashboard">

      <h1 class="heading">thống kê</h1>

      <div>
         <p>
            <select class="select-date option-btn" style="width:220px;height:40px">
               <option value="365ngay">365 ngày qua</option>
               <option value="7ngay">7 ngày qua</option>
               <option value="28ngay">28 ngày qua</option>
               <option value="90ngay">90 ngày qua</option>

            </select>
         </p>
         <p class="btn">Thống kê đơn hàng theo: <span id="text-date"></span></p>

      </div>
      <div id="chart" style="height: 300px; background-color:white ;"></div>

      <script type="text/javascript">
         $(document).ready(function() {
            thongke();
            var char = new Morris.Area({
               element: 'chart',
               xkey: 'placed_on',
               ykeys: ['placed_on', 'total_price'],
               labels: ['Ngày đặt hàng', 'Doanh thu']
            });

            $('.select-date').change(function() {
               var thoigian = $(this).val();
               if (thoigian == '365ngay') {
                  var text = '365 ngày qua';
               } else if (thoigian == '28ngay') {
                  var text = '28 ngày qua';
               } else if (thoigian == '90ngay') {
                  var text = '90 ngày qua';
               } else if (thoigian == '7ngay') {
                  var text = '7 ngày qua';
               }

               $.ajax({
                  url: "thongke.php",
                  method: "POST",
                  dataType: "JSON",
                  data: {
                     thoigian: thoigian
                  },
                  success: function(data) {
                     char.setData(data);
                     $('#text-date').text(text);
                  }
               });
            })

            function thongke() {
               var text = '365 ngày qua';
               $('#text-date').text(text);
               $.ajax({
                  url: "thongke.php",
                  method: "POST",
                  dataType: "JSON",

                  success: function(data) {
                     char.setData(data);
                     $('#text-date').text(text);
                  }
               });
            }
         });
      </script>
      <div class="box-container ">
         <?php
         $select_profile = $conn->prepare("SELECT * FROM `admins` WHERE ad_id = ?");
         $select_profile->execute([$admin_id]);
         $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <div class="box">
            <h3>Chào mừng!</h3>
            <p><?= $fetch_profile['ad_name']; ?></p>
            <a href="../admin/profile.php?update=<?= $fetch_profile['ad_id']; ?>" class="btn">Cập nhật hồ sơ</a>
         </div>

         <div class="box">
            <?php
            $total_pendings = 0;
            $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
            $select_pendings->execute(['Đang xử lý']);
            if ($select_pendings->rowCount() > 0) {
               while ($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)) {
                  $total_pendings += $fetch_pendings['total_price'];
               }
            }
            ?>
            <h3><?= number_format($total_pendings); ?><span> VNĐ/-</span></h3>
            <p>Đang chờ xử lý </p>
            <a href="placed_orders.php" class="btn">xem đơn đặt hàng</a>
         </div>

         <div class="box">
            <?php
            $total_completes = 0;
            $select_completes = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
            $select_completes->execute(['Đã hoàn thành']);
            if ($select_completes->rowCount() > 0) {
               while ($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)) {
                  $total_completes += $fetch_completes['total_price'];
               }
            }
            ?>
            <h3><?= number_format($total_completes); ?><span> VNĐ/-</span></h3>
            <p>đơn hàng đã hoàn thành</p>
            <a href="placed_orders.php" class="btn">xem đơn đặt hàng</a>
         </div>

         <div class="box">
            <?php
            $select_orders = $conn->prepare("SELECT * FROM `orders`");
            $select_orders->execute();
            $number_of_orders = $select_orders->rowCount()
            ?>
            <h3><?= $number_of_orders; ?></h3>
            <p>Đặt hàng</p>
            <a href="placed_orders.php" class="btn">xem đơn đặt hàng</a>
         </div>

         <div class="box">
            <?php
            $select_products = $conn->prepare("SELECT * FROM `products`");
            $select_products->execute();
            $number_of_products = $select_products->rowCount()
            ?>
            <h3><?= $number_of_products; ?></h3>
            <p>Sản phẩm</p>
            <a href="products.php" class="btn">xem sản phẩm</a>
         </div>

         <div class="box">
            <?php
            $select_users = $conn->prepare("SELECT * FROM `users`");
            $select_users->execute();
            $number_of_users = $select_users->rowCount()
            ?>
            <h3><?= $number_of_users; ?></h3>
            <p>Tài khoản khách hàng</p>
            <a href="users_accounts.php" class="btn">xem chi tiết</a>
         </div>

         <div class="box">
            <?php
            $select_admins = $conn->prepare("SELECT * FROM `admins`");
            $select_admins->execute();
            $number_of_admins = $select_admins->rowCount()
            ?>
            <h3><?= $number_of_admins; ?></h3>
            <p>Tài khoản quản trị viên</p>
            <a href="admin_accounts.php" class="btn">xem chi tiết</a>
         </div>

         <div class="box">
            <?php
            $select_messages = $conn->prepare("SELECT * FROM `messages`");
            $select_messages->execute();
            $number_of_messages = $select_messages->rowCount()
            ?>
            <h3><?= $number_of_messages; ?></h3>
            <p>Tin nhắn</p>
            <a href="messagess.php" class="btn">xem tin nhắn</a>
         </div>

         <div class="box">
            <?php
            $select_comment = $conn->prepare("SELECT * FROM `comment`");
            $select_comment->execute();
            $number_of_comment = $select_comment->rowCount()
            ?>
            <h3><?= $number_of_comment; ?></h3>
            <p>Bình luận</p>
            <a href="admin_comment.php" class="btn">xem bình luận</a>
         </div>

      </div>

   </section>

   <script src="../js/admin_script.js"></script>

</body>

</html>