<?php
require_once 'config.php';

// Fungsi Diffie-Hellman
function diffie_hellman($prime, $generator, $private_key) {
    return bcpowmod($generator, $private_key, $prime);
}

// Fungsi Blowfish
function blowfish_encrypt($data, $key) {
    $cipher = "bf-ecb";
    $ivlen = openssl_cipher_iv_length($cipher);
    $encrypted = openssl_encrypt($data, $cipher, $key, 0);
    return base64_encode($encrypted);
}

function blowfish_decrypt($data, $key) {
    $cipher = "bf-ecb";
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivlen);
    $decrypted = openssl_decrypt(base64_decode($data), $cipher, $key, 0, $iv);
    return $decrypted;
}

// Fungsi untuk menambah data
function add_data($name, $email, $phone) {
    global $conn;
    
    // Generate Diffie-Hellman keys
    $prime = 23; // Contoh prima, gunakan yang lebih besar untuk keamanan yang lebih baik
    $generator = 5;
    $private_key = rand(1, $prime-1);
    $public_key = diffie_hellman($prime, $generator, $private_key);
    
    // Enkripsi data menggunakan Blowfish
    $blowfish_key = $public_key; // Gunakan public key sebagai kunci Blowfish
    $encrypted_phone = blowfish_encrypt($phone, $blowfish_key);
    
    $sql = "INSERT INTO users (name, email, phone, public_key) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $encrypted_phone, $public_key);
    
    if(mysqli_stmt_execute($stmt)){
        return true;
    } else {
        return false;
    }
}

// Fungsi untuk mengambil data
function get_data() {
    global $conn;
    $sql = "SELECT * FROM users";
    $result = mysqli_query($conn, $sql);
    return $result;
}

// Fungsi untuk mengedit data
function edit_data($id, $name, $email, $phone) {
    global $conn;

    // Ambil public key dari database
    $sql = "SELECT public_key FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    $public_key = $row['public_key'];

    // Enkripsi data menggunakan Blowfish
    $blowfish_key = $public_key; // Gunakan public key sebagai kunci Blowfish
    $encrypted_phone = blowfish_encrypt($phone, $blowfish_key);

    $sql = "UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssi", $name, $email, $encrypted_phone, $id);

    if(mysqli_stmt_execute($stmt)){
        return true;
    } else {
        return false;
    }
}

// Fungsi untuk menghapus data
function delete_data($id) {
    global $conn;
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    return mysqli_stmt_execute($stmt);
}
?>