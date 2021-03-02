<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>mission_5-1</title>
    </head>
    <body>
        
         <h1>掲示板</h1>
        簡易的な掲示板です。ファイル保存ではなくデータはデータベースに保存されます。
        <h2>ルール</h2>
        <ol>
            <li>名前とコメントを記入後、パスワードを設定してから送信を押してください</li>
            <li>削除及び編集機能は入力したパスワードと保存したパスワードが
            一致しなければ実行されません</li>
            <li>削除済みの番号とそれに付随するパスワードを入力すると番号がズレるのでおやめください</li>
        </ol>
        <hr>
        
        <?php
            
            //DB接続設定
            $dsn = 'データベース名';
            $user = 'ユーザー名';
            $dbpassword = 'データベースパスワード';
            $pdo = new PDO($dsn, $user, $dbpassword, 
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
            
            //データを登録するためのテーブルを作成
            $sql = "CREATE TABLE IF NOT EXISTS mission_5_1"
            ." ("
            . "id INT AUTO_INCREMENT PRIMARY KEY,"
            . "name char(32),"
            . "date char(32),"
            . "password char(32)," //パスワード保存用
            . "comment TEXT"
            .");";
            $stmt = $pdo->query($sql);
        
            //新規投稿か編集かの条件分岐
            if (isset($_POST["normal"]) && !empty($_POST["password"])) {
                //編集モード1
                if (!empty($_POST["editpost"])) {
                    $sql = 'SELECT * FROM mission_5_1';
                    $stmt = $pdo->query($sql);
                    $results = $stmt->fetchAll();
                    foreach($results as $row) {
                        if ($row['id'] == $_POST["editpost"]) {
                            $id = $_POST["editpost"];
                            $name = $_POST["name"];
                            $comment = $_POST["comment"];
                            $date = date("Y/m/d H:i:s");
                            $password = $_POST["password"];
                            $sql = 'UPDATE mission_5_1 SET name=:name,comment=:comment,date=:date,password=:password WHERE id=:id ';
                            $stmt = $pdo->prepare($sql);
                            $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
                            $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
                            $stmt -> bindParam(':date', $date, PDO::PARAM_STR);
                            $stmt -> bindParam(':password', $password, PDO::PARAM_STR);
                            $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
                            $stmt -> execute();
                        }
                    }
                } else {
                    //新規投稿モード
                    $sql = $pdo -> prepare("INSERT INTO mission_5_1 (name, comment, date, password) 
                    VALUES (:name, :comment, :date, :password)");
                    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                    $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                    $sql -> bindParam(':password', $password, PDO::PARAM_STR);
                    $name = $_POST["name"];
                    $comment = $_POST["comment"];
                    $date = date("Y/m/d H:i:s");
                    $password = $_POST["password"];
                    $sql -> execute();
                }
            } elseif (isset($_POST["reset"]) && !empty($_POST["resetpassword"])) {
                //削除モード
                if (isset($_POST["resetnumber"])) {
                    $sql = 'SELECT * FROM mission_5_1';
                    $stmt = $pdo->query($sql);
                    $results = $stmt->fetchAll();
                    foreach($results as $row) {
                        if ($row['id'] == $_POST["resetnumber"] && $row['password'] == $_POST["resetpassword"]) {
                            $id = $_POST["resetnumber"];
                            $sql = 'delete from mission_5_1 where id=:id';
                            $stmt = $pdo -> prepare($sql);
                            $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
                            $stmt -> execute();
                        }
                    }
                }
            } elseif (isset($_POST["edit"]) && !empty($_POST["editpassword"])) {
                //編集モード2
                $sql = 'SELECT * FROM mission_5_1';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach($results as $row) {
                    if ($row['id'] == $_POST["editnumber"] && $row['password'] == $_POST["editpassword"]) {
                        $editnumber = $_POST["editnumber"];
                        $editname = $row['name'];
                        $editcomment = $row['comment'];
                        $savepassword = $row['password'];
                        break;
                    }
                }
            }
        ?>
        
        <form action="" method="POST">
            <input type="hidden" name="editpost" value="<?php echo $editnumber;?>">
            <input type="hidden" name="passwordpost" value="<?php echo $password;?>">
            <input type="text" name="name" placeholder="名前" 
            value="<?php echo $editname;?>">
            <input type="text" name="comment" placeholder="コメント" value="<?php echo $editcomment;?>">
            <input type="text" name="password" placeholder="パスワード保存"
            value="<?php echo $savepassword;?>">
            <input type="submit" name="normal" value="送信">
        </form>
        <form action="" method="POST">
            <input type="number" name="resetnumber" placeholder="削除対象番号">
            <input type="text" name="resetpassword" placeholder="パスワード入力">
            <input type="submit" name="reset" value="削除">
        </form>
        <form action="" method="POST">
            <input type="number" name="editnumber" placeholder="編集対象番号">
            <input type="text" name="editpassword" placeholder="パスワード入力">
            <input type="submit" name="edit" value="編集">
        </form>
        
        <hr>
        <?php
            //表示して確認
            $sql = 'SELECT * FROM mission_5_1';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach($results as $row) {
                echo $row['id'].',';
                echo $row['name'].',';
                echo $row['comment'].',';
                echo $row['date'].'<br>';
            }
        ?>
        
    </body>
</html>