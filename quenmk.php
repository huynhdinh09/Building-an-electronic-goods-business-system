<?php

include 'components/connect.php';
include 'components/checkmail.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if (isset($_POST['guiemail']) && ($_POST['guiemail'])) {
    $u_email = $_POST['u_email'];
    $checkemail = checkemail($u_email);
    if (is_array($checkemail)) {
        $thongbao = "Mật khẩu của bạn là: " . $checkemail['u_password'];
    } else {
        $thongbao = "Email này không tồn tại";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Quên mật khẩu</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="form-container">

   <form action="" method="post">
      <h3>Quên mật khẩu</h3>
      <input type="email" name="u_email" required placeholder="Nhập vào email" maxlength="50"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      
      <input type="submit" value="Gửi" class="btn" name="guiemail">
      <h2 class="thongbao">
                    <?php

                    if (isset($thongbao) && ($thongbao != "")) {
                        echo $thongbao;
                    }
                    ?>
                </h2>
     
   </form>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>