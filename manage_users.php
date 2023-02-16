<?php 

    session_start();
    require_once 'config/db.php';
    if (!isset($_SESSION['admin_login'])) {
        $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ!';
        header('location: signin.php');
    }

    if (isset($_REQUEST['delete_id'])) {
        $id = $_REQUEST['delete_id'];

        $select_stmt = $conn->prepare("SELECT * FROM tbl_users WHERE id = :id");
        $select_stmt->bindParam(':id', $id);
        $select_stmt->execute();
        $row = $select_stmt->fetch(PDO::FETCH_ASSOC);

        // Delete an original record from db
        $delete_stmt = $conn->prepare('DELETE FROM tbl_users WHERE id = :id');
        $delete_stmt->bindParam(':id', $id);
        $delete_stmt->execute();

        header('Location:manage_users.php');
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
    ?>
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
                        <li class="nav-item">
                            <a class="nav-link " aria-current="page" href="maintenance_view.php">
                                <i data-feather="folder"></i>
                                <span class="ml-2">ข้อมูลแจ้งซ่อม</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " aria-current="page" data-toggle="collapse" href="#Adepartment">
                                <i data-feather="list"></i>
                                <span class="ml-2">จัดการข้อมูลพื้นฐาน</span>
                            </a>
                            <div class="collapse in" id="Adepartment">
                                <ul id="">
                                    <li id="" class="nav-item">
                                        <a class="nav-link" href="manage_department.php">
                                            <span class="link-collapse">ข้อมูลแผนกงาน</span>
                                        </a>
                                    </li>
                                    <li id="" class="nav-item">
                                        <a class="nav-link" href="manage_status.php">
                                            <span class="link-collapse">ข้อมูลสถานะ</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" data-toggle="collapse"
                                href="manage_users.php">
                                <i data-feather="users"></i>
                                <span class="ml-2">จัดการข้อมูลผู้ใช้งาน</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " aria-current="page" data-toggle="collapse"
                                href="manage_maintenance.php">
                                <i data-feather="settings"></i>
                                <span class="ml-2">จัดการข้อมูลแจ้งซ่อม</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 py-4">
                <div class="row">
                    <div class="col-12 ">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title text-center">จัดการข้อมูลผู้ใช้งาน</h4>
                            </div>
                            <div class="row">
                                <div class="col-xs-6 col-sm-4 col-md-5 col-lg-7 mt-3 ">
                                    <a href="signup.php" class="btn btn-success mb-3 ml-1 ">+ เพิ่มข้อมูลผู้ใช้งาน</a>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
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
                                        <table class="table  table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th scope="col">ลำดับ</th>
                                                    <th scope="col">ชื่อ</th>
                                                    <th scope="col">นามสกุล</th>
                                                    <th scope="col">ตำแหน่ง</th>
                                                    <th scope="col">username</th>
                                                    <th scope="col">password</th>
                                                    <th scope="col">แก้ไข</th>
                                                    <th scope="col">เปลี่ยนรหัสผ่าน</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $select_stmt = $conn->query("select * from tbl_users 
                                                    INNER JOIN tbl_department ON department = department_id WHERE status = '0'
                                                    ");
                                                    $select_stmt->execute();
                                                    while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)){
                                                ?>
                                                <tr>
                                                    <td><?php echo $row["id"]; ?></td>
                                                    <td><?php echo $row["firstname"]; ?></td>
                                                    <td><?php echo $row["lastname"]; ?></td>
                                                    <td><?php echo $row["urole"]; ?></td>
                                                    <td><?php echo $row["username"]; ?></td>
                                                    <td><?php echo $row["password"]; ?></td>
                                                    <td><button type="button" class="btn btn-warning"
                                                            data-toggle="modal"
                                                            data-target="#exampleModalCenter<?php echo $row["id"]; ?>">Edit</button>
                                                    </td>
                                                    <td><a href="resetpassword.php?id=<?php echo $row["id"]; ?>"
                                                            class="btn btn-primary">resetpassword</a>
                                                    </td>
                                                </tr>
                                                <div class="modal fade bd-example-modal-lg"
                                                    id="exampleModalCenter<?php echo $row["id"]; ?>" tabindex="-1"
                                                    role="dialog" aria-labelledby="exampleModalCenterTitle"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered  modal-lg"
                                                        role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLongTitle">
                                                                    ข้อมูลสมาชิก</h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form id="myform1" name="form1" method="post"
                                                                    class="row g-3" action=db_signup.php>
                                                                    <div class="modal-body">
                                                                        <div class="form-group">
                                                                            <h5 mt-4>รายละเอียด</h5>
                                                                            <hr>
                                                                            <div class="row">
                                                                                <div class="col-md-2">
                                                                                    <label for="uid"
                                                                                        class="form-label">เลขที่</label>
                                                                                    <input readonly
                                                                                        value="<?php echo $row['id']; ?>"
                                                                                        required class="form-control"
                                                                                        name="uid">
                                                                                </div>

                                                                                <div class="col-md-4">
                                                                                    <label for="username"
                                                                                        class="form-label">Username</label>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        name="username"
                                                                                        aria-describedby="username"
                                                                                        value="<?php echo $row['username']; ?>"
                                                                                        required readonly>
                                                                                    <div class="invalid-feedback">
                                                                                        กรุณากรอก username
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="password"
                                                                                        class="form-label">Password
                                                                                    </label>

                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        name="password"
                                                                                        value="<?php echo $row['password']; ?>"
                                                                                        required readonly>

                                                                                </div>

                                                                                <div class="col-md-4">
                                                                                    <label for="firstname"
                                                                                        class="form-label">First
                                                                                        Name</label>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        name="firstname"
                                                                                        aria-describedby="firstname"
                                                                                        value="<?php echo $row['firstname']; ?>"
                                                                                        required>
                                                                                    <div class="invalid-feedback">
                                                                                        กรุณากรอกชื่อจริง
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-4">
                                                                                    <label for="lastname"
                                                                                        class="form-label">Last
                                                                                        Name</label>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        name="lastname"
                                                                                        aria-describedby="lastname"
                                                                                        value="<?php echo $row['lastname']; ?>"
                                                                                        required>
                                                                                    <div class="invalid-feedback">
                                                                                        กรุณากรอกนามสกุล
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-4">
                                                                                    <label for="phone"
                                                                                        class="form-label">Phone
                                                                                        Number</label>
                                                                                    <input type="tel"
                                                                                        class="form-control"
                                                                                        name="phone"
                                                                                        pattern="^0([8|9|6])([0-9]{8}$)"
                                                                                        autocomplete="off"
                                                                                        value="0<?php echo $row['phone']; ?>"
                                                                                        required>
                                                                                    <div class="invalid-feedback">
                                                                                        กรุณากรอกเบอร์มือถือตัวเลข 10
                                                                                        หลัก
                                                                                    </div>
                                                                                </div>
                                                                                <?php 
                                                                                    $select_department = $conn->prepare("SELECT department_name FROM tbl_department ");
                                                                                    $select_department->execute();                                                        
                                                                                ?>
                                                                                <div class="col-md-4">
                                                                                    <label for="department"
                                                                                        class="form-label">Department</label>
                                                                                    <select name="department"
                                                                                        class="form-select"
                                                                                        name="department" required>
                                                                                        <option selected>
                                                                                            <?php echo $row['department_name']; ?>
                                                                                        </option>
                                                                                        <?php 
                                                                                             while($rows = $select_department->fetch(PDO::FETCH_ASSOC)) { ?>
                                                                                        <option>
                                                                                            <?php echo $rows['department_name'] ?>
                                                                                        </option>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                    </select>
                                                                                    <div class="invalid-feedback">
                                                                                        กรุณาเลือกแผนกงาน
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-4">
                                                                                    <label for="urole"
                                                                                        class="form-label">urole</label>
                                                                                    <select id="urole"
                                                                                        class="form-select" name="urole"
                                                                                        required>
                                                                                        <option selected>
                                                                                            <?php echo $row["urole"]; ?>
                                                                                        </option>
                                                                                        <option>admin</option>
                                                                                        <option>leader</option>
                                                                                        <option>technician</option>
                                                                                        <option>users</option>
                                                                                    </select>
                                                                                    <div class="invalid-feedback">
                                                                                        กรุณาเลือกบทบาท
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-4">
                                                                                    <label for="status"
                                                                                        class="form-label">status
                                                                                        (0-active,1-ban)</label>
                                                                                    <select id="urole"
                                                                                        class="form-select"
                                                                                        name="status" required>
                                                                                        <option selected>
                                                                                            <?php echo $row["status"]; ?>
                                                                                        </option>
                                                                                        <option>0</option>
                                                                                        <option>1</option>

                                                                                    </select>
                                                                                    <div class="invalid-feedback">
                                                                                        กรุณาเลือกบทบาท
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-12">
                                                                                    <a style="float: right;"
                                                                                        href="resetpassword.php?id=<?php echo $row["id"]; ?>">forgot
                                                                                        password</a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="modal-footer">
                                                                        <a href="manage_users.php"
                                                                            class="btn btn-secondary">กลับ</a>
                                                                        <button type="submit" name="update_users"
                                                                            class="btn btn-primary">บันทึก</button>
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
    <script src="https://unpkg.com/jquery@3.3.1/dist/jquery.min.js"></script>

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