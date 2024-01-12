<?php
include('../components/connect.php');
require('../cacbon/autoload.php');

use Carbon\Carbon;
use Carbon\CarbonInterval;

if (isset($_POST['thoigian'])) {
   $thoigian = $_POST['thoigian'];
} else {
   $thoigian = '';
   $subdays  = Carbon::now('Asia/Ho_Chi_Minh')->subDays(365)->toDateString();
}

if ($thoigian == '365ngay') {
   $subdays  = Carbon::now('Asia/Ho_Chi_Minh')->subDays(365)->toDateString();
} elseif ($thoigian == '7ngay') {
   $subdays  = Carbon::now('Asia/Ho_Chi_Minh')->subDays(7)->toDateString();
} elseif ($thoigian == '28ngay') {
   $subdays  = Carbon::now('Asia/Ho_Chi_Minh')->subDays(28)->toDateString();
} elseif ($thoigian == '90ngay') {
   $subdays  = Carbon::now('Asia/Ho_Chi_Minh')->subDays(90)->toDateString();
}

$now = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();

$total_completes = 0;

$select_thongke = $conn->prepare("SELECT  SUM(total_price) as tien,placed_on FROM `orders` WHERE date(placed_on) BETWEEN '$subdays' AND '$now'  group by placed_on ASC");
$select_thongke->execute();
if ($select_thongke->rowCount() > 0) {
   while ($fetch_thongke = $select_thongke->fetch(PDO::FETCH_ASSOC)) {
      $order = $select_thongke->rowCount();
      $total_completes = $fetch_thongke['tien'];
      $chart_data[] = array(
         'placed_on' => $fetch_thongke['placed_on'],

         'total_price' => $total_completes

      );
   }
}


//print_r($chart_data);
echo $data = json_encode($chart_data);
