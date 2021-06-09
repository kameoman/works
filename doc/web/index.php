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

$pdo = connect_db();

if($_SERVER['REQUEST_METHOD']=='POST'){
  // 日報登録処理

  // 入力値をPOSTパラメーターから取得
  $target_date = $_POST['target_date'];
  $modal_start_time = $_POST['modal_start_time'];
  $modal_end_time = $_POST['modal_end_time'];
  $modal_break_time = $_POST['modal_break_time'];
  $modal_comment = $_POST['modal_comment'];



  // 対象日のデータがあるかを確認
  $sql = "SELECT id FROM work WHERE user_id = :user_id AND date = :date LIMIT 1";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':user_id',(int)$session_user['id'],PDO::PARAM_INT);
  $stmt->bindValue(':date',$target_date,PDO::PARAM_STR);
  $stmt->execute();
  $work = $stmt->fetch();



  if($work){
    // 対象日のデータがあればUPDATE
    $sql ="UPDATE work SET start_time = :start_time,end_time = :end_time,break_time = :break_time,comment = :comment WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id',(int)$work['id'],PDO::PARAM_INT);
    $stmt->bindValue(':start_time',$modal_start_time,PDO::PARAM_STR);
    $stmt->bindValue(':end_time',$modal_end_time,PDO::PARAM_STR);
    $stmt->bindValue(':break_time',$modal_break_time,PDO::PARAM_STR);
    $stmt->bindValue(':comment',$modal_comment,PDO::PARAM_STR);
    $stmt->execute();
  }else{
    // 対象日のデータが無ければINSERT
    $sql ="INSERT INTO work (user_id,date,start_time, end_time, break_time, comment)VALUES(:user_id, :date, :start_time, :end_time, :break_time, :comment)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':user_id',(int)$session_user['id'],PDO::PARAM_INT);
    $stmt->bindValue(':date',$target_date,PDO::PARAM_STR);
    $stmt->bindValue(':start_time',$modal_start_time,PDO::PARAM_STR);
    $stmt->bindValue(':end_time',$modal_end_time,PDO::PARAM_STR);
    $stmt->bindValue(':break_time',$modal_break_time,PDO::PARAM_STR);
    $stmt->bindValue(':comment',$modal_comment,PDO::PARAM_STR);
    $stmt->execute();
  }


}

// var_dump($target_date);
// exit;

// ２．ユーザーの業務日報データを取得
if(isset($_GET['m'])){
  $yyyymm = $_GET['m'];
  $day_count = date('t',strtotime($yyyymm));
}else{
$yyyymm = date('Y-m');
$day_count = date('t');
}


$sql = "SELECT date,id,start_time,end_time,break_time,comment FROM work WHERE user_id = :user_id AND DATE_FORMAT(date,'%Y-%m') = :date";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id',(int)$session_user['id'],PDO::PARAM_INT);
$stmt->bindValue(':date',$yyyymm,PDO::PARAM_STR);
$stmt->execute();
$work_list = $stmt->fetchAll(PDO::FETCH_UNIQUE);

// echo'<pre>';
// var_dump($work_list);
// echo'</pre>';
// exit;

  // 当日のデータが無ければINSERT
  $sql =  "SELECT id,start_time,end_time,break_time,comment FROM work WHERE user_id = :user_id AND date = :date LIMIT 1";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':user_id',(int)$session_user['id'],PDO::PARAM_INT);
  $stmt->bindValue(':date',date('Y-m-d'),PDO::PARAM_STR);
  $stmt->execute();
  $today_work = $stmt->fetch();

//  モーダルの自動表示判定
$modal_view_flg = TRUE;
if($today_work){
  $modal_start_time =$today_work['start_time'];
  $modal_end_time =$today_work['end_time'];
  $modal_break_time =$today_work['break_time'];
  $modal_comment =$today_work['comment'];

  if(date($modal_start_time)&&date($modal_end_time)){
    $modal_view_flg = FALSE;
  }
}else{
  $modal_start_time ='';
  $modal_end_time ='';
  $modal_break_time ='01:00';
  $modal_comment ='';
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


    <title>日報登録|WoRks</title>
  </head>
  <body class="text-center bg-light">

  <div>
   <img class="mb-4"src="/img/logo.jpeg" alt="WORKS" width="300" height="80">
  </div>

    <form class="border rounded bg-white form-time-table" action="index.php">
    <h1 class="h3 my-3">月別リスト</h1>
  　<select class="form-control rounded-pill mb-3" name="m" onchange="submit(this.form)">
      <option value="<?= date('Y-m') ?>"><?= date('Y/m') ?></option>
      <?php for($i = 1;$i <12;$i++):?>
        <?php $target_yyyymm = strtotime("-{$i}months");?>
      <option value="<?= date('Y-m',$target_yyyymm) ?>"<?php if($yyyymm == date('Y-m',$target_yyyymm))echo 'selected'?>><?= date('Y/m',$target_yyyymm) ?></option>
      <?php endfor;?>
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
      <td><button type="button" class="btn btn-default h-auto py-0" data-toggle="modal" data-target="#inputModal" data-day="<?= ($yyyymm.'-'.sprintf('%02d',$i)) ?>"><i class="fas fa-pencil-alt"></i></button></td>
    </tr>
    <?php endfor; ?>
  </tbody>
</table>

</form>



<!-- Modal -->
<form method='post'>
  <div class="modal fade" id="inputModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                <?= date('n',strtotime($yyyymm))?>/<span id= "modal_day"><?= time_format_dw(date('Y-m-d'))?></span>
            </div>
            <div class="row">
              <div class="col-sm">
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="出勤" id="modal_start_time" name="modal_start_time" value="<?= date($modal_start_time)?>">
                  <div class="input-group-prepend">
                  <button type="button" class="input-group-text" id="start_btn">打刻</button>
                  </div>
                </div>
              </div>
              <div class="col-sm">
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="退勤" id="modal_end_time" name="modal_end_time" value="<?=  date($modal_end_time)?>">
                  <div class="input-group-prepend">
                  <button type="button" class="input-group-text" id="end_btn">打刻</button>
                  </div>
                </div>
              </div>
              <div class="col-sm">
                <div class="input-group">
                <input type="text" class="form-control" placeholder="休憩" id="modal_break_time" name="modal_break_time"value="<?= $modal_break_time?>">
                </div>
              </div>
            </div>
              <div class="form-group pt-3">
                <textarea class="form-control" id="modal_comment" name="modal_comment" rows="5" placeholder="業務内容"><?= $modal_comment?></textarea>
              </div>
            </div>
          </div>
            <div class="model-footer">
              <button type="submit" class="btn btn-primary text-white rounded-pill px-5 ">登録</button>
          </div>
       </div>
    </div>
  </div>
  <input type="hidden" id="target_date" name="target_date">
</form>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <script>
    <?php if($modal_view_flg):?>
    var inputModal = new bootstrap.Modal(document.getElementById('inputModal'));
    inputModal.toggle();
    <?php endif; ?>

    $('#start_btn').click(function(){
      const now = new Date()
      const hour = now.getHours().toString().padStart(2,'0')
      const minute = now.getMinutes().toString().padStart(2,'0')
      $('#modal_start_time').val(hour+':'+minute)
    })
    $('#end_btn').click(function(){
      const now = new Date();
      const hour = now.getHours().toString().padStart(2,'0')
      const minute = now.getMinutes().toString().padStart(2,'0')
      $('#modal_end_time').val(hour+':'+minute)
    })

    $('#inputModal').on('show.bs.modal',function(event){
      var button = $(event.relatedTarget)
      var target_day = button.data('day')
      console.log(target_day)

      //  編集ボタンが押されたときに対象日のデータを取得
      var day = button.closest('tr').children('th')[0].innerText
      var start_time = button.closest('tr').children('td')[0].innerText
      var end_time = button.closest('tr').children('td')[1].innerText
      var break_time = button.closest('tr').children('td')[2].innerText
      var comment = button.closest('tr').children('td')[3].innerText

      // 取得したデータをモーダルに設定
      $('#modal_day').text(day)
      $('#modal_start_time').val(start_time)
      $('#modal_end_time').val(end_time)
      $('#modal_break_time').val(break_time)
      $('#modal_comment').val(comment)
      $('#target_date').val(target_day)
    })
    </script>

  </body>
</html>