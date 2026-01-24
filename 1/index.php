<?php
// 构造 URL
$url = "tencentmsdk1104466820://?gamedata=JUNMFORM_305_4_4010";

// 打印 URL（如果需要查看生成的 URL）
//echo $url;

// 如果您想通过 PHP 直接在浏览器中打开该 URL，可以使用 header 重定向
header("Location: $url");
exit;