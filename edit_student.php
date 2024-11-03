<?php
include 'db.php';

// Lấy thông tin sinh viên dựa trên student_id
if (isset($_GET['student_id'])) {
    $student_id = $_GET['student_id'];
    $sql = "SELECT * FROM students WHERE student_id = $student_id";
    $result = $conn->query($sql);
    $student = $result->fetch_assoc();
}

// Xử lý cập nhật sinh viên
if (isset($_POST['edit_student'])) {
    $student_code = $_POST['student_code'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $attendance_status = $_POST['attendance_status'];
    $birth_date = $_POST['birth_date'];
    $gender = $_POST['gender'];
    $class_id = $_POST['class_id'];
    $course_id = $_POST['course_id']; // Thêm biến course_id

    $sql = "UPDATE students SET 
            student_code='$student_code', 
            full_name='$full_name', 
            email='$email', 
            attendance_status='$attendance_status', 
            birth_date='$birth_date', 
            gender='$gender', 
            class_id='$class_id',
            course_id='$course_id' 
            WHERE student_id=$student_id";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: manage_students.php?success=1");
        exit();
    } else {
        echo "Lỗi: " . $conn->error;
    }
}

// Lấy danh sách lớp học
$classes_sql = "SELECT * FROM classes";
$classes_result = $conn->query($classes_sql);

// Lấy danh sách môn học
$courses_sql = "SELECT * FROM courses";
$courses_result = $conn->query($courses_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sửa Sinh Viên</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .form-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 20px auto;
        }
        .form-container label {
            margin-top: 10px;
            display: block;
            font-weight: bold;
        }
        .form-container input[type="text"], 
        .form-container input[type="email"], 
        .form-container select, 
        .form-container input[type="date"] {
            padding: 10px;
            width: 100%;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-container button {
            margin-top: 15px;
            padding: 10px;
            background-color: #5cb85c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }
        .form-container button:hover {
            background-color: #4cae4c;
        }
    </style>
</head>
<body>

<h2>Sửa Thông Tin Sinh Viên</h2>

<!-- Form sửa sinh viên -->
<div class="form-container">
    <form method="POST">
        <label>Mã sinh viên:</label>
        <input type="text" name="student_code" value="<?= htmlspecialchars($student['student_code']); ?>" required>
        
        <label>Họ tên:</label>
        <input type="text" name="full_name" value="<?= htmlspecialchars($student['full_name']); ?>" required>
        
        <label>Ngày sinh:</label>
        <input type="date" name="birth_date" value="<?= htmlspecialchars($student['birth_date']); ?>" required>
        
        <label>Giới tính:</label>
        <select name="gender" required>
            <option value="Nam" <?= ($student['gender'] == 'Nam') ? 'selected' : ''; ?>>Nam</option>
            <option value="Nữ" <?= ($student['gender'] == 'Nữ') ? 'selected' : ''; ?>>Nữ</option>
        </select>

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($student['email']); ?>" required>
        
        <label>Tên lớp học:</label>
        <select name="class_id" required>
            <?php while ($class = $classes_result->fetch_assoc()): ?>
                <option value="<?= $class['class_id']; ?>" <?= ($student['class_id'] == $class['class_id']) ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($class['class_name']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Môn học:</label>
        <select name="course_id" required>
            <?php while ($course = $courses_result->fetch_assoc()): ?>
                <option value="<?= $course['course_id']; ?>" <?= ($student['course_id'] == $course['course_id']) ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($course['course_name']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Trạng thái điểm danh:</label>
        <select name="attendance_status" required>
            <option value="Đi học" <?= ($student['attendance_status'] == 'Đi học') ? 'selected' : ''; ?>>Đi học</option>
            <option value="Đi muộn" <?= ($student['attendance_status'] == 'Đi muộn') ? 'selected' : ''; ?>>Đi muộn</option>
            <option value="Vắng" <?= ($student['attendance_status'] == 'Vắng') ? 'selected' : ''; ?>>Vắng</option>
        </select>

        <button type="submit" name="edit_student">Cập nhật</button>
    </form>
</div>

</body>
</html>
