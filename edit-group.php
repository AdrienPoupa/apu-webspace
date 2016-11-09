<?php
include 'include/functions.php';

checkAdmin();

if (!isset($_GET['id'])) {
    redirect('groups');
}

$id = $_GET['id'];

// Required field names
$required = array('name');
$error = checkForm($required);

if (isset($_GET['delete']) && isset($id)) {
    try {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->beginTransaction();
        $query = $db->prepare("DELETE FROM intake WHERE id=:id");
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

        $query = $db->prepare('UPDATE intake SET name=:name WHERE id=:id');
        $query->bindParam(':name', $_POST['name']);
        $query->bindParam(':id', $id);
        $query->execute();

        if ($_POST['id_student_add'] != '') {
            $query = $db->prepare('INSERT INTO intake_student (id_student4, id_groupe) VALUES (:student_id, :id_groupe)');
            $query->bindParam(':student_id', $_POST['id_student_add']);
            $query->bindParam(':id_groupe', $id);
            $query->execute();
        }

        if ($_POST['id_student_delete'] != '') {
            $query = $db->prepare('DELETE FROM intake_student WHERE id_student4=:student_id AND id_groupe=:id_groupe');
            $query->bindParam(':student_id', $_POST['id_student_delete']);
            $query->bindParam(':id_groupe', $id);
            $query->execute();
        }

        $db->commit();

        redirect('groups');
    } catch (Exception $e) {
        $db->rollBack();
        $error[] = $e->getMessage();
    }

}

$query = $db->prepare("SELECT * FROM intake WHERE id=:id");
$query->bindParam(':id', $id);
$query->execute();
$intake = $query->fetch();

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
                        <div class="panel-heading"> Edit an intake - <a href="edit-group.php?id=<?php echo htmlspecialchars($id) ?>&delete">Delete this intake</a> </div>
                        <div class="panel-body">
                            <?php errorMessages(); ?>
                            <div class="form-group">
                                <form method="post">
                                    <label>Name</label>
                                    <input class="form-control" name="name" value="<?php echo htmlspecialchars($intake['name']) ?>">
                                    <label>Student to add</label>
                                    <select class="form-control" name="id_student_add">
                                        <option value="">None</option>
                                        <?php
                                        $query = $db->prepare("SELECT name, surname, student_id FROM student");
                                        $query->execute();
                                        while ( $row = $query->fetch())
                                            echo '<option value="'.htmlspecialchars($row['student_id']).'">'.htmlspecialchars($row['surname'].' '.$row['name']).'</option>';
                                        ?>
                                    </select>
                                    <label>Student to delete</label>
                                    <select class="form-control" name="id_student_delete">
                                        <option value="">None</option>
                                        <?php
                                        $query = $db->prepare("SELECT name, surname, student_id FROM student");
                                        $query->execute();
                                        while ( $row = $query->fetch())
                                            echo '<option value="'.htmlspecialchars($row['student_id']).'">'.htmlspecialchars($row['surname'].' '.$row['name']).'</option>';
                                        ?>
                                    </select>
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
