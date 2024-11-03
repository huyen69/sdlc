<?php
include 'db.php';

// Lấy danh sách giáo viên
$teachers_sql = "SELECT teachers.*, courses.course_name, classes.class_name 
                 FROM teachers 
                 LEFT JOIN courses ON teachers.course_id = courses.course_id 
                 LEFT JOIN classes ON teachers.class_id = classes.class_id";
$teachers_result = $conn->query($teachers_sql);

// Xử lý khi người dùng thêm giáo viên mới
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['teacher_name'], $_POST['course_id'], $_POST['class_id'])) {
    $teacher_name = $_POST['teacher_name'];
    $course_id = $_POST['course_id'];
    $class_id = $_POST['class_id'];

    // Thêm giáo viên vào cơ sở dữ liệu
    $insert_teacher_sql = "INSERT INTO teachers (teacher_name, course_id, class_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insert_teacher_sql);
    $stmt->bind_param("sii", $teacher_name, $course_id, $class_id);
    $stmt->execute();
    $stmt->close();

    // Chuyển hướng lại trang để cập nhật danh sách
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Xử lý xóa giáo viên
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_teacher_sql = "DELETE FROM teachers WHERE teacher_id = ?";
    $stmt = $conn->prepare($delete_teacher_sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();

    // Chuyển hướng lại trang để cập nhật danh sách
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Lấy danh sách môn học và lớp học để hiển thị trong form thêm giáo viên
$courses_sql = "SELECT * FROM courses";
$courses_result = $conn->query($courses_sql);

$classes_sql = "SELECT * FROM classes";
$classes_result = $conn->query($classes_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý giáo viên</title>
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
<h2>Quản lý giáo viên</h2>

<form method="POST" action="">
    <input type="text" name="teacher_name" placeholder="Tên giáo viên" required>
    
    <select name="course_id" required>
        <option value="">-- Chọn môn học --</option>
        <?php while ($row = $courses_result->fetch_assoc()): ?>
            <option value="<?= htmlspecialchars($row['course_id']); ?>">
                <?= htmlspecialchars($row['course_name']); ?>
            </option>
        <?php endwhile; ?>
    </select>

    <select name="class_id" required>
        <option value="">-- Chọn lớp học --</option>
        <?php while ($row = $classes_result->fetch_assoc()): ?>
            <option value="<?= htmlspecialchars($row['class_id']); ?>">
                <?= htmlspecialchars($row['class_name']); ?>
            </option>
        <?php endwhile; ?>
    </select>

    <button type="submit">Thêm giáo viên</button>
</form>

<h3>Danh sách giáo viên</h3>
<table>
    <tr>
        <th>ID</th>
        <th>Tên giáo viên</th>
        <th>Môn học</th>
        <th>Lớp học</th>
        <th>Hành động</th>
    </tr>
    <?php while ($row = $teachers_result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['teacher_id']); ?></td>
            <td><?= htmlspecialchars($row['teacher_name']); ?></td>
            <td><?= htmlspecialchars($row['course_name']); ?></td>
            <td><?= htmlspecialchars($row['class_name']); ?></td>
            <td>
                <a href="edit_teacher.php?teacher_id=<?= $row['teacher_id']; ?>">Sửa</a>
                <a href="?delete_id=<?= $row['teacher_id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa không?');">Xóa</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
