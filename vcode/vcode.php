<?php

$class = isset($_GET['class']) && $_GET['class'] ? $_GET['class'] : '';

switch ($class)
{
    case 'get':
        getVcode();
        break;
    default:
        verify();
        break;
}


function getVcode()
{
    include "Verification.php";
    $obj = new Verification();
    $obj->showImage();
    setcookie('demo-vcode',$obj->getResult(),time()+36000000,'/');
}

function verify()
{
    $result = $_COOKIE['demo-vcode'];
    if(isset($_GET['code']) && $_GET['code'] && $_GET['code'] == $result)
    {
        echo '验证成功';
    } else {
        echo '验证失败';
    }
}
