<?php
include 'include/functions.php';

checkAdmin();

// Required field names
$required = array('name', 'surname', 'id', 'address', 'cp', 'city', 'tel');
$error = checkForm($required);

if (isset($_POST['form_sent']) && empty($error)) {
    try {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->beginTransaction();
        $query = $db->prepare('INSERT INTO responsible (student_id, name_responsible, surname_responsible, mobile_responsible, email_responsible, street_responsible, cp_responsible, city_responsible) VALUES (:id, :name, :surname, :tel, :email, :street, :cp, :city)');
        $query->bindParam(':id', $_POST['id']);
        $query->bindParam(':name', $_POST['name']);
        $query->bindParam(':surname', $_POST['surname']);
        $query->bindParam(':tel', $_POST['tel']);
        $query->bindParam(':email', $_POST['mail']);
        $query->bindParam(':street', $_POST['address']);
        $query->bindParam(':cp', $_POST['cp']);
        $query->bindParam(':city', $_POST['city']);
        $query->execute();
        $db->commit();

        redirect('responsibles');
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
                        <div class="panel-heading"> Add a responsible </div>
                        <div class="panel-body">
                            <?php errorMessages(); ?>
                            <div class="form-group">
                                <form method="post">
                                    <label>Last name</label>
                                    <input class="form-control" name="name">
                                    <label>First name</label>
                                    <input class="form-control" name="surname">
                                    <label>Student</label>
                                    <select class="form-control" name="id">
                                        <option value="">None</option>
                                        <?php
                                        $query = $db->prepare("SELECT name, surname, student_id FROM student");
                                        $query->execute();
                                        while ( $row = $query->fetch())
                                            echo '<option value="'.htmlspecialchars($row['student_id']).'">'.htmlspecialchars($row['surname'].' '.$row['name']).'</option>';
                                        ?>
                                    </select>
                                    <label>Email</label>
                                    <input class="form-control" name="mail">
                                    <label>Address</label>
                                    <input class="form-control" name="address">
                                    <label>Postal code</label>
                                    <input class="form-control" name="cp">
                                    <label>City</label>
                                    <input class="form-control" name="city">
                                    <label>Phone</label>
                                    <input class="form-control" name="tel">
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
