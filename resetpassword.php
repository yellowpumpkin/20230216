<?php 

    session_start();
    require_once 'config/db.php';
    if (!isset($_SESSION['admin_login'])) {
        $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ!';
        header('location: signin.php');
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
                            <a class="nav-link active " aria-current="page" data-toggle="collapse"
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
                <div class="container mt-2">
                    <h1>Reset password</h1>
                    <hr>
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

                    <form action=db_signup.php enctype="multipart/form-data" id="myform1" name="form1" method="post"
                        novalidate>
                        <?php
                if (isset($_GET['id'])) {
                        $id = $_GET['id'];
                        $select_stmt = $conn->query("select * from tbl_users 
                         where id = $id");
                        $select_stmt->execute();
                        $row = $select_stmt->fetch();
                }
            ?>
                        <div class="row">


                            <div class="col-md-6">
                                <label for="uid" class="form-label">เลขที่</label>
                                <input readonly value="<?php echo $row['id']; ?>" required class="form-control"
                                    name="uid">
                            </div>
                            <div class="col-md-6">
                                <label for="username" class="form-label">username</label>
                                <input readonly value="<?php echo $row['username']; ?>" required class="form-control"
                                    name="username">
                            </div>
                            <div class="col-md-12">
                                <label for="password" class="form-label">Password (must be between 5 - 20
                                    characters)</label>
                                <input type="password" class="form-control" name="password" value="" required>
                                <div class="invalid-feedback">
                                    กรุณากรอกรหัสผ่าน
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="confirm password" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" name="c_password" value="" required>
                                <div class="invalid-feedback">
                                    กรุณาคอนเฟิร์มรหัสผ่าน
                                </div>
                            </div>


                            <div class="col-md-12">
                                <hr>
                                <a href="manage_users.php" class="btn btn-secondary">Go Back</a>
                                <button type="submit" name="reset_password" class="btn btn-primary">Reset</button>
                            </div>
                        </div>
                    </form>
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
    <script>
    feather.replace()
    </script>
</body>

</html>