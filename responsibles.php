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
                            Responsible list (click on a name to edit it)
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="jsTable">
                                    <thead>
                                    <tr>
                                        <th>Last name</th>
                                        <th>First name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Address</th>
                                        <th>Student</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $result = $db->query("SELECT * FROM responsible, student WHERE responsible.student_id=student.student_id");
                                    while ( $line = $result->fetch())
                                    {
                                        $student = $line['surname'].' '.$line['name'];
                                        ?>
                                        <tr class="odd">
                                            <td><a href="edit-responsible.php?id=<?php echo htmlspecialchars($line['id']) ?>"><?php echo htmlspecialchars($line['name_responsible']) ?></a></td>
                                            <td><?php echo htmlspecialchars($line['surname_responsible']) ?></td>
                                            <td><?php echo htmlspecialchars($line['mobile_responsible']) ?></td>
                                            <td><?php echo htmlspecialchars($line['email_responsible']) ?></td>
                                            <td><?php echo htmlspecialchars($line['street_responsible']). ' '.htmlspecialchars($line['cp_responsible']). ' '.htmlspecialchars($line['city_responsible']) ?></td>
                                            <td><?php echo htmlspecialchars($student) ?></td>
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
