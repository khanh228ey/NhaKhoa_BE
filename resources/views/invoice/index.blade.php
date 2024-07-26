<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phiếu Thu</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 20px;
        }
        .container {
            width: 600px;
            margin: 0 auto;
            border: 1px solid #000;
            padding: 20px;
            box-sizing: border-box; /* Thêm để quản lý padding và border */
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
        }
        .info, .signatures {
            margin-bottom: 20px;
        }
        .info div, .signatures div {
            margin-bottom: 10px;
        }
        .signatures {
            display: table;
            width: 100%;
        }
        .signatures div {
            display: table-cell;
            width: 50%;
            vertical-align: top; /* Căn chỉnh theo chiều dọc */
            text-align: center;
            box-sizing: border-box; /* Thêm để quản lý padding và border */
        }
        .highlight {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>NHA KHOA OK-VIP</h1>
            <p>Giấy phép KCB: 00145 GPKCB</p>
            <p>Địa chỉ: 180 Cao lỗ P4 Q8 TPHCM</p>
            <h2>PHIẾU THU</h2>
        </div>
        <div class="info">
            <div><span class="highlight">Mã hồ sơ: </span>{{$invoice->history_id}}</div>
            <div><span class="highlight">Ngày lập phiếu: </span><span>{{$invoice->created_at}}</span></div>
            <div><span class="highlight">Họ và tên: </span>{{$invoice->history->customer->name}}</div>
            <div><span class="highlight">Điện thoại: </span><span>{{$invoice->history->customer->phone_number}}</span></div>
            <div><span class="highlight">Nội dung: </span><span>{{$invoice->history->noted}}</span></div>
            <div><span class="highlight">Tổng chi phí: </span><span>{{ number_format($invoice->total_price, 0, '.', ',') }} VND</span></div>
            @if ($invoice->method_payment == 1)
            <div><span class="highlight">Hình thức thanh toán: </span><span>Tiền mặt</span></div>
            @else
            <div><span class="highlight">Hình thức thanh toán: </span><span>Chuyển khoản</span></div>
            @endif
        </div>
        <div class="signatures">
            <div>
                <p class="highlight">Người thu</p>
                <p>(ký và ghi rõ họ tên)</p>
                <br><br>
                <p class="highlight">{{$invoice->user->name}}</p>
            </div>
            <div>
                <p class="highlight">Người nộp</p>
                <p>(ký và ghi rõ họ tên)</p>
                <br><br>
                <p class="highlight">{{$invoice->history->customer->name}}</p>
            </div>
        </div>
    </div>
</body>
</html>
