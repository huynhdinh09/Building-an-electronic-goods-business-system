<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
};

if (isset($_POST['send'])) {

   $u_name = $_POST['u_name'];
   $u_name = filter_var($u_name, FILTER_SANITIZE_STRING);
   $mes_email = $_POST['mes_email'];
   $mes_email = filter_var($mes_email, FILTER_SANITIZE_STRING);
   $mes_phone = $_POST['mes_phone'];
   $mes_phone = filter_var($mes_phone, FILTER_SANITIZE_STRING);
   $mes_message = $_POST['mes_message'];
   $mes_message = filter_var($mes_message, FILTER_SANITIZE_STRING);

   $select_message = $conn->prepare("SELECT * FROM `messages` WHERE u_name = ? AND mes_email = ? AND mes_phone = ? AND mes_message = ?");
   $select_message->execute([$u_name, $mes_email, $mes_phone, $mes_message]);

   if ($select_message->rowCount() > 0) {
      $message[] = 'already sent message!';
   } else {

      $insert_message = $conn->prepare("INSERT INTO `messages`(user_id, u_name, mes_email, mes_phone, mes_message) VALUES(?,?,?,?,?)");
      $insert_message->execute([$user_id, $u_name, $mes_email, $mes_phone, $mes_message]);

      $message[] = 'sent message successfully!';
   }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Liên hệ</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'components/user_header.php'; ?>

   <section class="contact">

      <form action="" method="post">
         <?php
         $select_profile = $conn->prepare("SELECT * FROM `users` WHERE u_id = ?");
         $select_profile->execute([$user_id]);
         if ($select_profile->rowCount() > 0) {
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
            <h3>Gửi lời nhắn</h3>
            <input type="text" name="u_name" value="<?= $fetch_profile['u_name']; ?> " class="box">
            <input type="email" name="mes_email" value="<?= $fetch_profile['u_email']; ?> " class="box">
            <input type="text" name="mes_phone"  value="<?= $fetch_profile['u_sdt']; ?> " class="box">
            <textarea name="mes_message" class="box" placeholder="Nhập tin nhắn" cols="30" rows="10"></textarea>
            <input type="submit" value="Gửi tin nhắn" name="send" class="btn">
         <?php
         } else {
            echo '<input type="text" name="u_name" placeholder="Nhập tên" required maxlength="20" class="box">
            <input type="email" name="mes_email" placeholder="Nhập email" required maxlength="50" class="box">
            <input type="number" name="mes_phone" placeholder="nhập số điện thoại" required onkeypress="if(this.value.length == 10) return false;" class="box">
            <textarea name="mes_message" class="box" placeholder="Nhập tin nhắn" cols="30" rows="10"></textarea>
            <input type="submit" value="Gửi tin nhắn" name="send" class="btn">
            ';
         }
         ?>
      </form>

   </section>

   <?php include 'components/footer.php'; ?>

   <script src="js/script.js"></script>

</body>

</html>