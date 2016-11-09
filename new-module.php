<?php
include 'include/functions.php';

checkAdmin();

// Required field names
$required = array('name');
$error = checkForm($required);

if (isset($_POST['form_sent']) && empty($error)) {
    try {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->beginTransaction();
        $query = $db->prepare('INSERT INTO module (name_module, id_group2) VALUES (:name, :intake)');
        $query->bindParam(':name', $_POST['name']);
        $query->bindParam(':intake', $_POST['intake']);
        $query->execute();

        $module_id = $db->lastInsertId();

        $total = 0;
        for ($i = 1; $i < 6; $i++) {
            if ($_POST['coeff'.$i] != '') {
                $total += $_POST['coeff'.$i];
            }
        }

        if ($total != 1) {
            $error[] = 'Sum of coefficients is not equal to 1';
        }
        else {
            for ($i = 1; $i < 6; $i++) {
                if ($_POST['professor'.$i] != '') {
                    $query = $db->prepare('INSERT INTO teaches_module (id_professor, id_module) VALUES (:prof, :module)');
                    $query->bindParam(':prof', $_POST['professor'.$i]);
                    $query->bindParam(':module', $module_id);
                    $query->execute();
                }

                if ($_POST['grade'.$i] != '' && $_POST['coeff'.$i] != '') {
                    $query = $db->prepare('INSERT INTO coefficient (grade_type, id_module3, coefficient) VALUES (:grade_type, :module, :coeff)');
                    $query->bindParam(':grade_type', $_POST['grade'.$i]);
                    $query->bindParam(':module', $module_id);
                    $query->bindParam(':coeff', $_POST['coeff'.$i]);
                    $query->execute();
                }
            }

            $db->commit();
        }
        
        redirect('modules');
    } catch (Exception $e) {
        $db->rollBack();
        $error[] = $e->getMessage();
    }
}

$menu = getProfessorList();

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
                        <div class="panel-heading"> Add a module </div>
                        <div class="panel-body">
                            <?php errorMessages(); ?>
                            <div class="form-group">
                                <form method="post">
                                    <label>Name</label>
                                    <input class="form-control" name="name">
                                    <label>Intake</label>
                                    <select class="form-control" name="intake">
                                        <option value="">None</option>
                                        <?php intakeList(); ?>
                                    </select>
                                    <label>Professor 1</label>
                                    <select class="form-control" name="professor1">
                                        <option value="">None</option>
                                        <?php echo $menu; ?>
                                    </select>
                                    <label>Professor 2</label>
                                    <select class="form-control" name="professor2">
                                        <option value="">None</option>
                                        <?php echo $menu; ?>
                                    </select>
                                    <label>Professor 3</label>
                                    <select class="form-control" name="professor3">
                                        <option value="">None</option>
                                        <?php echo $menu; ?>
                                    </select>
                                    <label>Professor 4</label>
                                    <select class="form-control" name="professor4">
                                        <option value="">None</option>
                                        <?php echo $menu; ?>
                                    </select>
                                    <label>Professor 5</label>
                                    <select class="form-control" name="professor5">
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
                                    <label>Coefficient grade 1</label>
                                    <input class="form-control" name="coeff1">
                                    <label>Grade 2</label>
                                    <select class="form-control" name="grade2">
                                        <option value="">None</option>
                                        <option value="LEC">LEC</option>
                                        <option value="LAB">LAB</option>
                                        <option value="PRJ">PRJ</option>
                                    </select>
                                    <label>Coefficient grade 2</label>
                                    <input class="form-control" name="coeff2">
                                    <label>Grade 3</label>
                                    <select class="form-control" name="grade3">
                                        <option value="">None</option>
                                        <option value="LEC">LEC</option>
                                        <option value="LAB">LAB</option>
                                        <option value="PRJ">PRJ</option>
                                    </select>
                                    <label>Coefficient grade 3</label>
                                    <input class="form-control" name="coeff3">
                                    <label>Grade 4</label>
                                    <select class="form-control" name="grade4">
                                        <option value="">None</option>
                                        <option value="LEC">LEC</option>
                                        <option value="LAB">LAB</option>
                                        <option value="PRJ">PRJ</option>
                                    </select>
                                    <label>Coefficient grade 4</label>
                                    <input class="form-control" name="coeff4">
                                    <label>Grade 5</label>
                                    <select class="form-control" name="grade5">
                                        <option value="">None</option>
                                        <option value="LEC">LEC</option>
                                        <option value="LAB">LAB</option>
                                        <option value="PRJ">PRJ</option>
                                    </select>
                                    <label>Coefficient grade 5</label>
                                    <input class="form-control" name="coeff5">
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
