<?php
include 'include/functions.php';

checkUser();

if ($_SESSION['role'] == 'admin') {
    redirect('login');
}


// Required field names
$required = array('address', 'cp', 'city', 'fixe', 'portable');
$error = checkForm($required);

if (isset($_POST['form_sent']) && empty($error)) {

        try {
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $db->beginTransaction();

            $change_query = $db->prepare('UPDATE student SET street=:address, cp=:cp, city=:city, phone=:fixe, mobile=:portable WHERE student_id=:id');
            $change_query->bindParam(':address', $_POST['address']);
            $change_query->bindParam(':cp', $_POST['cp']);
            $change_query->bindParam(':city', $_POST['city']);
            $change_query->bindParam(':fixe', $_POST['fixe']);
            $change_query->bindParam(':portable', $_POST['portable']);
            $change_query->bindParam(':id', $_SESSION['student_id']);
            $change_query->execute();

            $db->commit();
            $success = true;
        } catch (Exception $e) {
            $db->rollBack();
            $error[] = $e->getMessage();
        }
}

if ($_SESSION['role'] == 'student') {
    $query = $db->prepare("SELECT * FROM student, responsible WHERE student.student_id = responsible.student_id AND student.student_id=:id");
    $query->bindParam(':id', $_SESSION['student_id']);
    $query->execute();
    $user = $query->fetch();
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
                        <div class="panel-heading"> Profile </div>
                        <div class="panel-body">
                            <?php errorMessages(); ?>
                            <?php successMessage(); ?>
                            <div class="form-group">
                                <form method="post">
                                    <label>Last name</label>
                                    <input id="disabledInput" disabled="" class="form-control" name="name" value="<?php echo htmlspecialchars($user['name']) ?>">
                                    <label>First name</label>
                                    <input id="disabledInput" disabled="" class="form-control" name="surname" value="<?php echo htmlspecialchars($user['surname']) ?>">
                                    <label>Birthdate</label>
                                    <input id="disabledInput" disabled="" class="form-control" name="birthdate" placeholder="AAAA-MM-JJ" value="<?php echo htmlspecialchars($user['birthdate']) ?>">
                                    <label>Gender</label>
                                    <input id="disabledInput" disabled="" class="form-control" name="gender" placeholder="M/F" value="<?php echo htmlspecialchars($user['gender']) ?>">
                                    <label>TP number</label>
                                    <input id="disabledInput" disabled="" class="form-control" name="tp_number" value="<?php echo htmlspecialchars($user['tp_number']) ?>">
                                    <label>Previous school</label>
                                    <input id="disabledInput" disabled="" class="form-control" name="etablissement" value="<?php echo htmlspecialchars($user['previous_school']) ?>">
                                    <label>Responsible</label>
                                    <input id="disabledInput" disabled="" class="form-control" name="address" value="<?php echo htmlspecialchars($user['name_responsible'].' '.$user['surname_responsible']) ?>">
                                    <label>Address</label>
                                    <input class="form-control" name="address" value="<?php echo htmlspecialchars($user['street']) ?>">
                                    <label>Postal code</label>
                                    <input class="form-control" name="cp" value="<?php echo htmlspecialchars($user['cp']) ?>">
                                    <label>City</label>
                                    <input class="form-control" name="city" value="<?php echo htmlspecialchars($user['city']) ?>">
                                    <label>Phone</label>
                                    <input class="form-control" name="fixe" value="<?php echo htmlspecialchars($user['phone']) ?>">
                                    <label>Mobile phone</label>
                                    <input class="form-control" name="portable" value="<?php echo htmlspecialchars($user['mobile']) ?>">
                                    <br />
                                    <input type="hidden" name="form_sent"><input type="submit">
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
