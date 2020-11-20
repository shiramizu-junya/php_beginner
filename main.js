// DOMContentLoadedは、HTMLドキュメント解析後に発火するイベント
window.addEventListener('DOMContentLoaded',
    function () {

        // テキストエリアのDOMを取得
        var node = document.getElementById('count-text');

        node.addEventListener('keyup', function () {

            // テキストの中身を取得し、その文字数（length）を数える。「.〜.〜」でつなぐことをメソッドチェーン
            // thisはイベントが発火したDOM（node）＝テキストエリアを示す。
            // ５や６などの数値が入る
            // thisは「こいつ」＝入力フォームのこと
            // valueは「値」＝入力された文字自体(文字列)です
            // lengthは「文字数」＝文字列を数えさせる命令
            //thisというのは「自分」という意味だと思って下さい。
            // jQueryの書き方Step2で紹介している例の場合、pタグがクリックされた時の処理の中でthisを使っているので、thisはpタグということになります。
            // （aタグがクリックされた時の処理内であればaタグがthisです）
            var count = this.value.length;

            // HTML５から使えるquerySelectorを使ったDOMの取得パターン
            // カウンターを表示する箇所のDOM（HTML）を取得する
            // .show-count-textのDOMを取得する
            var counterNode = document.querySelector('.show-count-text');

            // innerTextを使うと取得したDOMの中身のテキストを書き換えられる
            // innerTextで数値を変えている
            counterNode.innerText = count;

        }, false);

    }, false
);
