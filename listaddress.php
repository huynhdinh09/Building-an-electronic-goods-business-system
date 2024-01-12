<?php
session_start();
include 'components/list_address.php';
include 'components/connect.php';

$order_id = $_REQUEST['order_id']; //mảng kết hợp get post cookie
$list_address = loadall_address($order_id);
?>
<div class="row1 frmcontent">
    <div class="row1 mb10 cmt">
        <table>
            <tr>
                <th>ID</th>
                <th>Address</th>
            </tr>
            <?php
            $select_order = $conn->prepare("SELECT * FROM `orders`");
            $select_order->execute();
            if ($select_order->rowCount() > 0) {
                while ($fetch_order = $select_order->fetch(PDO::FETCH_ASSOC)) {
            ?>
                    <td class="center"><?= $fetch_order['orde_id'] ?></td>
                    <td class="center"><?= $fetch_order['order_address'] ?></td>
                    <td><a href="listaddress.php?delete=<?= $fetch_order['order_id']; ?>" onclick="return confirm('delete this address?');" class="delete-btn">delete</a></td>
                    </tr>
            <?php

                }
            } else {
                echo '<p class="empty">you have no address</p>';
            }
            ?>

        </table>
    </div>
    <!-- <?php
            // if (isset($_POST['guibinhluan']) && ($_POST['guibinhluan'])) {
            //     $cmt_noidung = $_POST['cmt_noidung'];
            //     $products_id = $_POST['products_id'];


            //     $select_users = $conn->prepare("SELECT * FROM `users`");
            //     $select_users->execute();
            //     if ($select_users->rowCount() > 0) {
            //         while ($fetch_users = $select_users->fetch(PDO::FETCH_ASSOC)) {

            //             $u_id =  $fetch_users['u_id'];
            //         }
            //     } else {
            //         echo '<p class="empty">no accounts available!</p>';
            //     }

            //     $cmt_ngay = date('h:i:sa d/m/Y');
            //     insert_cmt($cmt_noidung, $u_id, $products_id, $cmt_ngay);
            //     header("Location: " . $_SERVER['HTTP_REFERER']);
            // }
            ?>

    case 'dsbl':
    $listbinhluan = loadall_binhluan(0);
    include "binhluan/list.php";
    break;
    case 'xoabl':
    if (isset($_GET['id']) && ($_GET['id'] > 0)) {
    delete_binhluan($_GET['id']);
    }
    $listbinhluan = loadall_binhluan(0);
    include "binhluan/list.php";
    break; -->


</div>