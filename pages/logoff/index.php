<?php
    session_start();
    $_SESSION = [];
    session_destroy();
    echo "<script>
            localStorage.removeItem('authToken');
            window.location.href = '/login';  // Redirecionamento no cliente
          </script>";
    exit();
?>