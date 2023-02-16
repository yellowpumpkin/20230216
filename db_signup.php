<?php 
    session_start();
    require_once 'config/db.php';

    if (isset($_POST['signup'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $c_password = $_POST['c_password'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $phone =  $_POST['phone'];
        $department = $_POST['department'];
        $urole = $_POST['urole'];
        $status = '0';

        if (empty($username)) {
            $_SESSION['error'] = 'กรุณากรอก username';
            header("location: signup.php");
        } else if (empty($password)) {
            $_SESSION['error'] = 'กรุณากรอกรหัสผ่าน';
            header("location: signup.php");
        } else if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
            $_SESSION['error'] = 'รหัสผ่านต้องมีความยาวระหว่าง 5 ถึง 20 ตัวอักษร';
            header("location: signup.php");
        } else if (empty($c_password)) {
            $_SESSION['error'] = 'กรุณายืนยันรหัสผ่าน';
            header("location: signup.php");
        } else if ($password != $c_password) {
            $_SESSION['error'] = 'รหัสผ่านไม่ตรงกัน';
            header("location: signup.php");
        } else if (strlen($_POST['phone']) > 10) {
            $_SESSION['error'] = 'เบอร์โทรศัพท์ต้องมีความยาว 10 ตัว';
            header("location: signup.php");
        } else if (empty($urole)) {
            $_SESSION['error'] = 'กรุณากรอกระดับสมาชิก';
            header("location: signup.php");
        } else {
            try {
                $check_username = $conn->prepare("SELECT username FROM tbl_users WHERE username = :username");
                $check_username->bindParam(":username", $username);
                $check_username->execute();
                $row = $check_username->fetch(PDO::FETCH_ASSOC);
                
                if ($row['username'] == $username) {
                    $_SESSION['warning'] = "มี username นี้อยู่ในระบบแล้ว";
                    header("location: signup.php");
                } else if (!isset($_SESSION['error'])) {
                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("INSERT INTO tbl_users(username, password, firstname, lastname, phone, department, urole, status) 
                                            VALUES(:username , :password, :firstname, :lastname, :phone, (SELECT department_id FROM tbl_department WHERE department_id = (SELECT department_id FROM tbl_department WHERE department_name=:department)), :urole, :status)");
                    
                    $stmt->bindParam(":username", $username);
                    $stmt->bindParam(":password", $passwordHash);
                    $stmt->bindParam(":firstname", $firstname);
                    $stmt->bindParam(":lastname", $lastname);
                    $stmt->bindParam(":phone", $phone);
                    $stmt->bindParam(":department", $department);
                    $stmt->bindParam(":urole", $urole);
                    $stmt->bindParam(":status", $status);
                    $stmt->execute();
                    $_SESSION['success'] = "สมัครสมาชิกเรียบร้อยแล้ว!";
                    header("location: signup.php");
                } else {
                    $_SESSION['error'] = "มีบางอย่างผิดพลาด";
                    header("location: signup.php");
                }

            } catch(PDOException $e) {
                echo $e->getMessage();
            }
        }
        
    } else if (isset($_POST['update_users'])) {
        $uid = $_POST['uid'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $phone =  $_POST['phone'];
        $department = $_POST['department'];
        $urole = $_POST['urole'];
        $status = $_POST['status'];

        try {
            if (!isset($_SESSION['error'])){       
                $sql = $conn->prepare("UPDATE tbl_users SET username=:username , firstname=:firstname , lastname=:lastname  , phone=:phone , urole=:urole , status=
                :status  WHERE id = $uid ");
                $sql->bindParam(":username",  $username );
                $sql->bindParam(":firstname",  $firstname );
                $sql->bindParam(":lastname",  $lastname );
                  
                $sql->bindParam(":phone",  $phone);
                $sql->bindParam(":urole",  $urole);
                $sql->bindParam(":status", $status);        
                $sql->execute();           
                $_SESSION['success'] = "อัพเดทข้อมูลสำเร็จ";
                header("location: manage_users.php");
            } 
        } catch(PDOException $e) {
            echo $e->getMessage();
            }
        } else if (isset($_POST['reset_password'])) {
            $uid = $_POST['uid'];
            $password = $_POST['password'];
            $c_password = $_POST['c_password'];

            if (empty($password)) {
                $_SESSION['error'] = 'กรุณากรอกรหัสผ่าน';
                header("location: resetpassword.php");
            } else if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
                $_SESSION['error'] = 'รหัสผ่านต้องมีความยาวระหว่าง 5 ถึง 20 ตัวอักษร';
                header("location: resetpassword.php");
            } else if (empty($c_password)) {
                $_SESSION['error'] = 'กรุณายืนยันรหัสผ่าน';
                header("location: resetpassword.php");
            } else if ($password != $c_password) {
                $_SESSION['error'] = 'รหัสผ่านไม่ตรงกันค่ะ';
                header("location: resetpassword.php");
            }  else {
                try {
                    if (!isset($_SESSION['error'])){

                        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                        $sql = $conn->prepare("UPDATE tbl_users SET password=:password  WHERE id = $uid ");                      
                        $sql->bindParam(":password",  $passwordHash );                                
                        $sql->execute();           
                        $_SESSION['success'] = "รีเซ็ตรหัสผ่านสำเร็จ";
                        header("location: manage_users.php");
                            
                    } 
                
                    else {
                        $_SESSION['error'] = "มีบางอย่างผิดพลาด";
                        header("location: manage_users.php");
                    }
    
                } catch(PDOException $e) {
                    echo $e->getMessage();
                }
            }




        }
    
        
        
   
?>