<?php
header("Content-Type: application/json");
require_once 'connection.php';

// Ambil input JSON mentah dari proxy api.php
$inputData = file_get_contents("php://input");
$data = json_decode($inputData, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(["message" => "Format JSON tidak valid."]);
    exit();
}

$action = isset($data['action']) ? $data['action'] : '';
$email = isset($data['email']) ? $data['email'] : '';
$password = isset($data['password']) ? $data['password'] : '';

if (empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(["message" => "Email dan password tidak boleh kosong."]);
    exit();
}

// ---------------- PROSES REGISTER ----------------
if ($action === 'register') {
    // Cek apakah email sudah terdaftar sebelumnya
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        http_response_code(400);
        echo json_encode(["message" => "Email sudah terdaftar di database."]);
        exit();
    }

    // Hash password menggunakan BCRYPT (Rekomendasi keamanan standar)
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
    if ($stmt->execute([$email, $hashedPassword])) {
        http_response_code(201);
        echo json_encode(["message" => "Registrasi berhasil! Kredensial baru telah dibuat."]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Gagal menyimpan user baru ke database."]);
    }
    exit();
}

// ---------------- PROSES LOGIN ----------------
if ($action === 'login') {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Verifikasi password yang di-input dengan hash di DB
    if ($user && password_verify($password, $user['password'])) {
        http_response_code(200);
        echo json_encode([
            "message" => "Login sukses! Selamat datang di Portal Utama Zeta Infrastructure.",
            "user" => ["email" => $user['email']]
        ]);
    } else {
        http_response_code(401);
        echo json_encode(["message" => "Email atau password salah."]);
    }
    exit();
}

// Jika action di luar login / register
http_response_code(400);
echo json_encode(["message" => "Aksi tidak dikenali."]);
?>
