﻿<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Content-style-Type" dontent="text/css">
<title>PM学科専用闇キャンパスポータル</title>
<link rel="stylesheet"href="style1.css" type="text/css">
<style type="text/css">a { text-decoration: none; }</style>
<title>検索結果</title>
<meta charset="utf-8">
</head>
<body>
<body link="#000000" vlink="#000000" alink="000000">
<table width="100%" align="center" frame="void" rules="none" border="2"  bordercolor="#bdb76b" bgcolor="#ffffff" >
<tr>
<td align="center" valign="middle">
<a href="select.php"><font size="7"><b>検索結果</b></font></a>
</td>
</tr>
</table>
<?php
header("Content-type: text/html; charset=utf-8");

if(empty($_POST)) {
	header("Location: select.php");
	exit();
}else{
	//名前入力判定
	if (!isset($_POST['yourname'])  || $_POST['yourname'] === "" ){
		$errors['name'] = "名前が入力されていません。";
	}
}

if(count($errors) === 0){
	
require_once 'database_conf.php';
try{
	$dbh = new PDO($dsn, $dbUser, $dbPass);
	$statement = $dbh->prepare("SELECT * FROM posts WHERE teacher LIKE (:teacher) ");
		if($statement){
			$yourname = $_POST['yourname'];
			$like_yourname = "%".$yourname."%";
			//プレースホルダへ実際の値を設定する
			$statement->bindValue(':name', $like_yourname, PDO::PARAM_STR);
			
			if($statement->execute()){
				//レコード件数取得
				$row_count = $statement->rowCount();
				
				while($row = $statement->fetch()){
					$rows[] = $row;
				}
				
			}else{
				$errors['error'] = "検索失敗しました。";
			}
			
			//データベース接続切断
			$dbh = null;	
		}
	
	}catch (PDOException $e){
		print('Error:'.$e->getMessage());
		$errors['error'] = "データベース接続失敗しました。";
	}
}
?>

<font color='000000'><p align='center'><?=htmlspecialchars($yourname, ENT_QUOTES, 'UTF-8')."さんで検索。"?></p>
<p align='center'>検索結果は<b><?=$row_count?></b>件です。</p></font>

<table align='center' width='1000px' border='1'>

<tr align='center'>
<td><font color='000000'>演習名、所属名</font></td></tr></table>



<?php 
foreach($rows as $row){
?>

<table align='center' width='1000px' border='1'>
<tr align='center'><font color='000000'> 
	<td><a href=".$row["url"].">
		".<?=htmlspecialchars($row["name"],ENT_QUOTES,'UTF-8')?>."</a></td></font></a>
</tr> </table>


<?php elseif(count($errors) > 0):
foreach($errors as $value){
	echo "<p>".$value."</p>";
}
endif; ?>

<br>
<br>

<form align="center" action="kensaku.php" method="post">
キーワードを入力してください：<input type="text" name="yourname">
<input type="submit" value="検索する">
</form>

<br>
<br>

<table width="800" align="center" rules="all" frame="all" border="1" bgcolor="#dcdcdc">
<tr>
<td align="center"><a href="index.htm"><font size="7" color="000000">　トップページへ</font></a></td>
</tr>
</table>


</body>
</html>