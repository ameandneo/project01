<div class="container-right">
    <h4>新增課程</h4>
    <form class="createCourse rounded-5" name="createCourse" onsubmit="sendData(event)" method="post">
        <div class="mb-3 hstack">
            <label for="title" class="form-label me-auto">標題</label>
            <input type="text" class="form-control" id="title" name="title">
            <div id="Help" class="form-text text-danger"><i class="fa-solid fa-circle-exclamation"></i>必填</div>
        </div>
        <div class="mb-3 hstack">
            <label for="courseClassSN" class="form-label me-auto">類別</label>
            <select class="form-select" name="courseClassSN" id="courseClassSN">
                <?php $allCourseClass  = $pdo->query('SELECT * FROM `courseclass`;')->fetchAll(PDO::FETCH_ASSOC); ?>
                <?php foreach ($allCourseClass as $CourseClass) : ?>
                    <option value="<?= $CourseClass['courseClassSN'] ?>"> <?= $CourseClass['className'] ?> </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3 hstack">
            <label for="intro" class="form-label me-auto">簡介</label>
            <textarea type="text" class="form-control" id="intro" name="intro" cols="30" rows="10"></textarea>
        </div>
        <div class="mb-3 hstack">
            <label for="syllabus" class="form-label me-auto">課綱</label>
            <input type="text" class="form-control" id="syllabus" name="syllabus">
        </div>
        <div class="mb-3 hstack">
            <label for="price" class="form-label me-auto">價格</label>
            <input type="number" class="form-control" id="price" name="price">
        </div>
        <div class="mb-3 hstack">
            <label for="teacherID" class="form-label me-auto">教師</label>
            <select class="form-select" name="userID" id="teacherID">
                <?php $allTeachers = $pdo->query('SELECT userName,userID FROM `user` WHERE isTeacher=1;')->fetchAll(PDO::FETCH_ASSOC); ?>
                <?php foreach ($allTeachers as $teacher) : ?>
                    <option value="<?= $teacher['userID'] ?>"> <?= $teacher['userName'] ?> </option>
                <?php endforeach; ?>
            </select>
        </div>



        <div class="mb-3 hstack">
            <label for="formFile" class="hstack w-100">圖片<div class="form-control ms-auto">上傳圖片</div></label>
            <input hidden class="form-control" type="file" id="formFile" accept="image/jpeg,image/png,image/webp" multiple="false" onchange="showTemp(event)" name="imgFile" />
            <!-- 接受的檔案類型 -->
        </div>
        <div><!-- 空的img-->
            <img src="" alt="" id="myimg" width="100%" />
        </div>
        <button type="submit" class="btn btn-primary">新增課程</button>
    </form>
</div>
<script>
    const allTeachers = <?= json_encode($allTeachers, JSON_UNESCAPED_UNICODE) ?>;
    console.log(allTeachers);



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
        fetch('./api/addCourse.php', {
                method: "POST",
                body: createCourseFD,
            })
            .then((r) => r.json())
            .then((result) => {
                if (result.success) {
                    alert('已新增課程');

                } else {
                    alert("新增失敗");
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