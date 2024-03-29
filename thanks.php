<?php
include 'components/connect.php';
session_start();
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
};
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">
    <title>Cảm ơn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="vh-100 d-flex justify-content-center align-items-center" style="font-size: 1.7rem;">
        <div>
            <div class="mb-4 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="text-success" width="100" height="100" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                </svg>
            </div>
            <div class="text-center" style="color:white;">
                <h1>Thank You !</h1>
                <div>
                    <p>Cảm ơn quý khách! Mọi chi tiết xin liên hệ chúng tôi.</p>
                </div>
                <?php
                if (isset($_GET['orderId'])) {

                    // $partnerCode = $_GET['partnerCode'];
                    $mm_id = $_GET['orderId'];
                    //$orderId = $_GET['requestId'];
                    $amount = $_GET['amount'];
                    $orderInfo = $_GET['orderInfo'];
                    $orderType = $_GET['orderType'];
                    //$transId = $_GET['transId'];
                    $payType = $_GET['payType'];
                    //$signature = $_GET['signature'];

                    $insert_mo = $conn->prepare("INSERT INTO `momo`( mm_id, amount, orderInfo, orderType, payType) VALUES(?,?,?,?,?)");
                    $insert_mo->execute([$mm_id, $amount, $orderInfo, $orderType, $payType]);
                   
                }
                ?>
                <div>
                    <a href="../ecommercewebsite/orders.php" class="btn btn-primary" style="font-size: 1.7rem;">Xem đơn hàng</a>
                </div>
                
            </div>
</body>

</html>