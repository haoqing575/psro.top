<?php
// 获取当前时间戳
$timestamp = round(microtime(true) * 1000);

// 定义数据
$data = array(
    "actiontype" => "0",
    "message" => $timestamp,
    "launchFromYingDi" => "1"
);

// 将数组编码为 JSON 字符串
$jsonData = json_encode($data);

// 将 JSON 数据 Base64 编码
$encodedData = base64_encode($jsonData);

// 构造 URL
$url = "tencentmsdk1104466820://?gamedata=camplaunch_" . $encodedData . "&preAct_time=$timestamp&launchfrom=sq_gamecenter&big_brother_source_key=biz_src_zf_games&_wxobject_message_ext=WX_GameCenter";

// 打印 URL（如果需要查看生成的 URL）
//echo $url;

// 如果您想通过 PHP 直接在浏览器中打开该 URL，可以使用 header 重定向
header("Location: $url");
exit;