<?php
$password = "admin";
$hashed = password_hash($password, PASSWORD_DEFAULT);
echo $hashed;