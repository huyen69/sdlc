<?php
include 'db.php';

// Kiểm tra xem có ID giáo viên không
if (isset($_GET['teacher_id'])) {
    $teacher_id = $_GET['teacher_id'];

    // Lấy thông tin giáo viên
    $teacher_sql = "SELECT * FROM teachers WHERE teacher_id = $teacher_id";
    $teacher_result = $conn->query($teacher_sql);
    $teacher = $teacher_result->fetch_assoc();

    // Lấy danh sách môn học và lớp học
    $courses_sql = "SELECT * FROM courses";
    $courses_result = $conn->query($courses_sql);

    $classes_sql = "SELECT * FROM classes";
    $classes_result = $conn->query($classes_sql);
} else {
    header("Location: manage_teachers.php");
    exit();
}

// Xử lý cập nhật giáo viên
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['teacher_name'], $_POST['course_id'], $_POST['class_id'])) {
    $teacher_name = $_POST['teacher_name'];
    $course_id = $_POST['course_id'];
    $class_id = $_POST['class_id'];

    // Cập nhật thông tin giáo viên
    $update_sql = "UPDATE teachers SET teacher_name = ?, course_id = ?, class_id = ? WHERE teacher_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("siii", $teacher_name, $course_id, $class_id, $teacher_id);
    $stmt->execute();
    $stmt->close();

    // Chuyển hướng lại trang để cập nhật danh sách
    header("Location: manage_teachers.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Giáo Viên</title>
    <style>
        /* Giữ nguyên CSS từ trang quản lý giáo viên */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
            color: #333;
        }
        h2 {
            color: #007bff;
        }
        form {
            margin-bottom: 20px;
        }
        select, input[type="text"] {
            padding: 10px;
            width: 220px;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 16px;
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
    </style>
</head>
<body>

<h2>Sửa Giáo Viên</h2>

<form method="POST" action="">
    <input type="text" name="teacher_name" placeholder="Tên giáo viên" value="<?= htmlspecialchars($teacher['teacher_name']); ?>" required>
    
    <select name="course_id" required>
        <option value="">-- Chọn môn học --</option>
        <?php while ($row = $courses_result->fetch_assoc()): ?>
            <option value="<?= htmlspecialchars($row['course_id']); ?>" <?= ($row['course_id'] == $teacher['course_id']) ? 'selected' : ''; ?>>
                <?= htmlspecialchars($row['course_name']); ?>
            </option>
        <?php endwhile; ?>
    </select>

    <select name="class_id" required>
        <option value="">-- Chọn lớp học --</option>
        <?php while ($row = $classes_result->fetch_assoc()): ?>
            <option value="<?= htmlspecialchars($row['class_id']); ?>" <?= ($row['class_id'] == $teacher['class_id']) ? 'selected' : ''; ?>>
                <?= htmlspecialchars($row['class_name']); ?>
            </option>
        <?php endwhile; ?>
    </select>

    <button type="submit">Cập Nhật</button>
</form>

<a href="manage_teachers.php">Quay lại danh sách giáo viên</a>

</body>
</html>
