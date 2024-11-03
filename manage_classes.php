<?php
include 'db.php';

// Lấy danh sách môn học
$courses_sql = "SELECT * FROM courses";
$courses_result = $conn->query($courses_sql);

// Lấy danh sách lớp học
$classes_sql = "SELECT * FROM classes";
$classes_result = $conn->query($classes_sql);

// Khởi tạo biến để lưu danh sách sinh viên
$students = [];

// Xử lý khi người dùng chọn môn học và lớp học
if (isset($_POST['course_id']) && isset($_POST['class_id'])) {
    $course_id = $_POST['course_id'];
    $class_id = $_POST['class_id'];

    // Truy vấn danh sách sinh viên theo lớp học và môn học
    $students_sql = "SELECT students.*, classes.class_name, courses.course_name, teachers.teacher_name 
                     FROM students 
                     JOIN classes ON students.class_id = classes.class_id 
                     JOIN courses ON students.course_id = courses.course_id 
                     JOIN teachers ON teachers.class_id = classes.class_id AND teachers.course_id = courses.course_id
                     WHERE students.class_id = $class_id AND students.course_id = $course_id";
    $students = $conn->query($students_sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý lớp học</title>
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
        select {
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
<h2>Quản lý lớp học</h2>

<form method="POST" action="">
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

    <button type="submit">Xem sinh viên</button>
</form>

<?php if (!empty($students)): ?>
    <h3>Danh sách sinh viên</h3>
    <table>
        <tr>
            <th>Mã sinh viên</th>
            <th>Tên đầy đủ</th>
            <th>Email</th>
            <th>Giới tính</th>
            <th>Ngày sinh</th>
            <th>Tên lớp học</th>
            <th>Tên môn học</th>
            <th>Tên giáo viên</th> <!-- Thêm cột tên giáo viên -->
        </tr>
        <?php while ($row = $students->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['student_code']); ?></td>
                <td><?= htmlspecialchars($row['full_name']); ?></td>
                <td><?= htmlspecialchars($row['email']); ?></td>
                <td><?= htmlspecialchars($row['gender']); ?></td>
                <td><?= htmlspecialchars($row['birth_date']); ?></td>
                <td><?= htmlspecialchars($row['class_name']); ?></td>
                <td><?= htmlspecialchars($row['course_name']); ?></td>
                <td><?= htmlspecialchars($row['teacher_name']); ?></td> <!-- Hiển thị tên giáo viên -->
            </tr>
        <?php endwhile; ?>
    </table>
<?php endif; ?>

</body>
</html>
