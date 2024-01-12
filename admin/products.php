<?php

include '../components/connect.php';

session_start();
//$products_id = $_REQUEST['products_id'];
$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
};

if (isset($_POST['add_product'])) {

   $products_name = $_POST['products_name'];
   $products_name = filter_var($products_name, FILTER_SANITIZE_STRING);
   $products_price = $_POST['products_price'];
   $products_price = filter_var($products_price, FILTER_SANITIZE_STRING);
   $products_details = $_POST['products_details'];
   $products_details = filter_var($products_details, FILTER_SANITIZE_STRING);
   $products_quantity = $_POST['products_quantity'];
   $products_quantity = filter_var($products_quantity, FILTER_SANITIZE_STRING);

   $image_01 = $_FILES['image_01']['name'];
   $image_01 = filter_var($image_01, FILTER_SANITIZE_STRING);
   $image_size_01 = $_FILES['image_01']['size'];
   $image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
   $image_folder_01 = '../uploaded_img/' . $image_01;

   $image_02 = $_FILES['image_02']['name'];
   $image_02 = filter_var($image_02, FILTER_SANITIZE_STRING);
   $image_size_02 = $_FILES['image_02']['size'];
   $image_tmp_name_02 = $_FILES['image_02']['tmp_name'];
   $image_folder_02 = '../uploaded_img/' . $image_02;

   $image_03 = $_FILES['image_03']['name'];
   $image_03 = filter_var($image_03, FILTER_SANITIZE_STRING);
   $image_size_03 = $_FILES['image_03']['size'];
   $image_tmp_name_03 = $_FILES['image_03']['tmp_name'];
   $image_folder_03 = '../uploaded_img/' . $image_03;


   $select_products = $conn->prepare("SELECT * FROM `products` WHERE products_name = ?");
   $select_products->execute([$products_name]);
   if ($select_products->rowCount() > 0) {
      $message[] = 'product name already exist!';
   } else {
      $insert_products = $conn->prepare("INSERT INTO `products`(products_name, products_details, products_price, products_quantity, image_01, image_02, image_03) VALUES(?,?,?,?,?,?,?)");
      $insert_products->execute([$products_name, $products_details, $products_price, $products_quantity, $image_01, $image_02, $image_03]);


      if ($insert_products) {
         if ($image_size_01 > 2000000 or $image_size_02 > 2000000 or $image_size_03 > 2000000) {
            $message[] = 'image size is too large!';
         } else {
            move_uploaded_file($image_tmp_name_01, $image_folder_01);
            move_uploaded_file($image_tmp_name_02, $image_folder_02);
            move_uploaded_file($image_tmp_name_03, $image_folder_03);
            $message[] = 'new product added!';
         }
      }
   }
}
if (isset($_GET['delete'])) {

   $delete_id = $_GET['delete'];
   $delete_product_image = $conn->prepare("SELECT * FROM `products` WHERE products_id = ?");
   $delete_product_image->execute([$delete_id]);
   $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_img/' . $fetch_delete_image['image_01']);
   unlink('../uploaded_img/' . $fetch_delete_image['image_02']);
   unlink('../uploaded_img/' . $fetch_delete_image['image_03']);
   $delete_product = $conn->prepare("DELETE FROM `products` WHERE products_id = ?");
   $delete_product->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE c_id = ?");
   $delete_cart->execute([$delete_id]);
   $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE wl_id = ?");
   $delete_wishlist->execute([$delete_id]);

   header('location:products.php');
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Sản phẩm</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>

<body>

   <?php include '../components/admin_header.php'; ?>

   <section class="add-products">

      <h1 class="heading">Thêm sản phẩm</h1>

      <form action="" method="post" enctype="multipart/form-data">
         <div class="flex">
            <div class="inputBox">
               <span>Tên sản phẩm</span>
               <input type="text" class="box" required maxlength="100" placeholder="nhập tên sản phẩm" name="products_name">
            </div>
            <div class="inputBox">
               <span>Giá sản phẩm</span>
               <input type="number" min="0" class="box" required max="9999999999" placeholder="Nhập giá sản phẩm" onkeypress="if(this.value.length == 10) return false;" name="products_price">
            </div>
            <div class="inputBox">
               <span>Số lượng</span>
               <input type="number" min="1" class="box" required max="99" placeholder="số lượng" onkeypress="if(this.value.length == 1) return false;" name="products_quantity">
            </div>
            <div class="inputBox">
               <span>Hình 01</span>
               <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
            </div>
            <div class="inputBox">
               <span>Hình 02</span>
               <input type="file" name="image_02" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
            </div>
            <div class="inputBox">
               <span>Hình 03</span>
               <input type="file" name="image_03" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
            </div>
            <div class="inputBox">
               <span>Thông tin chi tiết</span>
               <textarea name="products_details" placeholder="Nhập thông tin chi tiết" class="box" required maxlength="500" cols="30" rows="10"></textarea>
            </div>
         </div>

         <input type="submit" value="thêm sản phẩm" class="btn" name="add_product">
      </form>

   </section>
   <h1 class="heading">Sản Phẩm</h1>
   <section class="search-form">
      <form action="" method="post">
         <input type="text" name="search_box" placeholder="search here..." maxlength="100" class="box" required>
         <button type="submit" class="fas fa-search" name="search_btn"></button>
      </form>
   </section>
   <section class="show-products">

      
       <div class="box-container">
         <?php
         if (isset($_POST['search_box']) or isset($_POST['search_btn'])) {
            $search_box = $_POST['search_box'];
            $select_products = $conn->prepare("SELECT * FROM `products` WHERE products_name LIKE '%{$search_box}%' order by products_id desc");
            $select_products->execute();
            if ($select_products->rowCount() > 0) {
               while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
         ?>
                  <div class="box">
                     <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
                     <div class="products_name"><?= $fetch_products['products_name']; ?></div>
                     <div class="products_price"><span><?= number_format($fetch_products['products_price']); ?></span> VNĐ</div>

                     <div class="products_quantity">Số lượng: <span><?= $fetch_products['products_quantity']; ?></span></div>

                     <div class="products_details"><span><?= $fetch_products['products_details']; ?></span></div>
                     <div class="flex-btn">
                        <a href="update_product.php?update=<?= $fetch_products['products_id']; ?>" class="option-btn">cập nhật</a>
                        <a href="products.php?delete=<?= $fetch_products['products_id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">xóa</a>
                     </div>
                  </div>
               <?php
               }
            }
         } else {
            $select_products = $conn->prepare("SELECT * FROM `products` order by products_id desc");
            $select_products->execute();
            if ($select_products->rowCount() > 0) {
               while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
               ?>
                  <div class="box">
                     <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
                     <div class="products_name"><?= $fetch_products['products_name']; ?></div>
                     <div class="products_price"><span><?= number_format($fetch_products['products_price']); ?></span> VNĐ</div>

                     <div class="products_quantity">Số lượng: <span><?= $fetch_products['products_quantity']; ?></span></div>

                     <div class="products_details"><span><?= $fetch_products['products_details']; ?></span></div>
                     <div class="flex-btn">
                        <a href="update_product.php?update=<?= $fetch_products['products_id']; ?>" class="option-btn">cập nhật</a>
                        <a href="products.php?delete=<?= $fetch_products['products_id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">xóa</a>
                     </div>
                  </div>
         <?php
               }
            }
         }
         ?>
         </div>

   </section>

   <script src="../js/admin_script.js"></script>

</body>

</html>