<?php
session_start();
if (isset($_GET['variable'])) {
    unset($_SESSION[$_GET['variable']]);
}
?>