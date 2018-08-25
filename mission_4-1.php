<!DOCTYPE html>
<html>
<head>
	<meta charset = "UTF-8">
	<title>おすすめの本・作家</title>
</head>
<?php
//mysqlに接続
$dsn = 'データベース名'; 
$user = 'ユーザー名'; 
$password = 'パスワード'; 
$pdo = new PDO($dsn,$user,$password);
$stmt = $pdo -> query('SET NAMES utf8');

$name = $_POST['name'];
$comment = $_POST['comment'];
$now = date("Y/m/d H:i:s");
$delete = $_POST['delete'];
$edit = $_POST['edit'];
$edit_num = $_POST['edit_num'];
$password = $_POST['password'];
$delete_pass = $_POST['delete_pass'];
$edit_pass = $_POST['edit_pass'];

//createでテーブルを作成
$sql= "CREATE TABLE list13" 
." (" 
. "id INT AUTO_INCREMENT PRIMARY KEY," 
. "name char(32)," 
. "comment TEXT," 
. "now DATETIME,"
. "password TEXT"
. ");"
; 
//->は複数行コマンドの次の行を待機している
//クエリ―はデータベースの検索、指定された条件を満たす情報を取り出すために行われる要求
$stmt = $pdo->query($sql);

//3-3
//テーブル一覧を表示するコマンド
//$sql ='SHOW TABLES'; 
//$result = $pdo -> query($sql); 
//テーブルを1行ずつ読み込む

//foreach ($result as $row){ 
	//テーブルの1区切り目を表示
	//echo $row[0]; 
	//echo '<br>'; 
//} 

//echo "<hr>";

//3-4
//$result = $pdo -> query($sql); 
//foreach ($result as $row){
//print_r($row); 
//} 
//echo "<hr>";

//3-5
//フォームに名前とコメントとパスワードが入っていたら
if(!empty($name)&&!empty($comment)&&!empty($password)){
	//hiddenの部分に編集番号が入っていたら
	if(!empty($_POST['edit_num'])&&!empty($_POST['name'])&&!empty($_POST['comment'])){
		//指定した投稿番号の名前とコメントを書き換える
		$sql = "update list13 set name='$name', comment='$comment', password = '$password' where id = $edit_num";
		$result = $pdo->query($sql);
	}else{
	//新規投稿をする
	$sql = $pdo -> prepare("INSERT INTO list13 (id,name, comment, now, password) VALUES (null, :name, :comment, :now, :password)"); 
	$sql -> bindParam(':name', $name, PDO::PARAM_STR); 
	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR); 
	$sql -> bindparam(':now',$now,PDO::PARAM_STR); 
	$sql -> bindparam(':password',$password,PDO::PARAM_STR);
	$sql -> execute();
	}
}

//削除機能
if(!empty($_POST['delete'])&&is_numeric($_POST['delete'])&&!empty($_POST['delete_pass'])){
	$sql = "select * from list13  where id = $delete";
	$result = $pdo->query($sql);
	$row2 = $result -> fetch();
	if($row2['password'] == $delete_pass){
		$sql = "DELETE FROM list13 WHERE id = $delete";
		$results = $pdo -> query($sql); 
	}
}

//編集機能　
if(isset($_POST['edit'])&&$_POST['edit']!=""&&!empty($_POST['edit_pass'])){
	$sql = "select * from list13  where id = $edit";
	$result = $pdo->query($sql);
	$row1 = $result -> fetch();
	if($row1['password'] == $edit_pass){
		$row1_id = $row1['id'];
		$row1_name = $row1['name'];
		$row1_comment = $row1['comment'];
	}
}		

//resultというテーブルがあったら削除する
$sql = "DROP TABLE IF EXISTS result[CASCADE]";
$result = $pdo->query($sql);

$sql = "DROP TABLE IF EXISTS おすすめの本・作家[CASCADE]";
$result = $pdo->query($sql);

//$id = 1; 
//$sql = "delete from list4 where id=$id";  
//$result = $pdo->query($sql);

?>
<body>
おすすめの本・作家
 <form action="mission_4-1_hasegawa.php"method="POST">
  <p><input type="text" name="name" value = "<?php echo($row1_name);?>" placeholder="名前"></p>
  <p><input type="text" name="comment" value = "<?php echo($row1_comment);?>" placeholder="コメント"></p>
  <p><input type="password" name="password" placeholder = "パスワード">
     <input type="submit"  value="送信"></p>
  <p><input type="hidden" name="edit_num" value="<?php echo ($row1_id); ?>"> </p>
  <p><input type="text" name="delete" placeholder="削除番号"></p>
  <p><input type="password" name="delete_pass" placeholder = "パスワード">
     <input type="submit" value="削除"></p>
  <p><input type="text" name="edit" placeholder="編集番号"></p>
  <p><input type="password" name = "edit_pass" placeholder = "パスワード">
     <input type="submit" value="編集"></p>
</body>

<?php
//3-6
$sql = 'SELECT * FROM list13 order by id'; 
$results = $pdo -> query($sql); 
foreach ($results as $row){    //$rowの中にはテーブルのカラム名が入る    
	echo $row['id'].'<>';    
	echo $row['name'].'<>';    
	echo $row['comment'].'<>';
	echo $row['now'].'<br>'; 
}
?>
</html>
