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
                            Professor list (click on a name to edit it)
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="jsTable">
                                    <thead>
                                    <tr>
                                        <th>Last name</th>
                                        <th>First name</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $result = $db->query("SELECT * FROM professor");
                                    while ( $line = $result->fetch())
                                    {
                                        ?>
                                        <tr class="odd">
                                            <td><a href="edit-professor.php?id=<?php echo htmlspecialchars($line['id_professor']) ?>"><?php echo htmlspecialchars($line['name_professor']) ?></a></td>
                                            <td><?php echo htmlspecialchars($line['surname_professor']) ?></td>
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
