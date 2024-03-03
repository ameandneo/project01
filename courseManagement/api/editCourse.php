<?php
include __DIR__ . '/../parts/PDOconnect.php';

$dir = __DIR__ . '/../uploads/'; # 存放檔案的資料夾
$exts = [   # 檔案類型的篩選 給附檔名
    'image/jpeg' => '.jpg',
    'image/png' =>  '.png',
    'image/webp' => '.webp',
];

#先寫好要回應給用戶端的東西格式
$output = [
    'success' => false,
    'file' => '', //成功後儲存的檔案名稱
    'postData' => $_POST, #除錯用
    'error' => '',
    'code' => 0,
];


# 保存檔案，確保有上傳檔案，並且有imgFile 欄位，並且沒有錯誤
if (!empty($_FILES) and !empty($_FILES['imgFile']) and $_FILES['imgFile']['error'] == 0) {
    # 如果類型有對應到副檔名
    $type = $_FILES['imgFile']['type']; //可能是image/jpeg,image/png或image/webp
    if (!empty($exts[$type])) { # 如果上船的檔案類型有對應到$exts裡的值
        $ext = $exts[$type]; # 副檔名
        $f = sha1($_FILES['imgFile']['name'] . uniqid()); # 隨機的主檔名
        if (move_uploaded_file($_FILES['imgFile']['tmp_name'], $dir . $f . $ext)) {
            $output['success'] = true;
            $output['file'] = $f . $ext;
        }
    }
}
//開始寫進資料庫
// 避免SQL injection: 先prepare再execute，把單引號便跳脫字元
$sql =
    "INSERT INTO `course`(`title`, `intro`, `syllabus`, `teacherSN`, `courseImg`, `price`,`whenApply`,whenApproved,approverID ) 
VALUES (
?,?,?,?,?,?,
NOW(),NOW(),'2'
)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    $_POST['title'],
    $_POST['intro'],
    $_POST['syllabus'],
    $_POST['userID'],
    $f . $ext,
    $_POST['price'],
]);
$output['success'] = '成功的筆數' . $stmt->rowCount();


header('Content-Type: application/json');
// 防止亂碼
echo json_encode($output, JSON_UNESCAPED_UNICODE);
