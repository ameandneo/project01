<!-- 表格內容 -->


<div class="container-right">
    <h4>課程列表</h4>
    
    <!-- 排序 -->
    <div class="hstack gap-3">
        <div class="p-2 rounded-4 bg-white py-2 function-wrap">
        <h5>共<?= $totalRows ?>筆/每頁顯示<?= $perPage ?>筆</h5>
    </div>
        <div class="p-2 rounded-4 bg-white py-2 function-wrap"><h5>排序</h5></div>
        <div class="p-2 rounded-4 bg-white py-2 function-wrap"><h5>顯示方式</h5></div>
    </div>
    <div class="table-wrap rounded-5">
        <table class="table m-0  pe-2">
            <thead class="text-center">
                <tr>
                    <th scope="col" style="width: 85px;">
                        <h4>編號</h4>
                    </th>
                    <th scope="col" style="width: 550px;">
                        <h4>標題</h4>
                    </th>
                    <th scope="col">
                        <h4>教師</h4>
                    </th>
                    <th scope="col">
                        <h4>現價</h4>
                    </th>
                    <th scope="col" class="text-center">
                        <h4>上架狀態</h4>
                    </th>
                    <th scope="col" class="text-center">
                        <h4>促銷狀態</h4>
                    </th>
                    <th scope="col">
                        <h4>售出數量</h4>
                    </th>
                    <th scope="col" class="text-center">
                        <h4>操作</h4>
                    </th>
                    <th scope="col" class="text-center" style="width: 140px;">
                        <h4>編輯</h4>
                    </th>
                </tr>
            </thead>
        </table>
        <div class="table-data-wrap">
            <table class="table table-hover table-sm">

                <tbody class="text-center">
                    <?php foreach ($rows as $r) : ?>
                        <tr>
                            <th style="width: 87px;" scope="row"><?= $r['courseID'] ?></th>
                            <td style="width: 550px;"><?= $r['title'] ?></td>
                            <td style="width: 120px;"><?= $r['userName'] ?></td>
                            <td style="width: 120px;"><?= $r['promotionName'] ? '<span class="text-decoration-line-through"style="font-size:16px" >' . $r['price'] . '</span><span class="text-warning">' . $r['price'] * $r['percentage'] / 100 . '</span>' : $r['price'] ?></td>
                            <td class="text-center"><?= $r['approverID'] ? ($r['available'] ? '<span class="text-success">已上架</span>' : '<span class="text-body-tertiary">下架</span>') : '<span class="text-danger">未審核</span>' ?></td>
                            <td class="text-center"><?= $r['promotionName'] ? '<span class="text-warning">' . $r["promotionName"] . '</span>' : "-" ?></td>
                            <td class="text-end"><?php
                                                    $soldRows = $pdo->query(sprintf("SELECT COUNT(*) FROM payment WHERE courseID =" . $r['courseID'] . ";"))->fetch(PDO::FETCH_NUM)[0]; #總筆數
                                                    echo $soldRows;
                                                    ?></td>
                            <td style="width: 123px;" class="text-center"><button class="btn <?= $r['approverID'] ? ($r['available'] ? 'btn-secondary' : 'btn-launch') : 'btn-success' ?>">
                                    <?= $r['approverID'] ? ($r['available'] ? '<i class="fa-solid fa-arrow-turn-down me-2"></i>下架' : '<i class="fa-solid fa-arrow-up-from-bracket me-2"></i>上架') : '<i class="fa-solid fa-check me-2"></i>核准' ?></button></td>
                            <!-- 編輯按鈕 -->
                            <td style="width: 123px;" class="text-center"><a class="btn btn-primary" href="edit.php?courseID=<?= $r['courseID'] ?> "><i class="fa-solid fa-pen-to-square me-2 "></i>編輯</a></td>

                        </tr>
                        
                        <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- 選頁 -->
    <nav class="d-flex gap-3 page-nav align-items-center">
        <a class="btn btn-primary rounded-5 <?= $page !== 1 ?: 'disabled';
                                            //若已經是最前頁就關閉按鈕
                                            ?>" href="?page=<?= $page - 1 ?>">
            <button class="page-link">上一頁</button>
        </a>
        <ul class="pagination -sm m-0">
            <?php for ($i = $page - $perPage; $i <= $page + $perPage; $i++) : ?>
                <!-- 選數字頁 -->
                <?php if ($i >= 1 && $i <= $totalPages) : ?>

                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endif ?>
            <?php endfor ?>
        </ul>

        <a class="btn btn-primary rounded-5 
        <?= $page == $totalPages ? 'disabled' : ''; ?>" href="?page=<?= $page + 1 ?>">
            <button class="page-link ">下一頁</button>
        </a>
        <span>共<?= $totalPages ?>頁 </span>
    </nav>
</div>