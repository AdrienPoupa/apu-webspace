<?php
include 'include/functions.php';

checkProfessor();

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
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            List of modules, their professors and their groups
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="jsTable">
                                    <thead>
                                    <tr>
                                        <th>Module</th>
                                        <th>Intake</th>
                                        <th>Professor(s)</th>
                                        <th>Coefficients</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if ($_SESSION['role'] == 'prof') {
                                        $result = $db->prepare("SELECT module.id_module, name_module, id_group2 FROM module, professor, teaches_module WHERE module.id_module=teaches_module.id_module AND teaches_module.id_professor=professor.id_professor AND professor.id_professor=:id");
                                        $result->bindParam(':id', $_SESSION['id_prof']);
                                    } else {
                                        $result = $db->query("SELECT id_module, name_module, id_group2 FROM module");
                                    }
                                    $result->execute();
                                    while ($line = $result->fetch())
                                    {
                                        $query = $db->prepare("SELECT name FROM intake WHERE id=:id");
                                        $query->bindParam(':id', $line['id_group2']);
                                        $query->execute();
                                        if ($query->rowCount() > 0) {
                                            while ($nom = $query->fetch())
                                            {
                                                $query2 = $db->prepare("SELECT id_professor FROM teaches_module WHERE id_module=:id");
                                                $query2->bindParam(':id', $line['id_module']);
                                                $query2->execute();

                                                $query4 = $db->prepare("SELECT id_grade_module, grade_type, coefficient FROM coefficient WHERE id_module3=:id");
                                                $query4->bindParam(':id', $line['id_module']);
                                                $query4->execute();

?>
                                                <tr class="odd">
                                                    <td><a href="edit-module.php?id=<?php echo htmlspecialchars($line['id_module']) ?>"><?php echo htmlspecialchars($line['name_module']) ?></a></td>
                                                <td><?php echo htmlspecialchars($nom['name']) ?></td>
                                                <td>
<?php
                                                while ($id_prof = $query2->fetch())
                                                {
                                                    $query3 = $db->prepare("SELECT id_professor, name_professor, surname_professor FROM professor WHERE id_professor=:id");
                                                    $query3->bindParam(':id', $id_prof['id_professor']);
                                                    $query3->execute();
                                                    $row = $query3->fetch();
                                                    echo htmlspecialchars($row['surname_professor'].' '.$row['name_professor']).'<br />';
                                                }
                                                ?> </td> <td>
                                                <?php
                                                while ($coeff = $query4->fetch())
                                                {
                                                    echo '<a href="new-grade.php?cours_id='.htmlspecialchars($line['id_module']).'&grade_id='.htmlspecialchars($coeff['id_grade_module']).'">'.htmlspecialchars($coeff['grade_type'].' : '.$coeff['coefficient']).'</a>';
                                                    echo '<br />';
                                                }
                                                ?> </td> <?php
                                            }
                                        }
                                        else {
                                            ?>
                                                <td>No group</td>
                                                <td>No group</td>
                                                <td>No group</td>
                                            <?php
                                        }
                                            ?>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /#page-wrapper -->

<?php
include 'include/foot.php';
