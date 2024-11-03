<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Xử lý cập nhật trạng thái điểm danh
    foreach ($_POST['attendance'] as $student_id => $status) {
        $course_id = $_POST['course_id'];
        $attendance_date = date('Y-m-d');

        $sql = "INSERT INTO attendance (student_id, course_id, attendance_date, status)
                VALUES ('$student_id', '$course_id', '$attendance_date', '$status')
                ON DUPLICATE KEY UPDATE status='$status'";
        $conn->query($sql);
    }
}

$sql = "SELECT * FROM students";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance</title>
</head>
<body>
    <form method="POST">
        <input type="hidden" name="course_id" value="1"> <!-- ID môn học -->
        <table >
            <tr>
                <th>Sinh viên</th>
                <th>Trạng thái</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['full_name']; ?></td>
                    <td>
                        <select name="attendance[<?= $row['student_id']; ?>]">
                            <option value="Đi học">Đi học</option>
                            <option value="Đi muộn">Đi muộn</option>
                            <option value="Vắng">Vắng</option>
                        </select>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
        <button type="submit">Lưu điểm danh</button>
    </form>
</body>
</html>
