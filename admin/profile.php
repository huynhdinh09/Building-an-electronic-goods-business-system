<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}


if (isset($_POST['update']) && $_POST['update']) {

    $ad_id = $_POST['ad_id'];
    $ad_name = $_POST['ad_name'];
    $ad_name = filter_var($ad_name, FILTER_SANITIZE_STRING);
    $ad_phone = $_POST['ad_phone'];
    $ad_phone = filter_var($ad_phone, FILTER_SANITIZE_STRING);
    $ad_email = $_POST['ad_email'];
    $ad_email = filter_var($ad_email, FILTER_SANITIZE_STRING);
    $ad_address = $_POST['ad_address'];
    $ad_address = filter_var($ad_address, FILTER_SANITIZE_STRING);
    $ad_about = $_POST['ad_about'];
    $ad_about = filter_var($ad_about, FILTER_SANITIZE_STRING);

    $update_profile = $conn->prepare("UPDATE `admins` SET ad_name = ?, ad_phone = ?,ad_email = ?, ad_address = ? , ad_about = ? WHERE ad_id = ?");
    $update_profile->execute([$ad_name, $ad_phone, $ad_email, $ad_address, $ad_about, $ad_id]);

    $message[] = 'Cập nhật thành công!';

    $image = $_FILES['avatar']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $image_size = $_FILES['avatar']['size'];
    $image_tmp_name = $_FILES['avatar']['tmp_name'];
    $image_folder = '../uploaded_img/' . $image;

    if (!empty($image)) {
        if ($image_size > 2000000) {
            $message[] = 'Kích thước ảnh quá lớn!';
        } else {
            $update_image = $conn->prepare("UPDATE `admins` SET ad_image = ? WHERE ad_id = ?");
            $update_image->execute([$image, $ad_id]);
            move_uploaded_file($image_tmp_name, $image_folder);
            //unlink('../uploaded_img/' . $old_image);
            $message[] = 'Thay đổi ảnh thành công!';
        }
    }
}

// $select_profile = $conn->prepare("SELECT * FROM `admins`");
// $select_profile->execute();

// $insert_profile = $conn->prepare("INSERT INTO `admins`(ad_name, ad_phone,ad_address, ad_about) VALUES(?,?,?,?)");
// $insert_profile->execute([$ad_name, $ad_phone, $ad_address, $ad_about]);


//     if ($insert_profile) {
//         if ($image_size > 2000000) {
//             $message[] = 'Kích thước ảnh quá lớn!';
//         } else {
//             move_uploaded_file($image_tmp_name, $image_folder);

//             $message[] = 'Đã thêm thông tin!';
//         }
//     }
//}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật thông tin</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

</head>

<body>
    <div>
        <?php include '../components/admin_header.php'; ?>

    </div>
    <div class="container mt-3">

        <form class="card p-3 text-center" action="" method="post" enctype="multipart/form-data">

            <div class="d-flex flex-row justify-content-center mb-3">
                <div class="image">
                    <img src="../uploaded_img/<?= $fetch_profile['ad_image']; ?>" alt="">
                    <input class='bx bxs-camera-plus' type="file" name="avatar">
                </div>
                <div class="d-flex flex-column ms-3 user-details">
                    <h4 class="mb-0"><?= $fetch_profile['ad_name']; ?></h4>
                    <div class="ratings"> <span>4.5</span> <i class='bx bx-star ms-1'></i> </div> <span>Quản trị viên</span>
                </div>
            </div>
            <h4>Chỉnh sữa thông tin</h4>

            <?php
            $update_id = $_GET['update'];
            $select_products = $conn->prepare("SELECT * FROM `admins` WHERE ad_id = ?");
            $select_products->execute([$update_id]);
            if ($select_products->rowCount() > 0) {
                while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {


            ?>
                    <form action="" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="ad_id" value="<?= $fetch_products['ad_id']; ?>">
                        <div class="row">
                            <div class="mt-3 col-md-6">
                                <div class="inputs">
                                    <label>Tên</label>
                                    <input class="form-control" type="text" name="ad_name" required class="box" maxlength="100" placeholder="enter product name" value="<?= $fetch_products['ad_name']; ?>">
                                </div>
                            </div>
                            <div class="mt-3 col-md-6">
                                <div class="inputs">
                                    <label>Số điện thoại</label>
                                    <input class="form-control" type="text" name="ad_phone" required class="box" placeholder="enter product price" value="<?= $fetch_products['ad_phone']; ?>">
                                </div>
                            </div>
                            <div class="mt-3 col-md-6">
                                <div class="inputs">
                                    <label>Email</label>
                                    <input class="form-control" type="text" name="ad_email" required class="box" value="<?= $fetch_products['ad_email']; ?>">
                                </div>
                            </div>
                            <div class=" mt-3 col-md-6">
                                <div class="inputs">
                                    <label>Địa chỉ</label>
                                    <input class="form-control" type="text" name="ad_address" required class="box" value="<?= $fetch_products['ad_address']; ?>">
                                </div>
                            </div>
                            <div class=" mt-3 col-md-12">
                                <div class="inputs">
                                    <label>Giới thiệu</label>
                                    <textarea class="form-control" style="font-size: 14px;" type="text" name="ad_about"><?= $fetch_products['ad_about']; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- <span>update image </span>
                     <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">
                     <span>update image 02</span>
                     <input type="file" name="image_02" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">
                     <span>update image 03</span>
                     <input type="file" name="image_03" accept="image/jpg, image/jpeg, image/png, image/webp" class="box"> -->
                     <a class="delete-btn" style="text-decoration: none;" href="../admin/update_profile.php">Đổi mật khẩu</a>
                        <div class="flex-btn">
                            
                            <input type="submit" name="update" class=" btn btn btn-success" style="font-size: 1.7rem;" value="Cập nhật">
                            <button type="button" class="option-btn" onclick="quay_lai_trang_truoc()">Quay lại</button>
                            <script>
                                function quay_lai_trang_truoc() {
                                    history.back();
                                }
                            </script>
                        </div>
                    </form>

            <?php
                }
            } else {
                echo '<p class="empty">no product found!</p>';
            }
            ?>
        </form>

    </div>
    <script src="../js/admin_script.js"></script>

</body>

</html>