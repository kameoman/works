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
  <body class="text-center bg-secondary">

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
      <th scope="col"><i class="fas fa-pencil-alt"></i></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">1(水)</th>
      <td>09:00</td>
      <td>18:00</td>
      <td>01:00</td>
      <td>テスト</td>
      <td></td>
    </tr>
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
                <input type="text" class="form-control" placeholder="出勤"　>
            </div>
            <div class="col-sm">
                <input type="text" class="form-control" placeholder="退勤"　>
            </div>
            <div class="col-sm">
              <input type="text" class="form-control" placeholder="休憩"　>
            </div>
          </div>
            <div class="form-group pt-3">
              <textarea class="form-control" id="exampleFormControlTextarea1" rows="5" placeholder="業務内容"></textarea>
            </div>
          </div>
        </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary text-white rounded-pill px-5 ">登録</button>
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