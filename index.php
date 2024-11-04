<?php
// Bao gồm file db.php để kết nối cơ sở dữ liệu
include 'db.php';

// Lấy danh sách môn học
$courses = $conn->query("SELECT course_id, course_name FROM courses");
$teachers = $conn->query("SELECT teacher_id, teacher_name FROM teachers");
$classes = $conn->query("SELECT class_id, class_name FROM classes");

// Lưu trữ tên môn học, giáo viên và lớp học vào mảng
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

// Khởi tạo mảng để lưu lịch
$schedule = [];

// Xử lý thêm thời khóa biểu
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $classDate = $_POST['classDate'] ?? null;
    $courseId = $_POST['courseId'] ?? null;
    $teacherId = $_POST['teacherId'] ?? null;
    $classId = $_POST['classId'] ?? null;

    if ($classDate && $courseId && $teacherId && $classId) {
        $stmt = $conn->prepare("INSERT INTO schedule (class_date, course_id, teacher_id, class_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siii", $classDate, $courseId, $teacherId, $classId);
        $stmt->execute();
        $stmt->close();

        // Chuyển hướng sau khi lưu dữ liệu
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
// Xử lý xóa thời khóa biểu
if (isset($_GET['delete'])) {
    $scheduleId = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM schedule WHERE schedule_id = ?");
    $stmt->bind_param("i", $scheduleId);
    $stmt->execute();
    $stmt->close();
    
    // Chuyển hướng sau khi xóa dữ liệu
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Lấy danh sách thời khóa biểu từ cơ sở dữ liệu
$result = $conn->query("SELECT * FROM schedule");
while ($row = $result->fetch_assoc()) {
    $schedule[] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Thống Điểm Danh Cao Đẳng</title>
    <style>
        /* CSS ở đây giống như trước */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f2f2f2;
        }
        .app {
            width: 100%;
            display: flex;
        }
        .container {
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: block;
            margin-right: auto;
            width: 60%;
            height: auto;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .text {
            display: flex;
            margin: 10px 0;
            padding: 20px;
            text-decoration: none;
            color: #fff;
            background-color: #0056b3;
            margin: 10px;
            border-radius: 5px;
            height: 35px;
            align-items: center;
            justify-content: center;
        }
        .text:hover {
            background-color: #0056b3;
        }
        .attendance {
            font-size: 50px;
            color: #333;
            margin: 20px;
            text-align: center;
        }
        .item {
            margin-bottom: 30px;
            margin-right: 10px;
        }
        .menu {
            margin-bottom: 50px;
            font-size: 50px;
            color: #333;
            text-align: center;
        }
        .text {
            font-size: 20px;
            text-align: center;
        }
        .header {
            width: 25%;
            background-color: #fff;
            height: 850px;
        }
        .container {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin: 20px auto;
    width: 100%; /* Chiều rộng của container */
    max-width: 1000px; /* Chiều rộng tối đa */
}

h1 {
    color: #333;
    margin-bottom: 20px;
    text-align: center; /* Căn giữa tiêu đề */
}

form {
    display: flex;
    flex-direction: column; /* Sắp xếp các phần tử theo cột */
    gap: 10px; /* Khoảng cách giữa các phần tử */
    margin-bottom: 20px; /* Khoảng cách dưới cùng của form */
}

input[type="date"],
select,
button {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}

button {
    background-color: #0056b3;
    color: white;
    cursor: pointer;
    transition: background-color 0.3s;
}

button:hover {
    background-color: #004494; /* Màu nền khi hover */
}

table {
    width: 100%; /* Chiều rộng của bảng */
    border-collapse: collapse; /* Xóa khoảng cách giữa các ô */
    margin-top: 20px; /* Khoảng cách phía trên bảng */
}

thead {
    background-color: #f2f2f2; /* Màu nền cho tiêu đề bảng */
}

th, td {
    padding: 12px;
    text-align: left; /* Căn trái cho các ô */
    border-bottom: 1px solid #ddd; /* Đường viền dưới cho các ô */
}

tr:hover {
    background-color: #f9f9f9; /* Màu nền khi hover hàng */
}
.delete-btn {
    text-decoration: none;
    color: #fff;
}

    </style>
</head>
<body>
    <div class="app">
        <div class="header">
            <div class="menu">Danh mục</div>
            <div class="item">
                <a class="text" href="manage_students.php">Quản Lý Sinh Viên</a>
            </div>
            <div class="item">
                <a class="text" href="add_student.php">Thêm sinh viên</a>
            </div>
            <div class="item">
                <a class="text" href="manage_classes.php">Quản lý lớp học</a>
            </div>
            <div class="item">
                <a class="text" href="manage_courses.php">Quản lý môn học</a>
            </div>
            <div class="item">
                <a class="text" href="manage_teachers.php">Quản lý giáo viên</a>
            </div>
            <div class="item">
                <a class="text" href="student_detail.php?id=1">Xem Chi Tiết Sinh Viên</a>
            </div>
        </div>
        <div class="container">
            <div>
            <h1>Thời Khóa Biểu</h1>

                <form method="POST" id="scheduleForm">
                    <select name="teacherId" id="teacherSelect" required>
                        <option value="">Chọn Giáo Viên</option>
                        <?php foreach($teacherNames as $id => $name): ?>
                            <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select name="courseId" id="courseSelect" required>
                        <option value="">Chọn Môn Học</option>
                        <?php foreach($courseNames as $id => $name): ?>
                            <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select name="classId" id="classSelect" required>
                        <option value="">Chọn Lớp Học</option>
                        <?php foreach($classNames as $id => $name): ?>
                            <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
                            <?php endforeach; ?>
                     </select>
                        <input type="date" name="classDate" id="classDate" required>           
                    <button type="submit">Thêm</button>
                </form>
            </div>
            <table>
            <thead>
                    <tr>
                        <th>Tên Giáo Viên</th>
                        <th>Tên Môn Học</th>
                        <th>Tên Lớp Học</th>
                        <th>Ngày Dạy</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody id="scheduleTable">
                    <?php foreach ($schedule as $entry): ?>
                        <tr>
                            <td><?php echo $teacherNames[$entry['teacher_id']]; ?></td>
                            <td><?php echo $courseNames[$entry['course_id']]; ?></td>
                            <td><?php echo $classNames[$entry['class_id']]; ?></td>
                            <td><?php echo $entry['class_date']; ?></td>
                            <td>
                            <button class="edit-btn" onclick="window.location.href='edit_schedule.php?id=<?php echo $entry['schedule_id']; ?>'">Sửa</button>                                
                            <button><a href="?delete=<?php echo $entry['schedule_id']; ?>" class="delete-btn">Xóa</a></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <script>
        function editSchedule(id, classDate, courseId, teacherId, classId) {
            document.getElementById('scheduleId').value = id;
            document.getElementById('classDate').value = classDate;
            document.getElementById('courseSelect').value = courseId;
            document.getElementById('teacherSelect').value = teacherId;
            document.getElementById('classSelect').value = classId;
            document.querySelector('button[type="submit"]').setAttribute('name', 'action');
            document.querySelector('button[type="submit"]').setAttribute('value', 'update');
        }
    </script>
        </div>
    </div>
    
</body>
</html>

<?php
$conn->close();
?>
