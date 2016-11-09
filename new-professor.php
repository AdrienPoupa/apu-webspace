<?php
include 'include/functions.php';

checkAdmin();

// Required field names
$required = array('name', 'surname', 'password');
$error = checkForm($required);

if (isset($_POST['form_sent']) && empty($error)) {
    try {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->beginTransaction();
        $query = $db->prepare('INSERT INTO professor (name_professor, surname_professor, password) VALUES (:name, :surname, :password)');
        $query->bindParam(':name', $_POST['name']);
        $query->bindParam(':surname', $_POST['surname']);
        $query->bindParam(':password', password_hash($_POST['password'], PASSWORD_BCRYPT));
        $query->execute();

        $db->commit();

        redirect('professors');
    } catch (Exception $e) {
        $db->rollBack();
        $error[] = $e->getMessage();
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
                        <div class="panel-heading"> Add a professor </div>
                        <div class="panel-body">
                            <?php errorMessages(); ?>
                            <div class="form-group">
                                <form method="post">
                                    <label>Last name</label>
                                    <input class="form-control" name="name">
                                    <label>First name</label>
                                    <input class="form-control" name="surname">
                                    <label>Password</label>
                                    <input class="form-control" name="password" type="password">
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
