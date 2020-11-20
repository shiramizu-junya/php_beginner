<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');

if (!empty($_POST)) {

    $error_msg = '';

    // エラーメッセージを渡す
    function errMsg()
    {
        return '入力した情報が正しくありません。<br>確認してから再度入力をお試しください。';
    }

    // フォームに入力されているか
    if (empty($_POST['email']) || empty($_POST['pass'])) {
        $error_msg = errMsg();
    }

    // emailの形式になっているか
    if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $_POST['email'])) {
        $error_msg = errMsg();
    }

    // パスワードが半角英数字 または パスワードが６文字以上になっているか
    if (!preg_match("/^[a-zA-Z0-9]+$/", $_POST['pass'])) {
        $error_msg = errMsg();
    } else if (mb_strlen($_POST['pass']) < 6) {
        $error_msg = errMsg();
    }

    // バリデーションチェックに引っ掛からなかった場合
    if (empty($error_msg)) {

        // DBへの接続準備
        $dsn = 'mysql:dbname=php_sample01;host=localhost;charset=utf8';
        $user = 'root';
        $password = 'root';

        $option = array(
            // SQL実行失敗時に例外をスロー
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            // デフォルトフェッチモードを連想配列形式に設定
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // バッファードクエリを使う(一度に結果セットをすべて取得し、サーバー負荷を軽減)
            // SELECTで得た結果に対してもrowCountメソッドを使えるようにする
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        );

        // PDOオブジェクト生成（DBへ接続）
        $dbh = new PDO($dsn, $user, $password, $option);

        //SQL文（クエリー作成）
        $stmt = $dbh->prepare('SELECT * FROM users WHERE email = :email AND pass = :pass');

        //プレースホルダに値をセットし、SQL文を実行。
        $stmt->execute(array(':email' => $_POST['email'], ':pass' => $_POST['pass']));

        // 結果を取得
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($result)) {

            // セッションを使う
            session_start();

            //SESSION['login']に値を代入
            $_SESSION['login'] = true;

            // マイページへ遷移
            header('Location:mypage.php');
        }
    }
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>ホームページのタイトル</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="wrap">
        <h1 class="heading">ログイン</h1>
        <form action="" method="post">
            <span class="error_msg"><?php if (!empty($error_msg)) echo $error_msg; ?></span>
            <input type="text" name="email" placeholder="email" value="<?php if (!empty($_POST['email'])) echo $_POST['email']; ?>">
            <input type="password" name="pass" placeholder="パスワード" value="<?php if (!empty($_POST['pass'])) echo $_POST['pass']; ?>">
            <input type="submit" value="送信">
        </form>
        <a href="index.html">TOPページ</a>
    </div>
</body>

</html>