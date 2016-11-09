<?php
include 'include/functions.php';

checkAdmin();

// Required field names
$required = array('name', 'surname', 'birthdate', 'gender', 'id_resp', 'etablissement', 'registered', 'address', 'cp', 'city', 'fixe', 'portable', 'tp_number', 'password');
$error = checkForm($required);

if (isset($_POST['form_sent']) && empty($error)) {
    try {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->beginTransaction();
        $query = $db->prepare('INSERT INTO student (name, surname, birthdate, gender, registered, previous_school, street, cp, city, phone, mobile, tp_number, password) VALUES (:name, :surname, :birthdate, :gender, :registered, :previous_school, :street, :cp, :city, :phone, :mobile, :tp_number, :password)');
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
        $query->bindParam(':password', password_hash($_POST['password'], PASSWORD_BCRYPT));
        $query->execute();

        if ($_POST['id_resp'] != '') {
            $query = $db->prepare('UPDATE responsible SET student_id=:id WHERE id=:id_resp');
            $query->bindParam(':id', $db->lastInsertId());
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
                        <div class="panel-heading"> Add a student </div>
                        <div class="panel-body">
                            <?php errorMessages(); ?>
                            <div class="form-group">
                                <form method="post">
                                    <label>Last name</label>
                                    <input class="form-control" name="name">
                                    <label>First name</label>
                                    <input class="form-control" name="surname">
                                    <label>Birthdate</label>
                                    <input class="form-control" name="birthdate" placeholder="AAAA-MM-JJ">
                                    <label>Gender</label>
                                    <input class="form-control" name="gender" placeholder="M/F">
                                    <label>Registration date</label>
                                    <input class="form-control" name="registered" placeholder="AAAA-MM-JJ">
                                    <label>Previous school</label>
                                    <input class="form-control" name="etablissement">
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
                                    <input class="form-control" name="address">
                                    <label>Postal code</label>
                                    <input class="form-control" name="cp">
                                    <label>City</label>
                                    <input class="form-control" name="city">
                                    <label>Phone</label>
                                    <input class="form-control" name="fixe">
                                    <label>Mobile phone</label>
                                    <input class="form-control" name="portable">
                                    <label>TP number</label>
                                    <input class="form-control" name="tp_number" placeholder="TPXXXXXX">
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
