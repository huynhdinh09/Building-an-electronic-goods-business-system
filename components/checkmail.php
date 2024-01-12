<?php
function checkemail($u_email)
{
    
    $sql = "select * from users where u_email='" . $u_email."' ";
    $sp = pdo_query_one($sql);
    return $sp;
}