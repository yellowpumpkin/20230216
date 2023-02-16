<body>
    <?php 
        if (isset($_SESSION['technician_login'])) {
            $technician_id = $_SESSION['technician_login'];
            $stmt = $conn->query("SELECT * FROM tbl_users WHERE id = $technician_id ");
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $urole = $row["username"];
        }
    ?>
    <nav class="navbar navbar-light bg-light p-3">
        <div class="d-flex col-12 col-md-3 col-lg-2 mb-2 mb-lg-0 flex-wrap flex-md-nowrap justify-content-between">
            <a class="navbar-brand" href="technician.php">
                <?php echo $row['urole'] ?>
            </a>
            <button class="navbar-toggler d-md-none collapsed mb-3" type="button" data-toggle="collapse"
                data-target="#sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        <div class="col-12 col-md-4 col-lg-2">
            <h3> Dashboard</h3>
        </div>
        <div class="col-12 col-md-5 col-lg-8 d-flex align-items-center justify-content-md-end mt-3 mt-md-0">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                    data-toggle="dropdown" aria-expanded="false">
                    Hello, <?php echo $row['firstname'] . ' ' . $row['lastname'] ?>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li><a class="dropdown-item" href="signout.php">Sign out</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="row">
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <?php if (isset($_SESSION['technician_login'])) { ?>
                        <a class="nav-link active" aria-current="page" href=technician.php>
                            <i data-feather="rewind"></i>
                            <span class="ml-2">BACK</span>
                        </a>
                        <?php } ?>
                    </ul>
                </div>
            </nav>
            <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 py-4">
                <div class="row">
                    <div class="col-12 ">
                        <div class="card">
                            <?php if(isset($_SESSION['error'])) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?php 
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                    ?>
                            </div>
                            <?php } ?>
                            <?php if(isset($_SESSION['success'])) { ?>
                            <div class="alert alert-success" role="alert">
                                <?php 
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                    ?>
                            </div>
                            <?php } ?>
                            <?php if(isset($_SESSION['warning'])) { ?>
                            <div class="alert alert-warning" role="alert">
                                <?php 
                        echo $_SESSION['warning'];
                        unset($_SESSION['warning']);
                    ?>
                            </div>
                            <?php } ?>
                            <div class="card-header">
                                <h4 class="card-title text-center">ข้อมูลงานแจ้งซ่อม</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="myTable" class="display" style="width: 100%;">
                                        <thead>
                                            <th>No</th>
                                            <th>วันที่แจ้งซ่อม</th>
                                            <th>หมายเลขเครื่อง</th>
                                            <th>ชื่อเครื่อง</th>
                                            <th>อาการ</th>
                                            <th>สถานที่</th>
                                            <th>วันที่ต้องการใช้</th>
                                            <th>คนแจ้งซ่อม</th>
                                            <th>Technician</th>
                                            <th>สถานะ</th>
                                            <th>จัดการ</th>


                                        </thead>
                                        <tbody>
                                            <?php
                                                $select_stmt = $conn->query("SELECT * FROM tbl_case 
                                                INNER JOIN tbl_users ON user_id = id 
                                                INNER JOIN tbl_status ON status_id = sid 
                                                INNER JOIN tbl_department ON department = department_id  Where sid = '2' and  tech =  '$urole'");
                                                $select_stmt->execute();
                                                $cases = $select_stmt->fetchAll();

                                                $sth = $conn->prepare("SELECT status_name FROM tbl_status");
                                                $sth->execute();

                                                /* Fetch all of the remaining rows in the result set */
                                                $status = $sth->fetchAll(PDO::FETCH_COLUMN, 0);

                                                foreach($cases as $row) {
                                                    ?>
                                            <tr>
                                                <td><?php echo $row["case_id"]; ?></td>
                                                <td><?php echo $row["date_start"]; ?></td>
                                                <td><?php echo $row["machine_no"]; ?></td>
                                                <td><?php echo $row["machine_name"]; ?></td>
                                                <td><?php echo $row["problem_case"]; ?></td>
                                                <td><?php echo $row["place_name"]; ?></td>
                                                <td><?php echo $row["date_end"]; ?> <br />
                                                    <?php  echo $row["urgency"]; ?> </td>
                                                <td><?php echo $row["username"]; ?></td>
                                                <td><?php echo $row["tech"]; ?></td>
                                                <?php
                                                    if ($row["status_name"] == $status[5]){  ?>
                                                <td><a style="color: #8ebf42"><?php echo $row["status_name"]; ?></a>
                                                </td>
                                                <?php } ?>
                                                <?php
                                                    if ($row["status_name"] == $status[6]){ ?>
                                                <td><a style="color: #E90B0B"><?php echo $row["status_name"]; ?></a>
                                                </td>
                                                <?php } ?>
                                                <?php
                                                    if ($row["status_name"] == $status[1]){ ?>
                                                <td><a style="color: #8601AF"><?php echo $row["status_name"]; ?></a>
                                                </td>
                                                <?php } ?>
                                                <?php
                                                    if ($row["status_name"] == $status[2]){ ?>
                                                <td><a style="color: #0E90EA"><?php echo $row["status_name"]; ?></a>
                                                </td>
                                                <?php } ?>
                                                <?php
                                                    if ($row["status_name"] == $status[3]){ ?>
                                                <td><a style="color: #E9940B"><?php echo $row["status_name"]; ?></a>
                                                </td>
                                                <?php } ?>
                                                <td> <button type="button" class="btn btn-primary" data-toggle="modal"
                                                        data-target="#exampleModalCenter<?php echo $row["case_id"]; ?>"
                                                        class="btn-manage"><i class="fa fa-gear"></i></button></td>

                                            </tr>
                                            <!-- Modal -->
                                            <div class="modal fade bd-example-modal-lg"
                                                id="exampleModalCenter<?php echo $row["case_id"]; ?>" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalCenterTitle"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog  modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLongTitle">
                                                                ข้อมูลแจ้งซ่อม</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form id="myform1" name="form1" method="post"
                                                                class="row g-3" action=db_general.php novalidate>
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <h5 mt-4>รายละเอียด</h5>
                                                                        <hr>
                                                                        <div class="row">
                                                                            <div class="col-md-2">

                                                                                <label for="case_id"
                                                                                    class="form-label">เลขที่</label>
                                                                                <input readonly
                                                                                    value="<?php echo $row['case_id']; ?>"
                                                                                    required class="form-control"
                                                                                    name="case_id">
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <!-- Date input -->
                                                                                <label class="form-label"
                                                                                    for="date">วันที่แจ้งซ่อม</label>
                                                                                <fieldset disabled>
                                                                                    <input type="text"
                                                                                        id="username_case"
                                                                                        class="form-control"
                                                                                        placeholder="<?php echo $row["date_start"]; ?>">
                                                                                </fieldset>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <!-- Date input -->
                                                                                <label class="form-label"
                                                                                    for="date">วันที่ต้องการใช้เครื่อง</label>
                                                                                <input class="form-control"
                                                                                    id="date_end" name="date_end"
                                                                                    placeholder=<?php echo $row["date_end"]; ?>
                                                                                    type="text" readonly>
                                                                            </div>
                                                                            <div class="col-md-2">
                                                                                <label for="urgency"
                                                                                    class="form-label">ความเร่งด่วน</label>
                                                                                <input type="text" class="form-control"
                                                                                    name="urgency"
                                                                                    aria-describedby="urgency"
                                                                                    value=" <?php  echo $row["urgency"]; ?>"
                                                                                    required readonly>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <label for="machine_no"
                                                                                    class="form-label">หมายเลขเครื่องจักร</label>
                                                                                <input type="text" class="form-control"
                                                                                    name="machine_no"
                                                                                    aria-describedby="machine_no"
                                                                                    value="<?php echo $row["machine_no"]; ?>"
                                                                                    required readonly>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <label for="machine_name"
                                                                                    class="form-label">ชื่อเครื่องจักร</label>
                                                                                <input type="text" class="form-control"
                                                                                    name="machine_name"
                                                                                    aria-describedby="machine_name"
                                                                                    value="<?php echo $row["machine_name"]; ?>"
                                                                                    required readonly>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <label for="place_name"
                                                                                    class="form-label">สถานที่</label>
                                                                                <input type="text" class="form-control"
                                                                                    name="place_name"
                                                                                    aria-describedby="place_name"
                                                                                    value="<?php echo $row["place_name"]; ?>"
                                                                                    required readonly>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label for="problem_case"
                                                                                    class="form-label">อาการเบื้องต้น</label>
                                                                                <textarea class="form-control"
                                                                                    name="problem_case"
                                                                                    id="problem_case" rows="3" required
                                                                                    readonly><?php echo $row["problem_case"]; ?></textarea>
                                                                            </div>
                                                                            <div class="col-md-2">
                                                                                <label for="agency"
                                                                                    class="form-label">หน่วยงาน</label>
                                                                                <input type="text" class="form-control"
                                                                                    name="agency"
                                                                                    aria-describedby="agency"
                                                                                    value="<?php echo $row["agency"]; ?>"
                                                                                    required readonly>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <label for="username_case"
                                                                                    class="form-label">พนักงานแจ้งซ่อม
                                                                                </label>
                                                                                <input id="username_case"
                                                                                    class="form-control"
                                                                                    name="username_case"
                                                                                    value="<?php echo $row['username'] ?>"
                                                                                    readonly>
                                                                            </div>

                                                                            <?php                                                                  
                                                                        $select_technician = $conn->query("SELECT username FROM tbl_users WHERE urole = 'technician' ");
                                                                        $select_technician->execute();                                                                                          
                                                                    ?>
                                                                            <h6 class="mt-4">รายละเอียดการซ่อม</h6>
                                                                            <hr>
                                                                            <div class="col-md-6">
                                                                                <!-- Date input -->
                                                                                <label class="form-label"
                                                                                    for="date">วันที่แล้วเสร็จ</label>
                                                                                <input class="form-control"
                                                                                    id="date_completion"
                                                                                    name="date_completion"
                                                                                    placeholder="YYYY-MM-DD"
                                                                                    type="text">
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <label for="problems_found"
                                                                                    class="form-label">ปัญหาที่พบ</label>
                                                                                <textarea class="form-control"
                                                                                    id="problems_found"
                                                                                    name="problems_found" rows="2"
                                                                                    required></textarea>
                                                                                    <div class="invalid-feedback">
                                                                                กรุณากรอกข้อมูล
                                                                            </div>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <label for="details"
                                                                                    class="form-label">รายละเอียดการซ่อม</label>
                                                                                <textarea class="form-control"
                                                                                    id="details" name="details"
                                                                                    rows="2"></textarea>
                                                                                <div class="invalid-feedback">
                                                                                    กรุณากรอกข้อมูล
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <label for="spare_part"
                                                                                    class="form-label">อะไหล่ที่่เปลี่ยน</label>
                                                                                <textarea class="form-control"
                                                                                    id="spare_part" name="spare_part"
                                                                                    rows="2" required></textarea>

                                                                            </div>

                                                                            <div class="col-md-4">

                                                                                <?php                                                                  
                                                                        $select_status = $conn->query("SELECT * FROM tbl_status Where sid >=2  and sid <=4");
                                                                        $select_status->execute();                                                                                          
                                                                    ?>
                                                                                <label for="status_id"
                                                                                    class="form-label">สถานะ</label>
                                                                                <select name="status_id"
                                                                                    class="form-select" required>
                                                                                    <option selected></option>
                                                                                    <?php 
                                                while($row = $select_status->fetch(PDO::FETCH_ASSOC)) { ?>
                                                                                    <option>
                                                                                        <?php echo $row['status_name'] ?>
                                                                                    </option>
                                                                                    <?php
                                                }?>
                                                                                </select>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <label for="note"
                                                                                    class="form-label">หมายเหตุ</label>
                                                                                <textarea class="form-control" id="note"
                                                                                    name="note" rows="2"></textarea>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <a href="tech_maintenance.php"
                                                                        class="btn btn-secondary">Go Back</a>
                                                                    <button type="submit" name="update"
                                                                        class="btn btn-primary">อนุมัติ</button>
                                                                </div>
                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


            </main>
        </div>
    </div>