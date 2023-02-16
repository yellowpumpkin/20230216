<?php 

    session_start();
    require_once 'config/db.php';
    if (!isset($_SESSION['user_login'])) {
        $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ!';
        header('location: signin.php');
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <!--  jQuery -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>

    <!-- Isolated Version of Bootstrap, not needed if your site already uses Bootstrap -->
    <link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" />

    <!-- Bootstrap Date-Picker Plugin -->
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css"
        integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
    <!-- cdn bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

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
        if (isset($_SESSION['user_login'])) {
        $user_id = $_SESSION['user_login'];
        $stmt = $conn->query("SELECT * FROM tbl_users WHERE id = $user_id ");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    ?>
    <nav class="navbar navbar-light bg-light p-3">
        <div class="d-flex col-12 col-md-3 col-lg-2 mb-2 mb-lg-0 flex-wrap flex-md-nowrap justify-content-between">
            <a class="navbar-brand" href="users.php">
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
                                <span class="ml-2">ข้อมูลงานแจ้งซ่อม</span>
                            </a>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" data-toggle="collapse"
                                href="users_maintenance.php">
                                <i data-feather="settings"></i>
                                <span class="ml-2">เพิ่มข้อมูลแจ้งซ่อม</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 py-4">

                <div class="container">
                    <div class="main-panel">
                        <div class="content">
                            <div class="container-fluid">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title text-center">แบบฟอร์มแจ้งซ่อม</h4>
                                        </div>
                                        <div class="form-row card-body justify-content-center">
                                            <h4 class="mt-4">เพิ่มข้อมูลแจ้งซ่อม</h4>
                                            <hr>
                                            <form id="myform1" name="form1" method="post" class="row g-3"
                                                action=db_general.php novalidate>
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
                                                <?php
                                 $nextId = $conn->query("SHOW TABLE STATUS LIKE 'tbl_case'")->fetch(PDO::FETCH_ASSOC)['Auto_increment'];
                                ?>
                                                <div class="col-md-1">
                                                    <label for="no" class="form-label">เลขที่</label>
                                                    <fieldset disabled>
                                                        <input type="text" id="username_case" class="form-control"
                                                            placeholder="" value="<?php echo "$nextId" ?>">
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-4">
                                                    <!-- Date input -->
                                                    <label class="form-label" for="date">วันที่แจ้งซ่อม</label>
                                                    <fieldset disabled>
                                                        <input type="text" id="date_start" class="form-control" name="date_start" 
                                                             value="<?php echo date("Y-m-d ") ;?>" ">
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-4">
                                                    <!-- Date input -->
                                                    <label class="form-label" for="date">วันที่ต้องการใช้เครื่อง</label>
                                                    <input class="form-control" id="date_end" name="date_end"
                                                        placeholder="YYYY-MM-DD" type="text">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="urgency" class="form-label">ความเร่งด่วน</label>
                                                    <select id="urgency" class="form-select" name="urgency" required>
                                                        <option selected>ปกติ</option>
                                                        <option>ด่วน</option>
                                                        <option>ด่วนที่สุด</option>
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        กรุณาเลือกความเร่งด่วน
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="machine_no"
                                                        class="form-label">หมายเลขเครื่องมือ/เครื่องจักร</label>
                                                    <input type="text" class="form-control" name="machine_no"
                                                        aria-describedby="machine_no" value="" required>
                                                    <div class="invalid-feedback">
                                                        กรุณากรอก หมายเลขเครื่องมือ/เครื่องจักร
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="machine_name"
                                                        class="form-label">ชื่อเครื่องมือ/เครื่องจักร</label>
                                                    <input type="text" class="form-control" name="machine_name"
                                                        aria-describedby="machine_name" value="" required>
                                                    <div class="invalid-feedback">
                                                        กรุณากรอก หมายเลขเครื่องมือ/เครื่องจักร
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="place_name" class="form-label">สถานที่</label>
                                                    <input type="text" class="form-control" name="place_name"
                                                        aria-describedby="place_name" value="" required>
                                                    <div class="invalid-feedback">
                                                        กรุณากรอก ชื่อเครื่องมือ/เครื่องจักร
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="problem_case" class="form-label">อาการเบื้องต้น</label>
                                                    <textarea class="form-control" name="problem_case" id="problem_case"
                                                        rows="3" required></textarea>
                                                    <div class="invalid-feedback">
                                                        กรุณากรอกอาการเบื้องต้นที่พบ
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="agency" class="form-label">หน่วยงานที่พบปัญหา</label>
                                                    <select id="agency" class="form-select" name="agency" required>
                                                        <option selected>โรงงาน</option>
                                                        <option>ออฟฟิศ</option>
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        กรุณาเลือกหน่วยงาน
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="username_case" class="form-label">พนักงานแจ้งซ่อม
                                                    </label>
                                                    <input id="username_case" class="form-control" name="username_case"
                                                        value="<?php echo $row['username'] ?>" readonly>
                                                </div>

                                                <div class="col-md-12">
                                                    <br>
                                                    <br>
                                                    <button type="submit" name="insert_info_maintenance"
                                                        class="btn btn-primary " style="float: right;"
                                                        onclick="return confirm('กรุณาตรวจสอบความถูกต้อง')">บันทึก
                                                    </button>
                                                </div>
                                            </form>

                                        </div>

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
    $(document).ready(function() {

        var date_closed = $('input[name="date_end"]'); //our date input has the name "date"
        var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
        var options = {

            format: 'yyyy-mm-dd ',
            container: container,
            orientation: 'auto top',
            todayHighlight: true,
            setDate: new Date(),
            autoclose: true,
        };
        date_closed.datepicker(options);

    })
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
    <script>
    feather.replace()
    </script>
</body>

</html>