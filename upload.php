<?php
// 关键：添加 UTF-8 编码头（确保中文正常输出）
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// 仅允许 POST 请求
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // 直接输出中文，不转义
    echo json_encode(['status' => 'error', 'msg' => '仅支持 POST 上传请求'], JSON_UNESCAPED_UNICODE);
    exit;
}

$uploadDir = __DIR__ . '/uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$uploadedFiles = [];
$errors = [];

if (isset($_FILES['files'])) {
    foreach ($_FILES['files']['name'] as $key => $name) {
        $tmpName = $_FILES['files']['tmp_name'][$key];
        $size = $_FILES['files']['size'][$key];
        $error = $_FILES['files']['error'][$key];

        if ($error !== UPLOAD_ERR_OK) {
            $errors[] = "文件 $name 上传失败：错误码 $error";
            continue;
        }

        $ext = pathinfo($name, PATHINFO_EXTENSION);
        $uniqueName = uniqid() . '.' . $ext;
        $targetPath = $uploadDir . $uniqueName;

        if (move_uploaded_file($tmpName, $targetPath)) {
            $fileUrl = 'http://psro.top/uploads/' . $uniqueName;
            $uploadedFiles[] = [
                'name' => $name,
                'url' => $fileUrl,
                'size' => round($size / 1024 / 1024, 1) . 'MB'
            ];
        } else {
            $errors[] = "文件 $name 保存失败，请检查目录权限";
        }
    }
}

// 关键：添加 JSON_UNESCAPED_UNICODE 参数，禁止 Unicode 转义
if (empty($errors) && !empty($uploadedFiles)) {
    echo json_encode([
        'status' => 'success',
        'msg' => '所有文件上传成功',
        'files' => $uploadedFiles
    ], JSON_UNESCAPED_UNICODE); // 核心参数
} else {
    echo json_encode([
        'status' => 'error',
        'msg' => empty($errors) ? '未收到有效文件' : implode('；', $errors),
        'files' => $uploadedFiles
    ], JSON_UNESCAPED_UNICODE); // 核心参数
}
?>
