<?php		
	error_reporting(-1);
	
	 // Count total files
	 $countfiles = count($_FILES['fileUpload']['name']);
	 
	 // Looping all files
	 for($i = 0; $i < $countfiles; $i++){
		$uploadedfilename = $_FILES['fileUpload']['name'][$i];
	   
		// Upload file
		move_uploaded_file($_FILES['fileUpload']['tmp_name'][$i], 'files/'.$uploadedfilename);
	   		
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
			$thumbImg = imageThumb($uploadedfilename);
		}
		
		$statement = $pdo->prepare("INSERT INTO files (fileName, fileTags, fileDesc, fileSrc, fileThumb, fileDate, fileType) VALUES (?, ?, ?, ?, ?, ?, ?)");
		$statement->execute(array(
			$_POST["fileName"], 
			strtolower($_POST["fileTags"]), 
			substr($descriptionText, 0, 512), 
			$uploadedfilename, 
			$thumbImg, 
			$_POST["fileDate"],
			$fileType
		)); 
		
		
		//TODO: ERROR HANDLING:
		
		$arr = $statement->errorInfo();
		//print_r($arr);
		
	   
		echo "✅ " . $uploadedfilename . "<br>";
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
	
	
	
	/*
	*
	*/
	function imageThumb($fileName){
		// Calculate new size
		list($width, $height) = getimagesize("files/" . $fileName);
		
		$resize = 500;
		if($width > $resize){
			$newwidth = $resize;
			$newheight = $resize / $width * $height;
		}
		else if($height > $resize){
			$newheight = $resize;
			$newwidth = $resize / $height * $width;
		}

		// Load picture
		$thumb = imagecreatetruecolor($newwidth, $newheight);
		$source = imagecreatefromjpeg("files/" . $fileName);

		// Scale picture
		imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

		// Save file
		$newFileName = substr_replace($fileName, "-thumb.", strrpos($fileName, "."), strlen("."));
		imagejpeg($thumb, "files/" . $newFileName);
		return $newFileName;
	}