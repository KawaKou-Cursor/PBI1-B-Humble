<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Creation KD-info</title>
    <!-- TailwindCSSに必要なリンク -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome CSSを追加 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- タブのアイコン設定(相対パスは非表示になるバグがあるので絶対パスで指定中) -->
    <link rel="icon" type="image/png" href="\PBI1-B-Humble\KD-infoApp\public\Components\static\AppIcon\KD-info2.png">
    <style>
        body {
            background-color: #111;
        }
    </style>
</head>

<?php
// ヘッダーのインポートと表示
include '../Components/src/renderHeader.php';
renderHeader('posting');
?>


<body class="bg-black text-white">
        <!-- 投稿一覧ボタン -->
    <form action="index.php" class="pl-1 pt-1">
        <input type="submit" value="投稿一覧へ" class="bg-black border border-white hover:bg-white hover:text-black rounded px-2 py-1">
    </form>

    <h2 class="text-xl max-w-5xl mx-auto px-4 pt-0">プログラム作品投稿フォーム</h2>
    <!-- Form バックグラウンド -->
    <div class="max-w-5xl mx-auto px-4 pt-5 pb-5">
        <div class="rounded-md shadow">
            <!-- PostForm -->
            <form action="submit.php" method="post">

                <!-- Posting Title Entry -->
                <div class="pb-2">
                    <label for="title" class="">作品のタイトル入力</label><br>
                    <input type="text" id="title" name="title" required class="w-full bg-black rounded border-2 border-white text-3xl px-2 py-2">
                </div>

                <!-- Language -->
                <div class="pl-2 pb-2">
                    <label for="language" class="">使用言語選択</label><br>
                    <!-- プルダウンメニュー表示 -->
                    <select id="language" name="language" class="bg-black border-2 border-white rounded">
                        <option value="C">C</option>
                        <option value="C++">C++</option>
                        <option value="Java">Java</option>
                        <option value="Python">Python</option>
                        <option value="JavaScript">JavaScript</option>
                        <option value="HTML">HTML</option>
                        <option value="CSS">CSS</option>
                        <option value="PHP">PHP</option>
                        <option value="Ruby">Ruby</option>
                        <option value="Swift">Swift</option>
                        <option value="Kotlin">Kotlin</option>
                        <option value="Go">Go</option>
                        <option value="Rust">Rust</option>
                        <!-- 新しく追加する言語オプション -->
                        <option value="Scala">Scala</option>
                        <option value="Haskell">Haskell</option>
                        <option value="Erlang">Erlang</option>
                        <option value="Lua">Lua</option>
                        <option value="TypeScript">TypeScript</option>
                        <option value="Perl">Perl</option>
                        <option value="Elixir">Elixir</option>
                        <option value="Clojure">Clojure</option>
                    </select>
                </div>

                <!-- Code -->
                <div class="pb-2">
                    <label for="code" class="">プログラムコード入力</label>
                    <textarea id="code" name="code" rows="9" required class="w-full bg-black rounded border-2 border-white"></textarea><br>
                </div>

                <!-- Posting Description Entry -->
                <label for="description" class="pl-0">作品の詳細説明</label>
                <textarea id="description" name="description" rows="4" required class="w-full bg-black rounded border-2 border-white"></textarea>

                <!-- 投稿ボタン -->
                <div class="flex justify-center pt-2">
                    <input type="submit" value="作品を投稿" class="bg-black border border-white hover:bg-white hover:text-black rounded px-4 py-2">
                </div>
            </form>
        </div>
    </div>
</body>

</html>