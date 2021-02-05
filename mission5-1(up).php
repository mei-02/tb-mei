<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>mission5-1</title>
    </head>
</html>
<body>
    <?php
    // DB接続設定
	$dsn = 'データベース名';
	$user = '名前';
	$pass = 'パスワード';
	
    // データベースへ接続する
    $pdo= new PDO($dsn, $user, $pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
   
    //テーブル作成
    $sql = "CREATE TABLE IF NOT EXISTS mission5_1"
    ." ("
    ."id INT AUTO_INCREMENT PRIMARY KEY,"
    ."name char(32),"
    ."comment TEXT,"
    ."date datetime,"
    ."password char(32)"
    .");";
    $stmt = $pdo -> query($sql);

    //定数の設定
    $date0= date("Y/m/d H:i:s");
    $editnum="";
    $editname="";
    $editcom="";
    $editpas="";
    
    //削除押された時
    if(isset($_POST["clear"])){
        if(empty($_POST["del"])){
            echo "編集対象番号を入力してください<br>";
        }
        elseif(empty($_POST["delpass"])){
            echo "パスワードを入力してください<br>";
        }
        else{
            //idとpasをバインドする変数に代入
            $delnum=$_POST["del"];
            $delpass=$_POST["delpass"];
            //DBから一致するものを削除
            $sql = 'DELETE FROM mission5_1 WHERE id=:id AND password=:password';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $delnum, PDO::PARAM_INT);
            $stmt->bindParam(':password', $delpass, PDO::PARAM_STR);
            $stmt->execute();
        } 
    }

    //編集押された時
    if(isset($_POST["edit"])){
        if(!empty($_POST["editnum"])&&!empty($_POST["editpass"])){
            //idとpasをバインドする変数に代入
            $id=$_POST["editnum"];
            $password=$_POST["editpass"];
            //DBから一致するものを取り出す
            $sql = 'SELECT * FROM mission5_1 WHERE id=:id AND password=:password';
            $stmt = $pdo->prepare($sql);
            //プレースホールダーに表示
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->execute();
            //全ての行の内容を取り出す
            $editdata= $stmt->fetchAll();
            foreach ($editdata as $data) {
                $editnum = $data['id'];
                $editname = $data['name'];
                $editcom = $data['comment'];
                $editpas=$data['password'];
            }
        }
        elseif(!empty($_POST["editnum"])&&empty($_POST["editpass"])){
            echo "パスワードを入力してください<br>";
        }
        elseif(empty($_POST["editnum"])){
            echo "編集対象番号を入力してください<br>";
        }
    }

    //送信(submit)押された時
    if(isset($_POST["submit"])){
        if(empty($_POST["name"])){
            echo "名前を入力してください<br>";
        }
        elseif(empty($_POST["comment"])){
            echo "コメントを入力してください<br>";
        }
        elseif(empty($_POST["pass"])){
            echo "パスワードを入力してください<br>";
        }
        else{
        //番号記入されてない時
            if(empty($_POST["renum"])){
                //変数に代入
                $name = $_POST["name"];
                $comment = $_POST["comment"];
                $password = $_POST["pass"];
                $date=$date0;
                //投稿内容をテーブルに入力
                $stmt = $pdo->prepare("INSERT INTO mission5_1 (name, comment, date, password) VALUES (:name, :comment, :date, :password)");
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                $stmt -> execute();
            }
            //番号が記入されている時
            else{
                //変数に代入
                $id = $_POST["renum"];
                $name = $_POST["name"];
                $comment = $_POST["comment"];
                $password = $_POST["pass"];
                //idが一致する投稿内容を変更する
                $sql = 'UPDATE mission5_1 SET name=:name,comment=:comment,password=:password WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                //変更内容
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            }
        }
    }
  
    ?>

        <form action="" method="post">
            <input type="text" name="name" placeholder="名前"value="<?php if(isset($editname)){echo $editname;} ?>" ><br>
            <input type="text" name="comment" placeholder="コメント" value="<?php if(isset($editcom)){echo $editcom;}; ?>"><br>
            <input type="txt" name="pass" placeholder="パスワード"value="<?php echo $editpas; ?>">
            <input type="hidden" name="renum" value="<?php if(isset($editnum)){echo $editnum;}; ?>">
            <input type="submit" name="submit"><br><br>
            <input type="number" name="del" placeholder="削除対象番号"><br>
            <input type="txt" name="delpass" placeholder="パスワード">
            <input type="submit" name="clear" value="削除"><br><br>
            <input type="number" name="editnum" placeholder="編集対象番号"><br>
            <input type="txt" name="editpass"placeholder="パスワード">
            <input type="submit" name="edit" value="編集"><br>
        </form>
    <?php
    //テーブル表示
    $show_sql = "SELECT * FROM mission5_1";
    //sql実行
    $show_stmt = $pdo->query($show_sql);
    $results = $show_stmt->fetchAll();
    foreach($results as $result){
        echo "投稿番号:". $result['id'].'<br>';
        echo "投稿者:" . $result['name'].'<br>';
        echo "コメント:". $result['comment'].'<br>';
        echo "投稿日時:" . $result['date'].'<br>';
        echo "<hr>";
    }
    
    /*
    //テーブル表示
    $show_sql = "SELECT*FROM mission5_1";
    //sql実行
    $show_stmt = $pdo->query($show_sql);
    $shows = $show_stmt->fetchAll();
    foreach($shows as $show){
        echo $show['id'].'<br>';
        echo "投稿者:" . $show['name'].'<br>';
        echo "投稿日時:" . $show['date'].'<br>';
        echo $show['comment'].'<br>';
        echo "<hr>";
    }

    //行番号用変数を用意
    $i=1;
    //データベースのテーブルすべて読み出すまでループ
    while($table_rec = $table_stmt->fetch(PDO::FETCH_ASSOC)){
        //連想配列すべてを読み出すまでループ
        foreach($table_rec as $key => $val){
            //番号とテーブル名とキーを表示
            print '<tr><td>'.$i.'</td><td>'.$val.'('.$key.')</td></tr>';
            $i+=1;
        }
    }
    print '</table>';
    */

    ?>
</body>  
</html>