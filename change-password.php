<?php
include 'include/functions.php';

checkUser();

if ($_SESSION['role'] == 'admin') {
    redirect('login');
}


// Required field names
$required = array('old_password', 'password', 'password2');
$error = checkForm($required);

if (isset($_POST['form_sent']) && empty($error)) {
    
    if ($_POST['password'] != $_POST['password2']) {
        $error[] = 'Passwords mismatch';
    }

    if (strlen($_POST['password']) < 8) {
        $error[] = 'Password must be at least 8 characters long';
    }

    if ($_SESSION['role'] == 'student') {
        $query = $db->prepare("SELECT password FROM student WHERE student_id=:id");
        $query->bindParam(':id', $_SESSION['student_id']);
        $query->execute();
        $row = $query->fetch();
        $old_password = $row['password'];
    } else { // Professor
        $query = $db->prepare("SELECT password FROM professor WHERE id_professor=:id");
        $query->bindParam(':id', $_SESSION['id_prof']);
        $query->execute();
        $row = $query->fetch();
        $old_password = $row['password'];
    }

    if (!password_verify($_POST['old_password'], $old_password)) {
        $error[] = 'Old password is incorrect';
    }

    if (empty($error)) {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        try {
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $db->beginTransaction();

            if ($_SESSION['role'] == 'student') {
                $change_query = $db->prepare('UPDATE student SET password=:password WHERE student_id=:id');
                $change_query->bindParam(':password', $password);
                $change_query->bindParam(':id', $_SESSION['student_id']);
            } else {
                $change_query = $db->prepare('UPDATE professor SET password=:password WHERE id_professor=:id');
                $change_query->bindParam(':password', $password);
                $change_query->bindParam(':id', $_SESSION['id_prof']);
            }
            $change_query->execute();

            $db->commit();
            $success = true;
        } catch (Exception $e) {
            $db->rollBack();
            $error[] = $e->getMessage();
        }
    }
}

include 'include/head.php';
include 'include/navigation.php';
?>

    <!-- Page Content -->
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Welcome <?php echo htmlspecialchars($_SESSION['user']) ?></h1>
                </div>
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"> Change password </div>
                        <div class="panel-body">
                            <?php errorMessages(); ?>
                            <?php successMessage(); ?>
                            <div class="form-group">
                                <form method="post">
                                    <label>Old password</label>
                                    <input class="form-control" type="password" name="old_password" value="<?php echo (isset($_POST['old_password'])) ? htmlspecialchars($_POST['old_password']) : '' ?>">
                                    <label>New password</label>
                                    <input class="form-control" name="password" type="password" value="<?php echo (isset($_POST['password'])) ? htmlspecialchars($_POST['password']) : '' ?>">
                                    <label>Repeat new password</label>
                                    <input class="form-control" name="password2" type="password" value="<?php echo (isset($_POST['password2'])) ? htmlspecialchars($_POST['password2']) : '' ?>">
                                    <br />
                                    <input type="hidden" name="form_sent">
                                    <input type="submit">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /#page-wrapper -->

<?php
include 'include/foot.php';
