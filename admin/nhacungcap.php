<?php

include '../components/connect.php';

session_start();
//$suppliers_id = $_REQUEST['suppliers_id'];
$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
};

if (isset($_POST['add_suppliers'])) {

   $ncc_ten = $_POST['ncc_ten'];
   $ncc_ten = filter_var($ncc_ten, FILTER_SANITIZE_STRING);
   $ncc_diachi = $_POST['ncc_diachi'];
   $ncc_diachi = filter_var($ncc_diachi, FILTER_SANITIZE_STRING);
   $ncc_sdt = $_POST['ncc_sdt'];
   $ncc_sdt = filter_var($ncc_sdt, FILTER_SANITIZE_STRING);
   $ncc_socialmedia = $_POST['ncc_socialmedia'];
   $ncc_socialmedia = filter_var($ncc_socialmedia, FILTER_SANITIZE_STRING);

   $select_ncc = $conn->prepare("SELECT * FROM `nhacungcap` WHERE ncc_ten = ?");
   $select_ncc->execute([$ncc_ten]);
   if ($select_ncc->rowCount() > 0) {
      $message[] = 'supplier name already exist!';
   } else {
      $insert_ncc = $conn->prepare("INSERT INTO `nhacungcap`(ncc_ten, ncc_diachi, ncc_sdt, ncc_socialmedia) VALUES(?,?,?,?)");
      $insert_ncc->execute([$ncc_ten,$ncc_diachi,$ncc_sdt,$ncc_socialmedia]);
   }
}

if (isset($_GET['delete'])) {

   $delete_id = $_GET['delete'];
   $delete_ncc = $conn->prepare("DELETE FROM `nhacungcap` WHERE ncc_id = ?");
   $delete_ncc->execute([$delete_id]);

   header('location:nhacungcap.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Nhà cung cấp</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>

<body>

   <?php include '../components/admin_header.php'; ?>

   <section class="add-suppliers">

      <h1 class="heading">Thêm nhà cung cấp</h1>

      <form action="" method="post" enctype="multipart/form-data">
         <div class="flex">
            <div class="inputBox">
               <span>Tên (required)</span>
               <input type="text" class="box" required maxlength="100" placeholder="nhập tên nhà cung cấp" name="ncc_ten">
            </div>
            <div class="inputBox">
               <span>Địa chỉ (required)</span>
               <input type="text" class="box" required maxlength="100" placeholder="nhập địa chỉ" name="ncc_diachi">
            </div>
            <div class="inputBox">
               <span>Số điện thoại (required)</span>
               <input type="text" class="box" required maxlength="100" placeholder="nhập số điện thoại" name="ncc_sdt">
            </div>
            <div class="inputBox">
               <span>Mạng xã hội (required)</span>
               <input type="text" class="box" required maxlength="100" placeholder="địa chỉ mạng xã hội" name="ncc_socialmedia">
            </div>
         </div>

         <input type="submit" value="thêm nhà cung cấp" class="btn" name="add_suppliers">
      </form>

   </section>

   <section class="show-suppliers">

      <h1 class="heading">Các nhà cung cấp</h1>

      <div class="box-container">
         <?php
         $select_ncc = $conn->prepare("SELECT * FROM `nhacungcap`");
         $select_ncc->execute();
         if ($select_ncc->rowCount() > 0) {
            while ($fetch_ncc = $select_ncc->fetch(PDO::FETCH_ASSOC)) {
         ?>
               <div class="box">
                  
                  <p class="name"> Tên : <span><?= $fetch_ncc['ncc_ten']; ?></span> </p>
                  <p class="address"> Địa chỉ : <span><?= $fetch_ncc['ncc_diachi']; ?></span> </p>
                  <p class="phone"> Số điện thoại : <span><?= $fetch_ncc['ncc_sdt']; ?></span> </p>
                  <p class="socialmedia"> Truyền thông mạng xã hội : <span><?= $fetch_ncc['ncc_socialmedia']; ?></span> </p>
                  <form action="" method="post">
                     <input type="hidden" name="ncc_id" value="<?= $fetch_ncc['ncc_id']; ?>">
                     <div class="flex-btn">
                        <a href="nhacungcap.php?delete=<?= $fetch_ncc['ncc_id']; ?>" class="delete-btn" onclick="return confirm('delete this ncc?');">xóa</a>
                     </div>
                  </form>
               </div>
         <?php
            }
         } else {
            echo '<p class="empty">Không có nhà cung cấp!</p>';
         }
         ?>

      </div>
   </section>

   <script src="../js/admin_script.js"></script>

</body>

</html>