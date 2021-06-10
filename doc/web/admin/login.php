<?php
require_once(dirname(__FILE__).'/../../config/config.php');
require_once(dirname(__FILE__).'/../functions.php');

try{
  session_start();

  if(isset($_SESSION['USER'])&& $_SESSION['USER']['auth_type'] == 1){
    // ログイン済みの場合はホーム画面へ
    header('Location:/admin/user_list.php');
    exit;
  }

  if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $user_no = $_POST['user_no'];
    $password = $_POST['password'];

    //  echo $user_no.'<br>';
    //  echo $password;
    //  exit;


  // ここまではあってる

      // 2.バリデーションチェック
    $err = array();

    if(!$user_no){
      $err['user_no'] = '社員番号を入力してください。';
    }elseif(!preg_match('/^[0-9]+$/',$user_no)){
      $err['user_no'] = '社員番号を正しく入力してください。';
    }elseif(mb_strlen($user_no,'utf-8') > 20){
      $err['user_no'] = '社員番号が長すぎます。';
    }

      if(!$password){
        $err['password']='パスワードを入力してください。';
      }

      if(empty($err)){
        $pdo = connect_db();


        $sql = "SELECT id, user_no, name, auth_type FROM user WHERE user_no = :user_no AND password = :password AND auth_type = 1 LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':user_no',$user_no,PDO::PARAM_STR);
        $stmt->bindValue(':password',$password,PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch();

      // var_dump($user);
      // exit;


        if($user){
  //     //4.ログイン処理（セッションに保存）
        $_SESSION['USER'] = $user;
  //     //5.HOME画面へ遷移
        header('Location:/admin/user_list.php');
        exit;

        }else{
          $err['password']='認証に失敗しました';
        }

      }

  }else{
  //    //画面初回アクセス時
    $user_no = "";
    $password = "";

  }
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

    <!-- Original CSS -->
    <link rel="stylesheet" href="/css/style.css">


    <title>ログイン|WoRks</title>
  </head>
  <body class="text-center bg-green">
  <div>
   <img class="mb-4"src="/img/logo.jpeg" alt="WORKS" width="300" height="80">
  </div>

    <form class="border rounded bg-white form-login" method="post">
    <h1 class="h3 my-3">Login</h1>
  <div class="form-group pt-3">
    <input type="text" class="form-control rounded-pill <?php if(isset($err['user_no']))echo 'is-invalid';?>"name="user_no"placeholder="社員番号" required>
    <div class="invalid-feedback"><?= $err['user_no']?></div>
  </div>
  <div class="form-group">
    <input type="password" class="form-control rounded-pill <?php if(isset($err['password']))echo 'is-invalid';?>"name="password"placeholder="パスワード">
    <div class="invalid-feedback"><?= $err['password']?></div>
  </div>
  <button type="submit" class="btn btn-primary text-white rounded-pill px-5 my-4">ログイン</button>
</form>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>