<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phiếu Thu</title>
    <style>
       body {
    font-family: Arial, sans-serif;
}

.container {
    width: 70%;
    margin: 0 auto;
    padding: 20px;
    border: 1px solid #000;
    box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.header img {
    max-width: 150px;
}

.header .info {
    text-align: right;
}

.header .info p {
    margin: 0;
}

.title {
    text-align: center;
    font-weight: bold;
    text-decoration: underline;
    margin-bottom: 20px;
}

.details, .content {
    margin-bottom: 20px;
}

.details .row, .content .row {
    display: flex;
    justify-content: space-between;
    align-items: center; /* Ensures vertical alignment is centered */
    margin-bottom: 5px; /* Reduces space between rows */
}

.details .row p, .content .row p {
    margin: 0; /* Removes default margin */
}

.footer {
    display: flex;
    justify-content: space-between;
    margin-top: 50px;
}

.footer .sign {
    text-align: center;
}

    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <img src="https://th.bing.com/th/id/OIP.P_bPOXoFrDha2WtneRzq-wAAAA?rs=1&pid=ImgDetMain" alt="Logo">
            </div>
            <div class="info">
                <p><strong>NHA KHOA OK-VIP</strong></p>
                <p>Giấy phép KCB: 00145/GPCKB</p>
                <p>Số điện thoại: 0342231544</p>
                <p>Địa chỉ: 180 Cao Lỗ P4 Q8 TPHCM</p>
            </div>
        </div>

        <div class="title">PHIẾU THU</div>

        <div class="details">
            <div class="row">
                <p>Mã hồ sơ: 61</p>
                <p>Ngày lập phiếu: 16/04/2021</p>
            </div>
            <div class="row">
                <p>Họ và tên: Nguyễn Thị Trang</p>
                <p>Giới tính: Nữ</p>
            </div>
            <div class="row">
                <p>Ngày sinh: Hà Nhật Khánh </p>
                <p>Điện thoại: 0338230318</p>
            </div>
            <div class="row">
                <p>Địa chỉ: TPHCM</p>
            </div>
        </div>

        <div class="content">
            <p>Nội dung: Nhổ răng</p>
            <p>Tổng chi phí: 2,000,000</p>
            <p>Bằng chữ: Hai triệu đồng</p>
            <p>Hình thức thanh toán: Tiền mặt</p>
        </div>

        <div class="footer">
            <div class="sign">
                <p>Người thu</p>
                <p>(ký và ghi rõ họ tên)</p>
                <br><br>
                <p>Ngô Thị Ngọc Ánh</p>
            </div>
            <div class="sign">
                <p>Người nộp</p>
                <p>(ký và ghi rõ họ tên)</p>
                <br><br>
                <p>Nguyễn Thị Trang</p>
            </div>
        </div>
    </div>
</body>
</html>
