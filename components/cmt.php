
<?php
function insert_cmt($cmt_noidung,$u_id,$products_id,$cmt_ngay){
    $sql="insert into comment(cmt_noidung,u_id,products_id,cmt_ngay) values('$cmt_noidung','$u_id','$products_id','$cmt_ngay')";
    pdo_execute($sql);
}

function loadall_cmt($products_id){

    $sql="select * from comment where 1"; 
    if($products_id>0) $sql.=" AND products_id='".$products_id."'";
    $sql.=" order by cmt_id desc";
    $listcmt=pdo_query($sql);
    return $listcmt;
}

function delete_cmt($cmt_id){
    $sql="delete from comment where cmt_id=".$cmt_id;
    pdo_execute($sql);
};