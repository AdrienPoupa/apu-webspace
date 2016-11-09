<?php
include 'include/functions.php';

checkAdmin();

if (!isset($_GET['id'])) {
    redirect('modules');
}

$id = $_GET['id'];

// Required field names
$required = array('name');
$error = checkForm($required);

if (isset($_GET['delete']) && isset($id)) {
    try {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->beginTransaction();

        $query = $db->prepare('DELETE FROM teaches_module WHERE id_module=:module');
        $query->bindParam(':module', $module_id);
        $query->execute();

        $query = $db->prepare('DELETE FROM coefficient WHERE id_module3=:module');
        $query->bindParam(':module', $id);
        $query->execute();

        $query = $db->prepare('DELETE FROM module WHERE id_module=:module');
        $query->bindParam(':module', $id);
        $query->execute();

        $db->commit();

        redirect('modules');
    } catch (Exception $e) {
        $db->rollBack();
        $error[] = $e->getMessage();
    }
}

if (isset($_POST['form_sent']) && empty($error)) {
    try {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->beginTransaction();
        $query = $db->prepare('UPDATE module SET name_module=:name, id_group2=:id_groupe WHERE id_module=:id');
        $query->bindParam(':name', $_POST['name']);
        $query->bindParam(':id_groupe', $_POST['groupe_id']);
        $query->bindParam(':id', $id);
        $query->execute();

        $total = 0;
        for ($i = 1; $i < 6; $i++) {
            if ($_POST['coeff'.$i] != '') {
                $total += $_POST['coeff'.$i];
            }
        }

        if ($total != 1) {
            throw new Exception('Sum of coefficients is not equal to 1');
        }

        $query = $db->prepare('DELETE FROM teaches_module WHERE id_module=:module');
        $query->bindParam(':module', $id);
        $query->execute();

        $delete = true;

        for ($i = 1; $i < 6; $i++) {
            if (isset($_POST['id_grade'.$i]) && $_POST['id_grade'.$i] != '' && isset($_POST['coeff'.$i]) && $_POST['coeff'.$i] != '' && isset($_POST['grade'.$i]) && $_POST['grade'.$i] != '') {
                $query = $db->prepare('UPDATE coefficient SET grade_type=:grade_type, coefficient=:coeff WHERE id_grade_module=:id_note');
                $query->bindParam(':grade_type', $_POST['grade'.$i]);
                $query->bindParam(':id_note', $_POST['id_grade'.$i]);
                $query->bindParam(':coeff', $_POST['coeff'.$i]);
                $query->execute();
            }
            elseif (isset($_POST['coeff'.$i]) && $_POST['coeff'.$i] != '' && isset($_POST['grade'.$i]) && $_POST['grade'.$i] != '') {
                $query = $db->prepare('INSERT INTO coefficient (grade_type, id_module3, coefficient) VALUES (:grade_type, :module, :coeff)');
                $query->bindParam(':grade_type', $_POST['grade'.$i]);
                $query->bindParam(':module', $id);
                $query->bindParam(':coeff', $_POST['coeff'.$i]);
                $query->execute();
            }
        }

        if (!$delete) {
            $query = $db->prepare('DELETE FROM coefficient WHERE id_module3=:module');
            $query->bindParam(':module', $id);
            $query->execute();
        }

        for ($i = 1; $i < 6; $i++) {
            if ($_POST['professor'.$i] != '') {
                $query = $db->prepare('INSERT INTO teaches_module (id_professor, id_module) VALUES (:prof, :module)');
                $query->bindParam(':prof', $_POST['professor'.$i]);
                $query->bindParam(':module', $id);
                $query->execute();
            }
        }

        $db->commit();

        redirect('modules');
    } catch (Exception $e) {
        $db->rollBack();
        $error[] = $e->getMessage();
    }
}

$menu = getProfessorList();

$query = $db->prepare("SELECT id_grade_module, coefficient FROM coefficient WHERE id_module3=:id");
$query->bindParam(':id', $id);
$query->execute();
$ids = $query->fetchAll();

$query = $db->prepare("SELECT * FROM module WHERE id_module=:id");
$query->bindParam(':id', $id);
$query->execute();
$cours = $query->fetch();

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
                        <div class="panel-heading"> Edit a module - <a href="edit-module.php?id=<?php echo htmlspecialchars($id) ?>&delete">Delete this module</a> </div>
                        <div class="panel-body">
                            <?php errorMessages(); ?>
                            <div class="form-group">
                                <form method="post">
                                    <label>Name</label>
                                    <input class="form-control" name="name" value="<?php echo htmlspecialchars($cours['name_module']); ?>">
                                    <label>Group</label>
                                    <select class="form-control" name="groupe_id">
                                        <option value="">None</option>
                                        <?php
                                        intakeList();
                                        ?>
                                    </select>
                                    <label>Professor 1</label>
                                    <select class="form-control" name="professeur1">
                                        <option value="">None</option>
                                        <?php echo $menu; ?>
                                    </select>
                                    <label>Professor 2</label>
                                    <select class="form-control" name="professeur2">
                                        <option value="">None</option>
                                        <?php echo $menu; ?>
                                    </select>
                                    <label>Professor 3</label>
                                    <select class="form-control" name="professeur3">
                                        <option value="">None</option>
                                        <?php echo $menu; ?>
                                    </select>
                                    <label>Professor 4</label>
                                    <select class="form-control" name="professeur4">
                                        <option value="">None</option>
                                        <?php echo $menu; ?>
                                    </select>
                                    <label>Professor 5</label>
                                    <select class="form-control" name="professeur5">
                                        <option value="">None</option>
                                        <?php echo $menu; ?>
                                    </select>
                                    <label>Grade 1</label>
                                    <select class="form-control" name="grade1">
                                        <option value="">None</option>
                                        <option value="LEC">LEC</option>
                                        <option value="LAB">LAB</option>
                                        <option value="PRJ">PRJ</option>
                                    </select>
                                    <input type="hidden" value="<?php if (isset($ids[0][0])) echo htmlspecialchars($ids[0][0]) ?>" name="id_grade1">
                                    <label>Coefficient grade 1</label>
                                    <input class="form-control" name="coeff1" value="<?php if (isset($ids[0][1])) echo htmlspecialchars($ids[0][1]) ?>">
                                    <label>Grade 2</label>
                                    <select class="form-control" name="grade2">
                                        <option value="">None</option>
                                        <option value="LEC">LEC</option>
                                        <option value="LAB">LAB</option>
                                        <option value="PRJ">PRJ</option>
                                    </select>
                                    <input type="hidden" value="<?php if (isset($ids[1][0])) echo htmlspecialchars($ids[1][0]) ?>" name="id_grade2">
                                    <label>Coefficient grade 2</label>
                                    <input class="form-control" name="coeff2" value="<?php if (isset($ids[1][1])) echo htmlspecialchars($ids[1][1]) ?>">
                                    <label>Grade 3</label>
                                    <select class="form-control" name="grade3">
                                        <option value="">None</option>
                                        <option value="LEC">LEC</option>
                                        <option value="LAB">LAB</option>
                                        <option value="PRJ">PRJ</option>
                                    </select>
                                    <input type="hidden" value="<?php if (isset($ids[2][0])) echo htmlspecialchars($ids[2][0]) ?>" name="id_grade3">
                                    <label>Coefficient grade 3</label>
                                    <input class="form-control" name="coeff3" value="<?php if (isset($ids[2][1])) echo htmlspecialchars($ids[2][1]) ?>">
                                    <label>Grade 4</label>
                                    <select class="form-control" name="grade4">
                                        <option value="">None</option>
                                        <option value="DE">DE</option>
                                        <option value="TP">TP</option>
                                        <option value="PRJ">PRJ</option>
                                    </select>
                                    <input type="hidden" value="<?php if (isset($ids[3][0])) echo htmlspecialchars($ids[3][0]) ?>" name="id_grade4">
                                    <label>Coefficient grade 4</label>
                                    <input class="form-control" name="coeff4" value="<?php if (isset($ids[3][1])) echo htmlspecialchars($ids[3][1]) ?>">
                                    <label>Grade 5</label>
                                    <select class="form-control" name="grade5">
                                        <option value="">None</option>
                                        <option value="LEC">LEC</option>
                                        <option value="LAB">LAB</option>
                                        <option value="PRJ">PRJ</option>
                                    </select>
                                    <input type="hidden" value="<?php if (isset($ids[4][0])) echo htmlspecialchars($ids[4][0]) ?>" name="id_grade5">
                                    <label>Coefficient grade 5</label>
                                    <input class="form-control" name="coeff5" value="<?php if (isset($ids[4][1])) echo htmlspecialchars($ids[4][1]) ?>">
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
