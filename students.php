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
                            Student list (click on a name to edit the student)
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="jsTable">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Birthdate</th>
                                        <th>Reg</th>
                                        <th>Previous</th>
                                        <th>Address</th>
                                        <th>Phones</th>
                                        <th>Res</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $result = $db->query("SELECT * FROM student, responsible WHERE student.student_id=responsible.student_id");
                                    while ( $line = $result->fetch())
                                    {
                                        $responsible = $line['surname_responsible'].' '.$line['name_responsible'];
                                        ?>
                                        <tr class="odd">
                                            <td><a href="edit-student.php?id=<?php echo htmlspecialchars($line['student_id']) ?>"><?php echo htmlspecialchars($line['name'].' '.$line['surname']) ?></a><br /><?php echo htmlspecialchars($line['tp_number']) ?><br /><a href="report-card.php?id=<?php echo htmlspecialchars($line['student_id']) ?>">Report card</a></td>
                                            <td><?php echo htmlspecialchars($line['birthdate']) ?></td>
                                            <td><?php echo htmlspecialchars($line['registered']) ?></td>
                                            <td><?php echo htmlspecialchars($line['previous_school']) ?></td>
                                            <td><?php echo htmlspecialchars($line['street']). ' '.htmlspecialchars($line['cp']). ' '.htmlspecialchars($line['city']) ?></td>
                                            <td><?php echo htmlspecialchars($line['mobile']).'<br />'.htmlspecialchars($line['phone']) ?></td>
                                            <td><?php echo htmlspecialchars($responsible) ?></td>
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
