<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>link_link_link</title>
    </head>
    <body>
        <h1>リンク共有サイト　link_link_link</h1>
        共有したいリンク先の名前とリンクをそれぞれ入力して送信ボタンを押してください！<br>
        リンクのところをクリックすれば新規タブでリンク先が開かれます！<br>
        共有したいものがあればこちらを使っていただければ遡らずに済みます！
        <hr>
        <?php
            $number = 1;
            $title = $_POST["title"];
            $link = $_POST["link"];
            $resetnumber = $_POST["reset"];
            $editnumber = $_POST["edit"];
            $fname = "link2.txt";
            $lines = file($fname, FILE_IGNORE_NEW_LINES);
            
            if (!empty($title && $link)) {
                $data = ($editpost ?: count($lines)+1)."<>".$title."<>".$link;
                if (!empty($editpost)) {
                    foreach($lines as $line) {
                        $items = explode("<>", $line);
                        if ($items[0] == $editpost) {
                            $line = $data;
                        }
                    }
                } else {
                    $lines[] = $data;
                }
                file_put_contents($fname, implode("\n", $lines));
            } elseif (!empty($resetnumber)) {
                foreach($lines as &$line) {
                    $items = explode("<>", $line);
                    if ($items[0] == $resetnumber) {
                        $line = "";
                    }
                }
                file_put_contents($fname, implode("\n", $lines));
            } elseif (!empty($editnumber)) {
                foreach($lines as $line) {
                    $items = explode("<>", $line);
                    if ($items[0] == $editnumber) {
                        $edittitle = $items[1];
                        $editlink = $items[2];
                        break;
                    }
                }
            }
        ?>
        <form action="" method="POST">
            <input type="hidden" name="editpost" value="<?php echo $editnumber;?>">
            <input type="text" name="title" size="50" placeholder="リンク先の名前" 
            value="<?php echo $edittitle;?>">
            <input type="text" name="link" size="100" placeholder="リンク"
            value="<?php echo $editlink;?>">
            <input type="submit" name="submit">
        </form>
        <form action="" method="POST">
            <input type="number" name="reset" placeholder="削除対象番号">
            <input type="submit" value="削除">
        </form>
        <form action="" method="POST">
            <input type="number" name="edit" placeholder="編集対象番号">
            <input type="submit" value="編集">
        </form>
        <hr>
        <?php 
            if (file_exists($fname)) {
                $lines = file($fname, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach($lines as $line) {
                    $items = explode("<>", $line);
                    echo "  ".$items[0]."   ".$items[1]."   "."<a href=".$items[2]." target=_blank>"
                    .$items[2]."</a>"."<br>"."<br>";
                }
            }
        ?>
    </body>
</html>