<?php		
	error_reporting(-1);
	
	 // Count total files
	 $countfiles = count($_FILES['fileUpload']['name']);
	 
	 // Looping all files
	 for($i = 0; $i < $countfiles; $i++){
		$uploadedfilename = $_FILES['fileUpload']['name'][$i];
	   
		// Upload file
		move_uploaded_file($_FILES['fileUpload']['tmp_name'][$i], 'files/'.$uploadedfilename);
	   
		var_dump($_POST["fileDesc"]);
		
		$thumbImg = "default.jpg";
		$descriptionText = $_POST["fileDesc"];
		$fileType = $_FILES['fileUpload']['type'][$i];
		
		//If the file is plain text, write its content to the description
		if($fileType == "text/plain" || $fileType == "application/vnd.ms-excel"){
			//Only read description, if user didn't set one themselves
			if($_POST["fileDesc"] == ""){
				$descriptionText = file_get_contents("files/" . $uploadedfilename);
			}
		}
		//If the file is a pdf, get its content as text
		else if($fileType == "application/pdf"){
			//Only read pdf description, if user didn't set one themselves
			if($_POST["fileDesc"] == ""){
				$descriptionText = pdfExtractText($uploadedfilename);
			}
		}
		//If the file is an image, copy it to img folder
		else if(strpos($fileType, "image") !== false){
			//TODO: Generate thumbnail
			$thumbImg = $uploadedfilename;
		}
		
		$statement = $pdo->prepare("INSERT INTO files (fileName, fileTags, fileDesc, fileSrc, fileThumb, fileDate, fileType) VALUES (?, ?, ?, ?, ?, ?, ?)");
		$statement->execute(array(
			$_POST["fileName"], 
			strtolower($_POST["fileTags"]), 
			substr($descriptionText, 0, 255), 
			$uploadedfilename, 
			$thumbImg, 
			$_POST["fileDate"],
			$fileType
		)); 
		
		
		//TODO: ERROR HANDLING:
		
		$arr = $statement->errorInfo();
		//print_r($arr);
		
	   
		echo "✅ " . $_FILES['fileUpload']['type'][$i]. "<br>";
	}
	
	
	
	/*
	*
	*/
	function pdfExtractText($filePath){
		// Include Composer autoloader if not already done.
		include 'vendor/autoload.php';
		 
		// Parse pdf file and build necessary objects.
		$parser = new \Smalot\PdfParser\Parser();
		
		$pdftext = $parser->parseFile("files/" . $filePath)->getText();
		
		return $pdftext;
	}