<?php
$password = '09615008090Sydney27';
$hashed = password_hash($password, PASSWORD_DEFAULT);
echo "Your hashed password is: <br>";
echo $hashed;
?> 