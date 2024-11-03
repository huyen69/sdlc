<?php
include 'db.php';

// Lấy danh sách sinh viên
$sql_students = "SELECT student_id, full_name FROM students";
$students = $conn->query($sql_students);

// Lấy student_id từ tham số GET
if (isset($_GET['id'])) {
    $student_id = $_GET['id'];
    // Truy vấn thông tin sinh viên kèm theo tên lớp học
    $sql_student = "SELECT students.*, classes.class_name 
                    FROM students 
                    LEFT JOIN classes ON students.class_id = classes.class_id 
                    WHERE student_id = $student_id";
    $student = $conn->query($sql_student)->fetch_assoc();

    // Truy vấn lịch sử điểm danh của sinh viên
    $sql_attendance = "SELECT courses.course_name, attendance.attendance_date, attendance.status
                       FROM attendance
                       JOIN courses ON attendance.course_id = courses.course_id
                       WHERE attendance.student_id = $student_id";
    $attendance = $conn->query($sql_attendance);
} else {
    echo "ID sinh viên không hợp lệ!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết sinh viên</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
            color: #333;
        }
        h2, h3 {
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
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        a:hover {
            background-color: #218838;
        }
        .student-details {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
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
    <div>
        <h2>Chọn sinh viên</h2>
    </div>
    
    <form method="GET" action="">
        <select name="id" onchange="this.form.submit()">
            <option value="">-- Chọn sinh viên --</option>
            <?php while ($row = $students->fetch_assoc()): ?>
                <option value="<?= htmlspecialchars($row['student_id']); ?>" <?= (isset($student_id) && $student_id == $row['student_id']) ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($row['full_name']); ?>
                </option>
            <?php endwhile; ?>
        </select>
    </form>

    <?php if (isset($student)): ?>
        <div class="student-details">
            <h2>Chi tiết sinh viên</h2>
            <p><strong>Tên:</strong> <?= htmlspecialchars($student['full_name']); ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($student['email']); ?></p>
            <p><strong>Giới tính:</strong> <?= htmlspecialchars($student['gender']); ?></p>
            <p><strong>Ngày sinh:</strong> <?= htmlspecialchars($student['birth_date']); ?></p>
            <p><strong>Tên lớp học:</strong> <?= htmlspecialchars($student['class_name']); ?></p> <!-- Hiển thị tên lớp học -->
        </div>

        <h3>Lịch sử điểm danh</h3>
        <table>
            <tr>
                <th>Môn học</th>
                <th>Ngày điểm danh</th>
                <th>Trạng thái</th>
            </tr>
            <?php if ($attendance->num_rows > 0): ?>
                <?php while ($row = $attendance->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['course_name']); ?></td>
                        <td><?= htmlspecialchars($row['attendance_date']); ?></td>
                        <td><?= htmlspecialchars($row['status']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">Chưa có lịch sử điểm danh.</td>
                </tr>
            <?php endif; ?>
        </table>
    <?php endif; ?>

    <br>
    <a href="manage_students.php">Quay lại danh sách sinh viên</a>
</body>
</html>
