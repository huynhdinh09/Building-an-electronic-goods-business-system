
<?php
function insert_address($listaddress_content,$order_id,$u_id){
    $sql="insert into listaddress(listaddress_content,order_id,u_id) values('$listaddress_content','$order_id','$u_id')";
    pdo_execute($sql);
}

function loadall_address($order_id){

    $sql="select * from listaddress where 1"; 
    if($order_id>0) $sql.=" AND order_id='".$order_id."'";
    $sql.=" order by listaddress_id desc";
    $listaddress=pdo_query($sql);
    return $listaddress;
}

function delete_address($listaddress_id){
    $sql="delete from listaddress where listaddress_id=".$listaddress_id;
    pdo_execute($sql);
};