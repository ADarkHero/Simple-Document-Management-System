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
		
		//If the file is a pdf, get its content as text
		if($_FILES['fileUpload']['type'][$i] == "application/pdf"){
			//Only read pdf description, if user didn't set one themselves
			if($_POST["fileDesc"] == ""){
				$descriptionText = pdfExtractText($uploadedfilename);
			}
			//$thumbImg = pdfToImage($uploadedfilename);	
		}
		//If the file is an image, copy it to img folder
		else if(strpos($_FILES['fileUpload']['type'][$i], "image") !== false){
			$thumbImg = $uploadedfilename;
			copy('files/'.$uploadedfilename, 'img/'.$uploadedfilename);
		}
		
		$statement = $pdo->prepare("INSERT INTO files (fileName, fileTags, fileDesc, fileSrc, fileThumb, fileDate) VALUES (?, ?, ?, ?, ?, ?)");
		$statement->execute(array(
			$_POST["fileName"], 
			strtolower($_POST["fileTags"]), 
			substr($descriptionText, 0, 255), 
			$uploadedfilename, 
			$thumbImg, 
			$_POST["fileDate"]
		)); 
		
		
		//TODO: ERROR HANDLING:
		
		$arr = $statement->errorInfo();
		print_r($arr);
		
	   
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
	* TODO
	*/
	function pdfToImage($filePath){
		try{
			$imagick = new Imagick($uploadedfilename.'[0]');
			$imagick->setImageFormat('jpg');
			file_put_contents("img/test.jpg", $imagick);
			
			return "test.jpg";
		}catch(Exception $e){
			return "default.jpg";			
		}
	}