<?php if (!isset($pageName)) {
  $pageName = '';
}
?>
<!DOCTYPE html>
<html lang="zh-hant">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= isset($title) ? $title : '首頁' ?></title>
  <link rel="stylesheet" href="	https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />

  <script src="https://kit.fontawesome.com/b0405be273.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="./01.css" />
</head>

<body>
  <nav class="d-flex align-items-start sidebar">
    <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
      <li>
        <a class="d-flex flex-column navbar-brand fs-3 mb-5" href="#"><img src="" alt="" class="me-2" />後台管理系統</a>
      </li>
      <a class="nav-link <?= $pageName == 'create' ? 'active' : '' ?>" href="./create.php"><i class="fa-solid fa-file-circle-plus"></i>
        新增課程
      </a>
      <li class="nav-link" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true"><i class="fa-solid fa-file-circle-plus"></i>
        我創建的課程
      </li>
      <li class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">
        <i class="fa-solid fa-feather-pointed"></i>
        待審核的課程
      </li>
      <li class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-messages" type="button" role="tab" aria-controls="v-pills-messages" aria-selected="false">
        <i class="fa-solid fa-folder-tree"></i>
        課程分類
      </li>
      <li class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" data-bs-target="#v-pills-settings" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false">
        <i class="fa-solid fa-comments"></i>
        課程留言管理
      </li>
      <li class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" data-bs-target="#v-pills-settings" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false">
        <i class="fa-solid fa-bullhorn"></i>
        新上架課程
      </li>
      <li class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" data-bs-target="#v-pills-settings" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false">
        <i class="fa-solid fa-folder-closed"></i>
        已下架的課程
      </li>
      <li class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" data-bs-target="#v-pills-settings" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false">
        <i class="fa-solid fa-star"></i>
        課程評價管理
      </li>
      <li class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" data-bs-target="#v-pills-settings" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false">
        <i class="fa-solid fa-tag"></i>
        修改tag
      </li>
      <a href="./list.php" class="nav-link <?= $pageName == 'list' ? 'active' : '' ?> ">
        <i class="fa-solid fa-book"></i>
        所有上架課程
      </a>
    </div>

  </nav>
  <section id="header">
    <div class="container-right">
      <header class="d-flex flex-row justify-content-between align-items-center">
        <a class="navbar-brand fs-3" href="#">課程管理</a>

        <form class="d-flex" role="search" id="searchForm">
          <div class="input-group flex-nowrap">
            <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
            <input type="text" class="form-control" placeholder="搜尋所有課程" id="searchInput" name="" />
          </div>
        </form>
        <div class="d-flex gap-3">
          <button type="button" class="btn btn-outline-info header-btn rounded-circle">
            <i class="fa-solid fa-gear"></i>
          </button>
          <button type="button" class="btn btn-outline-info header-btn rounded-circle">
            <i class="fa-regular fa-bell"></i>
          </button>
          <div class="portrait d-flex justify-content-center align-items-center">
            <i class="fa-solid fa-house-user"></i>
          </div>
        </div>
      </header>
    </div>
  </section>
  <div id="banner">

  </div>