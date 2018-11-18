<?php
// 引入路径配置
include "config.php";
// 引入操作配置
include "operation.php";
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PHP中文网—Web在线文件管理器</title>
    <link rel="stylesheet" href="css/cikonss.css"/>
    <link rel="stylesheet" href="css/style.css"/>
    <link rel="stylesheet" href="css/jquery-ui-1.10.4.custom.css" type="text/css"/>
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/jquery-ui-1.10.4.custom.js"></script>
    <script src="js/action.js"></script>
</head>
<body>
<h1 align="center">PHP中文网—Web在线文件管理器</h1>
<div id="showDetail" style="display:none"><img src="" id="showImg" alt=""/></div>
<div id="top">
    <ul id="navi">
        <li><a href="index.php" title="主目录"><span style="margin-left: 8px; margin-top: 0px; top: 4px;"
                                                  class="icon icon-small icon-square"><span
                            class="icon-home"></span></span></a></li>
        <li><a href="#" onclick="show('createFile')" title="新建文件"><span
                        style="margin-left: 8px; margin-top: 0px; top: 4px;" class="icon icon-small icon-square"><span
                            class="icon-file"></span></span></a></li>
        <li><a href="#" onclick="show('createFolder')" title="新建文件夹"><span
                        style="margin-left: 8px; margin-top: 0px; top: 4px;" class="icon icon-small icon-square"><span
                            class="icon-folder"></span></span></a></li>
        <li><a href="#" onclick="show('uploadFile')" title="上传文件"><span
                        style="margin-left: 8px; margin-top: 0px; top: 4px;" class="icon icon-small icon-square"><span
                            class="icon-upload"></span></span></a></li>
        <?php
        $back = $data == "file" ? "file" : dirname($path);
        ?>
        <li><a href="#" title="返回上级目录" onclick="goBack('<?php echo $back?>')"><span
                        style="margin-left: 8px; margin-top: 0px; top: 4px;" class="icon icon-small icon-square"><span
                            class="icon-arrowLeft"></span></span></a></li>
    </ul>
</div>
<form action="index.php" method="post" enctype="multipart/form-data">
    <table width="100%" border="1" cellpadding="5" cellspacing="0" bgcolor="#ABCDEF" align="center">
        <tr id="createFolder" style="display:none;">
            <td>请输入文件夹名称</td>
            <td>
                <input type="text" name="dirname"/>
                <input type="hidden" name="path" value="<?php echo $path?>"/>
                <input type="submit" name="act" value="创建文件夹"/>
            </td>
        </tr>
        <tr id="createFile" style="display:none;">
            <td>请输入文件名称</td>
            <td>
                <input type="text" name="filename"/>
                <input type="hidden" name="path" value="<?php echo $path?>"/>
                <input type="submit" name="act" value="创建文件"/>
            </td>
        </tr>
        <tr id="uploadFile" style="display:none;">
            <td>请选择要上传的文件</td>
            <td><input type="file" name="myFile"/>
                <input type="submit" name="act" value="上传文件"/>
            </td>
        </tr>
    </table>
</form>
<table width="100%" border="1" cellpadding="5" cellspacing="0" bgcolor="#ABCDEF" align="center">
    <tr>
        <th>编号</th>
        <th>名称</th>
        <th>类型</th>
        <th>大小</th>
        <th>可读</th>
        <th>可写</th>
        <th>可执行</th>
        <th>创建时间</th>
        <th>修改时间</th>
        <th>访问时间</th>
        <th>操作</th>
    </tr>
    <?php
    // 判断主目录下的文件
    if (@$data['file']) {
        // 设置起始编号
        $i = 1;
        // 循环遍历主目录下的内容
        foreach ($data['file'] as $v) {
            // 拼接文件路径
            $p = $path . "/" . $v;
            ?>
            <tr>
                <td><?php echo $i;?></td>
                <td><?php echo $v;?></td>
                <td><?php $src = filetype($p)=="file"?"file_ico.png":"folder_ico.png" ?><img src="images/<?php echo $src;?>" title="文件"></td>
                <td><?php echo trans_byte(filesize($p))?></td>
                <td><?php $src = is_readable($p)?"correct.png":"error.png" ?><img class="small" src="images/<?php echo $src;?>"></td>
                <td><?php $src = is_writable($p)?"correct.png":"error.png" ?> <img class="small" src="images/<?php echo $src;?>"></td>
                <td><?php $src = is_executable($p)?"correct.png":"error.png" ?> <img class="small" src="images/<?php echo $src;?>"></td>
                <td><?php echo date("Y-m-d H:i:s",filectime($p))?></td>
                <td><?php echo date("Y-m-d H:i:s",filemtime($p))?></td>
                <td><?php echo date("Y-m-d H:i:s",fileatime($p))?></td>
                <td>
                    <?php
                    $ext = strtolower(pathinfo($v,PATHINFO_EXTENSION));
                    $imagesExt = ['gif','png','jpg','jpeg'];
                    if (in_array($ext,$imagesExt)){
                        ?>
                        <a href="#" onclick="showDetail('<?php echo $v;?>','<?php echo $p;?>')"> <img class="small" src="images/show.png" alt="" title="查看"/></a>
                    <?php
                        }else{
                        ?>
                        <a href="index.php?act=showContent&path=<?php echo $path;?>&filename=<?php echo $p;?>"><img class="small" src="images/show.png" alt="" title="查看"/></a>
                    <?php
                        }
                    ?>
                    <a href="index.php?act=editContent&path=<?php echo $path;?>&filename=<?php echo $p;?>"><img class="small" src="images/edit.png" alt="" title="修改"/></a>
                    <a href="index.php?act=renameFile&path=<?php echo $path; ?>&filename=<?php echo $p;?>"><img class="small" src="images/rename.png" alt="" title="重命名"/></a>
                    <a href="index.php?act=copyFile&path=<?php echo $path; ?>&filename=<?php echo $p;?>" ><img class="small" src="images/copy.png" alt="" title="复制"/></a>
                    <a href="index.php?act=cutFile&path=<?php echo $path; ?>&filename=<?php echo $p;?>"><img class="small" src="images/cut.png" alt="" title="剪切"/></a>
                    <a href="index.php?act=dowFile&path=<?php echo $path; ?>&filename=<?php echo $p;?>"><img class="small" src="images/download.png" alt="" title="下载"/></a>
                    <a href="#" onclick="delFile('<?php echo $p;?>','<?php echo $path; ?>')"><img class="small" src="images/delete.png" alt="" title="删除"/></a>
                </td>
            </tr>
            <?php
            // 增长编号
            $i++;
        }
        }
        // 判断是否有目录
        if (@$data['dir']){
            // 编号
            $i=1;
            // 循环遍历主目录下的内容
            foreach ($data['dir'] as $v){
                // 拼接文件路径
                $p = $path."/".$v;
        ?>
    <tr>
        <td><?php echo $i;?></td>
        <td><?php echo $v?></td>
        <td><?php $src = filetype($p) =="file"?"file_ico.png":"folder_ico.png" ?><img src="images/<?php echo $src;?>" title="文件夹"></td>
        <td><?php $sum=0; echo trans_byte(dir_size($p))?></td>
        <td><?php $src = is_readable($p)?"correct.png":"error.png" ?><img class="small" src="images/<?php echo $src;?>"></td>
        <td><?php $src = is_writable($p)?"correct.png":"error.png" ?> <img class="small" src="images/<?php echo $src;?>"></td>
        <td><?php $src = is_executable($p)?"correct.png":"error.png" ?> <img class="small" src="images/<?php echo $src;?>"></td>
        <td><?php echo date("Y-m-d H:i:s",filectime($p))?></td>
        <td><?php echo date("Y-m-d H:i:s",filemtime($p))?></td>
        <td><?php echo date("Y-m-d H:i:s",fileatime($p))?></td>
        <td>
            <a href="index.php?path=<?php echo $p;?>"><img class="small" src="images/show.png" alt="" title="查看"/></a>
            <a href="index.php?act=renameFolder&path=<?php echo $path;?>&dirname=<?php echo $p;?>"><img class="small" src="images/rename.png" alt="" title="重命名"/></a>
            <a href="index.php?act=copyFolder&path=<?php echo $path;?>&dirname=<?php echo $p;?>"><img class="small" src="images/copy.png" alt="" title="复制"/></a>
            <a href="index.php?act=cutFolder&path=<?php echo $path;?>&dirname=<?php echo $p; ?>"><img class="small" src="images/cut.png" alt="" title="剪切"/></a>
            <a href="#" onclick="delFolder('<?php echo $p; ?>','<?php echo $path?>')"><img class="small" src="images/delete.png" alt="" title="删除"/></a>
        </td>
    </tr>
    <?php
                $i++;
            }
        }?>
</table>
</body>
</html>

