<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:user_login.php');
};

if (isset($_POST['delete'])) {
   $c_id = $_POST['c_id'];
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE c_id = ?");
   $delete_cart_item->execute([$c_id]);
}

if (isset($_GET['delete_all'])) {
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart_item->execute([$user_id]);
   header('location:cart.php');
}

if (isset($_POST['update_qty'])) {
   $c_id = $_POST['c_id'];
   $c_quantity = $_POST['products_quantity'];
   $c_quantity = filter_var($c_quantity, FILTER_SANITIZE_STRING);
   $update_qty = $conn->prepare("UPDATE `cart` SET c_quantity = ? WHERE c_id = ?");
   $update_qty->execute([$c_quantity, $c_id]);
   $message[] = 'cart quantity updated';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Giỏ hàng</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'components/user_header.php'; ?>

   <section class="products shopping-cart">

      <h3 class="heading">Giỏ hàng</h3>

      <div class="box-container cart">

         <?php
         $grand_total = 0;
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if ($select_cart->rowCount() > 0) {
            while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
         ?>
               <form action="" method="post" class="box" style="height: 490px;">
                  <input type="hidden" name="c_id" value="<?= $fetch_cart['c_id']; ?>">
                  <a href="quick_view.php?products_id=<?= $fetch_cart['products_id']; ?>" class="fas fa-eye"></a>
                  <img src="uploaded_img/<?= $fetch_cart['c_image']; ?>" alt="">
                  <div class="name"><?= $fetch_cart['c_name']; ?></div>
                  <div class="flex">
                     <div class="price"><?= number_format($fetch_cart['c_price']); ?> VNĐ</div>
                     <div class="update_qty">
                        <input type="number" name="products_quantity" class="qty" min="1" max="10" onkeypress="if(this.value.length == 2) return false;" value="<?= $fetch_cart['c_quantity']; ?>">
                        <button type="submit" class="fas fa-edit" name="update_qty"></button>
                     </div>
                  </div>
                  <div class="btn-addcart">
                     <div class="sub-total"> Tổng tiền : <span><?= number_format($sub_total = ($fetch_cart['c_price'] * $fetch_cart['c_quantity'])); ?> VNĐ</span> </div>
                     <input type="submit" value="Xóa khỏi giỏ hàng" onclick="return confirm('delete this from cart?');" class="delete-btn" name="delete">
                  </div>
               </form>
         <?php
               $grand_total += $sub_total;
            }
         } else {
            echo '<p class="empty">your cart is empty</p>';
         }
         ?>
      </div>

      <div class="cart-total">
         <p>Tổng cộng : <span><?=number_format( $grand_total); ?> VNĐ</span></p>
         <a href="shop.php" class="option-btn">tiếp tục mua hàng</a>
         <a href="cart.php?delete_all" class="delete-btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>" onclick="return confirm('delete all from cart?');">xóa tất cả</a>
         <a href="checkout.php" class="btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>">Tiếp tục thanh toán</a>
      </div>

   </section>

   <?php include 'components/footer.php'; ?>

   <script src="js/script.js"></script>

</body>

</html>