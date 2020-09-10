
        <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>MicroWork</h3>
            </div>

            <ul class="list-unstyled components">
                <li>
                    <a href="../authorities"><h6>Home</h6></a>
                </li>
                <li>
                    <a href="#jobsubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><h6>Jobs</h6></a>
                    <ul class="collapse list-unstyled" id="jobsubmenu">
                      <li>
                          <a href="../authorities">Queue Jobs</a>
                      </li>
                        <li>
                            <a href="../authorities/index.php?action-status=complete">Complete Jobs</a>
                        </li>
                        <li>
                            <a href="../authorities/index.php?action-status=mute">Mute Jobs</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#workerssubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><h6>Workers</h6></a>
                    <ul class="collapse list-unstyled" id="workerssubmenu">
                      <li>
                          <a href="../workers">Active</a>
                      </li>
                      <li>
                          <a href="../workers/index.php?action-status=unverified">Unverified</a>
                      </li>
                        <li>
                            <a href="../workers/index.php?action-status=banned">Banned</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#payementsubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><h6>Payment</h6></a>
                    <ul class="collapse list-unstyled" id="payementsubmenu">
                      <li>
                          <a href="../payment">Payment Request</a>
                      </li>
                        <li>
                            <a href="../payment">Payment History</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" data-toggle="modal" data-target="#settings"><h6>Settings</h6></a>
                </li>
            </ul>

            <ul class="list-unstyled CTAs">
                <li>
                    <a href="add-job.php" class="download" data-toggle="modal" data-target="#addjob">ADD Jobs</a>
                </li>
            </ul>
        </nav>
        <!-- Modal -->
        <div class="modal fade" id="addjob" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add new jobs</h5>
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
                            <input required type="text" class="input-class" name="job_name" placeholder="Enter Job Name">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputPassword" class="col-sm-3 lebel-class">Provider</label>
                          <div class="col-sm-9">
                            <input required type="text" class="input-class" name="provider" placeholder="Enter Provider">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputPassword" class="col-sm-3 lebel-class">Job Rate</label>
                          <div class="col-sm-9">
                            <input required type="number" class="input-class" name="rate" placeholder="Enter Job Rate">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputPassword" class="col-sm-3 lebel-class">Total Count</label>
                          <div class="col-sm-9">
                            <input required type="number" class="input-class" name="total_count" placeholder="Enter Total Count">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputPassword" class="col-sm-3 lebel-class">Level</label>
                          <div class="col-sm-9">
                            <div class="form-check form-check-inline">
                              <input required class="form-check-input" type="radio" name="level" value="1">
                              <label class="form-check-label" for="inlineRadio1">one</label>
                            </div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="level" value="2">
                              <label class="form-check-label" for="inlineRadio2">two</label>
                            </div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="level" value="3">
                              <label class="form-check-label" for="inlineRadio3">three</label>
                            </div>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputPassword" class="col-sm-3 lebel-class">End date</label>
                          <div class="col-sm-9">
                            <input type="date" class="input-class" name="end_date" placeholder="Enter task end date">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputPassword" class="col-sm-3 lebel-class">Work details</label>
                          <div class="col-sm-9">
                            <textarea required class="input-class form-control" name="work_details" rows="6" placeholder="Enter work details"></textarea>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputPassword" class="col-sm-3 lebel-class">Required proves</label>
                          <div class="col-sm-9">
                            <textarea required class="input-class form-control" name="required_proves" rows="5" placeholder="Enter required proves"></textarea>
                          </div>
                        </div>

                    </div>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                <button type="submit" name="add_job_post" class="btn btn-info btn-sm">ADD Job</button>
              </form>
              </div>
            </div>
          </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="settings" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Settings</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                  <div class="card">
                    <div class="card-body">
                      <form class="form-horizontal" action="/action_page.php">
                        <div class="form-group row">
                          <label for="inputPassword" class="col-sm-3 lebel-class">User Name</label>
                          <div class="col-sm-9">
                            <input type="text" class="input-class" id="inputPassword" placeholder="Enter user Name">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputPassword" class="col-sm-3 lebel-class">Email</label>
                          <div class="col-sm-9">
                            <input type="email" class="input-class" id="inputPassword" placeholder="Enter User email">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputPassword" class="col-sm-3 lebel-class">Password</label>
                          <div class="col-sm-9">
                            <input type="password" class="input-class" id="inputPassword" placeholder="Enter Password">
                          </div>
                        </div>

                    </div>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-info btn-sm">Update</button>
              </form>
              </div>
            </div>
          </div>
        </div>
