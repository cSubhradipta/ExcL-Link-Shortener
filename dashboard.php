<?php
	
	session_start();

	if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!=true){
    	header("location: login.php");
    	exit;
	}

	include 'assets/components/dbconnect.php'; 
	$email = $_SESSION['email'];
	$sql = "Select * FROM excl_users WHERE email='$email'";
	$res = mysqli_query($conn, $sql);
	$num = mysqli_num_rows($res);
	while($row=mysqli_fetch_assoc($res)){
		$fname = $row['firstname'];
		$lname = $row['lastname'];
		$dt = $row['dt'];
	}
	$s = strtotime($dt);
	$fdt = date('M d, Y', $s);
	
	$total = 0;
	$sql2 = mysqli_query($conn, "SELECT * FROM excl_shortlinks WHERE user_email='$_SESSION[email]' ORDER BY link_id DESC");
	if(mysqli_num_rows($sql2) > 0){
		$sql3 = mysqli_query($conn, "SELECT COUNT(*) FROM excl_shortlinks WHERE user_email='$_SESSION[email]'");
		$res = mysqli_fetch_assoc($sql3);

		$sql4 = mysqli_query($conn, "SELECT clicks FROM excl_shortlinks WHERE user_email='$_SESSION[email]'");
		$total = 0;
		while($count = mysqli_fetch_assoc($sql4)){
			$total = $count['clicks'] + $total;
		}
	}

	function getTitle($url) {
		$page = file_get_contents($url);
		$title = preg_match('/<title[^>]*>(.*?)<\/title>/ims', $page, $match) ? $match[1] : null;
		return $title;
	}
			
?>

<!doctype html>
<html lang="en">
  	<head>
		<script src="assets/js/loader.js"></script>
		<?php require_once('assets/components/head.php'); ?>
		<script src="assets/js/main.js" defer></script>
	</head>
  <body>
  	
	<div id="load" class="position-fixed">
		<div class="dot-pulse"></div>
	</div>
	<div id="contents" style="visibility: hidden;">
		
		<header>
			<nav class="navbar fixed-top navbar-light shadow-sm" style="background-color: #F0F2F8;">
				<div class="container">
					<a class="navbar-brand h1" href="./index.php">ExcL</a>
					<div class="ml-auto">
						<div class="dropdown d-flex justify-content-around align-items-baseline fs-6">
							<button type="button" class="btn btn-transparent border-none shadow-none text-truncate" style="max-width: 60%; font-size: 1rem;" data-bs-toggle="dropdown">
								<span class="ml-2"><?php echo $fname." ".$lname;?></span>
							</button>
							<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" style="width: 12rem;">
								<div class="container" style="font-size: 1rem;">
									<div class="row my-2">
										<div class="col-md-12">
											<div class="d-flex justify-content-between">
												<div class="">Links</div>
												<div class=""><?php echo mysqli_num_rows($sql2);?></div>
											</div>
										</div>
									</div>
									<div class="dropdown-divider"></div>
									<div class="row my-2">
										<div class="col-md-12">
											<div class="d-flex justify-content-between">
												<div class="">Redirects</div>
												<div class=""><?php echo $total;?></div>
											</div>
										</div>
									</div>
								</div>
							</ul>
							<div class="vr"></div>
							<a href="logout.php" class="text-decoration-none text-danger">Logout</a>
						</div>
						
					</div>
				</div>
			</nav>
		</header>
		<main class="" style=" min-height: 88vh;">
			<section class="mt-4" style="background-color: rgba(220, 224, 239, 0.5);">
				<div class="container mt-4 py-5">
					<div class="form-row mt-5">
						<div class="col-md-9 mx-auto border-primary" style="border-radius: 0.25rem;">
							<form method = "POST" action = "" id="main-field" class="input-group mb-3 p-2 needs-validation">
								<input type="hidden" name="usermail" id="usermail" value="<?php echo $email;?>">
								<input type="url" name="org_url" id="org_url" class="form-control p-4 pr-3 shadow-none" placeholder="Paste your URL here . . ."  style="font-size: 1rem;" required>			
								<div class="input-group-append">
									<button type="button" id="shorten-btn" class="btn btn-primary shadow-lg py-4" style="font-size: 1rem;"></button>
									
								</div>
							</form>
							<div class="text-danger px-2" style="font-weight: 500;" id="validation-error">&nbsp;						
							</div>
							
						</div>
					</div>
				</div>
			</section>

			<section>
				<div class="container">

					<?php
						$i = 0;
						if(mysqli_num_rows($sql2) > 0){
					?>
					<div class="mt-5 mb-5 fs-2 text-center text-dark" style="font-weight: 600;">Links you've shortened</div>
					<?php
							while($row = mysqli_fetch_assoc($sql2)){
								$i++;
					?>
					<div id="link-container">
								
							<div class="row m-0 my-3" id="<?php echo "url".$i;?>">
								<div class="col-md-9 mx-auto my-1 p-0 py-1 card  ">
									<div class="card-body">
										<div class="d-flex justify-content-between">
											<div class="" style="max-width: 70%;">
												<h4 class="link_title" style="font-weight: 600;  max-width: 100%; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;"><?php echo $row['title'];?></h4>
											</div>
											<div class="d-flex flex-column">
												<div class="link_date small text-muted text-end" style="font-weight: 500;">
												<?php 
													
													$s = strtotime($row['link_date']);
													$fdt = date('M d, Y', $s);
													$ftm = date('H:m A', $s);
													echo $fdt;
												?>
												</div>
								
											</div>
										</div>
										<div class="d-flex justify-content-between align-items-baseline">
											<div class="" style="max-width: 80%;">
												<p class="link_long_url text-break small" style="font-weight: 500; max-width: 100%; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;">
													<?php echo $row['full_url'];?>
												</p>	
											</div>
											<div class="">
												<div class="link_clicks text-end">
													<p class="fw-bold fs-3 text-dark mb-0 pb-0" style="line-height: 1;"><?php echo $row['clicks'];?></p>
													<p class="mt-0 pt-0 small" style="font-weight: 500;">Redirect<?php if($row['clicks'] > 1) echo "s";?>
													</p>
												</div>
											</div>
										</div>
										<div class="d-flex justify-content-between">
											<div class="card-text" style="max-width: 50%;">
												<a href="<?php echo $row['short_url'];?>" target="_blank" id="<?php echo "link".$i?>" class="link_short_url text-decoration-none text-primary" style="font-weight: 600; max-width: 100%; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;"><?php echo $tempurl.$row['short_url']?></a>
											</div>
											<div class="">
												<div class="link_action_buttons">
													<button type="button" class="btn btn-sm btn-primary" onclick="copyLink('<?php echo '#link'.$i; ?>');">Copy</button>
													<button type="button" class="btn btn-sm btn-primary" onclick="customizeURL('<?php echo $row['title'];?>', '<?php echo $row['full_url'];?>', '<?php echo $row['short_url'];?>');">Edit</button>
													<a href="./assets/components/delete.php?id=<?php echo $row['short_url']."&link_id=url".($i-2);?>" type="button" class="delBtn btn btn-sm btn-danger">Delete</a>
												</div>
											</div>
										</div>
									</div>			
								</div>
							</div>
			
							
						
					</div>
					<?php
							}
						} else {
					?>
						<div class="row">
							<div class="col-md-9 mx-auto pt-5">
								<div class="welcome-msg m-2 mt-4 text-primary" >
									<p class="fs-1" style="font-weight: 600; color: #607ceb;">Welcome <?php echo $fname;?>&nbsp;!</p>
									<p class="fs-4" style="font-weight: 500; color: #607ceb;">Start creating your shortlinks. You can see all of your shortened links here.</p>		
								</div>
							</div>
						</div>
					<?php
						}
					?>
				</div>
			</section>
			<section>
				<div class="modal fade" id="modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content" id="main-modal">
							<div class="m-3 alert alert-success alert-dismissible fade show" id="alert" role="alert">
								<div id="alert-msg">Your shortlink is ready.</div>
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<form method="POST" action="">
									<div class="form-outline mb-4">
										<label class="form-label" for="title">Title</label>
										<input type="text" id="title" name="title" class="form-control"/>
									</div>
									
									<div class="form-outline mb-4">
										<label class="form-label" for="longurl">Original URL</label>
										<input type="url" id="longurl" name="longurl" value="https://firebasestorage.googleapis.com/v0/b/certificates-ffacb.appspot.com/o/120121%2F8e637ff2.png?alt=media&token=8a2d0529-ea33-470e-96ab-6351ffc27ad5" class="form-control" readonly/>
									</div>

									<div class="form-outline mb-4">
										<label for="field" class="form-label">Short URL</label>
										<div class="input-group mb-3">
											<input type="text" class="form-control" id="field" value="https://excl.gq/"  oncut="return false;" aria-describedby="basic-addon3">
											<button class="btn btn-outline-secondary" type="button" id="copy-btn">Copy</button>
										</div>
									</div>
								</form>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" id="dismiss-btn" data-bs-dismiss="modal">Cancel</button>
								<button type="button" class="btn btn-primary text-center" id="save-btn">Save</button>

								
							</div>
						</div>
					</div>
				</div>
			</section>			
		</main>
		<?php require_once('assets/components/footer.php'); ?>
	</div>
		
  </body>
</html>