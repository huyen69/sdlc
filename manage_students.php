<?php
include 'db.php';

// Xử lý thêm sinh viên
if (isset($_POST['add_student'])) {
    $student_code = $_POST['student_code'];
    $full_name = $_POST['full_name'];
    $birth_date = $_POST['birth_date'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $class_id = $_POST['class_id'];
    $course_id = $_POST['course_id']; // Thêm biến để lưu course_id

    $sql = "INSERT INTO students (student_code, full_name, birth_date, gender, email, class_id, course_id) 
            VALUES ('$student_code', '$full_name', '$birth_date', '$gender', '$email', '$class_id', '$course_id')";

    if ($conn->query($sql) === TRUE) {
        header("Location: manage_students.php?add_success=1");
        exit();
    } else {
        echo "Lỗi: " . $conn->error;
    }
}

// Xử lý cập nhật sinh viên
if (isset($_POST['edit_student'])) {
    // ... (giữ nguyên mã xử lý cập nhật sinh viên)
}

// Xử lý xóa sinh viên
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM students WHERE student_id = $delete_id";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: manage_students.php?delete_success=1");
        exit();
    } else {
        echo "Lỗi: " . $conn->error;
    }
}

// Lấy danh sách sinh viên kèm theo tên môn học và tên lớp học, sắp xếp theo mã sinh viên
$sql = "SELECT students.*, courses.course_name, classes.class_name 
        FROM students 
        LEFT JOIN courses ON students.course_id = courses.course_id 
        LEFT JOIN classes ON students.class_id = classes.class_id 
        ORDER BY student_code ASC"; // Thêm ORDER BY để sắp xếp theo mã sinh viên
$result = $conn->query($sql);

// Lấy danh sách môn học để hiển thị trong form thêm sinh viên
$courses_sql = "SELECT * FROM courses";
$courses_result = $conn->query($courses_sql);

// Lấy danh sách lớp học để hiển thị trong form thêm sinh viên
$classes_sql = "SELECT * FROM classes";
$classes_result = $conn->query($classes_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quản lý sinh viên</title>
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
        .form-container input[type="text"], 
        .form-container input[type="email"], 
        .form-container select, 
        .form-container input[type="date"] {
            padding: 10px;
            width: 100%;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-container button {
            margin-top: 10px;
            padding: 10px;
            background-color: #5cb85c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .form-container button:hover {
            background-color: #4cae4c;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f9f9f9;
        }
        a {
            color: #007bff;
            text-decoration: none;
            padding: 5px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        a:hover {
            background-color: #e7f1ff;
        }
        .logo {
            text-align: right;
            margin-bottom: 20px;
        }
        .logo img {
            width: 50px; /* Điều chỉnh kích thước logo */
            cursor: pointer;
        }
    </style>
    <script>
        // Hiển thị alert nếu có thông báo xóa thành công
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('delete_success')) {
                alert('Đã xóa sinh viên thành công!');
            }
            if (urlParams.has('add_success')) {
                alert('Đã thêm sinh viên thành công!');
            }
        };
    </script>
</head>
<body>
<!-- Phần logo -->
<div class="logo">
    <a href="index.php">
        <img src="logo.png" alt="Logo">
    </a>
</div>
<h2>Quản lý sinh viên</h2>
<a href="add_student.php">Thêm sinh viên mới</a>

<table>
    <tr>
        <th>Mã sinh viên</th>
        <th>Tên đầy đủ</th>
        <th>Ngày sinh</th>
        <th>Giới tính</th>
        <th>Email</th>
        <th>Tên lớp học</th>
        <th>Tên môn học</th> <!-- Thêm cột tên môn học -->
        <th>Trạng thái điểm danh</th>
        <th>Hành động</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['student_code']); ?></td>
            <td><?= htmlspecialchars($row['full_name']); ?></td>
            <td><?= htmlspecialchars($row['birth_date']); ?></td>
            <td><?= htmlspecialchars($row['gender']); ?></td>
            <td><?= htmlspecialchars($row['email']); ?></td>
            <td><?= htmlspecialchars($row['class_name']); ?></td> <!-- Hiển thị tên lớp học -->
            <td><?= htmlspecialchars($row['course_name']); ?></td> <!-- Hiển thị tên môn học -->
            <td><?= htmlspecialchars($row['attendance_status']); ?></td>
            <td>
                <a href="edit_student.php?student_id=<?= $row['student_id']; ?>">Sửa</a>
                <a href="?delete_id=<?= $row['student_id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa sinh viên này?');">Xóa</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
