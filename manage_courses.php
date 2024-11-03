<?php
include 'db.php';

// Xử lý thêm môn học
if (isset($_POST['add_course'])) {
    $course_name = $_POST['course_name'];
    $teacher_id = $_POST['teacher_id'];

    // Kiểm tra môn học đã tồn tại
    $check_sql = "SELECT * FROM courses WHERE course_name = '$course_name' AND teacher_id = '$teacher_id'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows == 0) {
        $add_sql = "INSERT INTO courses (course_name, teacher_id) VALUES ('$course_name', '$teacher_id')";
        $conn->query($add_sql);

        // Chuyển hướng sau khi thêm
        header("Location: manage_courses.php");
        exit();
    } else {
        echo "Môn học đã tồn tại.";
    }
}

// Xử lý sửa môn học
if (isset($_POST['edit_course'])) {
    $course_id = $_POST['course_id'];
    $course_name = $_POST['course_name'];
    $teacher_id = $_POST['teacher_id'];

    // Kiểm tra xem teacher_id có hợp lệ không
    $teacher_check_sql = "SELECT * FROM teachers WHERE teacher_id = $teacher_id";
    $teacher_check_result = $conn->query($teacher_check_sql);

    if ($teacher_check_result->num_rows > 0) {
        $edit_sql = "UPDATE courses SET course_name = '$course_name', teacher_id = '$teacher_id' WHERE course_id = $course_id";
        $conn->query($edit_sql);
    } else {
        echo "Giáo viên không tồn tại.";
    }
}

// Xử lý xóa môn học
if (isset($_GET['delete'])) {
    $course_id = $_GET['delete'];

    // Kiểm tra xem có giáo viên nào đang liên kết với môn học này không
    $check_sql = "SELECT * FROM teachers WHERE course_id = $course_id";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // Cập nhật khóa ngoại cho giáo viên
        $update_sql = "UPDATE teachers SET course_id = NULL WHERE course_id = $course_id";
        $conn->query($update_sql);
    }

    // Xóa môn học
    $delete_sql = "DELETE FROM courses WHERE course_id = $course_id";
    $conn->query($delete_sql);
}

// Lấy danh sách môn học cùng với tên giáo viên
$courses_sql = "SELECT courses.course_id, courses.course_name, teachers.teacher_name 
                FROM courses 
                LEFT JOIN teachers ON courses.teacher_id = teachers.teacher_id";
$courses_result = $conn->query($courses_sql);

// Lấy danh sách giáo viên cho dropdown
$teachers_sql = "SELECT * FROM teachers";
$teachers_result = $conn->query($teachers_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý môn học</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
            color: #333;
        }
        h2 {
            color: #007bff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        form {
            margin-bottom: 20px;
        }
        input, select {
            padding: 10px;
            margin-right: 10px;
        }
        button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
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
</head>
<body>
<div class="logo">
    <a href="index.php">
        <img src="logo.png" alt="Logo">
    </a>
</div>
<h2>Quản lý môn học</h2>

<!-- Form thêm môn học -->
<h3>Thêm môn học</h3>
<form method="POST" action="">
    <input type="text" name="course_name" placeholder="Tên môn học" required>
    <select name="teacher_id" required>
        <option value="">-- Chọn giáo viên --</option>
        <?php while ($row = $teachers_result->fetch_assoc()): ?>
            <option value="<?= htmlspecialchars($row['teacher_id']); ?>">
                <?= htmlspecialchars($row['teacher_name']); ?>
            </option>
        <?php endwhile; ?>
    </select>
    <button type="submit" name="add_course">Thêm môn học</button>
</form>

<!-- Hiển thị danh sách môn học -->
<table>
    <tr>
        <th>Mã môn học</th>
        <th>Tên môn học</th>
        <th>Tên giáo viên</th>
        <th>Hành động</th>
    </tr>
    <?php while ($row = $courses_result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['course_id']); ?></td>
        <td><?= htmlspecialchars($row['course_name']); ?></td>
        <td><?= htmlspecialchars($row['teacher_name']); ?></td>
        <td>
            <a href="edit_course.php?course_id=<?= $row['course_id']; ?>">Sửa</a>
            <a href="?delete=<?= $row['course_id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa không?');">Xóa</a>
        </td>
    </tr>
<?php endwhile; ?>

</table>
</body>
</html>
