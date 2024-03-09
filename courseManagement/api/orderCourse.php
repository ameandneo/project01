<?php
include __DIR__ . '/../parts/PDOconnect.php';

$postData = file_get_contents("php://input");
$data = json_decode($postData, true);

$page=$data['page'];
$orderValue=$data['orderValue'];
$limitPerpage=$data['limitPerpage'];
$otherLimit=$data['otherLimit'];
#先寫好要回應給用戶端的東西格式
// $output = [
//     'success' => false,
//     'file'=>'', //成功後儲存的檔案名稱
//     'postData' => $_POST, #除錯用
//     'error' => '',
//     'code' => 0,
// ];

$output = [
    'success' => false,
    'postData' => $data, #除錯用
];

//得到總筆數
$total_rows_sql = "SELECT count(1) FROM course c1 join user u1 on c1.teacherSN=u1.userID left join promotion p1 on c1.courseID= p1.courseID where promotionSN is null || CURRENT_DATE() BETWEEN whenStarted AND whenEnded";
$total_rows_stmt = $pdo->query("$total_rows_sql");
$totalRows = $total_rows_stmt->fetch(PDO::FETCH_NUM)[0]; 

// 一頁有幾筆資料
$totalPages = ceil($totalRows/$limitPerpage); #總頁數


// 每頁的表格內容
$rows = $pdo->query(sprintf("SELECT c1.courseID,title,intro,syllabus,teacherSN,courseImg,approverID,available,price,userName,promotionName,p1.percentage FROM course c1 join user u1 on c1.teacherSN=u1.userID left join promotion p1 on c1.courseID= p1.courseID where promotionSN is null || CURRENT_DATE() BETWEEN whenStarted AND whenEnded ORDER BY c1.$orderValue ASC LIMIT " . ($page - 1) * $limitPerpage . ",$limitPerpage"))->fetchAll(PDO::FETCH_ASSOC);

$output['totalRows'] = $totalRows;
$output['totalPages'] = $totalPages;
$output['rows'] = $rows;

echo json_encode($output, JSON_UNESCAPED_UNICODE);