<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
};

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $delete_comment = $conn->prepare("DELETE FROM `comment` WHERE cmt_id = ?");
   $delete_comment->execute([$delete_id]);
   header('location:admin_comment.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Bình luận</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>

<body>

   <?php include '../components/admin_header.php'; ?>

   <section class="contacts">

      <h1 class="heading">Bình luận</h1>
      <div class="row1 frmcontent">
         <div class="row1 mb10 cmt">
            <table>
               <tr>

                  <th>ID</th>
                  <th>Nộ dung</th>
                  <th>ID khách hàng</th>
                  <th>ID sản phẩm</th>
                  <th>Ngày bình luận</th>
                  <th></th>
               </tr>
               <?php
               $select_comment = $conn->prepare("SELECT * FROM `comment` order by cmt_id desc");
               $select_comment->execute();
               if ($select_comment->rowCount() > 0) {
                  while ($fetch_comment = $select_comment->fetch(PDO::FETCH_ASSOC)) {
               ?>

                     <td class="center"><?= $fetch_comment['cmt_id'] ?></td>
                     <td><?= $fetch_comment['cmt_noidung'] ?></td>
                     <td class="center"><?= $fetch_comment['u_id'] ?></td>
                     <td class="center"><?= $fetch_comment['products_id'] ?></td>
                     <td class="center"><?= $fetch_comment['cmt_ngay'] ?></td>
                     <td><a href="admin_comment.php?delete=<?= $fetch_comment['cmt_id']; ?>" onclick="return confirm('Xóa bình luận?');" class="delete-btn">Xóa</a></td>
                     </tr>

               <?php
                  }
               } else {
                  echo '<p class="empty">Không có bình luận</p>';
               }
               ?>
            </table>
         </div>

      </div>
   </section>

   <script src="../js/admin_script.js"></script>

</body>

</html>