<?php
include 'include/functions.php';

checkUser();

if (!isset($_GET['id']) && $_SESSION['student_id'] == '') {
    redirect('index');
}

// Use built-in ID for students, GET ID otherwise
if ($_SESSION['role'] != 'student') {
    $id = $_GET['id'];
}
else {
    $id = $_SESSION['student_id'];
}

include 'include/head.php';
include 'include/navigation.php';

$student_query = $db->prepare("SELECT name, surname FROM student WHERE student_id=:id");
$student_query->bindParam(':id', $id);
$student_query->execute();
$student = $student_query->fetch();

if (!$student) {
    redirect('index');
}
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
                            <?php echo htmlspecialchars($student['surname'].' '.$student['name']) ?>'s report card
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="jsTable">
                                    <thead>
                                    <tr>
                                        <th>Module</th>
                                        <th>Professor(s)</th>
                                        <th>Coefficients</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $result = $db->prepare("SELECT id_module, name_module, id_group2 FROM student, module, intake_student, intake WHERE student.student_id=intake_student.id_student4 AND intake_student.id_groupe=intake.id AND intake_student.id_student4=student.student_id AND intake_student.id_groupe=module.id_group2 AND student_id=:id");
                                    $result->bindParam(':id', $id);
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
                                                ?>
                                                <tr class="odd">
                                                <?php if ($_SESSION['role'] == 'admin') : ?>
                                                <td><a href="edit-module.php?id=<?php echo htmlspecialchars($line['id_module']) ?>"><?php echo htmlspecialchars($line['name_module']) ?></a></td>
                                                <?php else: ?>
                                                <td><?php echo htmlspecialchars($line['name_module']) ?></td>
                                                <?php endif; ?>
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
                                                $average = $count = 0;
                                                $query4 = $db->prepare("SELECT grade, id_grade_module, grade_type, coefficient FROM coefficient, grade WHERE grade.grade_id=coefficient.id_grade_module AND id_module3=:id AND student_id=:student_id");
                                                $query4->bindParam(':id', $line['id_module']);
                                                $query4->bindParam(':student_id', $id);
                                                $query4->execute();
                                                while ($coeff = $query4->fetch())
                                                {
                                                    $count++;
                                                    $average += $coeff['grade'];
                                                    echo htmlspecialchars($coeff['grade_type'].' ('.$coeff['coefficient']).') : '.htmlspecialchars($coeff['grade']);
                                                    echo '<br />';
                                                }
                                                $query5 = $db->prepare("SELECT id_grade_module, grade_type, coefficient FROM coefficient WHERE id_module3=:id AND id_grade_module NOT IN (SELECT id_grade_module FROM coefficient, grade WHERE grade.grade_id=coefficient.id_grade_module AND id_module3=:id AND student_id=:student_id)");
                                                $query5->bindParam(':id', $line['id_module']);
                                                $query5->bindParam(':student_id', $id);
                                                $query5->execute();
                                                while ($coeff = $query5->fetch())
                                                {
                                                    $count++;
                                                    $average += 0;
                                                    echo htmlspecialchars($coeff['grade_type'].' ('.$coeff['coefficient']).') : 0 (no grade in database)';
                                                    echo '<br />';
                                                }
                                                if ($count != 0) {
                                                    $average /= $count;
                                                    echo 'Module average : '.round($average, 2);
                                                    generatePassFailMessage($average);
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
