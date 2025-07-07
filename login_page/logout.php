<?php
session_start();
session_unset();       // Hapus semua variabel session
session_destroy();     // Hancurkan session

// Redirect ke login dengan pesan logout
header("Location: login.php?pesan=logout");
exit();
?>