<?php 
function loadall_cart_count($order_id)
{
    $sql = "select * from cart where order_id=" . $order_id;
    $order = pdo_query($sql);
    return sizeof($order);
}

function loadall_order($kyw="",$user_id)
{
    $sql="select * from orders where 1";
    if($user_id>0) $sql.=" AND user_id=".$user_id;
    if($kyw!="") $sql.=" AND order_name like'%".$kyw."%'";
    $sql.=" order by placed_on desc";
    
    $dsdonhang=pdo_query($sql);
    return $dsdonhang;

}

// function loadall_search_order($search_box="",$user_id){
   
//     $sql="select * from orders where 1";
//     if($user_id>0) $sql.=" AND user_id=".$user_id;
//     if($search_box!="") $sql.=" AND order_name like '%".$search_box."%'";
//     $sql.=" order by order_id desc";
//     $dsdonhang=pdo_query($sql);
//     return $dsdonhang;
// }

function loadone_order($order_id)
{
    $sql = "select * from orders where order_id=" . $order_id;
    $order = pdo_query_one($sql);
    return $order;
}

function delete_donhang($order_id)
{
    $sql = "delete from orders where idbill=" . $order_id;
    pdo_execute($sql);
}

// function   update_donhang($idbill,$bill_name,$bill_address,$bill_email,$bill_tel,$total,$ngaydathang)
// {
//     $sql = "update bill set bill_name='" . $bill_name . "',bill_address='" . $bill_address . "',bill_email='" . $bill_email . "',bill_tel='" . $bill_tel . "',total='" . $total . "',ngaydathang='".$ngaydathang."' where idbill=" . $idbill;
//     pdo_execute($sql);
// }