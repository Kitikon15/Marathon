<?php
require_once 'db.php';
$message = "";

if (isset($_POST['submit'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $citizen_id = $_POST['citizen_id'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $category_id = $_POST['category_id'];
    $shirt_size = $_POST['shirt_size'];
    $shipping_id = $_POST['shipping_id'];

    mysqli_begin_transaction($conn);
    try {
        $sql_runner = "INSERT INTO runner (first_name, last_name, date_of_birth, gender, citizen_id, phone, email, is_disabled) 
                       VALUES ('$first_name', '$last_name', '$dob', '$gender', '$citizen_id', '$phone', '$email', 0)";
        mysqli_query($conn, $sql_runner);
        $runner_id = mysqli_insert_id($conn);

        $reg_date = date('Y-m-d');
        $sql_reg = "INSERT INTO registration (runner_id, category_id, price_id, shipping_id, reg_date, shirt_size, status) 
                    VALUES ('$runner_id', '$category_id', 1, '$shipping_id', '$reg_date', '$shirt_size', 'Pending')";
        mysqli_query($conn, $sql_reg);

        mysqli_commit($conn);
        $message = "<div class='alert alert-success border-0 shadow-sm'>🎉 <b>ลงทะเบียนสำเร็จ!</b> สามารถตรวจสอบสถานะได้ทันที</div>";
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $message = "<div class='alert alert-danger border-0 shadow-sm'>❌ <b>เกิดข้อผิดพลาด:</b> " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>กูจะวิ่ง MARATHON 2025</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f9;
            font-family: 'Kanit', sans-serif;
        }

        /* สร้างคำสั่งขยับแบบลอยขึ้นลง */
        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-15px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        /* ส่วนหัว Header */
        .hero-banner {
            background: linear-gradient(180deg, #1a365d 0%, #2b6cb0 100%);
            color: white;
            padding: 60px 0 100px 0;
            text-align: center;
            position: relative;
        }

        /* ปรับแต่งรูปภาพให้ขยับ */
        .runner-img {
            width: 160px;
            height: 160px;
            object-fit: contain;
            filter: drop-shadow(0 10px 15px rgba(0, 0, 0, 0.3));
            margin-bottom: 15px;
            /* เรียกใช้ Animation float */
            animation: float 3s ease-in-out infinite;
            transition: transform 0.3s ease;
        }

        /* เมื่อเอาเมาส์วางให้ขยายตัวเล็กน้อย */
        .runner-img:hover {
            transform: scale(1.1);
            cursor: pointer;
        }

        .hero-title {
            font-size: 2.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .registration-container {
            margin-top: -60px;
            position: relative;
            z-index: 10;
        }

        .reg-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            background: white;
        }

        .section-label {
            color: #2c5282;
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 20px;
            border-bottom: 2px solid #edf2f7;
            padding-bottom: 10px;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            padding: 12px 15px;
        }

        .form-control:focus {
            border-color: #4299e1;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.15);
        }

        .btn-submit {
            background-color: #2b6cb0;
            border: none;
            padding: 16px;
            font-weight: 600;
            font-size: 1.2rem;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            background-color: #2c5282;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(43, 108, 176, 0.3);
        }
    </style>
</head>

<body>

    <div class="hero-banner">
        <div class="container">
            <img src="runner-logo.png" alt="Runner" class="runner-img">
            <h1 class="hero-title">กูจะวิ่ง MARATHON 2025</h1>
            <p class="lead opacity-75">ปลุกความกล้าในตัวคุณ ท้าทายขีดจำกัดไปพร้อมกัน</p>
        </div>
    </div>

    <div class="container registration-container mb-5">
        <div class="card reg-card mx-auto shadow" style="max-width: 800px;">
            <div class="card-body p-4 p-md-5">
                <?php echo $message; ?>

                <form method="POST">
                    <div class="section-label">1. ข้อมูลนักวิ่ง (Runner Profile)</div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">ชื่อจริง</label>
                            <input type="text" name="first_name" class="form-control" placeholder="First Name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">นามสกุล</label>
                            <input type="text" name="last_name" class="form-control" placeholder="Last Name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">เลขบัตรประชาชน</label>
                            <input type="text" name="citizen_id" class="form-control" maxlength="13"
                                placeholder="Citizen ID" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">วันเดือนปีเกิด</label>
                            <input type="date" name="dob" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">เพศ</label>
                            <select name="gender" class="form-select">
                                <option value="Male">ชาย (Male)</option>
                                <option value="Female">หญิง (Female)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">อีเมล</label>
                            <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">เบอร์โทรศัพท์ติดต่อ</label>
                            <input type="tel" name="phone" class="form-control" placeholder="Phone Number" required>
                        </div>
                    </div>

                    <div class="section-label">2. การแข่งขัน (Race & Options)</div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-12">
                            <label class="form-label">เลือกระยะทาง</label>
                            <select name="category_id" class="form-select">
                                <option value="1">Full Marathon (42.195 km) - 900 THB</option>
                                <option value="2">Half Marathon (21.1 km) - 700 THB</option>
                                <option value="3">Mini Marathon (10 km) - 500 THB</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">ไซส์เสื้อ (Shirt Size)</label>
                            <select name="shirt_size" class="form-select">
                                <option value="S">S (36")</option>
                                <option value="M">M (38")</option>
                                <option value="L" selected>L (40")</option>
                                <option value="XL">XL (42")</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">การจัดส่ง (Shipping)</label>
                            <select name="shipping_id" class="form-select">
                                <option value="1">รับเองหน้างาน (Pick up)</option>
                                <option value="2">ส่ง EMS (+80 THB)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 pt-2">
                        <button type="submit" name="submit"
                            class="btn btn-primary btn-submit w-100 shadow-sm text-white">
                            ยืนยันการลงทะเบียน
                        </button>
                        <div class="text-center mt-4">
                            <a href="check_status.php" class="text-decoration-none text-muted small hover-primary">
                                🔍 ลืมข้อมูล? ตรวจสอบสถานะการสมัครที่นี่
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer class="text-center pb-5 text-muted small">
        &copy; 2025 กูจะวิ่ง MARATHON. All rights reserved.
    </footer>

</body>

</html>