<?php
include 'include/functions.php';

checkAdmin();

if (!isset($_GET['id'])) {
    redirect('responsibles');
}

$id = $_GET['id'];

// Required field names
$required = array('name', 'surname', 'id', 'address', 'cp', 'city', 'tel');
$error = checkForm($required);

if (isset($_GET['delete']) && isset($id)) {
    try {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->beginTransaction();
        $query = $db->prepare("DELETE FROM responsible WHERE id=:id");
        $query->bindParam(':id', $id);
        $query->execute();
        $db->commit();

        redirect('responsibles');
    } catch (Exception $e) {
        $db->rollBack();
        $error[] = $e->getMessage();
    }
}

if (isset($_POST['form_sent']) && empty($error)) {
    try {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->beginTransaction();
        $query = $db->prepare('UPDATE responsible SET name_responsible=:name, surname_responsible=:surname, street_responsible=:street, cp_responsible=:cp, city_responsible=:city, mobile_responsible=:tel, student_id:student_id WHERE id=:id');
        $query->bindParam(':name', $_POST['name']);
        $query->bindParam(':surname', $_POST['surname']);
        $query->bindParam(':street', $_POST['address']);
        $query->bindParam(':cp', $_POST['cp']);
        $query->bindParam(':city', $_POST['city']);
        $query->bindParam(':tel', $_POST['fixe']);
        $query->bindParam(':student_id', $_POST['student_id']);
        $query->bindParam(':id', $id);
        $query->execute();
        $db->commit();

        redirect('responsibles');
    } catch (Exception $e) {
        $db->rollBack();
        $error[] = $e->getMessage();
    }

}

$query = $db->prepare("SELECT * FROM responsible WHERE id=:id");
$query->bindParam(':id', $id);
$query->execute();
$responsible = $query->fetch();

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
                        <div class="panel-heading"> Edit a responsible - <a href="edit-responsible.php?id=<?php echo htmlspecialchars($id) ?>&delete">Delete this responsible</a> </div>
                        <div class="panel-body">
                            <?php errorMessages(); ?>
                            <div class="form-group">
                                <form method="post">
                                    <label>Last name</label>
                                    <input class="form-control" name="name" value="<?php echo htmlspecialchars($responsible['name_responsible']) ?>">
                                    <label>First name</label>
                                    <input class="form-control" name="surname" value="<?php echo htmlspecialchars($responsible['surname_responsible']) ?>">
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
                                    <label>Address</label>
                                    <input class="form-control" name="address" value="<?php echo htmlspecialchars($responsible['street_responsible']) ?>">
                                    <label>Postal code</label>
                                    <input class="form-control" name="cp" value="<?php echo htmlspecialchars($responsible['cp_responsible']) ?>">
                                    <label>City</label>
                                    <input class="form-control" name="city" value="<?php echo htmlspecialchars($responsible['city_responsible']) ?>">
                                    <label>Phone</label>
                                    <input class="form-control" name="tel" value="<?php echo htmlspecialchars($responsible['mobile_responsible']) ?>">
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
