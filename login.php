<?php
include 'koneksi.php'; // Sertakan koneksi database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Siapkan dan jalankan query untuk mengambil data pengguna
    $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $username); // Menggunakan variabel yang sama untuk username dan email
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Mengambil data pengguna
        $user = $result->fetch_assoc();
        
        // Verifikasi kata sandi
        if (password_verify($password, $user['password'])) {
            // Kata sandi benar, lanjutkan ke halaman utama
            session_start();
            $_SESSION['username'] = $user['username']; // Simpan informasi pengguna di session
            header("Location: dashboard.html"); // Ganti dengan halaman yang sesuai
            exit();
        } else {
            // Kata sandi salah, kembali ke halaman login dengan pesan kesalahan
            header("Location: login.html?error=wrong_password"); // Ganti dengan halaman login Anda
            exit();
        }
    } else {
        // Pengguna tidak ditemukan, kembali ke halaman login dengan pesan kesalahan
        header("Location: login.html?error=user_not_found"); // Ganti dengan halaman login Anda
        exit();
    }

    $stmt->close();
}
$conn->close();
?>
