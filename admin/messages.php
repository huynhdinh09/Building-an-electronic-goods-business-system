<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
};

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $delete_message = $conn->prepare("DELETE FROM `messages` WHERE mes_id = ?");
   $delete_message->execute([$delete_id]);
   header('location:messages.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Tin nhắn</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>

<body>

   <?php include '../components/admin_header.php'; ?>

   <section class="contacts">

      <h1 class="heading">Tin nhắn góp ý</h1>

      <div id="wrapper">


         <div class="chat_wrapper">
            <div id="chat">

               <?php
               $select_messages = $conn->prepare("SELECT * FROM `messages` order by mes_date desc");
               $select_messages->execute();
               if ($select_messages->rowCount() > 0) {
                  while ($fetch_message = $select_messages->fetch(PDO::FETCH_ASSOC)) {
               ?>
                     <div class="single-msg">
                        <strong><?= $fetch_message['u_name']; ?>: </strong><?= $fetch_message['mes_message']; ?>
                        <span><?= date('H:i  d-m-Y', strtotime($fetch_message['mes_date'])) ?>
                        <a href="messages.php?delete=<?= $fetch_message['mes_id']; ?>" onclick="return confirm('Xóa tin nhắn?');" class="btn-primary">Xóa</a>
                        </span>
                     </div>
               <?php
                  }
               } else {
                  echo 'Không có tin nhắn nào!';
               }
               ?>

            </div>
         </div>

      </div>

   </section>

   <script src="../js/admin_script.js"></script>

</body>

</html>