<?php
session_start();
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
 } else {
    $user_id = '';
 };
include 'components/cmt.php';
include 'components/connect.php';

$products_id = $_REQUEST['products_id']; //mảng kết hợp get post cookie
$dscmt = loadall_cmt($products_id);
?>
<div">
    <div class="boxtitle bg-dark text-white text-center">Bình luận</div>
    <div class="binhluan boxcontent2">
        <table>
            <?php
            foreach ($dscmt as $cmt) {
                extract($cmt);
                echo '<tr><td>' . $cmt_noidung . '</td>';
                echo '<td>' . $u_id . '</td>';
                echo '<td>' . $cmt_ngay . '</td></tr>';
            }
            ?>
        </table>

        <form action="<?= $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="hidden" name="products_id" value="<?= $products_id ?>">
            <textarea name="cmt_noidung" placeholder="Write comment here!"></textarea>
            <!-- <input class="" type="text" name="noidung"> -->
            <input type="submit" class="btn btn-primary" name="guibinhluan" value="Gửi bình luận">
        </form>
    </div>

    <?php
    if (isset($_POST['guibinhluan']) && ($_POST['guibinhluan'])) {
        $cmt_noidung = $_POST['cmt_noidung'];
        $u_id = $user_id;
        $products_id = $_POST['products_id'];
        $cmt_ngay = date('h:i:sa d/m/Y');
        insert_cmt($cmt_noidung, $u_id, $products_id, $cmt_ngay);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }
    ?>
    </div>