<?php
// Bao gồm file db.php để kết nối cơ sở dữ liệu
include 'db.php';

// Khởi tạo mảng để lưu lịch
$schedule = [];

// Lấy danh sách môn học, giáo viên và lớp học
$courses = $conn->query("SELECT course_id, course_name FROM courses");
$teachers = $conn->query("SELECT teacher_id, teacher_name FROM teachers");
$classes = $conn->query("SELECT class_id, class_name FROM classes");

$courseNames = [];
while($row = $courses->fetch_assoc()) {
    $courseNames[$row['course_id']] = $row['course_name'];
}

$teacherNames = [];
while($row = $teachers->fetch_assoc()) {
    $teacherNames[$row['teacher_id']] = $row['teacher_name'];
}

$classNames = [];
while($row = $classes->fetch_assoc()) {
    $classNames[$row['class_id']] = $row['class_name'];
}

// Xử lý cập nhật thời khóa biểu
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $scheduleId = $_POST['scheduleId'];
    $classDate = $_POST['classDate'];
    $courseId = $_POST['courseId'];
    $teacherId = $_POST['teacherId'];
    $classId = $_POST['classId'];

    $stmt = $conn->prepare("UPDATE schedule SET class_date = ?, course_id = ?, teacher_id = ?, class_id = ? WHERE schedule_id = ?");
    $stmt->bind_param("siiii", $classDate, $courseId, $teacherId, $classId, $scheduleId);
    $stmt->execute();
    $stmt->close();

    // Chuyển hướng về trang chính sau khi cập nhật
    header("Location: index.php"); // Thay đổi 'index.php' thành tên tệp của trang chính của bạn
    exit();
}

// Lấy thông tin thời khóa biểu để chỉnh sửa
if (isset($_GET['id'])) {
    $scheduleId = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM schedule WHERE schedule_id = ?");
    $stmt->bind_param("i", $scheduleId);
    $stmt->execute();
    $result = $stmt->get_result();
    $entry = $result->fetch_assoc();
    $stmt->close();
} else {
    // Nếu không có ID, chuyển hướng về trang chính
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Thời Khóa Biểu</title>
    <style>
        /* CSS giống như trước */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f2f2f2;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        input[type="date"],
        select,
        button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #0056b3;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #004494;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Sửa Thời Khóa Biểu</h1>
        <form method="POST">
            <input type="hidden" name="scheduleId" value="<?php echo $entry['schedule_id']; ?>">
            <select name="teacherId" required>
                <option value="">Chọn Giáo Viên</option>
                <?php foreach($teacherNames as $id => $name): ?>
                    <option value="<?php echo $id; ?>" <?php echo $id == $entry['teacher_id'] ? 'selected' : ''; ?>><?php echo $name; ?></option>
                <?php endforeach; ?>
            </select>
            <select name="courseId" required>
                <option value="">Chọn Môn Học</option>
                <?php foreach($courseNames as $id => $name): ?>
                    <option value="<?php echo $id; ?>" <?php echo $id == $entry['course_id'] ? 'selected' : ''; ?>><?php echo $name; ?></option>
                <?php endforeach; ?>
            </select>
            <select name="classId" required>
                <option value="">Chọn Lớp Học</option>
                <?php foreach($classNames as $id => $name): ?>
                    <option value="<?php echo $id; ?>" <?php echo $id == $entry['class_id'] ? 'selected' : ''; ?>><?php echo $name; ?></option>
                <?php endforeach; ?>
            </select>
            <input type="date" name="classDate" value="<?php echo $entry['class_date']; ?>" required>
            <button type="submit">Cập Nhật</button>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>
