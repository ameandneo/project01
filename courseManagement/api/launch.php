<?php
include __DIR__ . '/../parts/PDOconnect.php';
$courseID = isset($_GET['courseID']) ? intval($_GET['courseID']) : 0;

if (empty($courseID)) { //如果不是點編輯紐進來的就跳回list頁
  header('Location:list.php');
}
$r = $pdo->query("SELECT * FROM course WHERE courseID=$courseID")->fetch();
if (empty($r)) { //如果亂給參數進來的就跳回list頁
  header('Location:list.php');
  exit;
}
$available =$pdo->query('select available from course where courseID ='.$courseID)->fetch();

$pdo->query('UPDATE `course` SET available ='.!($available).' where courseID ='.$courseID);

// 後用$_SERVER['HTTP_REFERER']回到原本的頁面
$backTo = './edit.php';
if (!empty($_SERVER['HTTP_REFERER'])) {
    $backTo = $_SERVER['HTTP_REFERER'];
}
header("Location: $backTo");//跳轉回清單頁