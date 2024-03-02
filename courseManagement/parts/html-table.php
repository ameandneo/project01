<!-- 表格內容 -->
<!-- 所有筆數 -->
<?php $total_rows_sql = "SELECT count(1) from course";
$total_rows_stmt = $pdo->query("$total_rows_sql");
$totalRows = $total_rows_stmt->fetch(PDO::FETCH_NUM)[0]; //得到總筆數

// 第一次$page為1
$page  = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
  header('Location: ?page=1');
  exit;
}
// 一頁有幾筆資料
$perPage = 10;
$total_pages = ceil($totalRows / $perPage); #總頁數



// 每頁的表格內容
$rows = $pdo->query(sprintf("SELECT c1.courseID,title,intro,syllabus,teacherSN,courseImg,approverID,available,price,userName,promotionName FROM course c1 join user u1 on c1.teacherSN=u1.userID left join promotion p1 on c1.courseID= p1.courseID where promotionSN is null || CURRENT_DATE() BETWEEN whenStarted AND whenEnded;
ORDER BY c1.courseID ASC LIMIT " . ($page - 1) * $perPage . ",$perPage"))->fetchAll(PDO::FETCH_ASSOC);

// 促銷狀態:SELECT * FROM course c1 join user u1 on c1.teacherSN=u1.userID left join promotion p1 on c1.courseID= p1.courseID where promotionSN is null || CURRENT_DATE() BETWEEN whenStarted AND whenEnded;

// 若現在時間在促銷時間範圍內的promotion的courseID等於course.courseID

// 選頁
?>

<div class="container-right">
  <h4>課程列表</h4>
  <caption>
    <h5>共10筆/顯示5筆</h5>
  </caption>
  <table class="table table-hover caption-top table-sm ">
    <thead>
      <tr>
        <th scope="col" style="max-width: fit-content;">課程編號</th>
        <th scope="col">標題</th>
        <th scope="col">教師</th>
        <th scope="col">現價</th>
        <th scope="col">上架狀態</th>
        <th scope="col">促銷狀態</th>
        <th scope="col">售出數量</th>
        <th scope="col">操作</th>
        <th scope="col">編輯</th>
      </tr>
    </thead>
    <tbody class="table-group-divider">
      <?php foreach ($rows as $r) : ?>
        <tr>
          <th scope="row"><?= $r['courseID'] ?></th>
          <td><?= $r['title'] ?></td>
          <td><?= $r['userName'] ?></td>
          <td><?= $r['price'] ?></td>
          <td><?= $r['approverID'] ? ($r['available'] ? '<span class="text-success">已上架</span>' : '<span class="text-body-tertiary">已下架</span>') : '<span class="text-danger">未審核</span>' ?></td>
          <td><?= $r['promotionName'] ? '<span class="text-warning">' . $r["promotionName"] . '</span>' : "-" ?></td>
          <td><?php
              $soldRows = $pdo->query(sprintf("SELECT COUNT(*) FROM payment WHERE courseID =" . $r['courseID'] . ";"))->fetch(PDO::FETCH_NUM)[0]; #總筆數
              echo $soldRows;
              ?></td>
          <td><button class="btn <?= $r['approverID'] ? ($r['available'] ? 'btn-secondary' : 'btn-launch') : 'btn-success' ?>">
              <?= $r['approverID'] ? ($r['available'] ? '<i class="fa-solid fa-arrow-turn-down me-2"></i>下架' : '<i class="fa-solid fa-arrow-up-from-bracket me-2"></i>上架') : '<i class="fa-solid fa-check me-2"></i>核准' ?></button></td>
          <td><button class="btn btn-primary"><i class="fa-solid fa-pen-to-square me-2 "></i>查看</button></td>

        </tr>
        <tr>
        <?php endforeach; ?>
    </tbody>
  </table>
  <!-- 選頁 -->
  <nav class="d-flex gap-3 page-nav align-items-center">
    <button class="btn btn-primary rounded-5">
      <span class="page-link">上一頁</span>
    </button>
    <!-- 選數字頁 -->
    <ul class="pagination -sm m-0">
      <li class="page-item active" aria-current="page">
        <span class="page-link">1</span>
      </li>
      <li class="page-item"><a class="page-link" href="#">2</a></li>
      <li class="page-item"><a class="page-link" href="#">3</a></li>
    </ul>
    <!-- <ul class="pagination m-0">

      <li class="page-item "><a class="page-link ps-3 rounded-start-pill " href="#">1</a></li>
      <li class="page-item active" aria-current="page">
        <span class="page-link">2</span>
      </li>
      <li class="page-item"><a class="page-link pe-3 rounded-end-circle " href="#">3</a></li>
    </ul> -->
    <button class="btn btn-primary rounded-5">
      <a class="page-link" href="#">下一頁</a>
    </button>
  </nav>
</div>
<!-- log看看 -->
<script>
  const myRows = <?= json_encode($rows, JSON_UNESCAPED_UNICODE) ?>;
  console.log(myRows);
</script>