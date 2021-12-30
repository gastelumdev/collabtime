<?php
$pdo = new PDO('mysql:host=localhost;dbname=collabti_db;charset=utf8', 'collabti_FzASgokdmrZuti', 'z=@5vBt!OyAj');
// $pdo = new PDO('mysql:host=localhost;dbname=fitin;charset=utf8', 'fitin', 'password');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);