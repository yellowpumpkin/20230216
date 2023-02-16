<?php 

    session_start();
    require_once 'config/db.php';
    if (!isset($_SESSION['admin_login']) and !isset($_SESSION['leader_login'])) {
        $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ!';
        header('location: signin.php');
    }

    if (isset($_POST['update'])) {
        $case_id = $_POST['case_id'];
        $status_id = $_POST['status_id'];
        $tech = $_POST['technician_name'];
    

        $sql = $conn->prepare("UPDATE tbl_case SET status_id=(SELECT sid FROM tbl_status WHERE sid = (SELECT sid FROM tbl_status WHERE status_name=:status_id))  , tech= :technician_name  WHERE case_id = $case_id ");
        $sql->bindParam(":status_id",  $status_id );
        $sql->bindParam(":technician_name",  $tech );
        $sql->execute();

        if ($sql) {
            $_SESSION['success'] = "Data has been updated successfully";
            header("location: manage_maintenance.php");
        } else {
            $_SESSION['error'] = "Data has not been updated successfully";
            header("location: manage_maintenance.php");
        }
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css"
        integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">

    <!-- Add icon library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- cdn data table   -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" />

    <!-- cdn feather icons   -->
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
    .sidebar {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        z-index: 100;
        padding: 90px 0 0;
        box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
        z-index: 99;
    }

    @media (max-width: 767.98px) {
        .sidebar {
            top: 11.5rem;
            padding: 0;
        }
    }

    .navbar {
        box-shadow: inset 0 -1px 0 rgba(0, 0, 0, .1);
    }

    @media (min-width: 767.98px) {
        .navbar {
            top: 0;
            position: sticky;
            z-index: 999;
        }
    }

    .sidebar .nav-link {
        color: #333;
    }

    .sidebar .nav-link.active {
        color: #0d6efd;
    }

    .btn-manage {
        background-color: DodgerBlue;
        /* Blue background */
        border: none;
        /* Remove borders */
        color: white;
        /* White text */
        padding: 12px 16px;
        /* Some padding */
        font-size: 16px;
        /* Set a font size */
        cursor: pointer;
        /* Mouse pointer on hover */
    }

    /* Darker background on mouse-over */
    .btn-manage:hover {
        background-color: RoyalBlue;
    }
    </style>
</head>

<body>
    <?php 
        if (isset($_SESSION['admin_login'])) {
            $admin_id = $_SESSION['admin_login'];
            $stmt = $conn->query("SELECT * FROM tbl_users WHERE id = $admin_id");
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        else  if  (isset($_SESSION['leader_login'])) {
            $leader_id = $_SESSION['leader_login'];
            $stmt = $conn->query("SELECT * FROM tbl_users WHERE id = $leader_id");
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    ?>
    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">ข้อมูลแจ้งซ่อม</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-light bg-light p-3">
        <div class="d-flex col-12 col-md-3 col-lg-2 mb-2 mb-lg-0 flex-wrap flex-md-nowrap justify-content-between">
            <a class="navbar-brand" href="admin.php">
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
                        <?php if (isset($_SESSION['admin_login'])) { ?>
                        <a class="nav-link active" aria-current="page" href=admin.php>
                            <i data-feather="rewind"></i>
                            <span class="ml-2">BACK</span>
                        </a>
                        <?php } ?>

                        <?php if (isset($_SESSION['leader_login'])) { ?>
                        <a class="nav-link active" aria-current="page" href=leader.php>
                            <i data-feather="rewind"></i>
                            <span class="ml-2">BACK</span>
                        </a>
                        <?php } ?>
                    </ul>
                </div>
            </nav>
            <main class="col-md-12 ml-sm-auto col-lg-10 px-md-4 py-4">
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
                                                INNER JOIN tbl_department ON department = department_id ");
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
                                                <?php
                                                    if ($row["status_name"] == $status[8]){ ?>
                                                <td><a style="color: #0E1BEA"><?php echo $row["status_name"]; ?></a>
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
                                                <div class="modal-dialog modal-dialog-centered  modal-lg"
                                                    role="document">
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
                                                                class="row g-3" action=manage_maintenance.php>
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
                                                                            <h6 class="mt-4">เลือกผู้ดำเนินการ</h6>
                                                                            <hr>
                                                                            <div class="col-md-4">

                                                                                <label for="technician_name"
                                                                                    class="form-label">Technician</label>
                                                                                <select name="technician_name"
                                                                                    class="form-select"
                                                                                    name="technician_name" required>
                                                                                    <option selected></option>
                                                                                    <?php 
                                                while($row = $select_technician->fetch(PDO::FETCH_ASSOC)) { ?>
                                                                                    <option>
                                                                                        <?php echo $row['username'] ?>
                                                                                    </option>
                                                                                    <?php
                                                }?>

                                                                                </select>


                                                                            </div>

                                                                            <div class="col-md-4">

                                                                                <?php                                                                  
                                                                        $select_status = $conn->query("SELECT * FROM tbl_status Where sid > 1");
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
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <a href="manage_maintenance.php"
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

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js"
        integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- cdn bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
    </script>
    <script>
    feather.replace()
    </script>

    <script>
    $(document).ready(function() {
        $("#myTable").DataTable();
    });
    </script>

    <script type="text/javascript">
    $(function() {
        $("#myform1").on("submit", function() {
            var form = $(this)[0];
            if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
    </script>
</body>

</html>