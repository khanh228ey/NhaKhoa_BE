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
            max-width: 100px;
        }

        .header .info {
            text-align: right;
        }

        .header .info p {
            margin: 2px 0;
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
            margin-bottom: 5px;
        }

        .details .row p, .content .row p {
            margin: 0;
        }

        .content p {
            margin: 0;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
        }

        .footer .sign {
            text-align: center;
            width: 45%;
        }

        .footer .sign p {
            margin: 0;
        }

        .field-label {
            font-weight: bold;
        }

        .field-value {
            font-style: italic;
        }

        .money {
            font-weight: bold;
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
                <p><strong>NHA KHOA NTQ</strong></p>
                <p>Giấy phép KCB: 00145/GPCKB</p>
                <p>Số điện thoại: 0342231544</p>
                <p>Địa chỉ: Tòa nhà Sông Đà, Mễ Trì, Hà Nội</p>
            </div>
        </div>

        <div class="title">PHIẾU THU</div>

        <div class="details">
            <div class="row">
                <p class="field-label">Mã hồ sơ:</p>
                <p class="field-value">61</p>
                <p class="field-label">Ngày lập phiếu:</p>
                <p class="field-value">16/04/2021</p>
            </div>
            <div class="row">
                <p class="field-label">Họ và tên:</p>
                <p class="field-value">Nguyễn Thị Trang</p>
                <p class="field-label">Giới tính:</p>
                <p class="field-value">Nữ</p>
            </div>
            <div class="row">
                <p class="field-label">Ngày sinh:</p>
                <p class="field-value"></p>
                <p class="field-label">Điện thoại:</p>
                <p class="field-value">0342231544</p>
            </div>
            <div class="row">
                <p class="field-label">Địa chỉ:</p>
                <p class="field-value">Từ Liêm</p>
            </div>
        </div>

        <div class="content">
            <p class="field-label">Nội dung:</p>
            <p class="field-value">Nhổ răng</p>
            <p class="field-label money">Tổng chi phí:</p>
            <p class="field-value money">2,000,000</p>
            <p class="field-label">Bằng chữ:</p>
            <p class="field-value">Hai triệu đồng</p>
            <p class="field-label">Hình thức thanh toán:</p>
            <p class="field-value">Tiền mặt</p>
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
