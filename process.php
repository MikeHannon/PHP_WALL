<?php
session_start();
require_once('new-connection.php');

$routing = [
  'login'=>function($post_data){
            login($post_data);
            },
  'register'=>function($post_data){
    register($post_data);
  },
  'messages'=>function($post_data){
    messages($post_data);
  },
  'comments'=>function($post_data){
    comments($post_data);
  }
];
$routing[$_POST['action']]($_POST);
// route response functions
function login($post_data){
  $_SESSION['errors'] = [];

  // takes info from the post and determines whether we can log in or not.
  $query = "SELECT password, id from users where email = '".$post_data['email']."'";

  $user = fetch_record($query);
  if(strlen($user['password'])>0){
    if (!password_verify($post_data['password'], $user['password'])){
      $_SESSION['errors']['login'] = "Email and/or password is invalid";
      header("Location:login_reg.php");
      exit();
    }
  }
  var_dump ($user);
  die('in login');
  $_SESSION['user_id'] = $user['id'];
  header("Location:wall.php");
}

function register($post_data){
  //validations
  $_SESSION['errors'] = [];
  $email = $post_data['email'];
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);
  if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
      // echo("$email is a valid email address");
  } else {
      // echo("$email is not a valid email address");
  }
  $query = "SELECT password, id from users where email = '".$post_data['email']."'";
  $user = fetch_record($query);

  if ($user['id'] > 0){
    $_SESSION['errors']['email'] = "Email is not valid";
    header('Location:login_reg.php');
    exit();
  }

  does_exist('first_name', $post_data['first_name']);
  does_exist('last_name', $post_data['last_name']);
  does_exist('password', $post_data['password']);
  does_exist('email', $post_data['email']);

  min_length('first_name', $post_data['first_name'], 2);
  min_length('last_name', $post_data['last_name'], 2);
  min_length('password', $post_data['password'], 6);
  max_length('first_name', $post_data['first_name'], 15);

  password_match($post_data['password'],$post_data['confirm_password']);


  if (count($_SESSION['errors']) > 0){
    header('Location:login_reg.php');
    exit();
  }

  $query = "INSERT into users (first_name, last_name, email, password, created_at, updated_at) values ('".$post_data['first_name']."', '".$post_data['last_name']."', '".$post_data['email']."', '".password_hash($post_data['password'], PASSWORD_DEFAULT)."', NOW(), NOW())";
  run_mysql_query($query);
  $_SESSION['errors']['no_error'] = "You have successfully registered";
  header('Location:login_reg.php');
  exit();
}


function min_length($key,$value,$length){
  if (strlen($value) < $length){
      $_SESSION['errors'][$key] = $key." must be longer than ".$length;
  }
}

function max_length($key,$value,$length){
  if (strlen($value) > $length){
      $_SESSION['errors'][$key] = $key." must not be longer than ".$length;
  }
}

function does_exist($key, $value){
  if (empty($value)){
    $_SESSION['errors'][$key] = $key." must not be empty.";
  }
}

function password_match($a, $b){
  if ($a != $b){
    $_SESSION['errors']['password_confirm'] = "Password and password confirmation must match";
  }
}

function messages($post_data){
  $query = "INSERT into messages(user_id, message, created_at, updated_at) value ('".$_SESSION['user_id']."', '".$post_data['message']."', NOW(), NOW())";
  run_mysql_query($query);
  header('Location:wall.php');
  exit();
}

function comments($post_data){
  $query = "INSERT into comments(user_id, message_id, comment, created_at, updated_at) value ('".$_SESSION['user_id']."','".$post_data['message_id']."', '".$post_data['comment']."', NOW(), NOW())";
  run_mysql_query($query);
  header('Location:wall.php');
  exit();
}
//password_hash($post_data['password'], PASSWORD_DEFAULT)


 ?>
