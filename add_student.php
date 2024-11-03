<?php
include 'db.php';

// Xử lý thêm sinh viên
if (isset($_POST['add_student'])) {
    $student_code = $_POST['student_code'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $attendance_status = $_POST['attendance_status'];
    $birth_date = $_POST['birth_date'];
    $gender = $_POST['gender'];
    $class_id = $_POST['class_id']; // Lớp học được chọn từ dropdown
    $course_id = $_POST['course_id'];

    // Kiểm tra mã sinh viên đã tồn tại chưa
    $check_sql = "SELECT * FROM students WHERE student_code='$student_code'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo "Lỗi: Mã sinh viên đã tồn tại!";
    } else {
        $sql = "INSERT INTO students (student_code, full_name, email, attendance_status, birth_date, gender, class_id, course_id) 
                VALUES ('$student_code', '$full_name', '$email', '$attendance_status', '$birth_date', '$gender', '$class_id', '$course_id')";
        if ($conn->query($sql) === TRUE) {
            header("Location: manage_students.php?success=1"); // Chuyển hướng về trang quản lý sinh viên
            exit();
        } else {
            echo "Lỗi: " . $conn->error;
        }
    }
}

// Lấy danh sách môn học
$courses_sql = "SELECT * FROM courses";
$courses_result = $conn->query($courses_sql);

// Lấy danh sách lớp học
$classes_sql = "SELECT * FROM classes";
$classes_result = $conn->query($classes_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thêm sinh viên</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .logo {
            text-align: end;
            margin-bottom: 20px;
        }
        .logo img {
            width: 50px; /* Điều chỉnh kích thước logo */
            cursor: pointer;
        }
        .form-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .form-container label {
            font-size: 1.2em; /* Tăng kích thước chữ cho label */
            margin-top: 10px;
            display: block; /* Đảm bảo label trên mỗi dòng */
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
            font-size: 1em; /* Kích thước chữ trong input */
        }
        .form-container button {
            margin-top: 10px;
            padding: 12px; /* Tăng kích thước nút */
            background-color: #5cb85c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 1.2em; /* Tăng kích thước chữ nút */
        }
        .form-container button:hover {
            background-color: #4cae4c;
        }
        h2 {
            text-align: center;
            font-size: 1.5em; /* Tăng kích thước chữ tiêu đề */
        }
    </style>
</head>
<body>
<!-- Phần logo -->
<div class="logo">
    <a href="index.php">
        <img src="logo.png" alt="Logo">
    </a>
</div>
<h2 style="text-align: center;">Thêm sinh viên mới</h2>

<div class="form-container">
    <form method="POST">
        <label>Mã sinh viên:</label>
        <input type="text" name="student_code" required>
        <label>Họ tên:</label>
        <input type="text" name="full_name" required>
        <label>Ngày sinh:</label>
        <input type="date" name="birth_date" required>
        <label>Giới tính:</label>
        <select name="gender" required>
            <option value="Nam">Nam</option>
            <option value="Nữ">Nữ</option>
        </select>
        <label>Email:</label>
        <input type="email" name="email" required>
        
        <label>Tên lớp học:</label>
        <select name="class_id" required>
            <?php while ($class = $classes_result->fetch_assoc()): ?>
                <option value="<?= $class['class_id']; ?>"><?= htmlspecialchars($class['class_name']); ?></option>
            <?php endwhile; ?>
        </select>

        <label>Môn học:</label>
        <select name="course_id" required>
            <?php while ($course = $courses_result->fetch_assoc()): ?>
                <option value="<?= $course['course_id']; ?>"><?= htmlspecialchars($course['course_name']); ?></option>
            <?php endwhile; ?>
        </select>

        <label>Trạng thái điểm danh:</label>
        <select name="attendance_status" required>
            <option value="Đi học">Đi học</option>
            <option value="Đi muộn">Đi muộn</option>
            <option value="Vắng">Vắng</option>
        </select>
        
        <button type="submit" name="add_student">Thêm</button>
    </form>
</div>

</body>
</html>
