<?php
include '../components/connect.php';
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
};


if (isset($_POST['redirect'])) {


    // dd($tongtien);
    $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
    $vnp_Returnurl = "https://localhost/vnpay_php/vnpay_return.php";
    $vnp_TmnCode = "PU9088KZ"; //Mã website tại VNPAY 
    $vnp_HashSecret = "XUGTJWMYRMKYODTFBUHDWAMGUDERFLTK"; //Chuỗi bí mật

    // $vnp_TxnRef = '1234'; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY

    // $vnp_TxnRef = uniqid(); // Sử dụng hàm uniqid() để tạo một giá trị ngẫu nhiên
    $vnp_TxnRef = rand(00, 9999); // Sử dụng hàm uniqid() để tạo một giá trị ngẫu nhiên
    $vnp_OrderInfo = 'Thanh toán đơn hàng test';
    $vnp_OrderType = 'Truc Tien Store';
    // $vnp_Amount = $data['product_total'] * 100;
    $vnp_Amount = 10000 * 100;
    $vnp_Locale = 'vn';
    $vnp_BankCode = 'NCB';
    $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

    $inputData = array(
        "vnp_Version" => "2.1.0",
        "vnp_TmnCode" => $vnp_TmnCode,
        "vnp_Amount" => $vnp_Amount,
        "vnp_Command" => "pay",
        "vnp_CreateDate" => date('YmdHis'),
        "vnp_CurrCode" => "VND",
        "vnp_IpAddr" => $vnp_IpAddr,
        "vnp_Locale" => $vnp_Locale,
        "vnp_OrderInfo" => $vnp_OrderInfo,
        "vnp_OrderType" => $vnp_OrderType,
        "vnp_ReturnUrl" => $vnp_Returnurl,
        "vnp_TxnRef" => $vnp_TxnRef,

    );

    if (isset($vnp_BankCode) && $vnp_BankCode != "") {
        $inputData['vnp_BankCode'] = $vnp_BankCode;
    }
    if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
        $inputData['vnp_Bill_State'] = $vnp_Bill_State;
    }

    var_dump($inputData);
    ksort($inputData);
    $query = "";
    $i = 0;
    $hashdata = "";
    foreach ($inputData as $key => $value) {
        if ($i == 1) {
            $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
        } else {
            $hashdata .= urlencode($key) . "=" . urlencode($value);
            $i = 1;
        }
        $query .= urlencode($key) . "=" . urlencode($value) . '&';
    }

    $vnp_Url = $vnp_Url . "?" . $query;
    if (isset($vnp_HashSecret)) {
        $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
    }

    return $vnp_Url; // Trả về URL để chuyển hướng
    // }
}

// if (isset($_POST['redirect'])) {

//     $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
//     $vnp_Returnurl = "https://localhost/vnpay_php/vnpay_return.php";
//     $vnp_TmnCode = "PU9088KZ"; //Mã website tại VNPAY 
//     $vnp_HashSecret = "XUGTJWMYRMKYODTFBUHDWAMGUDERFLTK"; //Chuỗi bí mật


//         $vnp_TxnRef = rand(00, 9999); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
//         $vnp_OrderInfo = 'Noi dung thanh toan';
//         $vnp_OrderType = $_POST['order_type'];
//         $vnp_Amount = $grand_total * 100;
//         $vnp_Locale = 'vn';
//         $vnp_BankCode = 'NCB';
//         $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

//     $inputData = array(
//         "vnp_Version" => "2.1.0",
//         "vnp_TmnCode" => $vnp_TmnCode,
//         "vnp_Amount" => $vnp_Amount,
//         "vnp_Command" => "pay",
//         "vnp_CreateDate" => date('YmdHis'),
//         "vnp_CurrCode" => "VND",
//         "vnp_IpAddr" => $vnp_IpAddr,
//         "vnp_Locale" => $vnp_Locale,
//         "vnp_OrderInfo" => $vnp_OrderInfo,
//         "vnp_OrderType" => $vnp_OrderType,
//         "vnp_ReturnUrl" => $vnp_Returnurl,
//         "vnp_TxnRef" => $vnp_TxnRef

//     );

//     if (isset($vnp_BankCode) && $vnp_BankCode != "") {
//         $inputData['vnp_BankCode'] = $vnp_BankCode;
//     }

//     //var_dump($inputData);
//     ksort($inputData);
//     $query = "";
//     $i = 0;
//     $hashdata = "";
//     foreach ($inputData as $key => $value) {
//         if ($i == 1) {
//             $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
//         } else {
//             $hashdata .= urlencode($key) . "=" . urlencode($value);
//             $i = 1;
//         }
//         $query .= urlencode($key) . "=" . urlencode($value) . '&';
//     }

//     $vnp_Url = $vnp_Url . "?" . $query;
//     if (isset($vnp_HashSecret)) {
//         $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
//         $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
//     }
//     $returnData = array(
//         'code' => '00', 'message' => 'success', 'data' => $vnp_Url
//     );
//     if (isset($_POST['redirect'])) {
//         header('Location: ' . $vnp_Url);
//         die();
//     } else {
//         echo json_encode($returnData);
//     }
// }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Tạo mới đơn hàng</title>
    <!-- Bootstrap core CSS -->
    <link href="/vnpay_php/assets/bootstrap.min.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="/vnpay_php/assets/jumbotron-narrow.css" rel="stylesheet">
    <script src="/vnpay_php/assets/jquery-1.11.3.min.js"></script>
</head>

<body>
    <?php require_once("./config.php"); ?>
    <div class="container">
        <div class="header clearfix">
            <h3 class="text-muted">VNPAY DEMO</h3>
        </div>
        <h3>Tạo mới đơn hàng</h3>
        <div class="table-responsive">
            <form action="/vnpay_php/vnpay_create_payment.php" id="create_form" method="post">
                <?php $grand_total = 0;
                $cart_items[] = '';
                $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                $select_cart->execute([$user_id]);
                if ($select_cart->rowCount() > 0) {
                    $fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC);
                    $cart_items[] = $fetch_cart['c_name'] . ' (' . $fetch_cart['c_price'] . ' x ' . $fetch_cart['c_quantity'] . ') - ';
                    $total_products = implode($cart_items);
                    $grand_total += ($fetch_cart['c_price'] * $fetch_cart['c_quantity']);
                ?>
                    <div class="form-group">
                        <label for="language">Loại hàng hóa </label>
                        <select name="order_type" id="order_type" class="form-control">
                            <option value="topup">Nạp tiền điện thoại</option>
                            <option value="billpayment">Thanh toán hóa đơn</option>
                            <option value="fashion">Thời trang</option>
                            <option value="other">Khác - Xem thêm tại VNPAY</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="order_id">Mã hóa đơn</label>
                        <input class="form-control" id="order_id" name="order_id" type="text" value="<?= $fetch_cart['c_id'] ?>" />
                    </div>
                    <div class="form-group">
                        <label for="amount">Số tiền</label>
                        <span></span><input class="form-control" id="amount" name="amount" type="number" value="<?= $grand_total ?>" /> <span>
                    </div>
                    <div class="form-group">
                        <label for="order_desc">Nội dung thanh toán</label>
                        <textarea class="form-control" cols="20" id="order_desc" name="order_desc" rows="2">Noi dung thanh toan</textarea>
                    </div>
                    <div class="form-group">
                        <label for="bank_code">Ngân hàng</label>
                        <select name="bank_code" id="bank_code" class="form-control">
                            <option value="">Không chọn</option>
                            <option value="NCB"> Ngan hang NCB</option>
                            <option value="AGRIBANK"> Ngan hang Agribank</option>
                            <option value="SCB"> Ngan hang SCB</option>
                            <option value="SACOMBANK">Ngan hang SacomBank</option>
                            <option value="EXIMBANK"> Ngan hang EximBank</option>
                            <option value="MSBANK"> Ngan hang MSBANK</option>
                            <option value="NAMABANK"> Ngan hang NamABank</option>
                            <option value="VNMART"> Vi dien tu VnMart</option>
                            <option value="VIETINBANK">Ngan hang Vietinbank</option>
                            <option value="VIETCOMBANK"> Ngan hang VCB</option>
                            <option value="HDBANK">Ngan hang HDBank</option>
                            <option value="DONGABANK"> Ngan hang Dong A</option>
                            <option value="TPBANK"> Ngân hàng TPBank</option>
                            <option value="OJB"> Ngân hàng OceanBank</option>
                            <option value="BIDV"> Ngân hàng BIDV</option>
                            <option value="TECHCOMBANK"> Ngân hàng Techcombank</option>
                            <option value="VPBANK"> Ngan hang VPBank</option>
                            <option value="MBBANK"> Ngan hang MBBank</option>
                            <option value="ACB"> Ngan hang ACB</option>
                            <option value="OCB"> Ngan hang OCB</option>
                            <option value="IVB"> Ngan hang IVB</option>
                            <option value="VISA"> Thanh toan qua VISA/MASTER</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="language">Ngôn ngữ</label>
                        <select name="language" id="language" class="form-control">
                            <option value="vn">Tiếng Việt</option>
                            <option value="en">English</option>
                        </select>
                    </div>

                    <button type="submit" name="redirect" id="redirect" class="btn btn-primary">Xác nhận thanh toán</button>
                <?php } ?>
            </form>
        </div>
        <p>
            &nbsp;
        </p>
        <footer class="footer">
            <p>&copy; VNPAY <?php echo date('Y') ?></p>
        </footer>
    </div>




</body>

</html>