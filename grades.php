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
                            List of grades (click on a grade to change it)
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="jsTable">
                                    <thead>
                                    <tr>
                                        <th>Module</th>
                                        <th>Last name</th>
                                        <th>First name</th>
                                        <th>Examination</th>
                                        <th>Grade</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if (isset($_SESSION['id_prof'])) {
                                        $query2 = $db->prepare("SELECT grade, grade.id AS gradeid, module.id_module, id_grade_module, id_student4, surname, name, grade_type, coefficient, name_module FROM student, coefficient, intake_student, module, grade, teaches_module WHERE intake_student.id_student4=student.student_id AND module.id_group2=intake_student.id_groupe AND grade.student_id=student.student_id AND grade.grade_id=coefficient.id_grade_module AND coefficient.id_module3=module.id_module AND teaches_module.id_module=module.id_module AND id_professor=:id_prof");
                                        $query2->bindParam(':id_prof', $_SESSION['id_prof']);
                                    } else {
                                        $query2 = $db->prepare("SELECT grade, grade.id AS gradeid, id_module, id_grade_module, id_student4, surname, name, grade_type, coefficient, name_module FROM student, coefficient, intake_student, module, grade WHERE intake_student.id_student4=student.student_id AND module.id_group2=intake_student.id_groupe AND grade.student_id=student.student_id AND grade.grade_id=coefficient.id_grade_module AND coefficient.id_module3=module.id_module");
                                    }
                                    $query2->execute();
                                    while ($grade = $query2->fetch())
                                    {
                                        ?>
                                        <tr>
                                            <td><a href="edit-module.php?id=<?php echo htmlspecialchars($grade['id_module']) ?>"><?php echo htmlspecialchars($grade['name_module']) ?></a></td>
                                            <td><?php echo htmlspecialchars($grade['name']) ?></td>
                                            <td><?php echo htmlspecialchars($grade['surname']) ?></td>
                                            <td><?php echo htmlspecialchars($grade['grade_type'] . ' : ' . $grade['coefficient']) ?></td>
                                            <td><a href="edit-grade.php?cours_id=<?php echo htmlspecialchars($grade['id_module']) ?>&grade_id=<?php echo htmlspecialchars($grade['id_grade_module']) ?>&grade=<?php echo htmlspecialchars($grade['gradeid']) ?>"><?php echo htmlspecialchars($grade['grade']) ?></a></td>
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
