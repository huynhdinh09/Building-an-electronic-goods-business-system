<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
};


if (isset($_POST['update']) && $_POST['update']) {

    $u_id = $_POST['u_id'];
    $u_name = $_POST['u_name'];
    $u_name = filter_var($u_name, FILTER_SANITIZE_STRING);
    $u_sdt = $_POST['u_sdt'];
    $u_sdt = filter_var($u_sdt, FILTER_SANITIZE_STRING);
    $u_email = $_POST['u_email'];
    $u_email = filter_var($u_email, FILTER_SANITIZE_STRING);

    $update_profile_user = $conn->prepare("UPDATE `users` SET u_name = ?, u_sdt = ? , u_email = ? WHERE u_id = ?");
    $update_profile_user->execute([$u_name, $u_sdt, $u_email, $u_id]);

    $message[] = 'Cập nhật thành công!';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật thông tin khách hàng</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

</head>

<body>
    <div>
        <?php include 'components/user_header.php'; ?>

    </div>
    <div class="container mt-3">

        <form class="card p-3 text-center" action="" method="post" enctype="multipart/form-data">

            <div class="d-flex flex-row justify-content-center mb-3">

                <div class="d-flex flex-column ms-3 user-details">
                    <h4 class="mb-0" style="font-size: 2.5rem;"><?= $fetch_profile['u_name']; ?></h4>

                </div>
            </div>
            <h4 style="font-size: 1.7rem;">Chỉnh sữa thông tin</h4>

            <?php
            $update_id = $_GET['update'];
            $select_profile_user = $conn->prepare("SELECT * FROM `users` WHERE u_id = ?");
            $select_profile_user->execute([$update_id]);
            if ($select_profile_user->rowCount() > 0) {
                while ($fetch_profile_user = $select_profile_user->fetch(PDO::FETCH_ASSOC)) {


            ?>
                    <form action="" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="u_id" value="<?= $fetch_profile_user['u_id']; ?>">
                        <div class="row">
                            <div class="mt-3 col-md-6">
                                <div class="inputs">
                                    <label>Tên</label>
                                    <input class="form-control" type="text" name="u_name" required class="box" maxlength="100" placeholder="enter product name" value="<?= $fetch_profile_user['u_name']; ?>">
                                </div>
                            </div>
                            <div class="mt-3 col-md-6">
                                <div class="inputs">
                                    <label>Số điện thoại</label>
                                    <input class="form-control" type="text" name="u_sdt" required class="box" placeholder="enter product price" value="<?= $fetch_profile_user['u_sdt']; ?>">
                                </div>
                            </div>
                            <div class="mt-3 col-md-6">
                                <div class="inputs">
                                    <label>Email</label>
                                    <input class="form-control" type="text" name="u_email" required class="box" value="<?= $fetch_profile_user['u_email']; ?>">
                                </div>
                            </div>

                        </div>

                        <a class="delete-btn" style="text-decoration: none;" href="update_user.php">Đổi mật khẩu</a>
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
    <script src="js/script.js"></script>

</body>

</html>