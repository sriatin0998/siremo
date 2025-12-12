<?php
session_start();
session_unset();
session_destroy();
// Location akan mengarah ke file login.php di folder /siremo/admin/
header("Location: login.php"); 
exit;
?>