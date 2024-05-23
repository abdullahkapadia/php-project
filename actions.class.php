<?php
class Actions {
    private $conn;

    function __construct() {
        require_once(realpath(__DIR__.'/../db-connect.php'));
        $this->conn = $conn;
    }

    /**
     * Save or update class information.
     */
    public function save_class() {
        $id = $_POST['id'] ?? null;
        $name = addslashes(htmlspecialchars($_POST['name']));

        if ($id) {
            $check = $this->conn->query("SELECT id FROM `class_tbl` WHERE `name` = '$name' AND `id` != '$id'");
            $sql = "UPDATE `class_tbl` SET `name` = '$name' WHERE `id` = '$id'";
        } else {
            $check = $this->conn->query("SELECT id FROM `class_tbl` WHERE `name` = '$name'");
            $sql = "INSERT INTO `class_tbl` (`name`) VALUES ('$name')";
        }

        if ($check->num_rows > 0) {
            return ['status' => 'error', 'msg' => 'Class Name Already Exists!'];
        } else {
            $qry = $this->conn->query($sql);
            if ($qry) {
                $msg = $id ? "Class Data has been updated successfully!" : "New Class has been added successfully!";
                $_SESSION['flashdata'] = ['type' => 'success', 'msg' => $msg];
                return ['status' => 'success'];
            } else {
                $msg = $id ? 'An error occurred while updating the Class Data!' : 'An error occurred while saving the New Class!';
                return ['status' => 'error', 'msg' => $msg];
            }
        }
    }

    /**
     * Delete a class by ID.
     */
    public function delete_class() {
        $id = $_POST['id'];
        $delete = $this->conn->query("DELETE FROM `class_tbl` WHERE `id` = '$id'");

        if ($delete) {
            $_SESSION['flashdata'] = ['type' => 'success', 'msg' => "Class has been deleted successfully!"];
            return ['status' => 'success'];
        } else {
            return ['status' => 'error', 'msg' => "Class deletion failed!"];
        }
    }

    /**
     * List all classes.
     */
    public function list_class() {
        $sql = "SELECT * FROM `class_tbl` ORDER BY `name` ASC";
        $qry = $this->conn->query($sql);
        return $qry->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get details of a specific class by ID.
     */
    public function get_class($id = "") {
        $sql = "SELECT * FROM `class_tbl` WHERE `id` = '$id'";
        $qry = $this->conn->query($sql);
        return $qry->fetch_assoc();
    }

    /**
     * Save or update student information.
     */
    public function save_student() {
        $id = $_POST['id'] ?? null;
        $name = addslashes(htmlspecialchars($_POST['name']));
        $class_id = $_POST['class_id'];

        if ($id) {
            $check = $this->conn->query("SELECT id FROM `students_tbl` WHERE `name` = '$name' AND `class_id` = '$class_id' AND `id` != '$id'");
            $sql = "UPDATE `students_tbl` SET `name` = '$name', `class_id` = '$class_id' WHERE `id` = '$id'";
        } else {
            $check = $this->conn->query("SELECT id FROM `students_tbl` WHERE `name` = '$name' AND `class_id` = '$class_id'");
            $sql = "INSERT INTO `students_tbl` (`name`, `class_id`) VALUES ('$name', '$class_id')";
        }

        if ($check->num_rows > 0) {
            return ['status' => 'error', 'msg' => 'Student Name Already Exists!'];
        } else {
            $qry = $this->conn->query($sql);
            if ($qry) {
                $msg = $id ? "Student Data has been updated successfully!" : "New Student has been added successfully!";
                $_SESSION['flashdata'] = ['type' => 'success', 'msg' => $msg];
                return ['status' => 'success'];
            } else {
                $msg = $id ? 'An error occurred while updating the Student Data!' : 'An error occurred while saving the New Student!';
                return ['status' => 'error', 'msg' => $msg];
            }
        }
    }

    /**
     * Delete a student by ID.
     */
    public function delete_student() {
        $id = $_POST['id'];
        $delete = $this->conn->query("DELETE FROM `students_tbl` WHERE `id` = '$id'");

        if ($delete) {
            $_SESSION['flashdata'] = ['type' => 'success', 'msg' => "Student has been deleted successfully!"];
            return ['status' => 'success'];
        } else {
            return ['status' => 'error', 'msg' => "Student deletion failed!"];
        }
    }

    /**
     * List all students with their class names.
     */
    public function list_student() {
        $sql = "SELECT `students_tbl`.*, `class_tbl`.`name` AS `class` FROM `students_tbl` INNER JOIN `class_tbl` ON `students_tbl`.`class_id` = `class_tbl`.`id` ORDER BY `students_tbl`.`name` ASC";
        $qry = $this->conn->query($sql);
        return $qry->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get details of a specific student by ID.
     */
    public function get_student($id = "") {
        $sql = "SELECT `students_tbl`.*, `class_tbl`.`name` AS `class` FROM `students_tbl` INNER JOIN `class_tbl` ON `students_tbl`.`class_id` = `class_tbl`.`id` WHERE `students_tbl`.`id` = '$id'";
        $qry = $this->conn->query($sql);
        return $qry->fetch_assoc();
    }

    /**
     * Get attendance for students in a specific class on a specific date.
     */
    public function attendanceStudents($class_id = "", $class_date = "") {
        if (empty($class_id) || empty($class_date)) return [];

        $sql = "SELECT `students_tbl`.*, COALESCE((SELECT `status` FROM `attendance_tbl` WHERE `student_id` = `students_tbl`.id AND `class_date` = '$class_date'), 0) AS `status` 
                FROM `students_tbl` WHERE `class_id` = '$class_id' ORDER BY `name` ASC";
        $qry = $this->conn->query($sql);
        return $qry->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get monthly attendance for students in a specific class.
     */
    public function attendanceStudentsMonthly($class_id = "", $class_month = "") {
        if (empty($class_id) || empty($class_month)) return [];

        $sql = "SELECT `students_tbl`.* FROM `students_tbl` WHERE `class_id` = '$class_id' ORDER BY `name` ASC";
        $qry = $this->conn->query($sql);
        $result = $qry->fetch_all(MYSQLI_ASSOC);

        foreach ($result as $k => $row) {
            $att_sql = "SELECT `status`, `class_date` FROM `attendance_tbl` WHERE `student_id` = '{$row['id']}'";
            $att_qry = $this->conn->query($att_sql);
            foreach ($att_qry as $att_row) {
                $result[$k]['attendance'][$att_row['class_date']] = $att_row['status'];
            }
        }
        return $result;
    }

    /**
     * Save attendance for students.
     */
    public function save_attendance() {
        $student_id = $_POST['student_id'];
        $status = $_POST['status'];
        $class_date = $_POST['class_date'];
        $sql_values = "";
        $errors = "";

        foreach ($student_id as $k => $sid) {
            $stat = $status[$k] ?? 3;

            $check = $this->conn->query("SELECT id FROM `attendance_tbl` WHERE `student_id` = '$sid' AND `class_date` = '$class_date'");
            if ($check->num_rows > 0) {
                $result = $check->fetch_assoc();
                $att_id = $result['id'];
                try {
                    $this->conn->query("UPDATE `attendance_tbl` SET `status` = '$stat' WHERE `id` = '$att_id'");
                } catch (Exception $e) {
                    $errors .= $e->getMessage() . "<br>";
                }
            } else {
                $sql_values .= "('$sid', '$class_date', '$stat'), ";
            }
        }

        if (!empty($sql_values)) {
            $sql_values = rtrim($sql_values, ", ");
            try {
                $this->conn->query("INSERT INTO `attendance_tbl` (`student_id`, `class_date`, `status`) VALUES $sql_values");
            } catch (Exception $e) {
                $errors .= $e->getMessage() . "<br>";
            }
        }

        if (empty($errors)) {
            $_SESSION['flashdata'] = ["type" => "success", "msg" => "Class Attendance Data has been saved successfully."];
            return ['status' => 'success'];
        } else {
            return ['status' => 'error', 'msg' => $errors];
        }
    }

    function __destruct() {
        if ($this->conn) $this->conn->close();
    }
}
