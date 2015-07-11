<!DOCTYPE html>
<html lang="ja">
<head>
<title>smarty date_format (php strftime) 変換指定子"G"のテスト</title>
<meta charset="UTF-8">
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div class="container-fluid">
  <div class="page-header">
    <h1>smarty date_format (php strftime) 変換指定子"G"のテスト&nbsp;<small><a href='https://twitter.com/shimabox'>@shimabox</a></small></h1>
  </div>
  <p class="text-muted">以下の確認をしたいと思います</p>
  <blockquote>
    <p>年をまたぐ週は、日数が多い方の年に属するものとする</p>
    <footer><cite title="Source Title"><a href="http://d.hatena.ne.jp/casualstartup/20130908/iso_weeknum" target="_blank"> ISO週番号の決め方とその背景 - Casual Startup - MBA/プログラマの起業日記</a></cite></footer>
  </blockquote>
  <div class="row">
    <div class="col-md-6">
      <p class="bg-primary" style="padding:10px;">月曜日基準で確認</p>
      {include file="./weeks.tpl" targetWeeks=$decemberBeginMonday}
    </div>
    <div class="col-md-6">
      <p class="bg-primary" style="padding:10px;">日曜日基準で確認</p>
      {include file="./weeks.tpl" targetWeeks=$december}
    </div>
  </div>
</div>
</body>
