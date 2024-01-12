<?php

include '../components/connect.php';

session_start();

if(isset($_POST['submit'])){

   $ad_email = $_POST['ad_email'];
   $ad_email = filter_var($ad_email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);//Hàm bâm
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE ad_email = ? AND password = ?");
   $select_admin->execute([$ad_email, $pass]);
   $row = $select_admin->fetch(PDO::FETCH_ASSOC);//Trả về dữ liệu dạng mảng với key là tên của column

   if($select_admin->rowCount() > 0){
      $_SESSION['admin_id'] = $row['ad_id'];
      header('location:dashboard.php');
   }else{
      $message[] = 'incorrect username or password!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Đăng nhập admin</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

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

<section class="form-container">

   <form action="" method="post">
      <h3>Đăng nhập admin</h3>
      <input type="email" name="ad_email" required placeholder="Email đăng nhập" maxlength="50" class="box" oninput="this.value = this.value.replace(/\s/g, '')"> <!-- xóa khoảng trắng -->
      <input type="password" name="pass" required placeholder="mật khẩu đăng nhập" maxlength="50"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Đăng nhập" class="btn" name="submit">
   </form>

</section>
   
</body>
</html>