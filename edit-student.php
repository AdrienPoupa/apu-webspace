<?php
include 'include/functions.php';

checkAdmin();

if (!isset($_GET['id'])) {
    redirect('students');
}

$id = $_GET['id'];

// Required field names
$required = array('name', 'surname', 'birthdate', 'gender', 'id_resp', 'etablissement', 'registered', 'address', 'cp', 'city', 'fixe', 'portable', 'tp_number');
$error = checkForm($required);

if (isset($_GET['delete']) && isset($id)) {
    try {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->beginTransaction();
        $query = $db->prepare("DELETE FROM student WHERE student_id=:id");
        $query->bindParam(':id', $id);
        $query->execute();
        $db->commit();

        redirect('students');
    } catch (Exception $e) {
        $db->rollBack();
        $error[] = $e->getMessage();
    }
}

if (isset($_POST['form_sent']) && empty($error)) {
    try {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->beginTransaction();
        $query = $db->prepare('UPDATE student SET name=:name, surname=:surname, birthdate=:birthdate, gender=:gender, registered=:registered, previous_school=:previous_school, street=:street, cp=:cp, city=:city, phone=:phone, mobile=:mobile, tp_number=:tp_number WHERE student_id=:id');
        $query->bindParam(':name', $_POST['name']);
        $query->bindParam(':surname', $_POST['surname']);
        $query->bindParam(':birthdate', $_POST['birthdate']);
        $query->bindParam(':gender', $_POST['gender']);
        $query->bindParam(':registered', $_POST['registered']);
        $query->bindParam(':previous_school', $_POST['etablissement']);
        $query->bindParam(':street', $_POST['address']);
        $query->bindParam(':cp', $_POST['cp']);
        $query->bindParam(':city', $_POST['city']);
        $query->bindParam(':phone', $_POST['fixe']);
        $query->bindParam(':mobile', $_POST['portable']);
        $query->bindParam(':tp_number', $_POST['tp_number']);
        $query->bindParam(':id', $id);
        $query->execute();

        if ($_POST['id_resp'] != '') {
            $query = $db->prepare('UPDATE responsible SET student_id=:id WHERE id=:id_resp');
            $query->bindParam(':id', $id);
            $query->bindParam(':id_resp', $_POST['id_resp']);
            $query->execute();
        }

        $db->commit();

        redirect('students');
    } catch (Exception $e) {
        $db->rollBack();
        $error[] = $e->getMessage();
    }

}

$query = $db->prepare("SELECT * FROM student WHERE student_id=:id");
$query->bindParam(':id', $id);
$query->execute();
$student = $query->fetch();

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
                        <div class="panel-heading"> Edit a student - <a href="edit-student.php?id=<?php echo htmlspecialchars($id) ?>&delete">Delete this student</a> </div>
                        <div class="panel-body">
                            <?php errorMessages(); ?>
                            <div class="form-group">
                                <form method="post">
                                    <label>Last name</label>
                                    <input class="form-control" name="name" value="<?php echo htmlspecialchars($student['name']) ?>">
                                    <label>First name</label>
                                    <input class="form-control" name="surname" value="<?php echo htmlspecialchars($student['surname']) ?>">
                                    <label>Birthdate</label>
                                    <input class="form-control" name="birthdate" placeholder="AAAA-MM-JJ" value="<?php echo htmlspecialchars($student['birthdate']) ?>">
                                    <label>Gender</label>
                                    <input class="form-control" name="gender" placeholder="M/F" value="<?php echo htmlspecialchars($student['gender']) ?>">
                                    <label>Registration date</label>
                                    <input class="form-control" name="registered" placeholder="AAAA-MM-JJ" value="<?php echo htmlspecialchars($student['registered']) ?>">
                                    <label>Previous school</label>
                                    <input class="form-control" name="etablissement" value="<?php echo htmlspecialchars($student['previous_school']) ?>">
                                    <label>Responsible</label>
                                    <select class="form-control" name="id_resp">
                                        <option value="">None</option>
                                        <?php
                                        $query = $db->prepare("SELECT id, name_responsible, surname_responsible, student_id FROM responsible");
                                        $query->execute();
                                        while ( $row = $query->fetch())
                                            echo '<option value="'.htmlspecialchars($row['id']).'">'.htmlspecialchars($row['surname_responsible'].' '.$row['name_responsible']).'</option>';
                                        ?>
                                    </select>
                                    <label>Address</label>
                                    <input class="form-control" name="address" value="<?php echo htmlspecialchars($student['street']) ?>">
                                    <label>Postal code</label>
                                    <input class="form-control" name="cp" value="<?php echo htmlspecialchars($student['cp']) ?>">
                                    <label>City</label>
                                    <input class="form-control" name="city" value="<?php echo htmlspecialchars($student['city']) ?>">
                                    <label>Phone</label>
                                    <input class="form-control" name="fixe" value="<?php echo htmlspecialchars($student['phone']) ?>">
                                    <label>Mobile phone</label>
                                    <input class="form-control" name="portable" value="<?php echo htmlspecialchars($student['mobile']) ?>">
                                    <label>TP number</label>
                                    <input class="form-control" name="tp_number" value="<?php echo htmlspecialchars($student['tp_number']) ?>">
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
