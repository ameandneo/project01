<?php
$title = '所有課程';
$pageName = 'list';
include __DIR__ . '/parts/PDOconnect.php';
// // 第一次$page為1
$page  = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
    header('Location: ?page=1');
    exit;
}
$total_rows_sql = "SELECT count(1) from course";
$total_rows_stmt = $pdo->query("$total_rows_sql");
$totalRows = $total_rows_stmt->fetch(PDO::FETCH_NUM)[0]; //得到總筆數


// 一頁有幾筆資料
$perPage = 10;
$totalPages = ceil($totalRows / $perPage); #總頁數


if ($totalRows > 0) {
    if ($page > $totalPages) {
        header('Location: ?page=' . $totalPages); //若page大於200就跳到page=200，然後結束
        exit;
    }
}

// 每頁的表格內容
$rows = $pdo->query(sprintf("SELECT c1.courseID,title,intro,syllabus,teacherSN,courseImg,approverID,available,price,userName,promotionName,p1.percentage FROM course c1 join user u1 on c1.teacherSN=u1.userID left join promotion p1 on c1.courseID= p1.courseID where promotionSN is null || CURRENT_DATE() BETWEEN whenStarted AND whenEnded ORDER BY c1.courseID ASC LIMIT " . ($page - 1) * $perPage . ",$perPage"))->fetchAll(PDO::FETCH_ASSOC);

// 促銷狀態:SELECT * FROM course c1 join user u1 on c1.teacherSN=u1.userID left join promotion p1 on c1.courseID= p1.courseID where promotionSN is null || CURRENT_DATE() BETWEEN whenStarted AND whenEnded;

// 若現在時間在促銷時間範圍內的promotion的courseID等於course.courseID

// 選頁
?>
<?php


include __DIR__ . '/parts/html-sidebar.php';
include __DIR__ . '/parts/html-table.php'; ?>

<!-- log看看 -->
<script>
    const myRows = <?= json_encode($rows, JSON_UNESCAPED_UNICODE) ?>;
    console.log(myRows);
</script>

<?php include __DIR__ . '/parts/html-foot.php';
