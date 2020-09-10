<?php include '../inc/header.php';?>
<?php
$limit = 5;
$start_from = 0;
if (isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 0) {
    $pn = test_input($_GET["page"]);
} else {
    $pn = 1;
}
$start_from = ($pn - 1) * $limit;


?>


      <div class="section-four content-width">
        <?php include '../inc/profile-menu.php';?>
        <div class="card-header-sec">
          <label>Task I Finished</label>
        </div>
        <div class="content-table">
          <table class="table table-bordered table-sm">
            <thead class="">
              <tr>
                <th scope="col" class="sl">SL</th>
                <th scope="col" class="job-name">Job Name</th>
                <th scope="col" class="item">Rate</th>
                <th scope="col" class="item">Date</th>
                <th scope="col" class="item">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if(isset($_SESSION['login']) && isset($_SESSION['user_id']) && $_SESSION['login'] == true){
                $user_id = $_SESSION['user_id'];
                $i = 0;
                $i = $start_from;
                $task_query = "SELECT * FROM task_table WHERE worker_id = '$user_id' LIMIT $start_from,$limit";
                $task_read = $db->select($task_query);
                if ($task_read) {
                    $count_task = mysqli_num_rows($task_read);
                    if($count_task > 0){
                      while ($task_row = $task_read->fetch_assoc()) {
                        $assign_date = $task_row['assign_date'];
                        $task_status = $task_row['status'];
                        $work_details = $task_row['work_details'];
                        $dev_comment = $task_row['dev_comment'];
                        $job_id = $task_row['job_id'];
                        $job_name_sql = "SELECT * FROM job_table WHERE id = '$job_id'";
                        $job_name_read = $db->select($job_name_sql);
                        if($job_name_read){
                          $job_name_row = $job_name_read->fetch_assoc();
                          $job_name = $job_name_row['job_name'];
                          if (strlen($job_name) > 13){
                            $job_name = substr($job_name, 0, 10) . '...';
                          }
                          $job_details = $job_name_row['details'];
                          $job_proves = $job_name_row['proves'];
                          $job_rate = $job_name_row['rate'];
                          $i++;

                ?>
              <tr>
                <th><?php echo $i;?></th>
                <td class="job-name" data-toggle="modal" data-target="#id_<?php echo $job_id;?>"><?php echo $job_name;?></td>
                <td><?php echo $job_rate;?> tk</td>
                <td><?php echo $assign_date;?></td>
                <?php if($task_status == 'accept'){ ?>
                  <td style="color:green;"><?php echo $task_status;?></td>
                <?php }elseif($task_status == 'reject'){ ?>
                  <td style="color:red;"><?php echo $task_status;?></td>
                <?php }else{ ?>
                  <td style="color:#FF9A18;"><?php echo $task_status;?></td>
                <?php }?>

              </tr>
              <!-- Modal -->
              <div class="modal fade" id="id_<?php echo $job_id;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLongTitle">Task details</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                            <div style="margin:0; text-align:left;" class="card-2-body-sec">
                              <label style="text-decoration:underline;">Waht need to do?</label>
                              <p><?php echo nl2br($job_details);?>
                              </p>
                              <label style="text-decoration:underline;">Required proof-</label>
                              <p>
                                <?php echo nl2br($job_proves);?>
                              </p>
                            </div>

                            <div style="margin:10px 0 0;  text-align: left;" class="card-2-body-sec">
                              <label style="text-decoration:underline;">Your profe-</label>
                              <p><?php echo nl2br($work_details);?>
                              </p>
                              <label style="text-decoration:underline;">Admin Comment-</label>
                              <p><?php echo nl2br($dev_comment);?>
                              </p>
                            </div>
                            <?php if($task_status == 'accept'){ ?>
                              <div style="text-align:center" class="card-top-sec alert-success">
                                <label>Accept</label>
                              </div>
                            <?php }elseif($task_status == 'reject'){ ?>
                              <div style="text-align:center" class="card-top-sec alert-danger">
                                <label>Reject</label>
                              </div>
                            <?php }else{ ?>
                              <div style="text-align:center" class="card-top-sec alert-warning">
                                <label>Uncheck</label>
                              </div>
                            <?php }?>

                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
              </div>


              <?php
                      }
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
                $job_query = "SELECT * FROM task_table WHERE worker_id = '$user_id'";
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
<?php include '../inc/footer.php';?>
