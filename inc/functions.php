<?php
require_once 'config.php';

// Fungsi Diffie-Hellman
function generateDHKeys() {
    $config = [
        "private_key_bits" => 2048,
        "private_key_type" => OPENSSL_KEYTYPE_DH,
    ];
    
    $res = openssl_pkey_new($config);
    openssl_pkey_export($res, $privKey);
    $pubKey = openssl_pkey_get_details($res)['dh']['pub_key'];
    
    return ['private' => $privKey, 'public' => $pubKey];
}

function computeSharedSecret($privKey, $otherPubKey) {
    $privKeyResource = openssl_pkey_get_private($privKey);
    return openssl_dh_compute_key($otherPubKey, $privKeyResource);
}

// Fungsi Blowfish
function blowfishEncrypt($data, $key) {
    $iv = openssl_random_pseudo_bytes(8);
    $encrypted = openssl_encrypt($data, 'BF-CBC', $key, OPENSSL_RAW_DATA, $iv);
    return base64_encode($iv . $encrypted);
}

function blowfishDecrypt($encryptedData, $key) {
    $data = base64_decode($encryptedData);
    $iv = substr($data, 0, 8);
    $encrypted = substr($data, 8);
    return openssl_decrypt($encrypted, 'BF-CBC', $key, OPENSSL_RAW_DATA, $iv);
}

// Fungsi untuk menambah data
function add_data($name, $email, $phone) {
    global $conn;
    
    $keys = generateDHKeys();
    $public_key = base64_encode($keys['public']);
    $private_key = base64_encode($keys['private']);
    
    $sessionKey = hash('sha256', $keys['private'], true);
    $encrypted_phone = blowfishEncrypt($phone, $sessionKey);
    
    $sql = "INSERT INTO users (name, email, phone, public_key, private_key) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $encrypted_phone, $public_key, $private_key);
    
    return mysqli_stmt_execute($stmt);
}

// Fungsi untuk mengambil data
function get_data() {
    global $conn;
    $sql = "SELECT * FROM users";
    $result = mysqli_query($conn, $sql);
    return $result;
}

// Fungsi untuk mengedit data
// Lanjutan fungsi edit_data
function edit_data($id, $name, $email, $phone) {
    global $conn;

    $sql = "SELECT private_key FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    $private_key = base64_decode($row['private_key']);

    $sessionKey = hash('sha256', $private_key, true);
    $encrypted_phone = blowfishEncrypt($phone, $sessionKey);

    $sql = "UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssi", $name, $email, $encrypted_phone, $id);

    return mysqli_stmt_execute($stmt);
}

// Fungsi untuk menghapus data
function delete_data($id) {
    global $conn;
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    return mysqli_stmt_execute($stmt);
}

// Fungsi untuk mendekripsi nomor telepon
function decrypt_phone($encrypted_phone, $private_key) {
    $private_key = base64_decode($private_key);
    $sessionKey = hash('sha256', $private_key, true);
    return blowfishDecrypt($encrypted_phone, $sessionKey);
}

// Fungsi untuk login
function login($username, $password) {
    global $conn;
    $sql = "SELECT id, password FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            return $row['id'];
        }
    }
    return false;
}

// Fungsi untuk registrasi
function register($username, $password, $name, $email, $phone) {
    global $conn;
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $keys = generateDHKeys();
    $public_key = base64_encode($keys['public']);
    $private_key = base64_encode($keys['private']);
    
    $sessionKey = hash('sha256', $keys['private'], true);
    $encrypted_phone = blowfishEncrypt($phone, $sessionKey);
    
    $sql = "INSERT INTO users (username, password, name, email, phone, public_key, private_key) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssss", $username, $hashed_password, $name, $email, $encrypted_phone, $public_key, $private_key);
    
    return mysqli_stmt_execute($stmt);
}