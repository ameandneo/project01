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
$rows = $pdo->query(sprintf("SELECT * FROM course ORDER BY courseID ASC LIMIT " . ($page - 1) * $perPage . ",$perPage"))->fetchAll(PDO::FETCH_ASSOC);

// 促銷狀態:


// 選頁
?>

<div class="container-right caption-top">
  <caption><span>共10筆/顯示5筆</span></caption>
  <table class="table table-hover table-bordered">
    <thead>
      <tr>
        <th scope="col">courseID</th>
        <th scope="col">Title</th>
        <th scope="col">教師</th>
        <th scope="col">Price</th>
        <th scope="col">上架狀態</th>
        <th scope="col">促銷狀態</th>
        <th scope="col">售出數量</th>
        <th scope="col">操作</th>
        <th scope="col">編輯</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($rows as $r) : ?>
        <tr>
          <th scope="row"><?= $r['courseID'] ?></th>
          <td><?= $r['title'] ?></td>
          <td><?= $r['teacherSN'] ?></td>
          <td><?= $r['price'] ?></td>
          <td><?= $r['approverID'] ? ($r['available'] ? '已上架' : '<span class="text-body-tertiary">已下架</span>') : '<span class="text-danger">未審核</span>' ?></td>
          <td><?= $r['approverID'] ?></td>
          <td><?= $r['courseID'] ?></td>
          <td><button class="btn <?= $r['approverID'] ? ($r['available'] ? 'btn-secondary' : 'btn-launch') : 'btn-success' ?>">
              <?= $r['approverID'] ? ($r['available'] ? '下架' : '上架') : '核准' ?></button></td>
          <td><button class="btn btn-primary"><i class="fa-solid fa-pen-to-square"></i>查看</button></td>

        </tr>
        <tr>
        <?php endforeach; ?>
    </tbody>
  </table>
  <!-- 選頁 -->
  <nav class="d-flex gap-3 page-nav align-items-center">
    <button class="btn btn-primary rounded-5">
      <span class="page-link">Previous</span>
    </button>
    <!-- 選數字頁 -->
    <!-- <ul class="pagination m-0">

      <li class="page-item "><a class="page-link ps-3 rounded-start-pill " href="#">1</a></li>
      <li class="page-item active" aria-current="page">
        <span class="page-link">2</span>
      </li>
      <li class="page-item"><a class="page-link pe-3 rounded-end-circle " href="#">3</a></li>
    </ul> -->
    <button class="btn btn-primary rounded-5">
      <a class="page-link" href="#">Next</a>
    </button>
  </nav>
</div>