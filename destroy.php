<?php
session_start();
session_destroy();
$ref = $_SERVER['HTTP_REFERER'];

header( 'refresh: 0; url='.$ref);

 ?>
