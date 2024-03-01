<div class="container-right">
  <div class="card-group d-flex flex-column py-5">
    <?php #foreach ($r as $rows) : 
    ?>
    <div class="card mb-3 rounded-5">
      <div class="row g-0">
        <!-- 圖 -->
        <div class="col-md-4">

          <img src="" class="img-fluid rounded-start" alt="...">
        </div>
        <!-- 字 -->
        <div class="col-md-8">
          <div class="card-body">
            <h5 class="card-title">Card title</h5>
            <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
            <p class="card-text"><small class="text-body-secondary">Last updated 3 mins ago</small></p>
          </div>
        </div>
      </div>
    </div>
    
    <?php # endforeach; 
    ?>
  </div>
</div>