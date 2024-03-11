<?php
$title = '排序結果';
include __DIR__ . '/parts/PDOconnect.php';
include __DIR__ . '/parts/html-sidebar.php';
?>

<!-- Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="modalTitle">是否核准課程</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="modalBody">
        操作不可逆
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
        <a class="btn btn-primary" id="approveBtn">核准</a>
      </div>
    </div>
  </div>
</div>
<!-- 表格內容 -->


<div class="container-right">
  <h4>課程列表</h4>

  <!-- 排序 -->
  <div class="hstack gap-3">
    <div class="p-2 rounded-4 bg-white py-2 function-wrap hstack">
      <h5 class="m-0">共</h5>
      <h5 id="totalRows" class="m-0"></h5>
      <h5 class="text-nowrap m-0">筆/每頁顯示</h5><select class="form-select mx-2" name="limitPerpageSelect" id="limitPerpageSelect">
        <option value="5">5</option>
        <option value="10" selected>10</option>
        <option value="20">20</option>
      </select>
      <h5 class="m-0">筆</h5>
    </div>
    <div class="p-2 rounded-4 bg-white py-2 function-wrap hstack justify-content-center">
      <h5 class="m-0 text-nowrap">排序</h5>

      <select name="orderSelect" id="orderSelect" class="form-select w-50">
        <option value="courseID">id</option>
        <option value="price">price</option>
      </select>

    </div>
    <div class="p-2 rounded-4 bg-white py-2 function-wrap">
      <h5 class="m-0">顯示方式</h5>
    </div>
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

        <tbody class="text-center" id="tbody">

        </tbody>
      </table>
    </div>
  </div>
  <!-- 選頁 -->
  <nav class="d-flex gap-3 page-nav align-items-center">
    <a id="previousPage" class="btn btn-primary rounded-5">
      <button class="page-link">上一頁</button>
    </a>
    <ul class="pagination -sm m-0" id="pageNumber">
    </ul>

    <a class="btn btn-primary rounded-5" id="nextPage">
      <button class="page-link ">下一頁</button>
    </a>
    <span>共<span id="totalPages"></span>頁</span>
  </nav>
</div>
<!-- JS -->
<script>
  let orderEl = document.getElementById("orderSelect");
  let limitPerpageEl = document.getElementById("limitPerpageSelect");
  let orderValue = orderEl.value;
  let limitPerpage = limitPerpageEl.value;
  let currentPage = 1;
  let previousPageBtn = document.getElementById('previousPage');
  let nextPageBtn = document.getElementById('nextPage');
  let otherLimit = 'otherLimit';
  let tbody = document.getElementById('tbody');
  let pageNumberUl = document.getElementById("pageNumber");
  let totalPages = document.getElementById('totalPages');
  let totalRows = document.getElementById('totalRows');
  let approveModal = document.getElementById('approveModal');
  let approveBtn = document.getElementById('approveBtn');
  let modalTitle = document.getElementById('modalTitle');
  let modalBody = document.getElementById('modalBody');
  // then要用的變數
  let rows;
  let courseState;
  let btnApprove;


  let loadData = (page, orderValue, limitPerpage, otherLimit) => {
    let data = {
      'page': page,
      'orderValue': orderValue,
      'limitPerpage': limitPerpage,
      'otherLimit': otherLimit,
    }
    fetch("api/orderCourse.php", {
      method: "POST",
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(data)
    }).then((r) => {
      return r.json();
    }).then((output) => {
      console.log(output);
      // 重置
      tbody.innerHTML = '';
      pageNumberUl.innerHTML = '';
      previousPageBtn.classList.remove('disabled');
      nextPageBtn.classList.remove('disabled');
      rows = output['rows'];

      for (let r of rows) {
        if (Number(r['available']) === 1) {
          courseState = '<span class="text-success">已上架</span>';
        } else if (r['approverID']) {
          courseState = '<span class="text-body-tertiary">未上架</span>';
        } else {
          courseState = '<span class="text-danger">未審核</span>'
        }
        btnApprove = (r['approverID'] ? (Number(r['available']) === 1 ? 'btn-secondary' : 'btn-launch') : 'btn-success');
        btnApproveContent = (r['approverID'] ? (Number(r['available']) === 1 ? '<i class="fa-solid fa-arrow-turn-down me-2"></i>下架' : '<i class="fa-solid fa-arrow-up-from-bracket me-2"></i>上架') : '<i class="fa-solid fa-check me-2"></i>核准');
        tbody.innerHTML +=
          `<tr>
              <th style="width: 87px;" scope="row"> ${r['courseID']}</th>
              <td style="width: 550px;">${r['title']} </td>
              <td style="width: 120px;"> ${r['userName']} </td>
              <td style="width: 120px;"> ${r['price']}</td>
              <td class="text-center">${courseState}</td>
              <td class="text-center">${r['promotionName']}</td>
              <td class="text-end">${r['soldCount']}</td>
              <td style="width: 123px;" class="text-center"><a class="btn ${btnApprove}" data-bs-toggle="modal" data-bs-target='#approveModal' onclick= "javascript:approveCourse(${r['courseID']},${r['approverID']},${r['available']})" >${btnApproveContent}
                   </a></td>
              <!-- 編輯按鈕 -->
              <td style="width: 123px;" class="text-center"><a class="btn btn-primary" href="edit.php?courseID= ${r['courseID']}"><i class="fa-solid fa-pen-to-square me-2 "></i>編輯</a></td>
            </tr>`;
      }
      if (currentPage === 1) {
        previousPageBtn.classList.add('disabled')
      }
      if (currentPage === output['totalPages']) {
        nextPageBtn.classList.add('disabled')
      }
      for (let i = currentPage - 3; i <= currentPage + 3; i++) {
        if (i >= 1 && i <= output['totalPages']) {
          if (i === currentPage) {
            pageNumberUl.innerHTML +=
              `<li class = "page-item active" >
          <a class = "page-link" >${i}</a> </li>`
          } else {
            pageNumberUl.innerHTML +=
              `<li class = "page-item" >
          <a class = "page-link" >${i}</a> </li>`
          }
        }

      }
      totalPages.innerText = output['totalPages'];
      totalRows.innerText = output['totalRows'];
    });
  }

  previousPageBtn.onclick = (e) => {
    if (currentPage > 1) {
      currentPage--;
      loadData(currentPage, orderValue, limitPerpage, otherLimit);
    }
  };
  nextPageBtn.onclick = (e) => {
    if (currentPage < 100 /*最大頁數 */ ) {
      currentPage++;
      loadData(currentPage, orderValue, limitPerpage, otherLimit);
    }
  };
  orderEl.onchange = (e) => {
    console.log(e.target.value);
    orderValue = e.target.value;
    loadData(currentPage, orderValue, limitPerpage, otherLimit)
  };
  limitPerpageEl.onchange = (e) => {
    console.log(e.target.value);
    limitPerpage = e.target.value;
    loadData(currentPage, orderValue, limitPerpage, otherLimit)
  };
  pageNumberUl.onclick = (e) => {
    console.log(Number(e.target.innerHTML));
    currentPage = Number(e.target.innerHTML);
    loadData(currentPage, orderValue, limitPerpage, otherLimit);
  }
  // 首次加載立即執行
  (function() {
    loadData(currentPage, orderValue, limitPerpage, otherLimit);
  })();

  function approveCourse(id, approver, available) {
    console.log(approver);
    console.log(id);
    if (!approver) {
      approveBtn.href = `api/approve.php?courseID=${id}`;//跳轉到approve頁面
      modalTitle.innerHTML = '是否核准課程'
      modalBody.innerHTML = '操作不可逆'
      approveBtn.href = '核准';
    } else if (available) {
      approveBtn.href = `api/launch.php?courseID=${id}`;
      modalTitle.innerHTML = '是否下架課程'
      modalBody.innerHTML = '下架課程'
      approveBtn.href = '下架';
    } else {
      approveBtn.href = `api/launch.php?courseID=${id}`;
      modalTitle.innerHTML = '是否上架課程'
      modalBody.innerHTML = '上架課程'
      approveBtn.href = '上架';
    }
    
  }
</script>
<?php

include __DIR__ . '/parts/html-foot.php';
// $sql;
// $pdo->query("$total_rows_sql");