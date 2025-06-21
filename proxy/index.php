<?php
// Cho phép mọi nguồn (CORS)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Lấy URL từ query
$url = isset($_GET['url']) ? $_GET['url'] : null;

// Nếu không có URL thì trả lỗi
if (!$url) {
    http_response_code(400);
    echo json_encode(["error" => "Thiếu tham số URL"]);
    exit;
}

// Kiểm tra URL hợp lệ
$url = filter_var($url, FILTER_VALIDATE_URL);
if (!$url) {
    http_response_code(400);
    echo json_encode(["error" => "URL không hợp lệ"]);
    exit;
}

// Thiết lập context giả trình duyệt
$options = [
    "http" => [
        "method" => "GET",
        "header" => "User-Agent: Mozilla/5.0\r\n"
    ]
];
$context = stream_context_create($options);

// Gửi yêu cầu
$response = @file_get_contents($url, false, $context);

// Nếu lỗi khi gọi
if ($response === FALSE) {
    http_response_code(502);
    echo json_encode(["error" => "Không thể truy cập URL gốc"]);
    exit;
}

// Trả kết quả từ API gốc
echo $response;
?>
