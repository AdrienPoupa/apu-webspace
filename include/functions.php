<?php

include 'config.php';

/**
 * Connect to database
 * @return PDO object to interact with
 */
function connection() {
    global $db_config;

    $db = new PDO('mysql:host='.$db_config['host'].';dbname='.$db_config['name'].';charset=utf8', $db_config['user'], $db_config['password']);

    return $db;
}

/**
 * Redirect to a page
 * @param $page page to redirect
 */
function redirect($page) {
    header("Location: ".(string)$page.".php");
}

/**
 * Loop over field names, make sure each one exists and is not empty
 * @param $required
 * @return array
 */
function checkForm($required) {
    if (isset($_POST['form_sent'])) {
        foreach($required as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                $error[] = 'Please fill all the fields';
                return $error;
            }
        }
    }
}

/**
 * Get the new grade query, as we need two different pointers
 * @param $module_id
 * @param $grade_id
 * @return PDOStatement
 */
function getNewGradeQuery($module_id, $grade_id) {
    global $db;

    $query = $db->prepare("SELECT id_grade_module, grade_type, coefficient, student.name, surname, student_id FROM student, module, intake_student, intake, coefficient WHERE student.student_id=intake_student.id_student4 AND intake_student.id_groupe=intake.id AND intake_student.id_student4=student.student_id AND intake_student.id_groupe=module.id_group2 AND coefficient.id_module3=module.id_module AND module.id_module=:id AND id_grade_module=:id_grade_module");
    $query->bindParam(':id', $module_id);
    $query->bindParam(':id_grade_module', $grade_id);
    $query->execute();

    return $query;
}

/**
 * Check if the current user is logged in
 */
function checkUser() {
    if (!isset($_SESSION['user'])) {
        redirect('login');
    }
}

/**
 * Check if the current user is an admin
 */
function checkAdmin() {
    if ($_SESSION['role'] != 'admin') {
        redirect('login');
    }
}

/**
 * Check if the current user is a student or an admin
 */
function checkStudent() {
    if ($_SESSION['role'] != 'student' && $_SESSION['role'] != 'admin') {
        redirect('login');
    }
}

/**
 * Check if the current user is a professor or an admin
 */
function checkProfessor() {
    if ($_SESSION['role'] != 'prof' && $_SESSION['role'] != 'admin') {
        redirect('login');
    }
}

/**
 * List professors for module creation/edition
 * @return string
 */
function getProfessorList() {
    global $db;

    $menu = '';

    $query = $db->prepare("SELECT id_professor, name_professor, surname_professor FROM professor");
    $query->execute();
    while ( $row = $query->fetch())
        $menu .= '<option value="'.htmlspecialchars($row['id_professor']).'">'.htmlspecialchars($row['surname_professor'].' '.$row['name_professor']).'</option>';

    return $menu;
}

/**
 * List intakes
 */
function intakeList() {
    global $db;

    $query = $db->prepare("SELECT id, name FROM intake");
    $query->execute();
    while ( $row = $query->fetch())
        echo '<option value="'.htmlspecialchars($row['id']).'">'.htmlspecialchars($row['name']).'</option>';
}

/**
 * Display error messages
 */
function errorMessages() {
    global $error;
    if (!empty($error)) {
        echo '<p class="text-danger">Your modifications have not been saved because of the following errors:</p>';
        foreach ($error as $e) {
            echo '<p class="text-danger">'.$e.'</p>';
        }
    }
}

/**
 * Display a success message
 */
function successMessage() {
    global $success;

    if ($success) {
        echo '<p class="text-success">Your modifications have been saved.</p>';
    }
}

/**
 * Generate pass fail message for the report card
 * @param $average
 */
function generatePassFailMessage($average) {
    if ($average >= 70) {
        echo '<p style="color: green">Pass: First Class Honours (A)</p>';
    }
    elseif ($average < 70 && $average >= 60) {
        echo '<p style="color: green">Pass: Second Class Honours Upper Division (B+)</p>';
    }
    elseif ($average < 60 && $average >= 50) {
        echo '<p style="color: orange">Pass: Second Class Honours Lower Division (B)</p>';
    }
    elseif ($average < 50 && $average >= 40) {
        echo '<p style="color: #62cd21">Pass (C)</p>';
    }
    elseif ($average < 40 && $average >= 30) {
        echo '<p style="color: red">Marginal Fail (D)</p>';
    }
    else {
        echo '<p style="color: red">Fail (F)</p>';
    }
}

// Connect to DB
$db = connection();

// Start a session, needed for authentication
session_start();
