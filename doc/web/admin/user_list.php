<?php
require_once(dirname(__FILE__).'/../../config/config.php');
require_once(dirname(__FILE__).'/../functions.php');

try{
  session_start();

  if(!isset($_SESSION['USER'])|| $_SESSION['USER']['auth_type']!= 1){
    // ログインされていないの場合はログイン画面へ
    header('Location:/admin/login.php');
    exit;
  }

  $pdo = connect_db();

  $sql = "SELECT * FROM user";
  $stmt = $pdo->query($sql);
  $user_list = $stmt->fetchAll();
} catch (Exception $e){
  //エラーの時の処理
  header('Location:/error.php');
  exit;
}
?>


<!doctype html>
<html lang="ja">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

  　<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
　　　
    <!-- Original CSS -->
    <link rel="stylesheet" href="/css/style.css">


    <title>社員一覧|WoRks</title>
  </head>
  <body class="text-center bg-secondary">

  <div>
   <img class="mb-4"src="/img/logo.jpeg" alt="WORKS" width="300" height="80">
  </div>

    <form class="border rounded bg-white form-user-list" action="index.php">
    <h1 class="h3 my-3">社員一覧</h1>

    <table class="table table-bordered">
  <thead>
    <tr class="bg-light">
      <th scope="col">社員番号</th>
      <th scope="col">社員名</th>
      <th scope="col">権限</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($user_list as $user): ?>
    <tr>
      <td scope="row"><?= $user['user_no']?></td>
      <td><a href="/admin/user_result.php?id=<?= $user['id']?>"><?= $user['name']?></a></td>
      <td scope="row"><?php if($user['auth_type']==1)echo'管理者'?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

</form>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>