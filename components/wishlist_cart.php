<?php

if(isset($_POST['add_to_wishlist'])){

   if($user_id == ''){
      header('location:user_login.php');
   }else{

      $products_id = $_POST['products_id'];
      $products_id = filter_var($products_id, FILTER_SANITIZE_STRING);
      $products_name = $_POST['products_name'];
      $products_name = filter_var($products_name, FILTER_SANITIZE_STRING);
      $products_price = $_POST['products_price'];
      $products_price = filter_var($products_price, FILTER_SANITIZE_STRING);
      $products_image = $_POST['products_image'];
      $products_image = filter_var($products_image, FILTER_SANITIZE_STRING);
      $products_quantity = $_POST['products_quantity'];
      $products_quantity = filter_var($products_quantity, FILTER_SANITIZE_STRING);

      $check_wishlist_numbers = $conn->prepare("SELECT * FROM `users` WHERE u_name = ? AND u_id = ?");
      $check_wishlist_numbers->execute([$products_name, $user_id]);

      $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE c_name = ? AND user_id = ?");
      $check_cart_numbers->execute([$products_name, $user_id]);

      if($check_wishlist_numbers->rowCount() > 0){
         $message[] = 'already added to wishlist!';
      }elseif($check_cart_numbers->rowCount() > 0){
         $message[] = 'already added to cart!';
      }else{
         $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(user_id, products_id, wl_name, wl_price, wl_image, products_quantity) VALUES(?,?,?,?,?,?)");
         $insert_wishlist->execute([$user_id, $products_id, $products_name, $products_price, $products_image, $products_quantity]);
         $message[] = 'added to wishlist!';
      }

   }

}

if(isset($_POST['add_to_cart'])){

   if($user_id == ''){
      header('location:user_login.php');
   }else{

      $products_id = $_POST['products_id'];
      $products_id = filter_var($products_id, FILTER_SANITIZE_STRING);
      $wl_name = $_POST['products_name'];
      $wl_name = filter_var($wl_name, FILTER_SANITIZE_STRING);
      $wl_price = $_POST['products_price'];
      $wl_price = filter_var($wl_price, FILTER_SANITIZE_STRING);
      $wl_image = $_POST['products_image'];
      $wl_image = filter_var($wl_image, FILTER_SANITIZE_STRING);
      $products_quantity = $_POST['products_quantity'];
      $products_quantity = filter_var($products_quantity, FILTER_SANITIZE_STRING);

      $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE c_name = ? AND user_id = ?");
      $check_cart_numbers->execute([$wl_name, $user_id]);

      if($check_cart_numbers->rowCount() > 0){
         $message[] = 'already added to cart!';
      }else{

         $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE wl_name = ? AND user_id = ?");
         $check_wishlist_numbers->execute([$wl_name, $user_id]);

         if($check_wishlist_numbers->rowCount() > 0){
            $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE wl_name = ? AND user_id = ?");
            $delete_wishlist->execute([$wl_name, $user_id]);
         }

         $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, products_id, c_name, c_price, c_quantity, c_image) VALUES(?,?,?,?,?,?)");
         $insert_cart->execute([$user_id, $products_id, $wl_name, $wl_price, $products_quantity, $wl_image]);
         $message[] = 'added to cart!';
         
      }

   }

}

?>