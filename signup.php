<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');

if (!empty($_POST)) {

    // エラーメッセージ
    define('MSG01', '入力必須です');
    define('MSG02', 'emailの形式で入力してください');
    define('MSG03', 'パスワード（再入力）が合っていません');
    define('MSG04', '半角英数字のみご利用いただけます');
    define('MSG05', '6文字以上で入力してください');
    // エラーメッセージ格納用配列
    $error_msg = array();

    // フォーム入力の確認
    function InputConfirmation($form_name)
    {
        if (empty($_POST[$form_name])) {
            global $error_msg;
            $error_msg[$form_name] = MSG01;
        }
    }

    // フォーム入力の確認
    InputConfirmation('email');
    InputConfirmation('pass');
    InputConfirmation('pass_re');

    if (empty($error_msg)) {

        // 各フォームの入力内容を格納
        $email = $_POST['email'];
        $pass = $_POST['pass'];
        $pass_re = $_POST['pass_re'];

        // emailの形式チェック
        if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email)) {
            $error_msg['email'] = MSG02;
        }

        // パスワードとパスワード再入力があっているか
        if ($pass !== $pass_re) {
            $error_msg['pass'] = MSG03;
        }

        if (empty($error_msg)) {

            // パスワードが半角英数字かどうか
            if (!preg_match("/^[a-zA-Z0-9]+$/", $pass)) {
                $error_msg['pass'] = MSG04;
            } else if (mb_strlen($pass) < 6) {
                $error_msg['pass'] = MSG05;
            }
        }

        if (empty($error_msg)) {

            // データベースへの接続準備

            // DBの設定情報を格納
            $dns = 'mysql:dbname=php_sample01;host=localhost;charset=utf8';

            // DBへ接続するユーザ名、パスワード
            $user = 'root';
            $password = 'root';

            // DBの設定
            $options = array(
                // SQL実行失敗時に例外をスロー
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                // デフォルトフェッチモードを連想配列形式に設定
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                // バッファードクエリを使う(一度に結果セットをすべて取得し、サーバー負荷を軽減)
                // SELECTで得た結果に対してもrowCountメソッドを使えるようにする
                PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
            );

            // PDOオブジェクトを生成
            // DBへ接続する情報を持ったインスタンスを生成
            $dbh = new PDO($dns, $user, $pass, $options);

            $stmt = $dbh->prepare('INSERT INTO users (email, pass, login_time) VALUES (:email, :pass, :login_time)');

            $stmt->execute(array(':email' => $email, ':pass' => $pass, ':login_time' => date('Y-m-d H:i:s ')));

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
        <h1 class="heading">ユーザー登録</h1>
        <form action="" method="post">
            <span class="error_msg"><?php if (!empty($error_msg['email'])) echo $error_msg['email']; ?></span>
            <input type="text" name="email" placeholder="email">
            <span class="error_msg"><?php if (!empty($error_msg['pass'])) echo $error_msg['pass']; ?></span>
            <input type="password" name="pass" placeholder="パスワード">
            <span class="error_msg"><?php if (!empty($error_msg['pass_re'])) echo $error_msg['pass_re']; ?></span>
            <input type="password" name="pass_re" placeholder="パスワード（再入力）">
            <input type="submit" value="送信">
        </form>
        <a href="index.html">TOPページ</a>
    </div>
</body>

</html>