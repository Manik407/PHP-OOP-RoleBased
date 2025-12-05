<?php

require_once __DIR__ . '/../config/config.php';
$db = new Database();
$repo = new UserRepository($db);
$auth = new Auth($repo);
$auth->logout();
flash_set('success','Logged out');
header('Location: login.php'); exit;
