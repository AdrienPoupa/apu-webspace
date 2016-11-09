<?php
include 'include/functions.php';

// Already logged in
if (isset($_SESSION['user'])) {
    redirect('index');
}

$error = false;

// If the form has been submitted
if (!empty($_POST['user']) && !empty($_POST['password']) && !empty($_POST['role'])) {

    // Student login
    if ($_POST['role'] == 'student') {

        // Get password from DB
        $query_student = $db->prepare("SELECT student_id, name, surname, password FROM student WHERE tp_number LIKE :tp_number");
        $query_student->bindParam(':tp_number', $_POST['user']);
        $query_student->execute();
        $student = $query_student->fetch();

        // Compare DB password and the submitted password
        if (!$student || !password_verify($_POST['password'], $student['password'])) {
            $error = true;
        }
        // If they match, start a session and redirect
        else {
            $_SESSION['student_id'] = $student['student_id'];
            $_SESSION['user'] = $_POST['user'].' '.$student['surname'].' '.$student['name'];
            $_SESSION['role'] = 'student';
            redirect('index');
        }

    // Professor login
    } else if ($_POST['role'] == 'prof') {

        // Get password from DB
        $query_prof = $db->prepare("SELECT id_professor, name_professor, surname_professor, password FROM professor WHERE name_professor LIKE :name");
        $query_prof->bindParam(':name', $_POST['user']);
        $query_prof->execute();
        $prof = $query_prof->fetch();

        // Compare DB password and the submitted password
        if (!$prof || !password_verify($_POST['password'], $prof['password'])) {
            $error = true;
        }
        // If they match, start a session and redirect
        else  {
            $_SESSION['id_prof'] = $prof['id_professor'];
            $_SESSION['user'] = $prof['surname_professor'].' '.$prof['name_professor'];
            $_SESSION['role'] = 'prof';
            redirect('index');
        }
    }

    // Admin login
    else if ($_POST['role'] == 'admin') { // Admin
        if ($_POST['password'] == $admin_password) {
            $_SESSION['user'] = $_POST['user'];
            $_SESSION['role'] = 'admin';
            redirect('index');
        }
        else {
            $error = true;
        }
    }

    // Unknown login
    else {
        $error = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>APU Webspace - Login</title>
	
	<link rel="shortcut icon" href="dist/img/favicon" />

    <link href="js/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="js/metisMenu/dist/metisMenu.min.css" rel="stylesheet">
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="js/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-7 col-md-offset-3">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <img src="dist/img/logo.png">
                        <h3 class="panel-title"><i class="fa fa-sign-in fa-fw"></i> WebSpace Single Sign On</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <select class="form-control" name="role">
                                        <option value="admin">Admin</option>
                                        <option value="prof">Professor</option>
                                        <option value="student">Student</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="User or TP number" name="user" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                </div>
                                <?php if ($error) : ?>
                                <p align="center" style="font-weight: bold; color: red">Bad credentials</p>
                                <?php endif; ?>
                                <input type="submit" value="Login" align="center">
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="js/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="js/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>

</body>

</html>
