<?php include '../inc/header.php';?>
<?php
 if(isset($_SESSION['login']) && isset($_SESSION['user_id']) && $_SESSION['login'] == true && isset($_SESSION["user_id"])){
   $user_id = test_input($_SESSION["user_id"]);
   $worker_query = "SELECT * FROM workers_table WHERE user_id = '$user_id'";
   $worker_read = $db->select($worker_query);
   if ($worker_read) {
       $count_job = mysqli_num_rows($worker_read);
       if($count_job > 0){
         $worker_row = $worker_read->fetch_assoc();
         $work_title = $worker_row['name'];
         $user_balance = $worker_row['balance'];
         $user_email = $worker_row['email'];
         $user_id_n = $worker_row['user_id'];
         $user_img = $worker_row['img_url'];
         if($worker_row['level'] == 2){
           $user_level = 'two';
         }elseif($worker_row['level'] == 3){
           $user_level = 'three';
         }else{
           $user_level = 'one';
         }

   ?>
   <div class="section-three content-width row">
     <div class="item col-sm-4">
       <div class="inner-item">
         <div class="">
           <lebel>Balance:<span class="badge badge-pill badge-light"><?php echo $user_balance;?> tk</span></lebel>
         </div>
         <div class="">
           <lebel>Level:<span class="badge badge-pill badge-light"><?php echo $user_level;?></span></lebel>
         </div>
       </div>
     </div>
     <div class="item col-sm-4">
       <div class="inner-item">
         <div class="">
           <lebel>User ID:<span class="badge badge-pill badge-light"><?php echo $user_id_n;?></span></lebel>
         </div>
         <div class="">
           <lebel>Email:<span class="badge badge-pill badge-light"><?php echo $user_email;?></span></lebel>
         </div>
       </div>
     </div>
     <div class="item col-sm-4">
       <div class="inner-item-e">
         <div class="innet-item-dev">
           <div class="profile-img">
             <img src="../user_img/<?php echo $user_img;?>" alt="">
           </div>
         </div>
         <div class="innet-item-dev">
           <div class="">
             <lebel><?php echo $work_title;?></lebel>
           </div>
           <div class="">
             <a href="system/profile.php" class="action-btn btn btn-light" name="button">Account</a>
           </div>
         </div>

       </div>
     </div>
   </div>
<?php
  }
}
} ?>
<?php
$limit = 10;
$start_from = 0;
if (isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 0) {
    $pn = test_input($_GET["page"]);
} else {
    $pn = 1;
}
$start_from = ($pn - 1) * $limit;
?>
      <div style="padding-top:5px;" class="section-four content-width">
        <div class="card-header-sec">
          <label>Top Earners</label>
        </div>
        <div class="content-table">
          <table class="table table-bordered table-sm">
            <thead class="">
              <tr>
                <th scope="col" class="sl">SL</th>
                <th scope="col" class="item">Name</th>
                <th scope="col" class="item">Total earn</th>
                <th scope="col" class="item">City</th>
                <th scope="col" class="item">Level</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $i = 0;
              $i = $start_from;
              $worker_query = "SELECT * FROM workers_table ORDER BY balance DESC LIMIT $start_from,$limit";
              $worker_read = $db->select($worker_query);
              if ($worker_read) {
                  $count_job = mysqli_num_rows($worker_read);
                  if($count_job > 0){
                    while ($worker_row = $worker_read->fetch_assoc()) {
                    $work_title = $worker_row['name'];
                    $user_balance = $worker_row['balance'];
                    $user_city = $worker_row['city'];
                    if($worker_row['level'] == 2){
                      $user_level = 'two';
                    }elseif($worker_row['level'] == 3){
                      $user_level = 'three';
                    }else{
                      $user_level = 'one';
                    }
                    $i++;
              ?>
              <tr>
                <th><?php echo $i;?></th>
                <td class=""><?php echo $work_title;?></td>
                <td><?php echo $user_balance;?> tk</td>
                <td><?php echo $user_city;?></td>
                <td><span class="badge badge-dark"><?php echo $user_level;?></span></td>
              </tr>
              <?php
                  }
                }
              }
              ?>
            </tbody>
          </table>
          <div class="pagination-section">
            <div class="pagination-sec">
              <ul class="pagination pagination-content">
                <?php
                $job_query = "SELECT * FROM workers_table";
                $job_read = $db->select($job_query);
                if ($job_read) {
                  $count_job = mysqli_num_rows($job_read);
                  $count4 = $count_job;
                  $total_pages = ceil($count4 / $limit);
                  $k = (($pn+1>$total_pages)?$total_pages-1:(($pn-1<1)?2:$pn));
                  $pagLink = "";
                  if($total_pages > 1){
                  if($pn>=2){
                      echo "<a href='?page=".($pn-1)."'><li class='page-btn'><</li></a>";
                      echo "<li><a href='?page=1'> 1 </a></li>";
                      if(($pn-1) > 2){
                        echo "<li><a href='?page=".($pn-2)."'> ... </a></li>";
                      }
                  }
                  if($pn == 1){
                    echo "<li class='active'><a href='?page=1'> 1 </a></li>";
                  }
                  for ($i=-1; $i<=1; $i++) {
                      if($k+$i != 1 && $k+$i != $total_pages && $k+$i < $total_pages && $k+$i > 0){
                        if($k+$i==$pn)
                          $pagLink .= "<li class='active'><a href='?page=".($k+$i)."'>".($k+$i)."</a></li>";
                        else
                          $pagLink .= "<li><a href='?page=".($k+$i)."'>".($k+$i)."</a></li>";
                      }
                  };
                  echo $pagLink;
                  if($pn == $total_pages){
                    echo "<li class='active'><a href='?page=".$total_pages."'> ".$total_pages." </a></li>";
                  }
                  if($pn<$total_pages){
                      if(($total_pages-$pn) > 2){
                        echo "<li><a href='index.php?page=".($pn+2)."'> ... </a></li>";
                      }
                      echo "<li><a href='?page=".$total_pages."'> ".$total_pages." </a></li>";
                      echo "<a href='?page=".($pn+1)."'><li class='page-btn'> > </li></a>";

                  }

                }
              }
                ?>
              </ul>
             </div>
          </div>
        </div>
      </div>
<?php include '../inc/footer.php';?>
