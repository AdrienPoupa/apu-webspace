<?php
include 'include/functions.php';

checkAdmin();

if (!isset($_GET['id'])) {
    redirect('professors');
}

$id = $_GET['id'];

// Required field names
$required = array('name', 'surname');
$error = checkForm($required);

if (isset($_GET['delete']) && isset($id)) {
    try {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->beginTransaction();
        $query = $db->prepare("DELETE FROM professor WHERE id_professor=:id");
        $query->bindParam(':id', $id);
        $query->execute();
        $db->commit();

        redirect('professors');
    } catch (Exception $e) {
        $db->rollBack();
        $error[] = $e->getMessage();
    }
}

if (isset($_POST['form_sent']) && empty($error)) {
    try {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->beginTransaction();

        $query = $db->prepare('UPDATE professor SET name_professor=:name, surname_professor=:surname WHERE id_professor=:id');
        $query->bindParam(':name', $_POST['name']);
        $query->bindParam(':surname', $_POST['surname']);
        $query->bindParam(':id', $id);
        $query->execute();

        $db->commit();

        redirect('professors');
    } catch (Exception $e) {
        $db->rollBack();
        $error[] = $e->getMessage();
    }

}

$query = $db->prepare("SELECT * FROM professor WHERE id_professor=:id");
$query->bindParam(':id', $id);
$query->execute();
$professeur = $query->fetch();

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
                        <div class="panel-heading"> Edit a professor - <a href="edit-professor.php?id=<?php echo htmlspecialchars($id) ?>&delete">Delete this professor</a> </div>
                        <div class="panel-body">
                            <?php errorMessages(); ?>
                            <div class="form-group">
                                <form method="post">
                                    <label>Last name</label>
                                    <input class="form-control" name="name" value="<?php echo htmlspecialchars($professeur['name_professor']) ?>">
                                    <label>First name</label>
                                    <input class="form-control" name="surname" value="<?php echo htmlspecialchars($professeur['surname_professor']) ?>">
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
