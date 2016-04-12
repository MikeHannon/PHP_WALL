<?php
session_start();
require_once('new-connection.php');
$query = "SELECT first_name, last_name from users where id = ".$_SESSION['user_id']."";
echo $query;
die('my query');
$user = fetch_record($query);

var_dump($user);
die('user');

$query = "select message, users.first_name, users.last_name, messages.created_at from messages left join users on users.id = messages.user_id";
$messages = fetch_all($query);

$query = "select comment, users.first_name, users.last_name, comments.created_at, comments.message_id from comments left join users on users.id = comments.user_id";
$comments = fetch_all($query);

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <h2>Welcome to the Wall <?= $user['first_name'] ?> <?= $user['last_name'] ?></h2>
    <form class="create_message" action="process.php" method="post">
      <textarea name="message" rows="8" cols="40"></textarea>
      <input type="hidden" name = "action" value = "messages">
      <input type="submit" value = "Create a Message">
    </form>
    <?php
      for ($i=0; $i < count($messages); $i++) { ?>
        <p><?= $messages[$i]['message'] ?> ||  <span>created by:  <?= $messages[$i]['first_name'] ?> <?= $messages[$i]['last_name'] ?> </span> </p>
        <p> <?= $messages[$i]['created_at'] ?> </p>
        <?php
        for ($j=0; $j < $comments; $j++) {
          if ($comments[$j]['message_id'] == $messages[$i]['id']){
            ?>
              <p><?= $comments[$j]['message'] ?> ||  <span>created by:  <?= $comments[$j]['first_name'] ?> <?= $comments[$j]['last_name'] ?> </span> </p>
              <p> <?= $comments[$j]['created_at'] ?> </p>
              <?php
          }
        }
        ?>
        <form class="create_comment" action="process.php" method="post">
          <textarea name="comment" rows="8" cols="30"></textarea>
          <input type="hidden" name = "action" value = "comments">
          <input type="hidden" name = "message_id" value = "<?= $messages[$i]['id']?>">
          <input type="submit" value = "Make a comment">
        </form>
      <?php
    }
     ?>


  </body>
</html>
