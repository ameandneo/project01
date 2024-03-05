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
    <form class="createCourse rounded-5" name="editCourse" onsubmit="sendData(event)" method="post">
        <div class="mb-3  hstack">

            <label for="courseID" class="form-label me-auto">課程編號</label>
            <input type="text" class="form-control" readonly id="courseID" name="courseID" value="<?= $r['courseID'] ?>">

        </div>
        <div class="mb-3  hstack">
            <label for="title" class="form-label me-auto">標題</label>
            <input type="text" class="form-control" id="title" name="title" value="<?= $r['title'] ?>">

        </div>
        <div class="mb-3  hstack">
            <label for="intro" class="form-label me-auto">簡介</label>
            <textarea type="text" class="form-control" id="intro" name="intro" value="<?= $r['intro'] ?>"></textarea>
        </div>
        <div class="mb-3  hstack">
            <label for="syllabus" class="form-label me-auto">課綱</label>
            <input type="text" class="form-control" id="syllabus" name="syllabus" value="<?= $r['syllabus'] ?>">
        </div>

        <div class="mb-3 hstack">
            <label for="price" class="form-label me-auto">價格</label>
            <input type="number" class="form-control" id="price" name="price">
        </div>
        <div class="mb-3 hstack">
            <label for="teacherID" class="form-label me-auto">教師</label>
            <select class="form-select" name="userID">

                <option value="<?= $r['teacherSN'] ?>">
                    <?php
                    $teacher = $pdo->query('SELECT userName FROM `user` WHERE userID=' . $r['teacherSN'] . ';')->fetch();
                    echo $teacher['userName'] ?> </option>
                <?php $allTeachers = $pdo->query('SELECT userName,userID FROM `user` WHERE isTeacher=1;')->fetchAll(PDO::FETCH_ASSOC); ?>
                <?php foreach ($allTeachers as $teacher) : ?>
                    <option value="<?= $teacher['userID'] ?>"> <?= $teacher['userName'] ?> </option>
                <?php endforeach; ?>
            </select>
        </div>


        <div class="mb-3 hstack w-100">
            <label for="formFile" class="hstack w-100">
                圖片
                <div class="form-control ms-auto">上傳圖片</div>
            </label>
            <input class="form-control" hidden type="file" id="formFile" accept="image/jpeg,image/png,image/webp" multiple="false" onchange="showTemp(event)" name="imgFile" />
            <!-- 接受的檔案類型 -->



            <!-- 一個隱藏的input來放原始圖片檔名 -->
            <input name="courseImg" type="text" hidden value="<?= $r['courseImg'] ?>" />
        </div>
        <!-- 原本的img-->
        <div><img src="./uploads/<?= $r['courseImg'] ?>" alt="" id="myimg" width="100%" /></div>
        <button type="submit" class="btn btn-primary">更改課程資料</button>
    </form>
</div>
<script>
    const myimg = document.querySelector("#myimg");

    function sendData(e) {
        e.preventDefault();
        // 防止送出表單重整
        // log看一下editCourse表單送出的資料
        const editCourseFD = new FormData(document.editCourse);
        for (let i of editCourseFD.entries()) {
            console.log(i);
        };
        //發ajax
        fetch('./api/editCourse.php', {
                method: "POST",
                body: editCourseFD,
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