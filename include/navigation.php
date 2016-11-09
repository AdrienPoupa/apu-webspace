<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php"><img src="dist/img/logo.png"></a>
    </div>
    <!-- /.navbar-header -->

    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <li>
                    <a href="index.php"><i class="fa fa-home fa-fw"></i> Home</a>
                </li>
                <?php if ($_SESSION['role'] == 'student') : ?>
                    <li>
                        <a href="report-card.php"><i class="fa fa-bar-chart fa-fw"></i> Report card</a>
                    </li>
                    <li>
                        <a href="profile.php"><i class="fa fa-user fa-fw"></i> Profile</a>
                    </li>
                <?php endif; ?>
                <?php if ($_SESSION['role'] == 'student' || $_SESSION['role'] == 'prof') : ?>
                    <li>
                        <a href="change-password.php"><i class="fa fa-lock fa-fw"></i> Change password</a>
                    </li>
                <?php endif; ?>
                <?php if ($_SESSION['role'] == 'prof') : ?>
                    <li>
                        <a href="grades.php"><i class="fa fa-bar-chart fa-fw"></i> Grades</a>
                    </li>
                    <li>
                    <li>
                        <a href="students.php"><i class="fa fa-graduation-cap fa-fw"></i> Student list</a>
                    </li>
                    <li>
                        <a href="responsibles.php"><i class="fa fa-users fa-fw"></i> Responsible list</a>
                    </li>
                    <li>
                        <a href="groups.php"><i class="fa fa-tags fa-fw"></i> Intake and student list</a>
                    </li>
                    <li>
                        <a href="modules.php"><i class="fa fa-university fa-fw"></i> Module list</a>
                    </li>
                <?php endif; ?>
                <?php if ($_SESSION['role'] == 'admin') : ?>
                <li>
                    <a href="students.php"><i class="fa fa-graduation-cap fa-fw"></i> Students <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="students.php">Student list</a>
                        </li>
                        <li>
                            <a href="new-student.php">Add a student</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li>
                    <a href="responsibles.php"><i class="fa fa-users fa-fw"></i> Reponsibles <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="responsibles.php">Responsible list</a>
                        </li>
                        <li>
                            <a href="new-responsible.php">Add a responsible</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li>
                    <a href="professors.php"><i class="fa fa-users fa-fw"></i> Professors <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="professors.php">Professor list</a>
                        </li>
                        <li>
                            <a href="new-professor.php">Add a professor</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li>
                    <a href="groups.php"><i class="fa fa-tags fa-fw"></i> Intakes <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="groups.php">Intake and student list</a>
                        </li>
                        <li>
                            <a href="new-group.php">Add an intake</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li>
                    <a href="modules.php"><i class="fa fa-university fa-fw"></i> Modules<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="modules.php">Module list</a>
                        </li>
                        <li>
                            <a href="new-module.php">Add a module</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="grades.php"><i class="fa fa-bar-chart fa-fw"></i> Grades</a>
                </li>
                <?php endif; ?>
                <li>
                    <a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                </li>
            </ul>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->
</nav>