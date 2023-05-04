<?php

header('Content-Type: text/html; charset=UTF-8');

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if (!empty($_SESSION['login'])) {
  header('Location: index.php');
  }else{
?>
<style>
  body{
  background-color: #eea273;
  }
  .log-in{
    font-family: "Montserrat", sans-serif;
    max-width: 960px;
    text-align: center;
    margin: 0 auto;
    padding: 40px;
    width: 250px;
    background-color: #F2B996;
    border: 2px solid black;
    border-radius: 5px;
  }
</style>
<div class="log-in">
<form action="login.php" method="post">
  <input name="login" /> Логин<br>
  <input name="password" type="password"/> Пароль<br>
  <input type="submit" value="Войти" />
</form>
</div>
<?php
  }
}
else {
  $login=$_POST['login'];
  $pswrd=$_POST['password'];
  $uid=0;
  $error=TRUE;
  $user = 'u52835';
  $pass = '5206479';
  $db1 = new PDO('mysql:host=localhost;dbname=u52835', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
  if(!empty($login) and !empty($pswrd)){
    try{
      $chk=$db1->prepare("SELECT * FROM userlogin WHERE login=?");
      $chk->bindParam(1,$login);
      $chk->execute();
      $username=$chk->fetchALL();
	  print($username[0]['password']);
      if(password_verify($pswrd,$username[0]['password'])){
        $uid=$username[0]['id'];
        $error=FALSE;
      }
    }
    catch(PDOException $e){
      print('Error : ' . $e->getMessage());
      exit();
    }
  }
  if($error==TRUE){
    print('Неправильные логин или пароль? <br> Создайте нового <a href="index.php">пользователя</a> или <a href="login.php">попробовать войти снова</a> ');
    session_destroy();
    exit();
  }
  $_SESSION['login'] = $login;
  $_SESSION['uid'] = $uid;
  header('Location: index.php');
}
