<?php
require_once 'db.php';

$runner_data = null;
$error_msg = "";

if (isset($_POST['search'])) {
    $citizen_id = mysqli_real_escape_string($conn, $_POST['citizen_id']);

    // ปรับ SQL ให้ดึงราคาจากตาราง price_rate แทน (ตามโครงสร้าง SQL ใหม่)
    $sql = "SELECT 
                rn.first_name, 
                rn.last_name, 
                rn.citizen_id,
                r.reg_id, 
                r.status, 
                r.shirt_size,
                c.name AS race_name,
                pr.amount AS price
            FROM runner rn
            JOIN registration r ON rn.runner_id = r.runner_id
            JOIN race_category c ON r.category_id = c.category_id
            JOIN price_rate pr ON r.price_id = pr.price_id
            WHERE rn.citizen_id = '$citizen_id'
            ORDER BY r.reg_id DESC LIMIT 1";

    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $runner_data = mysqli_fetch_assoc($result);
    } else {
        $error_msg = "ไม่พบข้อมูลการลงทะเบียนสำหรับเลขบัตรประชาชนนี้";
    }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตรวจสอบสถานะ - Marathon 2025</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Kanit', sans-serif;
        }

        .search-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        .status-badge {
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: bold;
        }

        .pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .paid {
            background-color: #d4edda;
            color: #155724;
        }
    </style>
</head>

<body>

    <div class="container py-5">
        <div class="card search-card mx-auto shadow-sm" style="max-width: 600px;">
            <div class="card-body p-4 p-md-5">
                <h3 class="text-center mb-4 text-primary fw-bold">ตรวจสอบสถานะนักวิ่ง</h3>

                <form method="POST" class="mb-4">
                    <div class="mb-3">
                        <label class="form-label text-muted">กรอกเลขบัตรประชาชน 13 หลัก</label>
                        <div class="input-group">
                            <input type="text" name="citizen_id" class="form-control form-control-lg"
                                placeholder="110xxxxxxxxxx" required maxlength="13">
                            <button class="btn btn-primary px-4" type="submit" name="search">ค้นหา</button>
                        </div>
                    </div>
                </form>

                <?php if ($error_msg): ?>
                    <div class="alert alert-danger border-0 shadow-sm text-center"><?php echo $error_msg; ?></div>
                <?php endif; ?>

                <?php if ($runner_data): ?>
                    <div class="border-top pt-4 mt-4">
                        <div class="text-center mb-4">
                            <span
                                class="status-badge <?php echo ($runner_data['status'] == 'Paid') ? 'paid' : 'pending'; ?>">
                                สถานะ:
                                <?php echo ($runner_data['status'] == 'Paid') ? 'ชำระเงินเรียบร้อยแล้ว' : 'รอการชำระเงิน'; ?>
                            </span>
                        </div>

                        <div class="row g-2">
                            <div class="col-5 text-muted">รหัสการสมัคร:</div>
                            <div class="col-7 fw-bold">#<?php echo $runner_data['reg_id']; ?></div>

                            <div class="col-5 text-muted">ชื่อ-นามสกุล:</div>
                            <div class="col-7 fw-bold">
                                <?php echo $runner_data['first_name'] . " " . $runner_data['last_name']; ?>
                            </div>

                            <div class="col-5 text-muted">รายการ:</div>
                            <div class="col-7 fw-bold text-primary"><?php echo $runner_data['race_name']; ?></div>

                            <div class="col-5 text-muted">ไซส์เสื้อ:</div>
                            <div class="col-7 fw-bold"><?php echo $runner_data['shirt_size']; ?></div>

                            <div class="col-5 text-muted">ยอดที่ต้องชำระ:</div>
                            <div class="col-7 fw-bold text-danger"><?php echo number_format($runner_data['price'], 2); ?>
                                บาท</div>
                        </div>

                        <?php if ($runner_data['status'] != 'Paid'): ?>
                            <div class="alert alert-warning mt-4 border-0 small">
                                <b>📌 ขั้นตอนต่อไป:</b> โปรดโอนเงินเข้าบัญชีธนาคาร xxx-xxx-xxx และส่งหลักฐานรหัสสมัคร
                                #<?php echo $runner_data['reg_id']; ?> ให้เจ้าหน้าที่
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="text-center mt-4">
                    <a href="register.php" class="text-decoration-none text-muted small">← กลับหน้าลงทะเบียน</a>
                </div>
            </div>
        </div>
    </div>

</body>

</html>