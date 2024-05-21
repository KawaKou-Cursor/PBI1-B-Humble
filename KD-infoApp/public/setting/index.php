<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Settings KD-info</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <!-- Font Awesome CSSを追加 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="icon" type="image/png" href="\PBI1-B-Humble\KD-infoApp\public\Components\static\AppIcon\KD-info2.png">

  <style>
    body {
      background-color: #111;
    }
  </style>
</head>

<body>
  <?php
  session_start(); // セッションを開始または継続
  include '..\Components\src\renderHeader.php';
  renderHeader('setting'); // アクティブページを指定
  ?>

  <div class="container mx-auto pt-5 pl-40 pr-40">
    <div class="text-left">
      <h1 class="pb-3 text-white text-2xl font-bold pt-6">設定変更</h1> <!-- テキストサイズを変更 -->
      <h2 class="text-white text-xl font-semibold py-4">パスワードの変更はこちら</h2> <!-- テキストサイズを変更 -->

      <!-- パスワード更新ページへのリンクボタン -->
      <a href="passwordUpdate.php" class="bg-red-600 hover:bg-red-800 text-white font-bold py-2 px-4 w-full rounded focus:outline-none focus:shadow-outline">
        パスワードを変更する
      </a>
    </div>
  </div>

</body>

</html>