<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Sistem Absen Marina</title>
    <style>
        body { display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100vh; font-family: Arial, sans-serif; background: #f8fafc; }
        .qr-box { background: #fff; padding: 2rem; border-radius: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        h1 { margin-bottom: 1.5rem; color: #333; }
    </style>
</head>
<body>
    <div class="qr-box">
        <h1>Scan QR untuk Akses Sistem</h1>
        {!! $qr !!}
        <p style="margin-top:1rem; color:#555;">https://sistemabsenmarina.shop</p>
    </div>
</body>
</html>
