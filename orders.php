<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
};

if (isset($_GET['delete'])) {
   
   $delete_id = $_GET['delete'];
   $payment_status = 'Hủy đơn hàng';
   $payment_status = filter_var($payment_status, FILTER_SANITIZE_STRING);
   $update_payment = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE order_id = ?");
   $update_payment->execute([$payment_status,$delete_id]);
   $message[] = 'payment status updated!';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'components/user_header.php'; ?>

   <section class="orders">

      <h1 class="heading">Đơn hàng</h1>

      <div class="box-container">

         <?php
         if ($user_id == '') {
            echo '<p class="empty">please login to see your orders</p>';
         } else {
            $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ? order by placed_on desc");
            $select_orders->execute([$user_id]);
            if ($select_orders->rowCount() > 0) {
               while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
         ?>
                  <div class="box">
                     <?php
                     if ($fetch_orders['payment_status'] == 'Đang xử lý') {
                     ?>
                        <p>Ngày đặt hàng : <span><?= date('d-m-Y', strtotime($fetch_orders['placed_on'])); ?></span></p>
                        <p>Tên khách hàng : <span><?= $fetch_orders['order_name']; ?></span></p>
                        <p>email : <span><?= $fetch_orders['order_email']; ?></span></p>
                        <p>Số điện thoại : <span><?= $fetch_orders['order_number']; ?></span></p>
                        <p>Địa chỉ : <span><?= $fetch_orders['order_address']; ?></span></p>
                        <p>Phương thức thanh toán : <span><?= $fetch_orders['order_method']; ?></span></p>
                        <p>Tổng sản phẩm : <span><?= $fetch_orders['total_products']; ?></span></p>
                        <p>Tổng tiền : <span><?= number_format($fetch_orders['total_price']); ?> VNĐ</span></p>
                        <p>Tình trạng đơn hàng : <span style="color:<?php if ($fetch_orders['payment_status'] == 'Đang xử lý') {
                                                                        echo 'blue';
                                                                     } elseif ($fetch_orders['payment_status'] == 'Hủy đơn hàng') {
                                                                        echo 'red';
                                                                     } elseif ($fetch_orders['payment_status'] == 'Đang giao hàng') {
                                                                        echo 'orange';
                                                                     } else {
                                                                        echo 'green';
                                                                     }; ?>"><?= $fetch_orders['payment_status']; ?>
                           </span>
                           <a href="orders.php?delete=<?= $fetch_orders['order_id']; ?>" class="delete-btn"  onclick="return confirm('Xóa đơn hàng?');">Hủy đơn hàng</a>
                        </p>
                     <?php } else {
                     ?>
                        <p>Ngày đặt hàng : <span><?= date('d-m-Y', strtotime($fetch_orders['placed_on'])); ?></span></p>
                        <p>Tên khách hàng : <span><?= $fetch_orders['order_name']; ?></span></p>
                        <p>email : <span><?= $fetch_orders['order_email']; ?></span></p>
                        <p>Số điện thoại : <span><?= $fetch_orders['order_number']; ?></span></p>
                        <p>Địa chỉ : <span><?= $fetch_orders['order_address']; ?></span></p>
                        <p>Phương thức thanh toán : <span><?= $fetch_orders['order_method']; ?></span></p>
                        <p>Tổng sản phẩm : <span><?= $fetch_orders['total_products']; ?></span></p>
                        <p>Tổng tiền : <span><?= number_format($fetch_orders['total_price']); ?> VNĐ</span></p>
                        <p>Tình trạng đơn hàng : <span style="color:<?php if ($fetch_orders['payment_status'] == 'Đang xử lý') {
                                                                        echo 'blue';
                                                                     } elseif ($fetch_orders['payment_status'] == 'Hủy đơn hàng') {
                                                                        echo 'red';
                                                                     } elseif ($fetch_orders['payment_status'] == 'Đang giao hàng') {
                                                                        echo 'orange';
                                                                     } else {
                                                                        echo 'green';
                                                                     }; ?>"><?= $fetch_orders['payment_status']; ?>
                           </span>
                        </p>
                     <?php
                     } ?>
                  </div>
         <?php
               }
            } else {
               echo '<p class="empty">Chưa có đơn hàng nào được đặt!</p>';
            }
         }
         ?>

      </div>

   </section>

   <?php include 'components/footer.php'; ?>

   <script src="js/script.js"></script>

</body>

</html>