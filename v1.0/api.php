<?php
// Izinkan Frontend membaca respon dari PHP ini
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Content-Type: application/json");

// Jika browser mengirimkan preflight request (OPTIONS), langsung selesaikan
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// CONFIG: Ubah ke IP Tailscale VM Ubuntu kamu dan port Node.js nya
$BACKEND_URL = "http://100.80.28.78"; 

// Ambil endpoint yang diminta dari query string, misal: api.php?endpoint=/login
$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

// Ambil data JSON mentah yang dikirim oleh Frontend (email & password)
$inputData = file_get_contents("php://input");

// Inisialisasi cURL untuk menembak ke VM Ubuntu lewat Tailscale
$ch = curl_init($BACKEND_URL . $endpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $inputData);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

// Eksekusi request dan ambil hasilnya
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    // Jika cURL gagal (misal VM mati atau Tailscale putus)
    http_response_code(502);
    echo json_encode(["message" => "Proxy Error: Gagal terhubung ke VM Ubuntu lewat Tailscale."]);
} else {
    // Teruskan respon dari Node.js ke Frontend
    http_response_code($httpCode);
    echo $response;
}

curl_close($ch);
?>