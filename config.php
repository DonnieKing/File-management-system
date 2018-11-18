<?php
/**
 * 配置文件
 */
// 引入方法类库
include "function.php";
// 设置主目录
$path = "file";
// 读取主目录内容
@$path = $_REQUEST['path'] ? $_REQUEST['path'] : $path;

$data = read_dir($path);
// 判断目录是否有内容
if (!$data) {
    echo "<script>alert('无文件或目录！')</script>";
}

// 判断点击操作
@$act = $_REQUEST['act'];
// 接收创建的文件名
@$filename = $_REQUEST['filename'];
@$dirname = $_REQUEST['dirname'];
// 拼接主页地址
@$url = "index.php?path={$path}";