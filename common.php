<?php
/**
 * 公共提示信息
 */
function alertMes($mes,$url)
{
    echo "<script>alert('{$mes}'); location.href='{$url}'</script>";
}