<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission5</title>
</head>
<?php

    $edname = "";
    $ednumber = "";
    $edmessage = "";
	$dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
	$sql = "CREATE TABLE IF NOT EXISTS mission5"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
    . "message TEXT,"
    . "date datetime,"
    . "pass char(100) "
    .");";
    $stmt = $pdo->query($sql);

    //新規投稿
    if(!empty($_POST["name"]) && !empty($_POST["message"]) && !empty($_POST["pass"]) && empty($_POST["ednum1"])){
        $sql = $pdo -> prepare("INSERT INTO mission5 (name, message, pass, date) VALUES (:name, :message, :pass, :date )");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':message', $message, PDO::PARAM_STR);
        $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
        $name = $_POST["name"]; //入力された名前、コメント、パスワードをテーブルに追加
        $message = $_POST["message"]; 
        $pass = $_POST["pass"];
        $date = date("Y/m/d H:i:s");
        $sql -> execute();
    }

    //削除
    if(!empty($_POST["rmnum"]) && !empty($_POST["rmpass"])){
        $id = $_POST["rmnum"];
        $rmpass = $_POST["rmpass"];
        $sql = 'select * from mission5';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach($results as $row){
                if($row['id'] == $id){
                    if($row['pass'] == $rmpass){
                                $sql = 'delete from mission5 where id=:id';
                                $stmt = $pdo->prepare($sql);
                                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                                $stmt->execute();
                    }
                }
            }
    }
    //編集フォームに表示させるやつ
    if(!empty($_POST["ednum"]) && !empty($_POST["edpass"])){
        $ednum = $_POST["ednum"];
        $edpass = $_POST["edpass"];
        $sql = 'select * from mission5';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach($results as $row){
                if($row['id'] == $ednum){
                    if($row['pass'] == $edpass){

                        $ednumber = $row['id'];
                        $edname = $row['name'];
                        $edmessage = $row['message'];
                    }
                }
            }  
    }
    //編集
    if(!empty($_POST["name"]) && !empty($_POST["message"]) && !empty($_POST["pass"]) && !empty(["ednum1"])){
        $date = date("Y/m/d H:i:s");
        $id = $_POST["ednum1"]; //変更する投稿番号
        $name = $_POST["name"];
        $pass = $_POST["pass"];
        $message = $_POST["message"]; //変更したい名前、変更したいコメント
        $sql = 'SELECT * FROM mission5 ';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach($results as $row){
                if($row['id'] == $id){
                    if($row['pass'] == $pass){
                        $sql = 'UPDATE mission5 SET name=:name,message=:message, date=:date WHERE id=:id '; 
                        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':message', $message, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->execute();
                    }
                }
            }
    }
    ?>
    <body>
    <p>【 新規投稿・編集フォーム 】</p>
    <form action="" method="post">
        <input type="text" name="name" placeholder="名前" value = "<?php echo $edname;?>" >
        <input type="text" name="message" placeholder="コメント" value = "<?php echo $edmessage;?>">
        <input type="text" name="ednum1" value="<?php echo $ednumber;?>">
        <input type="text" name="pass" placeholder="パスワード" value="">
        <input type="submit" name="add" value="送信">
    </form>
    <br>
    <P>【 削除フォーム 】</P>
    <form action = "" method = "post">
        <input type = "number" name = "rmnum" placeholder = "削除対象番号" value = "">
        <input type="text" name="rmpass" placeholder="パスワード" value="">
        <input type="submit" name="remove" value = "削除">
    </form>
    <br>
    <P>【 編集フォーム 】</P>
    <form action="" method="post">
        <input type="text" name="ednum" placeholder="編集対象番号" value="">
        <input type="text" name="edpass" placeholder="パスワード" value="">
        <input type="submit" name="edit" value="編集">
    </form>
    <p>
    </body>
    <?php
    //出力
    $sql = 'SELECT * FROM mission5';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
        echo $row['message'].',';
        echo $row['date']."<br>";
	    echo "<hr>";
    }
?>
</body>
</html>

