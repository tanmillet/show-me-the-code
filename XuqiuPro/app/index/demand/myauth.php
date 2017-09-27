<?php


    $demandstatus  = $_GET['demandstatus'];
    $demandop = $_GET['op'];

    switch($demandstatus){
        case 1:
            //需求未开始
        case 2:
            //需求已激活
            break;
        case 3:
            //需求进行中 不可以编辑 不可以变更优先度
            $op_arr = array('BJ','YXD');
            if(in_array($demandop,$op_arr)){
                echo json_encode(array("success"=>0,"error"=>"需求进行中...操作无效!"));
                exit;
            }
            break;
        case 4:
            //需求已完成 不可以编辑 不可以变更优先度 不可以分配 不可以点击开始
            $op_arr = array('JS','BJ','FP','YXD','KS');
            if(in_array($demandop,$op_arr)){
                echo json_encode(array("success"=>0,"error"=>"需求已验收...操作无效!"));
                exit;
            }
            break;
        case 5:
            //需求已关闭 不可以编辑 不可以变更优先度 不可以分配 不可以点击开始 不可以点击结束 不可以点击重置 不可以评论
            $op_arr = array('GB','FP','BJ','PL','YXD','KS','JS','CZ');
            if(in_array($demandop,$op_arr)){
                echo json_encode(array("success"=>0,"error"=>"需求已验收...操作无效!")); exit;
            }
            break;
        default :
            break;
    }


