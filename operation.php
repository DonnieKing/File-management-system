<?php
/**
 * 操作配置
 */
include "common.php";
// 判断点击操作
if ($act == "创建文件") {
    // 创建文件操作
    $mes = create_file($path . "/" . $filename);
    // 设置提示弹窗
    alertMes($mes, $url);
    // 判断点击操作
} elseif ($act == "showContent") {
    // 查询文件内容
    $content = read_file($filename);
    // 判断文件是否有内容
    if (strlen($content)) {
        // 给字符串添加高亮
        $newContent = highlight_string($content, true);
        // 制作显示表格
        $str = <<<HERE
    <table width="100%" bgcolor="#ffc0cb" cellpadding="5" cellspacing="0">
    <tr>
    <td>{$newContent}</td>
</tr>
</table>
HERE;
        echo $str;
    } else {
        alertMes('文件为空！请编辑后再查看~', $url);
    }
    // 判断点击操作
} elseif ($act == "editContent") {
    // 获取文件内容
    $content = file_get_contents($filename);
    // 制作表单
    $str = <<<HERE
<form action="index.php?act=doEdit" method="post">
    <textarea cols="180" rows="10" name="content">{$content}</textarea>
    <input type="hidden" name="filename" value="{$filename}">
    <input type="submit" value="修改内容">
</form>
HERE;
    echo $str;
    // 判断操作
} elseif ($act == "doEdit") {
    // 获取修改内容
    $content = $_REQUEST['content'];
    // 进行修改并判断
    if (file_put_contents($filename, $content)) {
        $mes = '文件修改成功！';
    } else {
        $mes = '文件修改失败！';
    }
    alertMes($mes, $url);
} elseif ($act == "renameFile") {
    $str = <<<HERE
<form action="index.php?act=doRename" method="post">
请输入文件新名称：<input type="text" name="newName" placeholder="输入新名称">
<input type="hidden" name="path" value="{$path}">
<input type="hidden" name="filename" value="{$filename}">
<input type="submit" value="重命名">
</form>
HERE;
    echo $str;
    // 判断操作
} elseif ($act == "doRename") {
    // 接收新名称
    $newName = $_REQUEST['newName'];
    // 进行修改文件名
    var_dump($path);
    $mes = rename_file($filename, $path . "/" . $newName);
    // 提示操作
    alertMes($mes, $url);
    // 判断点击操作
} elseif ($act == "copyFile") {
    // 制作表单
    $str = <<<HERE
<form action="index.php?act=doCopyFile" method="post">
文件复制到：<input type="text" name="destName" placeholder="将文件复制到">
<input type="hidden" name="path" value="{$path}">
<input type="hidden" name="filename" value="{$filename}">
<input type="submit" value="复制文件">
</form>
HERE;
    echo $str;
    // 判断操作
} elseif ($act == "doCopyFile") {
    // 接收目标目录
    $destName = $_REQUEST['destName'];
    // 进行复制操作
    $mes = copy_file($filename, $path . "/" . $destName);
    // 提示操作
    alertMes($mes, $url);
} elseif ($act == "cutFile") {
    $str = <<<HERE
<form action="index.php?act=doCutFile" method="post">
文件剪切到：<input type="text" name="destName" placeholder="将文件剪切到">
<input type="hidden" name="path" value="{$path}">
<input type="hidden" name="filename" value="{$filename}">
<input type="submit" value="剪切文件">
</form>
HERE;
    echo $str;
} elseif ($act == "doCutFile") {
    $destName = $_REQUEST['destName'];
    $mes = cut_file($filename, $path . "/" . $destName);
    alertMes($mes, $url);
} elseif ($act == "dowFile") {
    $mes = dow_file($filename);
} elseif ($act == "delFile") {
    if (unlink($filename)) {
        alertMes("文件删除成功！", $url);
    } else {
        alertMes("文件删除失败！", $url);
    }
} elseif ($act == "上传文件") {
    $fileInfo = $_FILES['myFile'];
    $mes = upload_file($fileInfo, $path);
    alertMes($mes, $url);
} elseif ($act == "创建文件夹") {
    $mes = create_folder($path . "/" . $dirname);
    alertMes($mes, $url);
} elseif ($act == "renameFolder") {
    $str = <<<HERE
<form action="index.php?act=doRenameFolder" method="post">
重命名为：<input type="text" name="newFolderName" placeholder="请输入文件夹名称">
<input type="hidden" name="dirname" value="{$dirname}">
<input type="hidden" name="path" value="{$path}">
<input type="submit" value="重命名">
</form>
HERE;
    echo $str;
} elseif ($act == "doRenameFolder") {
    $newFolderName = $_REQUEST['newFolderName'];
    $mes = rename_dir($dirname, $path . "/" . $newFolderName);
    alertMes($mes, $url);

} elseif ($act == "copyFolder") {
    $str = <<<HERE
<form action="index.php?act=doCopyFolder" method="post">
复制为：<input type="text" name="newFolderName" placeholder="复制文件夹到">
<input type="hidden" name="dirname" value="{$dirname}">
<input type="hidden" name="path" value="{$path}">
<input type="submit" value="复制">
</form>
HERE;
    echo $str;
} elseif ($act == "doCopyFolder") {
    $newFolderName = $_REQUEST['newFolderName'];
    $mes = copy_dir($dirname, $path . "/" . $newFolderName . "/" . basename($dirname));
    alertMes($mes, $url);
} elseif ($act == "cutFolder") {
    $str = <<<HERE
<form action="index.php?act=doCutFolder" method="post">
剪切为：<input type="text" name="newFolderName" placeholder="剪切文件夹到">
<input type="hidden" name="dirname" value="{$dirname}">
<input type="hidden" name="path" value="{$path}">
<input type="submit" value="剪切">
</form>
HERE;
    echo $str;
} elseif ($act == "doCutFolder") {
    $newFolderName = $_REQUEST['newFolderName'];
    $mes = cut_dir($dirname, $path . "/" . $newFolderName);
    alertMes($mes, $url);
} elseif ($act == "delFolder") {
    $mes = del_folder($dirname);
    alertMes($mes, $url);
}