<?php

include '../components/connect.php';
include '../components/order_config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

if (isset($_POST['update_payment'])) {
   $order_id = $_POST['order_id'];
   $payment_status = $_POST['payment_status'];
   $payment_status = filter_var($payment_status, FILTER_SANITIZE_STRING);
   $update_payment = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE order_id = ?");
   $update_payment->execute([$payment_status, $order_id]);
   $message[] = 'payment status updated!';
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE order_id = ?");
   $delete_order->execute([$delete_id]);
   header('location:placed_orders.php');
}

// if (isset($_POST['kyw']) && ($_POST['kyw'] != "")) {
//    $kyw = $_POST['kyw'];
//    $dsdonhang = loadall_order($kyw, 0);
// } else {
//    $kyw = "";
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Quản lý đặt hàng</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>

<body>

   <?php include '../components/admin_header.php'; ?>

   <section class="orders">

      <h1 class="heading">Quản lý đặt hàng</h1>

      <section class="search-form">
         <form action="" method="post">
            <input type="text" name="kyw" placeholder="search here..." maxlength="100" class="box" required>
            <button type="submit" class="fas fa-search" name="search_btn"></button>
         </form>
      </section>
      <div class="row1 frmcontent">
         <div class="row1 mb10 frmdsloai">
            <table>
               <tr>
                  <th>MÃ ĐƠN HÀNG</th>
                  <th>KHÁCH HÀNG</th>
                  <th>SẢN PHẨM</th>
                  <th>GIÁ TRỊ ĐƠN HÀNG</th>
                  <th>PHƯƠNG THỨC THANH TOÁN</th>
                  <th>NGÀY ĐẶT HÀNG</th>
                  <th>TÌNH TRẠNG ĐƠN HÀNG</th>
                  <th>THAO TÁC</th>
               </tr>
               <?php


               if (isset($_POST['kyw']) or isset($_POST['kyw'])) {
                  $kyw = $_POST['kyw'];
 
                  $dsdonhang = loadall_order($kyw , 0);
                  foreach ($dsdonhang as $order) {
                     extract($order);

                     $xoadh = "placed_orders.php?xoadh&order_id=" . $order_id;
                     $kh = $order["order_name"] . '
                            <br>' . $order["order_email"] . '
                            <br>' . $order["order_address"] . '
                            <br>' . $order["order_number"];
                     echo '<tr>  
                                <td>HD-' . $order['order_id'] . '</td>
                                <td>' . $kh . '</td>
                                <td>' . $order['total_products'] . '</td>
                                <td><strong>' . number_format($order['total_price']) . '</strong> VNĐ</td>
                                <td>' . $order['order_method'] . '</td>
                                <td>' . date("d-m-Y", strtotime($order['placed_on'])) . '</td>
                                <td>
                                             <form action="" method="post">
                                                <input type="hidden" name="order_id" value="' . $order_id . '">
                                                <select name="payment_status"  class="select">
                                                   <option selected disabled>' . $payment_status . '</option>
                                                   <option value="Đang xử lý">Đang xử lý</option>
                                                   <option value="Hủy đơn hàng">Hủy đơn hàng</option>
                                                   <option value="Đang giao hàng">Đang giao hàng</option>
                                                   <option value="Đã hoàn thành">Đã hoàn thành</option>
                                                </select>
                                                <div class="flex-btn">
                                                   <input type="submit" value="cập nhật" class="option-btn" style=" font-size: 1.2rem; padding: 1rem 1.5rem;"  name="update_payment">
                                                  
                                                </div>
                                             </form>
                                          </td>
                                <td>';
                              
                                 if($payment_status=="Đang giao hàng"){ 
                                    
                                   echo ' <a href="update_dh.php?update=' . $order_id . '" class="option-btn" style="font-size: 1.2rem; padding: 1rem 1.5rem;" >Sữa</a>';
                                 
                               
                                 }else{
                                
                                     echo  '<a href="update_dh.php?update=' . $order_id . '" class="option-btn" style="font-size: 1.2rem; padding: 1rem 1.5rem;" >Sữa</a>
                                       <a href="placed_orders.php?delete=' . $order_id . '" class="btn" style="font-size: 1.2rem; padding: 1rem 1.5rem;" onclick="return confirm("delete this order?");">Xóa</a>';
                                 
                                 } 
                                 
                                '</td>
                             </tr>';
                  }
               }else{
                  $dsdonhang = loadall_order("" , 0);
                  foreach ($dsdonhang as $order) {
                     extract($order);

                     $xoadh = "placed_orders.php?xoadh&order_id=" . $order_id;
                     $kh = $order["order_name"] . '
                            <br>' . $order["order_email"] . '
                            <br>' . $order["order_address"] . '
                            <br>' . $order["order_number"];
                     echo '<tr>  
                                <td>HD-' . $order['order_id'] . '</td>
                                <td>' . $kh . '</td>
                                <td>' . $order['total_products'] . '</td>
                                <td><strong>' . number_format($order['total_price']) . '</strong> VNĐ</td>
                                <td>' . $order['order_method'] . '</td>
                                <td>' . date("d-m-Y", strtotime($order['placed_on'])) . '</td>
                                <td>
                                             <form action="" method="post">
                                                <input type="hidden" name="order_id" value="' . $order_id . '">
                                                <select name="payment_status"  class="select">
                                                   <option selected disabled>' . $payment_status . '</option>
                                                   <option value="Đang xử lý">Đang xử lý</option>
                                                   <option value="Hủy đơn hàng">Hủy đơn hàng</option>
                                                   <option value="Đang giao hàng">Đang giao hàng</option>
                                                   <option value="Đã hoàn thành">Đã hoàn thành</option>
                                                </select>
                                                <div class="flex-btn">
                                                   <input type="submit" value="cập nhật" class="option-btn" style=" font-size: 1.2rem; padding: 1rem 1.5rem;"  name="update_payment">
                                                  
                                                </div>
                                             </form>
                                          </td>
                                          <td>';
                              
                                          if($payment_status=="Đang giao hàng"){ 
                                             
                                            echo ' <a href="update_dh.php?update=' . $order_id . '" class="option-btn" style="font-size: 1.2rem; padding: 1rem 1.5rem;" >Sữa</a>';
                                          
                                        
                                          }else{
                                         
                                            echo    '<a href="update_dh.php?update=' . $order_id . '" class="option-btn" style="font-size: 1.2rem; padding: 1rem 1.5rem;" >Sữa</a>
                                                <a href="placed_orders.php?delete=' . $order_id . '" class="btn" style="font-size: 1.2rem; padding: 1rem 1.5rem;" onclick="return confirm("delete this order?");">Xóa</a>';
                                          
                                          } 
                                          
                                         '</td>
                                      </tr>';
                                 }
               }
               ?>
            </table>
         </div>
      </div>

   </section>

   <script src="../js/admin_script.js"></script>
   <script src="../js/api_tinh_thanh.js"></script>

</body>

</html>