<?php
include 'include/functions.php';

checkProfessor();

if (!isset($_GET['cours_id']) || !isset($_GET['grade_id'])) {
    redirect('grades');
}

$module_id = $_GET['cours_id'];
$grade_id = $_GET['grade_id'];

$query = $db->prepare("SELECT name, name_module FROM module, intake WHERE id_module=:id AND intake.id=module.id_group2");
$query->bindParam(':id', $module_id);
$query->execute();
$nom = $query->fetch();

if (!$nom) {
    redirect('grades');
}

// Required field names
$required = array('student', 'grade');
$error = checkForm($required);

$newGradeQuery1 = getNewGradeQuery($module_id, $grade_id);

if ($newGradeQuery1->rowCount() == 0) {
    $error[] = 'You can not add grades to a class without students';
}

$grade = $newGradeQuery1->fetch();

$newGradeQuery2 = getNewGradeQuery($module_id, $grade_id);

if (!empty($_POST['grade']) && ($_POST['grade'] < 0 || $_POST['grade'] > 100)) {
    $error[] = 'Grade invalid';
}

if (isset($_POST['form_sent']) && empty($error)) {
    try {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->beginTransaction();
        $query = $db->prepare('INSERT INTO grade (student_id, grade_id, grade) VALUES (:student, :grade_id, :grade)');
        $query->bindParam(':student', $_POST['student']);
        $query->bindParam(':grade_id', $grade_id);
        $query->bindParam(':grade', $_POST['grade']);
        $query->execute();

        $db->commit();

        redirect('grades');
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
                        <div class="panel-heading"> Add a grade </div>
                        <div class="panel-body">
                            <?php errorMessages(); ?>
                            <div class="form-group">
                                <form method="post">
                                    <label>Module</label>
                                    <input id="disabledInput" class="form-control" type="text" disabled="" placeholder="<?php echo htmlspecialchars($nom['name'].' > '.$nom['name_module']); ?>">
                                    <label>Grade</label>
                                    <input id="disabledInput" class="form-control" type="text" disabled="" placeholder="<?php echo htmlspecialchars($grade['grade_type'].' : '.$grade['coefficient']); ?>">
                                    <label>Student</label>
                                    <select class="form-control" name="student">
                                        <option value="">None</option>
                                        <?php
                                        while ( $row = $newGradeQuery2->fetch())
                                            echo '<option value="'.htmlspecialchars($row['student_id']).'">'.htmlspecialchars($row['surname'].' '.$row['name']).'</option>';
                                        ?>
                                    </select>
                                    <label>Grade</label>
                                    <input class="form-control" name="grade">
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
