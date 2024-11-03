<?php
include 'db.php';

// Kiểm tra xem có ID môn học không
if (isset($_GET['course_id'])) {
    $course_id = $_GET['course_id'];

    // Lấy thông tin môn học
    $course_sql = "SELECT * FROM courses WHERE course_id = $course_id";
    $course_result = $conn->query($course_sql);
    $course = $course_result->fetch_assoc();

    // Lấy danh sách giáo viên cho dropdown
    $teachers_sql = "SELECT * FROM teachers";
    $teachers_result = $conn->query($teachers_sql);
} else {
    header("Location: manage_courses.php");
    exit();
}

// Xử lý cập nhật môn học
if (isset($_POST['update_course'])) {
    $course_name = $_POST['course_name'];
    $teacher_id = $_POST['teacher_id'];

    // Kiểm tra xem teacher_id có hợp lệ không
    $teacher_check_sql = "SELECT * FROM teachers WHERE teacher_id = $teacher_id";
    $teacher_check_result = $conn->query($teacher_check_sql);

    if ($teacher_check_result->num_rows > 0) {
        $update_sql = "UPDATE courses SET course_name = '$course_name', teacher_id = '$teacher_id' WHERE course_id = $course_id";
        $conn->query($update_sql);
        header("Location: manage_courses.php");
        exit();
    } else {
        echo "Giáo viên không tồn tại.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Môn Học</title>
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
    </style>
</head>
<body>
<h2>Sửa Môn Học</h2>

<form method="POST" action="">
    <input type="text" name="course_name" placeholder="Tên môn học" value="<?= htmlspecialchars($course['course_name']); ?>" required>
    <select name="teacher_id" required>
        <option value="">-- Chọn giáo viên --</option>
        <?php while ($teacher_row = $teachers_result->fetch_assoc()): ?>
            <option value="<?= htmlspecialchars($teacher_row['teacher_id']); ?>" <?= ($teacher_row['teacher_id'] == $course['teacher_id']) ? 'selected' : ''; ?>>
                <?= htmlspecialchars($teacher_row['teacher_name']); ?>
            </option>
        <?php endwhile; ?>
    </select>
    <button type="submit" name="update_course">Cập Nhật</button>
</form>

<a href="manage_courses.php">Quay lại danh sách môn học</a>
</body>
</html>
