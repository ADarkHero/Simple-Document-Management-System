<?php		
	error_reporting(-1);
		
	$statement = $pdo->prepare("DELETE FROM files WHERE FileID = ?");
	$statement->execute(array(
		$_GET["deleteFileID"]
	)); 

	unlink('files/' . $_GET["deleteFileSrc"]);
   
	echo '✅ Deleted file ' . $_GET["deleteFileID"] . '!';
