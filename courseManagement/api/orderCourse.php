<?php
include __DIR__ . '/../parts/PDOconnect.php';

$postData = file_get_contents("php://input");
$data = json_decode($postData, true); //true轉換為php陣列，否則轉為php物件

$page = $data['page'];
$orderValue = $data['orderValue'];
$limitPerpage = $data['limitPerpage'];
$otherLimit = $data['otherLimit'];
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
$total_rows_sql = "SELECT count(1) FROM course";
$total_rows_stmt = $pdo->query("$total_rows_sql");
$totalRows = $total_rows_stmt->fetch(PDO::FETCH_NUM)[0];

// 一頁有幾筆資料
$totalPages = ceil($totalRows / $limitPerpage); #總頁數


// 每頁的表格內容
$rows = $pdo->query(sprintf("SELECT c1.courseID,title,intro,syllabus,teacherSN,courseImg,approverID,available,price,userName FROM course c1 join user u1 on c1.teacherSN=u1.userID ORDER BY c1.$orderValue ASC LIMIT " . ($page - 1) * $limitPerpage . ",$limitPerpage"))->fetchAll(PDO::FETCH_ASSOC);


foreach ($rows as &$r) { //在 foreach 循環中無法直接修改 $r 變量。因為 $r 是從 $rows 數組中循環出來的元素的副本，而不是原始數組。因此，對$r的更改不會影響到原始數組。但&$r可以
    //售出數
    $soldCount = $pdo->query(sprintf("SELECT COUNT(*) FROM payment WHERE courseID =" . $r['courseID'] . ";"))->fetch(PDO::FETCH_NUM)[0]; #總筆數
    $r['soldCount'] = $soldCount;
    $promotionName = $pdo->query(sprintf("SELECT promotionName FROM promotion p1 join course c1 on c1.courseID= p1.courseID where c1.courseID =" . $r['courseID'] . " && CURRENT_DATE() BETWEEN whenStarted AND whenEnded;"))->fetchAll(PDO::FETCH_ASSOC);
    $r['promotionName'] = $promotionName ? $promotionName:'-';
};



$output['totalRows'] = $totalRows;
$output['totalPages'] = $totalPages;
$output['rows'] = $rows;

echo json_encode($output, JSON_UNESCAPED_UNICODE);
