<?php
session_start();
include 'components/connect.php';
//include 'components/online_checkout.php'
require 'cacbon/autoload.php';
use Carbon\Carbon;
use Carbon\CarbonInterval;

$now = Carbon::now('Asia/Ho_Chi_Minh');
if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:user_login.php');
};

function execPostRequest($url, $data)
{
   $ch = curl_init($url);
   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
   curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt(
      $ch,
      CURLOPT_HTTPHEADER,
      array(
         'Content-Type: application/json',
         'Content-Length: ' . strlen($data)
      )
   );
   curl_setopt($ch, CURLOPT_TIMEOUT, 5);
   curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
   //execute post
   $result = curl_exec($ch);
   //close connection
   curl_close($ch);
   return $result;
}
if (isset($_POST['COD']) || (isset($_POST['payUrl']))) {
   $order_name = $_POST['order_name'];
   $order_name = filter_var($order_name, FILTER_SANITIZE_STRING);
   $order_number = $_POST['order_number'];
   $order_number = filter_var($order_number, FILTER_SANITIZE_STRING);
   $order_email = $_POST['order_email'];
   $order_email = filter_var($order_email, FILTER_SANITIZE_STRING);
   //$order_method = 'Thanh toán khi nhận hàng';
   $order_address = $_POST['order_address'];
   $order_address = filter_var($order_address, FILTER_SANITIZE_STRING);
   $total_products = $_POST['total_products'];
   $total_price = $_POST['total_price'];

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);


   if (isset($_POST['COD'])) {

      // $order_name = $_POST['order_name'];
      // $order_name = filter_var($order_name, FILTER_SANITIZE_STRING);
      // $order_number = $_POST['order_number'];
      // $order_number = filter_var($order_number, FILTER_SANITIZE_STRING);
      // $order_email = $_POST['order_email'];
      // $order_email = filter_var($order_email, FILTER_SANITIZE_STRING);
      $order_method = 'Thanh toán khi nhận hàng';
      // $order_address = $_POST['order_address'];
      // $order_address = filter_var($order_address, FILTER_SANITIZE_STRING);
      // $total_products = $_POST['total_products'];
      // $total_price = $_POST['total_price'];

      $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
      $check_cart->execute([$user_id]);

      if ($check_cart->rowCount() > 0) {

         $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, order_name, order_number, order_email, order_method, order_address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
         $insert_order->execute([$user_id, $order_name, $order_number, $order_email, $order_method, $order_address, $total_products, $total_price]);

         $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
         $delete_cart->execute([$user_id]);

         $message[] = 'Đặt hàng thành công! <a href="orders.php">Xem đơn hàng</a> ';
      } else {
         $message[] = 'Đặt hàng không thành công!';
      }
   } elseif (isset($_POST['payUrl'])) {

      $order_method = 'Thanh toán MOMO';
      
      $grand_total = 0;
      $cart_items[] = '';
      $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
      $select_cart->execute([$user_id]);
      if ($select_cart->rowCount() > 0) {
         $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, order_name, order_number, order_email, order_method, order_address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
         $insert_order->execute([$user_id, $order_name, $order_number, $order_email, $order_method, $order_address, $total_products, $total_price]);

         $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
         $delete_cart->execute([$user_id]);
      
         while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
            $cart_items[] = '- ' . $fetch_cart['c_name'] . ' (' . $fetch_cart['c_price'] . ' x ' . $fetch_cart['c_quantity'] . ') ';
            $total_products = implode($cart_items);
            $grand_total += ($fetch_cart['c_price'] * $fetch_cart['c_quantity']);

            $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
            $partnerCode = 'MOMOBKUN20180529';
            $accessKey = 'klm05TvNBzhg7h7j';
            $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
            $orderInfo = $total_products;
            $amount = $grand_total;
            $orderId = rand(00, 9999);
            $redirectUrl = "http://localhost/ecommercewebsite/thanks.php";
            $ipnUrl = "http://localhost/ecommercewebsite/thanks.php";
            $extraData = "";
         }
      }


      $partnerCode = $partnerCode;
      $accessKey = $accessKey;
      $serectkey = $secretKey;
      $orderId = $orderId; // Mã đơn hàng
      $orderInfo = $orderInfo;
      $amount = $amount;
      $ipnUrl = $ipnUrl;
      $redirectUrl = $redirectUrl;
      $extraData = $extraData;

      $requestId = time() . "";
      $requestType = "payWithATM";
      $extraData = ($_POST["extraData"] ? $_POST["extraData"] : "");
      //before sign HMAC SHA256 signature
      $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
      $signature = hash_hmac("sha256", $rawHash, $serectkey);
      $data = array(
         'partnerCode' => $partnerCode,
         'partnerName' => "Test",
         "storeId" => "MomoTestStore",
         'requestId' => $requestId,
         'amount' => $amount,
         'orderId' => $orderId,
         'orderInfo' => $orderInfo,
         'redirectUrl' => $redirectUrl,
         'ipnUrl' => $ipnUrl,
         'lang' => 'vi',
         'extraData' => $extraData,
         'requestType' => $requestType,
         'signature' => $signature
      );
      $result = execPostRequest($endpoint, json_encode($data));
      $jsonResult = json_decode($result, true);  // decode json

      //Just a example, please check more in there

      header('Location: ' . $jsonResult['payUrl']);
   } else{

   }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Thanh toán</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.26.1/axios.min.js" integrity="sha512-bPh3uwgU5qEMipS/VOmRqynnMXGGSRv+72H/N260MQeXZIK4PG48401Bsby9Nq5P5fz7hy5UGNmC/W1Z51h2GQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'components/user_header.php'; ?>

   <section class="checkout-orders">

      <form action="" method="POST">

         <h3>Đơn hàng của bạn</h3>

         <div class="display-orders">
            <?php
            $grand_total = 0;
            $cart_items[] = '';
            $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $select_cart->execute([$user_id]);
            if ($select_cart->rowCount() > 0) {
               while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                  $cart_items[] = $fetch_cart['c_name'] . ' (' . $fetch_cart['c_price'] . ' x ' . $fetch_cart['c_quantity'] . ') - ';
                  $total_products = implode($cart_items);
                  $grand_total += ($fetch_cart['c_price'] * $fetch_cart['c_quantity']);
            ?>
                  <p> <?= $fetch_cart['c_name']; ?> <span>(<?= number_format($fetch_cart['c_price']) . ' VNĐ x ' . $fetch_cart['c_quantity']; ?>)</span> </p>
            <?php
               }
            } else {
               echo '<p class="empty">Chưa có đơn hàng nào!</p>';
            }
            ?>
            <input type="hidden" name="total_products" value="<?= $total_products; ?>">
            <input type="hidden" name="total_price" value="<?= $grand_total; ?>" value="">
            <div class="grand-total">tổng thanh toán : <span><?= number_format($grand_total); ?> VNĐ/-</span></div>
         </div>

         <h3>Nhập thông tin thanh toán</h3>

         <div class="flex">
            <?php $select_user = $conn->prepare("SELECT * FROM `users` WHERE u_id = ?");
            $select_user->execute([$user_id]);
            if ($select_user->rowCount() > 0) {
               $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC)
            ?>
               <div class="inputBox">
                  <span>Tên của bạn :</span>
                  <input type="text" name="order_name" placeholder="Nhập tên của bạn" class="box" maxlength="50" value="<?= $fetch_user['u_name'] ?>" required>
               </div>
               <div class="inputBox">
                  <span>Số điện thoại :</span>
                  <input type="text" name="order_number" placeholder="Nhập số điện thoại của bạn" class="box" value="<?= $fetch_user['u_sdt'] ?>" onkeypress="if(this.value.length == 10) return false;" required>
               </div>
               <div class="inputBox">
                  <span>email :</span>
                  <input type="email" name="order_email" placeholder="Nhập email của bạn" class="box" value="<?= $fetch_user['u_email'] ?>" maxlength="50" required>
               </div>
            <?php
            } else {
            } ?>
            <div class="inputBox">
               <span>Địa chỉ :</span>
               <form action="">
                  <select class="box" id="province">
                  </select>
                  <select class="box" id="district">
                     <option value="">Chọn quận, huyện</option>
                  </select>
                  <select class="box" id="ward">
                     <option value="">Chọn phường, Xã</option>
                  </select>

                  <!-- <input type="hidden" name="result" id="result" value=""> -->
                  <!-- <textarea class="box" name="result" id="result" cols="30" rows="10"></textarea> -->
               </form>

            </div>
            <div class="inputBox">

               <span>Ghi chú :</span>
               <input type="text" name="order_address" placeholder="Ghi chú thêm địa chỉ" id="result" class="box" maxlength="200" required>
            </div>
            <div class="flex">
               <div class="inputBox">
                  <input href="orders.php" type="submit" name="COD" class="btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>" value="Thanh toán khi nhận hàng">
               </div>
               <div class="inputBox">
                  <input type="submit" name="payUrl" class="btn<?= ($grand_total > 1) ? '' : 'disabled'; ?>" value="MOMO">
               </div>
             
            </div>
      </form>

   </section>

   <?php include 'components/footer.php'; ?>

   <script src="js/script.js"></script>
   <script src="js/api_tinh_thanh.js"></script>

</body>

</html>