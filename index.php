<?php
include 'database/config.php';
include "database/database.php";
include "func/session.php";
Session::init(); // session start
function test_input($data) {
    $db = new database();
    $data = trim($data);
    $data = strtolower($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($db->link,$data);
    return $data;
}
$db = new database();
$std = true;
if (isset($_SESSION["user_id"])) {
    $user_id = test_input($_SESSION["user_id"]);
    $std = false;
}
$level = 1;
if (isset($_GET["level"]) && ($_GET["level"] == 2 || $_GET["level"] == 3)) {
    $level = test_input($_GET["level"]);
}

$limit = 15;
$start_from = 0;
if (isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 0) {
    $pn = test_input($_GET["page"]);
} else {
    $pn = 1;
}
$start_from = ($pn - 1) * $limit;
?>
<?php if (isset($_GET['logout']) && $_GET['logout'] == "target") { // logout and destroy all session
    Session::destroy_d();
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Micro Works</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css"> <!-- bootstrap css library call -->
    <script src="assets/js/jquery.min.js"></script> <!-- jquery library call -->
    <script src="assets/js/bootstrap.min.js"></script> <!-- bootstrap js library call -->
    <!-- <link rel="stylesheet" href="assets/css/all.min.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
  </head>
  <body>
    <div class="warrper">
      <div class="secction-one content-width">
        <div class="logo">
          <h4><a href="index.php">Nirviqit</a></h4>
        </div>
        <div class="btn-group float-right">
        <?php
           if(isset($_SESSION['login']) && $_SESSION['login'] == true){?>
            <a href="?logout=target" class="action-btn btn-light" name="button">Logout</a>
        <?php  }else{?>
          <a href="system/registration.php" class="action-btn btn-light" name="button">Sign Up</a>
          <a href="system/login.php" class="action-btn btn-light" name="button">Login</a>
        <?php  }?>
        </div>
      </div>
      <div class="section-two">
        <div class="content-area content-width">
          <ul class="top-menu">
            <li><a href="index.php">HOME</a></li>
            <li><a href="?action-status=paying">MOST PAYING</a></li>
            <li><a href="system/best-earner.php">BEST EARNERS</a></li>
          </ul>
        </div>
      </div>
      <?php
       if(isset($_SESSION['login']) && isset($_SESSION['user_id']) && $_SESSION['login'] == true){
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
           <div class="item-a col-sm-4">
             <div class="inner-item">
               <div class="">
                 <lebel>Balance:<span class="badge badge-pill badge-light"><?php echo $user_balance;?> tk</span></lebel>
               </div>
               <div class="">
                 <lebel>Level:<span class="badge badge-pill badge-light"><?php echo $user_level;?></span></lebel>
               </div>
             </div>
           </div>
           <div class="item-b col-sm-4">
             <div class="inner-item">
               <div class="">
                 <lebel>User ID:<span class="badge badge-pill badge-light"><?php echo $user_id_n;?></span></lebel>
               </div>
               <div class="">
                 <lebel>Email:<span class="badge badge-pill badge-light"><?php echo $user_email;?></span></lebel>
               </div>
             </div>
           </div>
           <div class="item-c col-sm-4">
             <div class="inner-item-e">
               <div class="innet-item-dev">
                 <div class="profile-img">
                   <img src="user_img/<?php echo $user_img;?>" alt="">
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
      <div class="section-four content-width">
        <div class="btn-group-sec" role="group" aria-label="...">
          <a href="" class="action-btn btn-light">Level One</a>
          <a href="" class="action-btn btn-light">Level Two</a>
          <a href="" class="action-btn btn-light">Level Three</a>
        </div>
        <div class="content-table">
          <table class="table table-bordered table-sm">
            <thead class="">
              <tr>
                <th scope="col" class="sl">SL</th>
                <th scope="col" class="job-name">Job Name</th>
                <th scope="col" class="item">Rate</th>
                <th scope="col" class="item">Level</th>
                <th scope="col" class="item">Task Count</th>
                <th scope="col" class="item">Done</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // $user_query = "SELECT * FROM job_table WHERE validation  = 'Valid' LIMIT $start_from,$limit";
              $i = 0;
              $i = $start_from;
              if (isset($_GET["action-status"]) && $_GET["action-status"] == 'paying' ) {
                  $job_query = "SELECT * FROM job_table WHERE status = 'publish' ORDER BY rate DESC LIMIT $start_from,$limit";
              }else{
                $job_query = "SELECT * FROM job_table WHERE status = 'publish' ORDER BY id DESC LIMIT $start_from,$limit";
              }

              $job_read = $db->select($job_query);
              if ($job_read) {
                  $count_job = mysqli_num_rows($job_read);
                  if($count_job > 0){
                    while ($job_row = $job_read->fetch_assoc()) {
                      $job_id = $job_row['id'];
                      if(!$std){
                        $task_count_sql = "SELECT
                            (SELECT COUNT(*) FROM task_table WHERE status = 'accept' AND job_id = '$job_id'
                          ) AS Total_accept,
                          (SELECT COUNT(*) FROM task_table WHERE worker_id = '$user_id' AND job_id = '$job_id'
                        ) AS check_exits
                        FROM task_table";
                      }else{
                        $task_count_sql = "SELECT
                            (SELECT COUNT(*) FROM task_table WHERE status = 'accept' AND job_id = '$job_id'
                          ) AS Total_accept
                        FROM task_table";
                      }

                      $reject_task_read = $db->select($task_count_sql);
                      if($reject_task_read){
                        $task_row = $reject_task_read->fetch_assoc();
                        $accept =  $task_row['Total_accept'];
                        if(!$std){
                          $check_exits =  $task_row['check_exits'];
                          if($check_exits > 0){
                            $std = false;
                          }else{
                            $std = true;
                          }
                        }

                        $job_name = $job_row['job_name'];
                        if($job_row['level'] == 2){
                          $level = 'two';
                        }elseif($job_row['level'] == 3){
                          $level = 'three';
                        }else{
                          $level = 'one';
                        }
                        if (strlen($job_name) > 13){
                          $job_name = substr($job_name, 0, 10) . '...';
                        }
                        $i++;

                ?>
              <tr>
                <th><?php echo $i;?></th>
                <td class="job-name"> <a href="system/work-details.php?target=<?php echo $job_id;?>"><?php echo $job_name;?></a> </td>
                <td><?php echo $job_row['rate'];?> tk</td>
                <td><?php echo $level;?></td>
                <td><?php   if (!$std) { ?>
                  <span class="badge badge-success">done</span>
                <?php }else{ ?>
                  <span class="badge badge-dark">1 time</span>
                <?php }?></td>
                <td><?php echo $accept.'/'.$job_row['total_count'];?></td>
              </tr>
              <?php

                    }
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
                $job_query = "SELECT * FROM job_table WHERE status = 'publish'";
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
                        echo "<li><a href='?page=".($pn+2)."'> ... </a></li>";
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
    </div>
    <div class="footer">
      <label>Copyright @ 2019</label>
    </div>
    </body>
    </html>
