<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');

if (!empty($_POST)) {

    // emailの形式チェック
    if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $_POST['email'])) {
        $error_msg = 'emailの形式で入力してください';
    }

    // メール送信プログラム
    //==============================

    // 1.フォームが全て入力されていた場合
    //include()しているので、外部のphpファイルでも呼び出し元の変数が使える。

    // emailの形式になっているか
    if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $_POST['email'])) {

        $error_msg = 'emailの形式で入力してください';
    } else if (empty($_POST['email']) && empty($_POST['subject']) && empty($_POST['comment'])) {

        $error_msg = '全て入力必須です';
    } else {

        //A.文字化けしないように設定（お決まりパターン）メール内容が文字化けしないように設定
        mb_language("Japanese"); //現在使っている言語を設定する
        mb_internal_encoding("UTF-8"); //内部の日本語をどうエンコーディング（機械が分かる言葉へ変換）するかを設定

        //B.メール送信準備
        //送信者のメールアドレスfromは〜からという意味
        $from = 'jyunya.pg.7231@gmail.com';

        //C.メールを送信（送信結果はtrueかfalseで返ってくる）
        //mb_send_mail()メソッドがメールを送信します。
        $result = mb_send_mail($to, $subject, $comment, 'From:' . $from);

        //D.送信結果を判定
        if ($result) {
            //POSTに入っている情報はいらなくなるので消します。
            unset($_POST);
            $msg = 'メールが送信されました';
        } else {
            $msg = 'メール送信に失敗しました';
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
        <h1 class="heading">お問い合わせ</h1>
        <form action="" method="post">
            <span class="error_msg"><?php if (!empty($error_msg)) echo $error_msg; ?></span>
            <input type="text" name="email" placeholder="email（必須）" value="<?php if (!empty($_POST['email'])) echo $_POST['email']; ?>">
            <input type="text" name="subject" placeholder="件名（必須）" value="<?php if (!empty($_POST['subject'])) echo $_POST['subject']; ?>">
            <textarea id="count-text" name="comment" maxlength="500" placeholder="内容（必須）"><?php if (!empty($_POST['comment'])) echo $_POST['comment']; ?></textarea>
            <p><span class="show-count-text">0</span>/500</p>
            <input type="submit" value="送信">
        </form>
        <a href="index.html">TOPページ</a>
    </div>
    <script src="main.js"></script>
</body>

</html>