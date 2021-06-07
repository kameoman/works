<?php
require_once(dirname(__FILE__).'/../config/config.php');
require_once(dirname(__FILE__).'/functions.php');

//１．ログイン状態をチェック
session_start();

if(!isset($_SESSION['USER'])){
  // ログインされていない場合はログイン画面へ
  header('Location:/login.php');
  exit;
}

// ログインの情報をセッションから取得
$session_user = $_SESSION['USER'];

// var_dump($session_user);
// exit;

// ２．ユーザーの業務日報データを取得
$yyyymm = date('2021-6');

$pdo = connect_db();

$sql = "SELECT date,id,start_time,end_time,break_time,comment FROM work WHERE user_id = :user_id AND DATE_FORMAT(date,'2021-6') = :date";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id',(int)$session_user['id'],PDO::PARAM_INT);
$stmt->bindValue(':date',$yyyymm,PDO::PARAM_STR);
$stmt->execute();
$work_list = $stmt->fetchAll(PDO::FETCH_UNIQUE);

// echo'<pre>';
// var_dump($work_list);
// echo'</pre>';
// exit;

// 業務日報データをテーブルにリスト表示
$day_count = date('t');

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


    <title>日報登録|WoRks</title>
  </head>
  <body class="text-center bg-light">

  <div>
   <img class="mb-4"src="/img/logo.jpeg" alt="WORKS" width="300" height="80">
  </div>

    <form class="border rounded bg-white form-time-table" action="index.php">
    <h1 class="h3 my-3">月別リスト</h1>
  　<select class="form-control rounded-pill mb-3" id="exampleFormControlSelect1">
      <option>2020/11</option>
    </select>

    <table class="table table-bordered">
  <thead>
    <tr class="bg-light">
      <th scope="col">日</th>
      <th scope="col">出勤</th>
      <th scope="col">退勤</th>
      <th scope="col">休憩</th>
      <th scope="col">業務内容</th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
    <?php for ($i = 1; $i<=$day_count;$i++):?>
      <?php
        $start_time = '';
        $end_time = '';
        $break_time = '';
        $comment = '';

        if(isset($work_list[date('Y-m-d',strtotime($yyyymm.'-'.$i))])){
          $work = $work_list[date('Y-m-d',strtotime($yyyymm.'-'.$i))];

          if($work["start_time"]){
            $start_time = date('H:i',strtotime($work['start_time']));
          }
          if($work["end_time"]){
            $end_time = date('H:i',strtotime($work['end_time']));
          }
          if($work["break_time"]){
            $break_time = date('H:i',strtotime($work['break_time']));

          }

          if($work['comment']){
            $comment = mb_strimwidth($work['comment'],0,40,'...');

          }
        }
      ?>
    <tr>
      <th scope="row"><?= time_format_dw($yyyymm.'-'.$i) ?></th>
      <td><?= $start_time ?></td>
      <td><?= $end_time ?></td>
      <td><?= $break_time ?></td>
      <td><?= $comment ?></td>
      <td><i class="fas fa-pencil-alt"></i></td>
    </tr>
    <?php endfor; ?>
  </tbody>
</table>

</form>

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
  Launch demo modal
</button>

<!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
              <p></p>
                <h5 class="modal-title" id="exampleModalLabel">日報登録</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
              <div class="container">
              <div class="alert alert-primary" role="alert">
                11/1(水)
              </div>
          <div class="row">
            <div class="col-sm">
              <div class="input-group">
                <input type="text" class="form-control" placeholder="出勤"　>
                <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">打刻</span>
                </div>
              </div>
            </div>
            <div class="col-sm">
              <div class="input-group">
                <input type="text" class="form-control" placeholder="退勤"　>
                <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">打刻</span>
                </div>
              </div>
            </div>
            <div class="col-sm">
              <div class="input-group">
              <input type="text" class="form-control" placeholder="休憩"　>
              </div>
            </div>
          </div>
            <div class="form-group pt-3">
              <textarea class="form-control" id="exampleFormControlTextarea1" rows="5" placeholder="業務内容"></textarea>
            </div>
          </div>
        </div>
          <div class="model-footer">
            <button type="button" class="btn btn-primary text-white rounded-pill px-5 mt-3">登録</button>
          </div>
    </div>
  </div>
</div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>