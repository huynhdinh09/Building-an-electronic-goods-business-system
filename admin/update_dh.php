<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

if (isset($_POST['update'])) {

   $order_id = $_POST['order_id'];
   $order_name = $_POST['order_name'];
   $order_name = filter_var($order_name, FILTER_SANITIZE_STRING);
   $order_address = $_POST['order_address'];
   $order_address = filter_var($order_address, FILTER_SANITIZE_STRING);
   $order_email = $_POST['order_email'];
   $order_email = filter_var($order_email, FILTER_SANITIZE_STRING);
   $order_number = $_POST['order_number'];
   $order_number = filter_var($order_number, FILTER_SANITIZE_STRING);
   $total_price = $_POST['total_price'];
   $total_price = filter_var($total_price, FILTER_SANITIZE_STRING);
   $placed_on = $_POST['placed_on'];
   $placed_on = filter_var($placed_on, FILTER_SANITIZE_STRING);

   $update_order = $conn->prepare("UPDATE `orders` SET order_name = ?, order_address = ?, order_email = ?,order_number = ?, total_price = ?,placed_on = ? WHERE order_id = ?");
   $update_order->execute([$order_name, $order_address, $order_email, $order_number, $total_price,$placed_on, $order_id]);
 

   $message[] = 'order updated successfully!';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Cập nhật đơn hàng</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>

<body>

   <?php include '../components/admin_header.php'; ?>

   <section class="update-order">

      <h1 class="heading">Cập nhật đơn hàng</h1>

      <?php
      $update_id = $_GET['update'];
      $select_order = $conn->prepare("SELECT * FROM `orders` WHERE order_id = ?");
      $select_order->execute([$update_id]);
      if ($select_order->rowCount() > 0) {
         while ($fetch_order = $select_order->fetch(PDO::FETCH_ASSOC)) {
         
         
      ?>
                  <form action="" method="post" enctype="multipart/form-data">
                     <input type="hidden" name="order_id" value="<?= $fetch_order['order_id']; ?>">
                     <span>Tên khách hàng</span>
                     <input type="text" name="order_name" required class="box" maxlength="100" placeholder="cập nhật tên khách hàng" value="<?= $fetch_order['order_name']; ?>">
                     <span>Địa chỉ</span>
                     <input type="text" name="order_address" required class="box" maxlength="100" placeholder="cập nhật địa chỉ" value="<?= $fetch_order['order_address']; ?>">
                     <span>Email</span>
                     <input type="text" name="order_email" required class="box" maxlength="100" placeholder="cập nhật email" value="<?= $fetch_order['order_email']; ?>">
                     <span>Số điện thoại</span>
                     <input type="text" name="order_number" required class="box" maxlength="100" placeholder="cập nhật số điện thoại" value="<?= $fetch_order['order_number']; ?>">
                     <span>Tổng tiền</span>
                     <input type="number" name="total_price" required class="box" min="0" max="9999999999" placeholder="cập nhật  giá tiền" onkeypress="if(this.valuse.length == 10) return false;" value="<?= $fetch_order['total_price']; ?>">
                     <span>Ngày đặt hàng</span>
                     <input type="date" name="placed_on" required class="box" min="0" max="99" placeholder="cập nhật ngày đặt hàng" value="<?= $fetch_order['placed_on']; ?>">
                     <div class="flex-btn">
                        <input type="submit" name="update" class="btn" value="Cập nhật">
                        <a href="placed_orders.php" class="option-btn">Trở lại</a>
                     </div>
                  </form>

      <?php
               }
          
      } else {
         echo '<p class="empty">Không tìm thấy đơn hàng!</p>';
      }
      ?>

   </section>
   <script src="../js/admin_script.js"></script>

</body>

</html>