<!DOCTYPE html>
<html lang="en">

	<head>
	<meta charset="utf-8"/>
	<title>見本</title>
	</head>

	<body>

	<?php

	//まずはデータベースへの接続を行う。
	$dsn=‘'データベース名;
	$user='ユーザー名';
	$password='パスワード';
	$pdo= new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE =>
	PDO::ERRMODE_WARNING));

	//データベース内にテーブルを作成する。
	$sql="CREATE TABLE IF NOT EXISTS tbtest2"
	."("
	."id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,"
	."name char(32) NOT NULL,"
	."comment TEXT NOT NULL,"
	."date TEXT,"
	."password char(14) NOT NULL"
	.");";
	$stmt=$pdo->query($sql);


	//テーブル一覧を表示するコマンドを使って作成が出来たか確認する。
	$sql='SHOW TABLES';
	$result=$pdo->query($sql);
	foreach($result as $row)
	{
		echo $row[0];
		echo'<br>';
	}
	echo "<hr>";

	//テーブルの中身を確認するコマンドを使って、意図した内容のテーブルが作成されているか確認する。
	$sql='SHOW TABLES';
	$result=$pdo->query($sql);
	foreach($result as $row)
	{
		echo $row[0];
		echo'<br>';
	}
	echo "<hr>";



	//入力したデータをupdateによって編集する。
	$hensyu = $_POST["hensyu"];
	$pass3 = $_POST["pass3"];

	if(!empty($hensyu)==true and !empty($pass3)==true)

	{ //編集機能①
		$id=$_POST["hensyu"];
		$password=$_POST["pass3"];
		$sql="SELECT*FROM tbtest2 where id='$hensyu';";
		$stmt=$pdo->query($sql);
		
		foreach($stmt as $row)
		{
			if($id==$row[0] && $password==$row[4])
			{
				$secret = $row['id'];
				$beforename = $row['name'];
				$beforecomment = $row['comment'];
			}
		}
	}

	?>


	<h1>簡易掲示板</h1>

	<form method = "POST" action = "mission_4-1_Suzuki.php">

	氏名記入欄:<br/>
	<input type = "text" name = "name" placeholder="名前" value="<?php echo $beforename; ?>"><br>
	コメント欄:<br/>
	<input type = "text" name = "comment" placeholder="コメント" value="<?php echo $beforecomment; ?>"><br>
	パスワード:<br/>
	<input type = "password" name="password" size="10" maxlength="10" placeholder="パスワード">
	<input type = "submit" name ="sub" value ="送信"><br>
	<input type="hidden" name="secret" value="<?php echo $secret; ?>"><br>
	<br/>

	削除対象番号:<br/>
	<input type = "text" name ="delete" placeholder="削除対象番号"><br>
	パスワード:<br/>
	<input type = "password" name="pass2" size="10" maxlength="10" placeholder="パスワード">
	<input type = "submit" name ="sub2" value ="削除"><br>
	<br/>

	編集対象番号:<br/>
	<input type = "text" name = "hensyu" placeholder="編集対象番号"><br>
	パスワード:<br/>
	<input type = "password" name="pass3" size="10" maxlength="10" placeholder="パスワード">
	<input type = "submit" name ="sub3" value ="編集">

	</form>



	<?php

	$name = $_POST["name"];
	$comment = $_POST["comment"];
	$date = date("Y/m/d/H:i:s");
	$password = $_POST["password"];
	$delete = $_POST["delete"];
	$pass2 = $_POST["pass2"];
	$secret = $_POST["secret"];

	if(!empty($name)==true and !empty($comment)==true and !empty($secret)==true)
	{//編集②

		$id = $_POST["secret"];
		$name=$_POST["name"];
		$comment=$_POST["comment"];
		$date = date("Y/m/d/H:i:s");
		$password = $_POST["password"];

		$sql='update tbtest2 set name=:name,comment=:comment,date=:date where id=:id AND password=:password';
		$stmt=$pdo->prepare($sql);
		$stmt->bindParam(':name',$name,PDO::PARAM_STR);
		$stmt->bindParam(':comment',$comment,PDO::PARAM_STR);
		$stmt->bindParam(':date',$date,PDO::PARAM_STR);
		$stmt->bindParam(':id',$id,PDO::PARAM_INT);
		$stmt->bindParam(':password',$password,PDO::PARAM_STR);
		$stmt->execute();
	}



	//入力したデータをdeleteによって削除する。
	if(!empty($delete)==true and !empty($pass2)==true)
	
	{ //削除機能
		$id=$_POST["delete"];
		$password = $_POST["pass2"];
		$sql='delete from tbtest2 where id=:id AND password=:password';
		$stmt=$pdo->prepare($sql);
		$stmt->bindParam(':id',$id,PDO::PARAM_INT);
		$stmt->bindParam(':password',$password,PDO::PARAM_INT);
		$stmt->execute();
	}



	//作成したテーブルにinsertを行って、データを入力する。
	$name = $_POST["name"];
	$comment = $_POST["comment"];
	$date = date("Y/m/d/H:i:s");
	$password = $_POST["password"];
	$secret = $_POST["secret"];


	if(trim($name)==false or trim($comment)==false or trim($password)==false)
	{ //投稿機能
		$name != $_POST["name"];
		$commnet != $_POST["comment"];
		$password != $_POST["password"];
	}

	elseif(empty($secret)==true)
	{
		$sql = $pdo -> prepare("INSERT INTO tbtest2 (name,comment,date,password) VALUES (:name,:comment,:date,:password)");
		$sql -> bindParam(':name', $name, PDO::PARAM_STR); 
		$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
		$sql -> bindParam(':date', $date, PDO::PARAM_STR); 
		$sql -> bindParam(':password', $password, PDO::PARAM_STR); 

		$name = $_POST["name"];
		$comment = $_POST["comment"];
		$date = date("Y/m/d/H:i:s");
		$password = $_POST["password"];
		$sql->execute();
	}


	//入力したデータをselectによって表示する

	$sql='SELECT*FROM tbtest2 ORDER BY id' ;
	$stmt=$pdo->query($sql);
	$results=$stmt->fetchAll();
	foreach($results as $row)
	{
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['date'].'<br>';
	}



	?>

	</body>
<html>
	