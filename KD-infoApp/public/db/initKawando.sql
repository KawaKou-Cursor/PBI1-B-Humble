# クライアントから送信される文字コードをutf8に設定
set names utf8;
# もし「prosite」というデータベースが存在すれば削除
drop database if exists prosite;
# prositeというデータベースを作成
create database prosite character set utf8 collate utf8_general_ci;
# ユーザー「kobe」にパスワード「denshi」を設定し、データベース「prosite」に対する全ての権限を付与
grant all privileges on prosite.* to kobe@localhost identified by 'denshi';
# データベース「prosite」を使用
use prosite;
# テーブル「users」を作成
create table users(
    user_id int auto_increment primary key ,
    user_name varchar(255) not null,
    user_pass varchar(1000) not null,
    email_address varchar(255) UNIQUE,
    profile_title varchar(100) ,
    profile_text varchar(1000)
);
# テーブル「questions」を作成
create table questions(
    question_id int auto_increment primary key,
    user_id int not null,
    question_title varchar(100) not null,
    question_text varchar(1000) not null,
    question_good int default 0,
    question_code text,
    question_image_name varchar(100) default null,
    question_image mediumblob default null,
    question_time timestamp default current_timestamp,
    -- 外部キー制約
    FOREIGN KEY (user_id) REFERENCES users (user_id)
    -- 参照先をupdate/deleteした際はエラーを返す
    ON DELETE RESTRICT ON UPDATE RESTRICT
);
# テーブル「posts」を作成
create table posts(
    post_id int auto_increment primary key,
    user_id int not null,
    post_title varchar(100) not null,
    post_text varchar(1000) not null,
    post_good int(3) default null,
    post_code text,
    post_image_name varchar(100) default null,
    post_image mediumblob default null,
    post_time timestamp default current_timestamp,
    -- 外部キー制約
    FOREIGN KEY (user_id) REFERENCES users (user_id)
    -- 参照先をupdate/deleteした際はエラーを返す
    ON DELETE RESTRICT ON UPDATE RESTRICT
);
# テーブル「replies」を作成
create table replies(
    reply_id int auto_increment primary key,
    user_id int not null,
    question_id int,
    post_id int,
    reply_text varchar(1000) not null,
    reply_best_answer boolean default false,
    reply_time timestamp default current_timestamp,
    reply_good int default 0,
    -- 外部キー制約 user_id
    FOREIGN KEY (user_id) REFERENCES users (user_id)
    -- 参照先をupdate/deleteした際はエラーを返す
    ON DELETE RESTRICT ON UPDATE RESTRICT,
    -- 外部キー制約 question_id
    FOREIGN KEY (question_id) REFERENCES questions (question_id)
    -- 参照先をupdate/deleteした際はエラーを返す
    ON DELETE RESTRICT ON UPDATE RESTRICT,
    -- 外部キー制約 post_id
    FOREIGN KEY (post_id) REFERENCES posts (post_id)
    -- 参照先をupdate/deleteした際はエラーを返す
    ON DELETE RESTRICT ON UPDATE RESTRICT
);



# kawandoDB demo deta

# テーブル「users」にデータを挿入
insert into users (user_name, user_pass, email_address, profile_title, profile_text)
values ('densuke', '$2y$10$ocv0VLCWjSgoXjCHNeWuVueGpq8MI.ETE7Q9YQkmkHXfY7Lf8fXka', 'densuke@kobedenshi.ac.jp', 'NW大好きクラブ名誉会長', 'ソフトⅤコース６組の伝助です。NWの勉強が大好きです。よろしくお願いします！')
,('tsubo tea', '$2y$10$zk4LbDbdSmNr6J/FKSkePe3ThIq5dHaH84SGCxYTc3O6TMN3GFG3m', 'tsubo@kobedenshi.ac.jp', '神戸電子が生んだ異端児', 'ソフトⅠコース２組の坪ノ内です。DBが好きです！普段からDBのことしか考えてないです。DB以外のことに興味はありません！')
,('PonkotsuCoding','$2y$10$dBsdsMwAewDcKwO2J3gtjeaQMw1VWkmJMYJWLojAGTiO9pLLSjtAS' ,'ponpon@kobedenshi.ac.jp', 'プログラミングは日課！', 'ソフトⅧコース１組の碰骨と申します。毎日プログラミングをすることが日課で、今は就職先で使うCOBOLとBrainfuckを勉強中です。' )
,('fukuokaAI','$2y$10$GLCoFisRGFb3B77WTOpAnOwONQCXbQTr7UKMztJFhKZE/SzHo4fia' ,'huku@kobedenshi.ac.jp', 'Web開発と人工知能を愛してます！', 'みなさんこんにちは！プログラミングと旅行が大好きで、特にWeb開発と人工知能に興味があります。寝る間を惜しんで開発しているときやディープラーニングを学んでいるときに生を実感します。ぜひ私とともに最高の作品を作りましょう！' );

# densukeのハッシュ化前パスワード→Denden1234
# tsubo teaのハッシュ化前パスワード→teaOcha1234
# PonkotsuCodingのハッシュ化前パスワード→Ponkotsu1234
# fukuokaAIのハッシュ化前パスワード→fukufuku

# 投稿テーブルの情報は金城DBに保存されています。川人DBのuser_idを金城DBのpostsに保存するようにする。

# 質問テーブルへ仮データ追加
insert into questions(user_id, question_title, question_text, question_good)
values ('1', ' Pythonでのリストの使い方がわからない', '初心者です。Pythonでリストを使いたいのですが、基本的な操作方法（追加、削除、ソートなど）を教えてください。', '0')
, ('1', ' JavaScriptの非同期処理について', 'JavaScriptの非同期処理（Promise、async/await）について詳しく知りたいです。具体的な例を交えて説明してもらえると助かります。', '3')
, ('2', 'SQLクエリの最適化について', '大量のデータを扱うSQLクエリが遅くて困っています。パフォーマンスを向上させるための一般的な最適化手法を教えてください。', '5')
, ('2', 'Gitのマージコンフリクトの解消方法', 'Gitでマージコンフリクトが発生した時の対処方法がよくわかりません。具体的な解決手順と注意点を教えてください。', '8')
, ('3', 'TypeScriptの型システムについて', 'TypeScriptを使い始めたのですが、型システムの使い方がいまいち理解できません。基本的な型定義やカスタム型の作り方を教えてください。', '2');

# 返信テーブルへ質問に関連する仮データ追加
insert into replies(user_id, question_id, reply_text, reply_good)
values ('2', '1', 'リストの作成
リストは角括弧を使って作成し、複数の要素を持つことができます。
要素には数値や文字列など、様々なデータ型が含まれます。
要素の追加
リストに要素を追加する方法として、「append」と「insert」があります。
「append」はリストの末尾に要素を追加し、「insert」は指定した位置に要素を挿入します。
要素の削除
リストから要素を削除する方法として、「remove」と「pop」があります。
「remove」は指定した値を持つ要素を削除し、「pop」は指定した位置の要素を削除します。位置を指定しない場合、「pop」は末尾の要素を削除します。
リストのソート
リストを昇順または降順に並べ替える方法として、「sort」と「sorted」があります。
「sort」はリスト自体を並べ替え、「sorted」は並べ替えた新しいリストを返します。
以上の方法でできるはずです。頑張ってください！', '5')
, ('3', '3', '適切なクエリの書き方
SELECT: 使用する列を指定して、不要な列を選択しない。
JOIN: 必要なJOINのみを使用し、無駄なJOINを避ける。
WHERE: WHERE句を使用して、データをフィルタリングし、必要なデータのみを取得する。
クエリの分析
・クエリプランを確認して、クエリがどのように実行されているかを分析します。これにより、ボトルネックを特定し、最適化の方向性を見つけることができます。EXPLAINコマンドなどを使うと、クエリの実行計画を確認できます。
正規化と非正規化のバランス
・データベース設計の際に、正規化によってデータの重複を排除し、データの整合性を保つことが重要ですが、クエリのパフォーマンスが問題になる場合、非正規化を検討することもあります。特に、頻繁に実行される読み取り専用のクエリに対しては効果的です。
キャッシュの活用
・頻繁に使用されるデータをキャッシュすることで、データベースへのアクセス回数を減らし、クエリのパフォーマンスを向上させることができます。アプリケーションレベルやデータベースレベルでキャッシュを実装することが考えられます。
ハードウェアのアップグレード
・ハードウェアの性能がクエリのパフォーマンスに直接影響するため、必要に応じてCPU、メモリ、ディスクの性能を向上させることも検討します。
まずはクエリの実行計画を確認し、最も効果的な最適化手法を選択してください。すると効率化すると思います。', '2')
, ('3', '4', '１．マージコンフリクトの確認
マージを試みた際にコンフリクトが発生すると、Gitはどのファイルにコンフリクトがあるかを教えてくれます。例えば、git status コマンドを使うと、コンフリクトが発生したファイルがリスト表示されます。
２．コンフリクト箇所の特定
コンフリクトが発生したファイルを開くと、Gitが自動的にコンフリクト箇所を示すマーカー（<<<<<<, ======, >>>>>>）が挿入されています。このマーカーを使って、どこにコンフリクトがあるのか確認します。
３．コンフリクトの解消
コンフリクト箇所を確認したら、どの変更を採用するか、もしくは手動でマージする内容を決定します。必要な変更を行い、マーカーを削除してファイルを保存します。
４．修正内容のステージング
コンフリクトを解消したら、変更をステージングします。git add <ファイル名> を使って、修正したファイルをステージします。
５．マージの完了
すべてのコンフリクトが解消され、修正したファイルがステージングされると、git commit コマンドを使ってマージを完了させます。既にマージ用のメッセージが用意されているので、そのままコミットできます。
以上がGitのマージコンフリクトを解消するための基本的な手順と注意点です。
慣れるまでは少し手間取るかもしれませんが、落ち着いて一つずつ対処すれば大丈夫です！', '8');
