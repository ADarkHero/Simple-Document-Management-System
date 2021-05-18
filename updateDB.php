<?php		
	error_reporting(-1);
	
	$statement = $pdo->prepare("UPDATE files SET fileName = ?, fileTags = ?, fileDesc = ?, fileDate = ? WHERE fileId = ?");
	$statement->execute(array(
		$_POST["fileNameUpd"], 
		strtolower($_POST["fileTagsUpd"]), 
		substr($_POST["fileDescUpd"], 0, 255), 
		$_POST["fileDateUpd"],
		$_POST["fileIdUpd"]
	)); 		
   
	echo '✅ Updated file info!<br><a href="">Reload to view changes...</a>';
