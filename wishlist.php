<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:user_login.php');
};

include 'components/wishlist_cart.php';

if(isset($_POST['delete'])){
   $wishlist_id = $_POST['wishlist_id'];
   $delete_wishlist_item = $conn->prepare("DELETE FROM `wishlist` WHERE wl_id = ?");
   $delete_wishlist_item->execute([$wishlist_id]);
}

if(isset($_GET['delete_all'])){
   $delete_wishlist_item = $conn->prepare("DELETE FROM `wishlist` WHERE user_id = ?");
   $delete_wishlist_item->execute([$user_id]);
   header('location:wishlist.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Danh sách yêu thích</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="products">

   <h3 class="heading">Danh sách yêu thích của bạn</h3>

   <div class="box-container">

   <?php
      $grand_total = 0;
      $select_wishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
      $select_wishlist->execute([$user_id]);
      if($select_wishlist->rowCount() > 0){
         while($fetch_wishlist = $select_wishlist->fetch(PDO::FETCH_ASSOC)){
            $grand_total += $fetch_wishlist['wl_price'] * $fetch_wishlist['products_quantity'];  
   ?>
   <form action="" method="post" class="box" style="height: 480px;">
      <input type="hidden" name="products_id" value="<?= $fetch_wishlist['products_id']; ?>">
      <input type="hidden" name="wishlist_id" value="<?= $fetch_wishlist['wl_id']; ?>">
      <input type="hidden" name="products_name" value="<?= $fetch_wishlist['wl_name']; ?>">
      <input type="hidden" name="products_price" value="<?= $fetch_wishlist['wl_price']; ?>">
      <input type="hidden" name="products_image" value="<?= $fetch_wishlist['wl_image']; ?>">
      <a href="quick_view.php?products_id=<?= $fetch_wishlist['products_id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_wishlist['wl_image']; ?>" alt="">
      <div class="name"><?= $fetch_wishlist['wl_name']; ?></div>
      <div class="flex">
         <div class="price"><?= number_format($fetch_wishlist['wl_price']); ?> VNĐ</div>
         <input type="number" name="products_quantity" class="qty" min="1" max="10" onkeypress="if(this.value.length == 2) return false;" value="1">
         
      </div>
      <div class="btn-addcart">
      <input type="submit" value="thêm vào giỏ hàng" class="btn" name="add_to_cart">
      <input type="submit" value="bỏ yêu thích" onclick="return confirm('xóa sản phẩm này khỏi danh sách yêu thích?');" class="delete-btn" name="delete">
      </div>
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">Bạn chưa thích sản phẩm nào</p>';
   }
   ?>
   </div>

   <div class="wishlist-total">
      <!-- <p>Tổng cộng : <span>$<?= $grand_total; ?>/-</span></p> -->
      <a href="shop.php" class="option-btn">Tiếp tục mua hàng</a>
      <a href="wishlist.php?delete_all" class="delete-btn <?= ($grand_total > 1)?'':'disabled'; ?>" onclick="return confirm('xóa hết danh sách yêu thích?');">Xóa tất cả</a>
   </div>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>