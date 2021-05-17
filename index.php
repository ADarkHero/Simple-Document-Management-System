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
												if(isset($_GET["s"])){
													$s = htmlspecialchars($_GET["s"]);
													echo substr($s, 0, 50); 
													if(strlen($s) > 50){ echo "..."; }
													
													$sql = searchAlgorithm($s);
												}
												else{
													echo "Most recent files";
													
													$sql = "SELECT * FROM files ORDER BY fileDate DESC LIMIT 20";
												}
											?>
										</h2>
									</header>
								</section>
										<?php							
											//TODO: EXECUTE SQL and read response
											if($pdo->query($sql)->rowCount() > 1){	
										?>
												<div class="posts">	
										<?php
													foreach ($pdo->query($sql) as $row) {	
										?>
														<!-- Display multiple files -->	
														<article>
															<a href="#" class="image"><img src="img/<?php echo $row["fileThumb"]; ?>" alt="" /></a>
															<h3><?php echo $row['fileName']; ?></h3>
															<p><?php echo $row['fileDate']; ?></p>
															<p><?php echo $row['fileTags']; ?></p>
															<ul class="actions">
																<li><a href="files/<?php echo $row['fileSrc']; ?>" target="_blank" class="button">DOWNLOAD FILE</a></li>
																<li><a href="index.php?s=<?php echo $row['fileSrc']; ?>" class="button">FILEINFO</a></li>
															</ul>
														</article>
										<?php
													} //END of foreach
										?>
												</div> <!-- Close posts div -->
										<?php
											} // END of if
											else{
												foreach ($pdo->query($sql) as $row) {
										?>
													<!-- Display single file -->
													<section id="banner">
														<div class="content">
															<header>
																<h1><?php echo $row['fileName']; ?></h1>
																<p><?php echo $row['fileDate']; ?></p>
																<p><?php echo $row['fileTags']; ?></p>
															</header>
															<p><?php echo $row['fileDesc']; ?><</p>
															<ul class="actions">
																<li><a href="files/<?php echo $row['fileSrc']; ?>" target="_blank" class="button big">DOWNLOAD FILE</a></li>
															</ul>
														</div>
														<span class="image object">
															<img src="img/<?php echo $row["fileThumb"]; ?>" alt="" />
														</span>
													</section>
										<?php
												} //END of foreach
											} //END of else
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
									<header class="major">
										<h2>Add new file</h2>
									</header>
									<form method="post" action="index.php" enctype="multipart/form-data">
										<div class="form-group row">
											    <input type="text" class="form-control" id="fileName" name="fileName"  placeholder="Name your files">

										</div>
										<div class="form-group row mt-3">
											<input type="file" name="fileUpload[]" id="fileUpload" multiple>
										</div>
										<div class="form-group row mt-3">
											<input class="form-control" type="datetime-local" name="fileDate" id="fileDate" value="<?php echo date('Y-m-d\Th:i:s');?>" id="example-datetime-local-input">
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
										if(isset($_FILES["fileUpload"]["name"])){
									?>
											<header class="major">
												<h5>
													<?php
														require_once "addToDB.php";
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
			<script src="https://unpkg.com/filepond/dist/filepond.js"></script>
			<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>

			<!-- Turn all file input elements into ponds -->
			<script>
				FilePond.registerPlugin(FilePondPluginImagePreview);				
				FilePond.parse(document.body);
			</script>

	</body>
</html>

<?php

function searchAlgorithm($s){
	$sql = 'SELECT * FROM files WHERE fileSrc LIKE "' . $s. '" OR fileDesc LIKE "%'. $s . '%" ';
			
			foreach(explode(" ", $s) as $tag){
				$sql .= ' OR fileTags LIKE "%' . $tag . '%" ';
			}

	$sql .= 'ORDER BY fileDate DESC';
	
	return $sql;
}