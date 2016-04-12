<?php
session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    //error catching here! $_SESSION['errors']
    <form class="login" action="process.php" method="post">
      <input type="email" name = 'email' placeholder = 'email'>
      <input type="password" name='password' placeholder ='password'>
      <input type="hidden" name="action" value="login">
      <input type="submit" value = "login">
    </form>
    <form class="register" action="process.php" method="post">
      <input type="text" name = 'first_name'>
      <input type="text" name = 'last_name'>
      <input type="email" name = 'email' placeholder = 'email'>
      <input type="password" name='password' placeholder ='password'>
      <input type="password" name='confirm_password' placeholder ='password'>
      <input type="hidden" name="action" value="register">
      <input type="submit" value = "register">
    </form>

  </body>
</html>
