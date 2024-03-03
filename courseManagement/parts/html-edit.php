<?php
$courseID = isset($_GET['courseID']) ? intval($_GET['courseID']) : 0;

if (empty($courseID)) { //如果不是點編輯紐進來的就跳回list頁
    header('Location:list.php');
}
$r = $pdo->query("SELECT * FROM course WHERE courseID=$courseID")->fetch();
if (empty($r)) { //如果亂給參數進來的就跳回list頁
    header('Location:list.php');
    exit;
}
?>
<div class="container-right">
    <h4>編輯課程</h4>
    <form name="editCourse" onsubmit="sendData(event)" method="post">
        <div class="mb-3">

            <label for="courseID" class="form-label">課程編號</label>
            <input type="text" class="form-control disabled readonly" id="courseID" name="courseID" value="<?= $r['courseID'] ?>">

        </div>
        <div class="mb-3">
            <label for="title" class="form-label">標題</label>
            <input type="text" class="form-control" id="title" name="title" value="<?= $r['title'] ?>">
            
        </div>
        <div class="mb-3">
            <label for="intro" class="form-label">簡介</label>
            <input type="text" class="form-control" id="intro" name="intro" value="<?= $r['intro'] ?>">
        </div>
        <div class="mb-3">
            <label for="syllabus" class="form-label">課綱</label>
            <input type="text" class="form-control" id="syllabus" name="syllabus" value="<?= $r['syllabus'] ?>">
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">價格</label>
            <input type="number" class="form-control" id="price" name="price" value="<?= $r['price'] ?>">
        </div>

        <select class="form-select" name="userID">
         <option value="<?= $r['teacherSN'] ?>"> <?php 
         $teacher =$pdo->query('SELECT userName FROM `user` WHERE userID='.$r['teacherSN'].';')->fetch();
         echo $teacher['userName'] ?> </option>
            <?php $allTeachers = $pdo->query('SELECT userName,userID FROM `user` WHERE isTeacher=1;')->fetchAll(PDO::FETCH_ASSOC); ?>
            <?php foreach ($allTeachers as $teacher) : ?>
                <option value="<?= $teacher['userID'] ?>"> <?= $teacher['userName'] ?> </option>
            <?php endforeach; ?>
        </select>

        <div class="mb-3">
            <label for="formFile" class="form-label">圖片</label>
            <input class="form-control" type="file" id="formFile" accept="image/jpeg,image/png,image/webp" multiple="false" onchange="showTemp(event)" name="imgFile" />
            <!-- 接受的檔案類型 -->

            <!-- 原本的img--> 
            <img src="./uploads/<?=$r['courseImg']?>" alt="" id="myimg" width="300" />
        </div>
        <button type="submit" class="btn btn-primary">更改</button>
    </form>
</div>
<script>
    
    const myimg = document.querySelector("#myimg");

    function sendData(e) {
        e.preventDefault();
        // 防止送出表單重整
        // log看一下createCourse表單送出的資料
        const createCourseFD = new FormData(document.createCourse);
        for (let i of createCourseFD.entries()) {
            console.log(i);
        };
        //發ajax
        fetch('./api/editCourse.php', {
                method: "POST",
                body: createCourseFD,
            })
            .then((r) => r.json())
            .then((result) => {
                if (result.success) {
                    alert('已修改課程');

                } else {
                    alert("修改失敗");
                }
            });
    }

    // 顯示用戶自己的圖
    let formFile = document.getElementById('formFile');

    function showTemp(e) {
        let selectedFile = formFile.files[0] //用戶選的檔案
        let fr = new FileReader();
        fr.readAsDataURL(selectedFile); //FileReader方法把chosedFile轉換為URL，等一下塞到src裡
        fr.onload = (e) => { //如果FileReader()的onload事件觸發，即讀到了東西(用戶選的路徑及檔案)
            myimg.src = e.target.result
        }
    }
</script>