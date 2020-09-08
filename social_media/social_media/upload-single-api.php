<?php
require __DIR__. '/parts/__connect_db.php';
 header('Content-Type: application/json');
$path = __DIR__. '/uploads/';
$output = [
    'success' => false,
    'error' => '沒有上傳檔案',
    'filename' => ''
];
$ext = '';
if(!empty($_FILES) && !empty($_FILES['myfile']['name'])){
    $filename1 = md5($_FILES['myfile']['name']. uniqid());
    switch($_FILES['myfile']['type']){
        case 'image/png':
            $ext = '.png';
            break;
        case 'image/jpeg':
            $ext = '.jpg';
            break;
        case 'image/gif':
            $ext = '.gif';
            break;
        default:
            $output['error'] = '檔案格式不符';
            echo json_encode($output, JSON_UNESCAPED_UNICODE); exit;
    }
    $output['filename'] = $filename1. $ext;
    if(! move_uploaded_file( $_FILES['myfile']['tmp_name'],
        $path. $filename1. $ext)){
        $output['error'] = '無法搬移檔案';
    } else {
        $output['success'] = true;
        $output['error'] = '';
    }
}
echo json_encode($output, JSON_UNESCAPED_UNICODE);