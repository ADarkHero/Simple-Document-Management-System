<?php
	require_once "config.php";
?>

<!DOCTYPE HTML>
<!--
	Editorial by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>Editorial by HTML5 UP</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!-- Editorial by HTML5 UP stylesheet -->
		<link rel="stylesheet" href="assets/css/main.css" />
		<!-- Filepond stylesheet -->
		<link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
		<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
		<!-- Bootstrap v5.0 stylesheet -->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
	</head>
	<body class="is-preload">

		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Main -->
					<div id="main">
						<div class="inner">

							<!-- Header -->
								<header id="header">
									<form method="get" action="index.php">
										<input type="text" name="s" id="s" placeholder="Search" />
									</form>
								</header>

							<!-- Section -->
								<section>
									<header class="major">
										<h2>
											<?php 
												//Display specific file
												if(isset($_GET["id"])){
													$sql = "SELECT * FROM files WHERE fileId = " . $_GET["id"];
													if(isset($_GET["s"])){
														$s = htmlspecialchars($_GET["s"]);
														echo substr($s, 0, 50); 
														if(strlen($s) > 50){ echo "..."; }
													}
													else{
														echo $_GET["id"];
													}
												}
												//Search for files
												else if(isset($_GET["s"])){
													$s = htmlspecialchars($_GET["s"]);
													echo substr($s, 0, 50); 
													if(strlen($s) > 50){ echo "..."; }
													
													$sql = searchAlgorithm($s);
												}
												//Display most recent files
												else{
													echo "Most recent files";
													
													$sql = "SELECT * FROM files ORDER BY fileDate DESC LIMIT 20";
												}
											?>
										</h2>
									</header>
								</section>
										<?php							
											if($pdo->query($sql)->rowCount() > 1){	
												generateMultipleLayout($pdo, $sql);
											} 
											else{
												generateSingleLayout($pdo, $sql);
											}
										?>
						</div>
					</div>

				<!-- Sidebar -->
					<div id="sidebar">
						<div class="inner">

							<!-- Title -->
								<nav id="title" >
									<header class="major">
										<h3><a href="index.php">Document Management System</a></h3>
									</header>
								</section>

							<!-- Menu -->
								<nav id="menu">
									<header class="major mt-3">
										<h2>Add new file</h2>
									</header>
									<form method="post" action="index.php" enctype="multipart/form-data">
										<div class="form-group row mt-3">
											    <input type="text" class="form-control" id="fileName" name="fileName"  placeholder="Name your files">

										</div>
										<div class="form-group row mt-3">
											<input type="file" name="fileUpload[]" id="fileUpload" multiple>
										</div>
										<div class="form-group row mt-3">
											<input class="form-control" type="datetime-local" name="fileDate" id="fileDate" value="<?php echo date('Y-m-d\TH:i:s');?>" id="example-datetime-local-input">
										</div>
										<div class="form-group row mt-3">
											<textarea class="form-control" name="fileDesc" id="fileDesc" rows="5"placeholder="File description (gets generated automatically, when the file is a pdf or txt/csv document)"></textarea>
										</div>
										<div class="form-group row mt-3">
											<textarea class="form-control" name="fileTags" id="fileTags" rows="5"placeholder="Tag your files... (all tags are single words seperated by a space)"></textarea>
										</div>
										<div class="form-group row mt-3">
											  <button class="btn btn-primary-outline" type="submit">Add file to database</button>
										</div>
									</form>
								</nav>
								
								<?php
									if(isset($_FILES["fileUpload"]["name"]) || isset($_GET["deleteFileID"])){
								?>
										<header class="major">
											<h5>
												<?php
													if(isset($_FILES["fileUpload"]["name"])){
														require_once "addToDB.php";
													}
													else if(isset($_GET["deleteFileID"])){
														require_once "deleteFromDB.php";
													}
												?>
											</h5>
										</header>
								<?php
									}
								?>
								
							<!-- Footer -->
								<footer id="footer">
									<p class="copyright">Made with ❤ by <a href="https://www.adarkhero.de" target="_blank">ADarkHero</a><br>
									<!--File upload by <a href="https://pqina.nl/filepond/"  target="_blank">PQINA filepond</a><br> -->
									Pdf parser by <a href="https://github.com/smalot/pdfparser"  target="_blank">smalot</a><br>
									Design by <a href="https://html5up.net"  target="_blank">HTML5 UP</a></p>
								</footer>

						</div>
					</div>

			</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>
			
			<!-- Bootstrap v5.0 -->
			<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

			
			<!-- Load FilePond library -->
			<!--
			<script src="https://unpkg.com/filepond/dist/filepond.js"></script>
			<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
			-->
			
			<!-- Turn all file input elements into ponds -->
			<!--
			<script>
				FilePond.registerPlugin(FilePondPluginImagePreview);				
				FilePond.parse(document.body);
			</script>
			-->
			
			<script>
				function deleteFromDB(url){
					if (confirm('Do you really want to delete this file?')) {
						window.location.replace(url);
					} 	
				}
			</script>

	</body>
</html>



<?php
/*
*
*/
function searchAlgorithm($s){
	$sql = 'SELECT * FROM files WHERE 
			fileName LIKE "%' . $s. '%" OR 
			fileSrc LIKE "%' . $s. '%" OR 
			fileDesc LIKE "%'. $s . '%" ';
			
			foreach(explode(" ", $s) as $tag){
				$sql .= ' OR fileTags LIKE "%' . $tag . '%" ';
			}

	$sql .= 'ORDER BY fileDate DESC';
	
	return $sql;
}



/*
*
*/
function generatePreview($row){
	//File is pdf (display preview)
	if($row["fileType"] == "application/pdf"){
		echo '<iframe src="files/' . $row["fileSrc"] . '"></iframe>';
	}
	//File is txt or csv (show description)
	else if($row["fileType"] == "text/plain" || $row["fileType"] == "application/vnd.ms-excel"){
		echo $row["fileDesc"];
	}
	//File is something else (display thumb image)
	else{
		echo '<img src="files/' . $row["fileThumb"] . '" alt=""/>';
	}
}


/*
*
*/
function generateMultipleLayout($pdo, $sql){
	echo '<div class="posts">';	
	
	foreach ($pdo->query($sql) as $row) {	
	?>
		<!-- Display multiple files -->	
		<article>
			<a href="index.php?s=<?php echo $row['fileSrc']; ?>" class="image multiimage">
				<?php generatePreview($row); ?>
			</a>
			<h3><?php echo $row['fileName']; ?></h3>
			<p><?php echo $row['fileDate']; ?></p>
			<p><?php echo strtoupper($row['fileTags']); ?></p>
			<ul class="actions">
				<li><a href="files/<?php echo $row['fileSrc']; ?>" target="_blank" class="button">DOWNLOAD FILE</a></li>
				<li><a href="index.php?id=<?php echo $row['fileId']; ?>&s=<?php echo $row['fileSrc']; ?>" class="button">FILEINFO</a></li>
			</ul>
		</article>
	<?php
	}
	
	echo '</div>';
}



/*
*
*/
function generateSingleLayout($pdo, $sql){
	foreach ($pdo->query($sql) as $row) {
	?>
		<section id="banner">
			<div class="content">
				<form method="post" action="index.php?id=<?php echo $row['fileId']; ?>&s=<?php echo $row['fileSrc']; ?>" enctype="multipart/form-data">
			<div class="form-group row">
				<input type="text" class="form-control" id="fileNameUpd" name="fileNameUpd" value="<?php echo $row['fileName']; ?>">
			</div>
			<div class="form-group row mt-3">
				<input class="form-control" type="datetime-local" name="fileDateUpd" id="fileDateUpd" value="<?php $date = new DateTime($row['fileDate']); echo $date->format('Y-m-d\Th:i:s'); ?>" id="example-datetime-local-input">
			</div>
			<div class="form-group row mt-3">
				<textarea class="form-control" name="fileDescUpd" id="fileDescUpd" rows="5"><?php echo $row['fileDesc']; ?></textarea>
			</div>
			<div class="form-group row mt-3">
				<textarea class="form-control" name="fileTagsUpd" id="fileTagsUpd" rows="5"><?php echo $row['fileTags']; ?></textarea>
			</div>
			<div class="form-group row mt-3">
				<input type="hidden" name="fileIdUpd" id="fileIdUpd" value="<?php echo $row['fileId']; ?>">
				<button class="btn btn-primary-outline" type="submit">Update file</button>
				<a onclick='deleteFromDB("index.php?deleteFileID=<?php echo $row['fileId']; ?>&deleteFileSrc=<?php echo $row['fileSrc']; ?>")' class="btn btn-danger">DELETE FILE</a>
				<a href="files/<?php echo $row['fileSrc']; ?>" target="_blank" class="btn btn-primary">DOWNLOAD FILE</a>
			</div>
		</form>
			</div>
			<span class="image object">
				<?php generatePreview($row); ?>
			</span>
		</section>
	<?php
	}
	if(isset($_POST["fileIdUpd"])){
	?>
		<header class="major">
			<h5>
				<?php
					require_once "updateDB.php";
				?>
			</h5>
		</header>
	<?php
	}
}