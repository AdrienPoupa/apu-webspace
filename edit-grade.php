<?php
include 'include/functions.php';

checkProfessor();

if (!isset($_GET['cours_id']) || !isset($_GET['grade_id']) || !isset($_GET['grade'])) {
    redirect('grades');
}

// Required field names
$required = array('student', 'grade');
$error = checkForm($required);

$module_id = $_GET['cours_id'];
$grade_id = $_GET['grade_id'];
$grade_get = $_GET['grade'];

$query = $db->prepare("SELECT name, name_module FROM module, intake WHERE id_module=:id AND intake.id=module.id_group2");
$query->bindParam(':id', $module_id);
$query->execute();
$nom = $query->fetch();

if (!$nom) {
    redirect('grades');
}

$query2 = $db->prepare("SELECT grade, id_grade_module, grade_type, coefficient, student.name, surname, student.student_id FROM student, module, intake_student, intake, coefficient, grade WHERE student.student_id=intake_student.id_student4 AND intake_student.id_groupe=intake.id AND intake_student.id_student4=student.student_id AND intake_student.id_groupe=module.id_group2 AND coefficient.id_module3=module.id_module AND grade.grade_id=coefficient.id_grade_module AND grade.student_id=student.student_id AND module.id_module=:id AND id_grade_module=:id_grade_module AND grade.id=:grade");
$query2->bindParam(':id', $module_id);
$query2->bindParam(':id_grade_module', $grade_id);
$query2->bindParam(':grade', $grade_get);
$query2->execute();

if ($query2->rowCount() == 0) {
    $error[] = 'You can not add grades to a module without student';
}

$grade = $query2->fetch();

if (isset($_GET['delete']) && isset($grade_get)) {
    try {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->beginTransaction();
        $query = $db->prepare("DELETE FROM grade WHERE id=:id");
        $query->bindParam(':id', $grade_get);
        $query->execute();
        $db->commit();

        redirect('grades');
    } catch (Exception $e) {
        $db->rollBack();
        $error[] = $e->getMessage();
    }
}

if (!empty($_POST['grade']) && ($_POST['grade'] < 0 || $_POST['grade'] > 100)) {
    $error[] = 'Grade invalid';
}

if (isset($_POST['form_sent']) && empty($error)) {
    try {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->beginTransaction();
        $query = $db->prepare('UPDATE grade SET grade=:grade WHERE id=:grade_id');
        $query->bindParam(':grade', $_POST['grade']);
        $query->bindParam(':grade_id', $grade_get);
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
                        <div class="panel-heading"> Add a grade - <a href="edit-grade.php?cours_id=<?php echo htmlspecialchars($module_id)?>&grade_id=<?php echo htmlspecialchars($grade_id)?>&grade=<?php echo htmlspecialchars($grade_get)?>&delete">Delete this grade</a> </div>
                        <div class="panel-body">
                            <?php errorMessages(); ?>
                            <div class="form-group">
                                <form method="post">
                                    <label>Module</label>
                                    <input id="disabledInput" class="form-control" type="text" disabled="" placeholder="<?php echo htmlspecialchars($nom['name'].' > '.$nom['name_module']); ?>">
                                    <label>Grade type and coefficient</label>
                                    <input id="disabledInput" class="form-control" type="text" disabled="" placeholder="<?php echo htmlspecialchars($grade['grade_type'].' : '.$grade['coefficient']); ?>">
                                    <label>Student</label>
                                    <input id="disabledInput" class="form-control" type="text" disabled="" placeholder="<?php echo htmlspecialchars($grade['surname'].' '.$grade['name']); ?>">
                                    <label>Grade</label>
                                    <input class="form-control" name="grade" value="<?php echo htmlspecialchars($grade['grade']) ?>">
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
