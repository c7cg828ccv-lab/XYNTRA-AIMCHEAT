# HeadlockZen Server (PHP / cPanel)

## Upload
1. Upload toan bo thu muc nay vao public_html tren cPanel.
2. Dam bao thu muc data/ co quyen ghi (755/775).
3. Doi mat khau admin trong config.php (ADMIN_PASS).

## Mac dinh
- Tai khoan: admin
- Mat khau: admin123

## Endpoints (app se goi)
- POST /ipa/jerry/api.php  body: action=verify_app_key&key=YOURKEY
- GET  /ipa/jerry/apibaotri.php
- GET  /ipa/jerry/list.php?v=1
- GET  /ipa/jerry/ffmax/... va /ffnormal/...

## Quan ly Key
- Dang nhap dashboard (index.php) de them/xoa key, bat/tat bao tri.
- Neu chua co key nao trong he thong, server se chap nhan bat ky key (de de dang su dung). Sau khi them key, chi key trong danh sach moi duoc chap nhan.

## Ket noi app
- Sua hosts tren iPhone jailbreak: IP_SERVER solitudepremium.click
  hoac patch URL trong binary (nang cao).
