<?php

// 全てのエラーを画面に表示する
error_reporting(E_ALL);
ini_set('display_errors', 'On');

//セッションに値が入っているかで処理を分けるのでセッションを使います。
session_start();

// ログインしていない場合は、ログインページへリダイレクト
if (empty($_SESSION['login'])) header("Location:login.php");

if (!empty($_FILES)) {

    // エラーメッセージ格納
    $error_msg = '';
    //アップロードした画像を表示するパス格納
    $img_path = '';
    // アップロードした際のメッセージ格納
    $msg = '';

    // 画像ファイルのパス格納
    $upload_path = $_FILES['image']['name'];

    // 画像ファイルが存在するか または 画像ファイルかどうか
    if (file_exists($upload_path) || @exif_imagetype($upload_path)) {
        $error_msg =  "画像ファイルではありません<br>（もしくはファイルが存在しません）";
    }

    if (empty($error_msg)) {

        //表示用画像パス
        $upload_path = 'images_upload/' . $upload_path;

        // 画像アップロード
        $rst = move_uploaded_file($_FILES['image']['tmp_name'], $upload_path);

        if ($rst) {
            $msg = '画像をアップしました。アップした画像ファイル名：' . $_FILES['image']['name'];
            $img_path = $upload_path; // 表示用画像パスの変数へ画像パスを入れる
        } else {
            $msg = '画像はアップ出来ませんでした。再度お試しください';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>画像アップロード</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="wrap">
        <h1 class="heading">画像アップロード</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <span class="error_msg"><?php if (!empty($error_msg)) echo $error_msg; ?></span>
            <input type="file" name="image" name="MAX_FILE_SIZE" value="30000">
            <input type="submit" value="送信">
            <a href="index.html">TOPページ</a>
        </form>

        <div class="display_img">
            <p>アップロードした画像</p>
            <p><?php if (!empty($msg)) echo $msg; ?></p>
            <img src="<?php if (!empty($img_path)) echo $img_path; ?>">
        </div>
    </div>

</body>

</html>