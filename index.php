<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Thống Điểm Danh Cao Đẳng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f2f2f2;
        }
        .container {
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        a {
            display: block;
            margin: 10px 0;
            padding: 10px;
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
            border-radius: 5px;
        }
        a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Hệ Thống Điểm Danh</h1>
        <a href="manage_students.php">Quản Lý Sinh Viên</a>
        <a href="add_student.php">Thêm sinh viên</a>
        <a href="manage_classes.php">Quản lý lớp học</a>
        <a href="manage_courses.php">Quản lý môn học</a>
        <a href="manage_teachers.php">Quản lý giáo viên</a>
        <a href="student_detail.php">Xem Chi Tiết Sinh Viên</a>
    </div>
</body>
</html>
