<?php include '../include/header.php';?>
<?php include '../include/sidemenu.php';?>
<?php
include '../database/config.php';
include '../database/database.php';
$db = new database();

function test_input($data) { //filter value function
    $db = new database();
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = strtolower($data);
    $data = mysqli_real_escape_string($db->link, $data);
    return $data;
}
$action_status = 'publish';
if (isset($_GET["action-status"]) && ($_GET["action-status"] == 'mute' || $_GET["action-status"] == 'complete')) {
    $action_status = test_input($_GET["action-status"]);
}
$limit = 10;
$start_from = 0;
if (isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 0) {
    $pn = test_input($_GET["page"]);
} else {
    $pn = 1;
}
$start_from = ($pn - 1) * $limit;


?>
        <!-- Page Content  -->
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">

                    <button type="button" id="sidebarCollapse" class="btn btn-info">
                        <i class="fas fa-align-left"></i>
                        <!-- <span>Toggle Sidebar</span> -->
                    </button>
                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-align-justify"></i>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto">

                              <form class="form-inline md-form form-sm active-pink active-pink-2 mt-2" style="">
                                <i class="fas fa-search" aria-hidden="true"></i>
                                <input class="form-control form-control-sm ml-3 w-75" type="text" placeholder="Search"
                                  aria-label="Search">
                              </form>


                        </ul>
                    </div>
                </div>
            </nav>
            <div class="table-overflow">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">SL</th>
                    <th scope="col">Job Name</th>
                    <th scope="col">Orderd by</th>
                    <th scope="col">rate</th>
                    <th scope="col">Submit</th>
                    <th scope="col">Check</th>
                    <th scope="col">Uncheck</th>
                    <th scope="col">Success/Required</th>
                    <th scope="col">status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  // $user_query = "SELECT * FROM job_table WHERE validation  = 'Valid' LIMIT $start_from,$limit";
                  $i = 0;
                  $i = $start_from;
                  $job_query = "SELECT * FROM job_table WHERE status = '$action_status' ORDER BY id DESC LIMIT $start_from,$limit";
                  $job_read = $db->select($job_query);
                  if ($job_read) {
                      $count_job = mysqli_num_rows($job_read);
                      if($count_job > 0){
                        while ($job_row = $job_read->fetch_assoc()) {
                          $job_id = $job_row['id'];
                          $task_count_sql = "SELECT(
                                SELECT COUNT(*) FROM task_table WHERE status = 'uncheck' AND job_id = '$job_id'
                          	  ) AS Total_uncheck,
                          	  (SELECT COUNT(*) FROM task_table WHERE status = 'accept' AND job_id = '$job_id'
                            ) AS Total_accept,
                            (SELECT COUNT(*) FROM task_table WHERE status = 'reject' AND job_id = '$job_id'
                          ) AS Total_reject
                          FROM task_table";
                          $reject_task_read = $db->select($task_count_sql);
                          if($reject_task_read){
                            $task_row = $reject_task_read->fetch_assoc();
                            $uncheck = $task_row['Total_uncheck'];
                            $accept =  $task_row['Total_accept'];
                            $reject =  $task_row['Total_reject'];
                            $job_name = $job_row['job_name'];
                            if (strlen($job_name) > 13){
                              $job_name = substr($job_name, 0, 10) . '...';
                            }
                            $check = $accept + $reject;
                            $submit = $check + $uncheck;
                            $i++;


                    ?>
                  <tr>
                    <th scope="row"><?php echo $i;?></th>
                    <td class="job-name" style="cursor:pointer;" data-toggle="modal" data-target="#view<?php echo $job_id?>"><?php echo $job_name;?></td>
                    <td><?php echo $job_row['provider'];?></td>
                    <td><?php echo $job_row['rate'];?> tk</td>
                    <td><?php echo $submit;?></td>
                    <td><a href="work-check.php?target=<?php echo $job_id?>&&status=check" class="action-btn btn btn-secondary btn-sm"><?php echo $check;?></a></td>
                    <td><a href="work-check.php?target=<?php echo $job_id?>&&status=uncheck" class="action-btn btn btn-warning btn-sm"><?php echo $uncheck;?></a></td>
                    <td><?php echo $accept.'/'.$job_row['total_count'];?></td>
                    <td style="cursor:pointer;" data-toggle="modal" data-target="#view<?php echo $job_id?>">
                      <?php if ($job_row['status'] == 'publish') { ?>
                        <span class="badge badge-info">Publish</span>
                      <?php }
                      elseif ($job_row['status'] == 'mute') { ?>
                        <span class="badge badge-dark">Mute</span>
                      <?php }else{ ?>
                        <span class="badge badge-success">Complete</span>
                      <?php }?>
                    </td>
                  </tr>

<!-- ////////////////////////////////////////////////////////////////////////////////////////////////// -->
<!-- Modal -->
<div class="modal fade" id="view<?php echo $job_id?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Job Actions</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="card">
            <div class="card-body">
              <form class="form-horizontal" action="../functions/add_jobs.php" method="post">
                <div class="form-group row">
                  <label for="inputPassword" class="col-sm-3 lebel-class">Job Name</label>
                  <div class="col-sm-9">
                    <input type="text" class="input-class" id="inputPassword" name="job_name" value="<?php echo $job_row['job_name'];?>" placeholder="Enter Job Name">
                  </div>
                </div>
                <input type="number" name="job_id" hidden value="<?php echo $job_row['id'];?>">
                <input type="text" name="action_status" hidden value="<?php echo $action_status;?>">
                <input type="text" name="page" hidden value="<?php echo $pn;?>">
                <div class="form-group row">
                  <label for="inputPassword" class="col-sm-3 lebel-class">Provider</label>
                  <div class="col-sm-9">
                    <input type="text" class="input-class" id="inputPassword" name="provider" value="<?php echo $job_row['provider'];?>" placeholder="Enter Provider">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputPassword" class="col-sm-3 lebel-class">Job Rate</label>
                  <div class="col-sm-9">
                    <input type="number" class="input-class" id="inputPassword" name="rate" value="<?php echo $job_row['rate'];?>" placeholder="Enter Job Rate">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputPassword" class="col-sm-3 lebel-class">Total Count</label>
                  <div class="col-sm-9">
                    <input type="number" class="input-class" id="inputPassword" name="total_count" value="<?php echo $job_row['total_count'];?>" placeholder="Enter Total Count">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputPassword" class="col-sm-3 lebel-class">Level</label>
                  <div class="col-sm-9">
                    <div class="form-check form-check-inline">
                      <input required class="form-check-input" <?php if($job_row['level'] == 1){echo 'checked';} ?> type="radio" name="level" value="1">
                      <label class="form-check-label" for="inlineRadio1">one</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" <?php if($job_row['level'] == 2){echo 'checked';} ?> type="radio" name="level" value="2">
                      <label class="form-check-label" for="inlineRadio2">two</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" <?php if($job_row['level'] == 3){echo 'checked';} ?> type="radio" name="level" value="3">
                      <label class="form-check-label" for="inlineRadio3">three</label>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputPassword" class="col-sm-3 lebel-class">End date</label>
                  <div class="col-sm-9">
                    <input type="date" class="input-class" id="inputPassword" name="end_date" value="<?php echo $job_row['end_date'];?>" placeholder="Enter task end date">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputPassword" class="col-sm-3 lebel-class">Work details</label>
                  <div class="col-sm-9">
                    <textarea class="input-class form-control" id="exampleFormControlTextarea1" name="work_details" rows="6" placeholder="Enter work details"><?php echo $job_row['details'];?></textarea>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputPassword" class="col-sm-3 lebel-class">Required proves</label>
                  <div class="col-sm-9">
                    <textarea class="input-class form-control" id="exampleFormControlTextarea1" name="required_proves" rows="5" placeholder="Enter required proves"><?php echo $job_row['proves'];?></textarea>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputPassword" class="col-sm-3 lebel-class">Assign date</label>
                  <div class="col-sm-9">
                    <label for="inputPassword" class="lebel-class"><?php echo $job_row['assign_date'];?></label>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputPassword" class="col-sm-3 lebel-class">Status</label>
                  <div class="col-sm-9">
                    <div class="form-check form-check-inline">
                      <input required class="form-check-input" <?php if($job_row['status'] == 'publish'){echo 'checked';} ?> type="radio" name="status" value="publish">
                      <label class="form-check-label" for="inlineRadio1"><span class="badge badge-info">Publish</span></label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" <?php if($job_row['status'] == 'complete'){echo 'checked';} ?> type="radio" name="status" value="complete">
                      <label class="form-check-label" for="inlineRadio2"><span class="badge badge-success">Complete</span></label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" <?php if($job_row['status'] == 'mute'){echo 'checked';} ?> type="radio" name="status" value="mute">
                      <label class="form-check-label" for="inlineRadio3"><span class="badge badge-dark">Mute</span></label>
                    </div>
                  </div>
                </div>

            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
        <button type="submit" name="update_job_post" class="btn btn-info btn-sm">update</button>
      </form>
      </div>
    </div>
  </div>


<?php  }
    }
}
}
?>
<!-- ////////////////////////////////////////////////////////////////////// -->





                </tbody>
              </table>
              <div class="pagination-section">
                <div class="pagination-sec">
                  <ul class="pagination pagination-content">
                  <?php


                  $job_query = "SELECT * FROM job_table WHERE status = '$action_status'";
                  $job_read = $db->select($job_query);
                  if ($job_read) {
                    $count_job = mysqli_num_rows($job_read);
                    $count4 = $count_job;
                    $email_listing = "index.php";
                    $total_pages = ceil($count4 / $limit);
                    $k = (($pn+1>$total_pages)?$total_pages-1:(($pn-1<1)?2:$pn));
                    $pagLink = "";
                    if($total_pages > 1){
                    if($pn>=2){
                        echo "<a href='index.php?page=".($pn-1)."'><li class='page-btn'><</li></a>";
                        echo "<li><a href='index.php?page=1'> 1 </a></li>";
                        if(($pn-1) > 2){
                          echo "<li><a href='index.php?page=".($pn-2)."'> ... </a></li>";
                        }
                    }
                    if($pn == 1){
                      echo "<li class='active'><a href='index.php?page=1'> 1 </a></li>";
                    }
                    for ($i=-1; $i<=1; $i++) {
                        if($k+$i != 1 && $k+$i != $total_pages && $k+$i < $total_pages && $k+$i > 0){
                          if($k+$i==$pn)
                            $pagLink .= "<li class='active'><a href='index.php?page=".($k+$i)."'>".($k+$i)."</a></li>";
                          else
                            $pagLink .= "<li><a href='index.php?page=".($k+$i)."'>".($k+$i)."</a></li>";
                        }
                    };
                    echo $pagLink;
                    if($pn == $total_pages){
                      echo "<li class='active'><a href='index.php?page=".$total_pages."'> ".$total_pages." </a></li>";
                    }
                    if($pn<$total_pages){
                        if(($total_pages-$pn) > 2){
                          echo "<li><a href='index.php?page=".($pn+2)."'> ... </a></li>";
                        }
                        echo "<li><a href='index.php?page=".$total_pages."'> ".$total_pages." </a></li>";
                        echo "<a href='index.php?page=".($pn+1)."'><li class='page-btn'> > </li></a>";

                    }

                  }
                }
                  ?>
                  <!-- <li class="page-btn"><a href="#"><</a></li>
                  <li><a href="#">1</a></li>
                  <li class="active"><a href="#">2</a></li>
                  <li><a href="#">3</a></li>
                  <li><a href="#">...</a></li>
                  <li><a href="#">4</a></li>
                  <li class="page-btn"><a href="#">></a></li> -->
                  </ul>
                 </div>
              </div>
            </div>
        </div>

<?php include '../include/footer.php';?>
