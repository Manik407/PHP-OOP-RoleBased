<?php

$success = flash_get('success');
$error = flash_get('error');
if ($success) {
    echo '<div class="alert alert-success">'. $success .'</div>';
}
if ($error) {
    echo '<div class="alert alert-danger">'. $error .'</div>';
}
?>
