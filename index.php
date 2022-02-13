<?php 
  include "assets/components/dbconnect.php";
  $new_url = "";
  if(isset($_GET)){
    foreach($_GET as $key=>$val){
      $u = mysqli_real_escape_string($conn, $key);
      $new_url = str_replace('/', '', $u);
    }	
      $sql = mysqli_query($conn, "SELECT full_url FROM excl_shortlinks WHERE short_url = '{$new_url}'");
      if(mysqli_num_rows($sql) > 0){
        $sql2 = mysqli_query($conn, "UPDATE excl_shortlinks SET clicks = clicks + 1 WHERE short_url = '{$new_url}'");
        if($sql2){
            $full_url = mysqli_fetch_assoc($sql);
            header("Location:".$full_url['full_url']);
          }
      }
  }
?>

<!doctype html>
<html lang="en">
  	<head>
		<script src="assets/js/loader.js"></script>
  		<?php include('assets/components/head.php')?>
	</head>
  	<body>
		<div id="load" class="position-fixed">
			<div class="dot-pulse"></div>
		</div>
		<div id="contents" style="visibility: hidden;">
			<header>
				<nav class="navbar navbar-expand-lg navbar-light bg-transparent">
					<div class="container">
						<a class="navbar-brand h1" href="#">ExcL</a>
						<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
							<span class="navbar-toggler-icon"></span>
						</button>
						<div class="collapse navbar-collapse" id="navbarContent">
							<ul class="navbar-nav me-auto mb-2 mb-lg-0">
								
							</ul>
							<ul class="navbar-nav">
								<li class="nav-item">
									<a class="nav-link" href="./login.php">Login</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="./register.php">Register</a>
								</li>
							</ul>
						</div>
					</div>
				</nav>
			</header>
			<main>
				<section style="min-height: 0vh;">
					<div class="container mt-5 pt-5">
						<div class="row mt-2">
							<div class="col-md-6 pb-5">
								<h1 class="fw-bold" style="font-size: 3.2rem;">Make your links <span class="text-primary">Recognizable</span></h1>
								<h5 class="pt-2">Shorten your URL and start sharing&nbsp;. . .</h5>
								<a href="./login.php" type="button" class="btn btn-primary btn-lg mt-5 py-3 px-4">Get Started <span class="arrow-hover">&#8594;<span></a>
							</div>
							<div class="col-md-6 hero-img pb-5">
								<img src="assets/images/hero-img.png" class="img-fluid" alt="">
							</div>
						</div>
					</div>
				</section>
				<?php
					$sql_user = mysqli_query($conn, "SELECT * FROM excl_users");
					$sql_link = mysqli_query($conn, "SELECT * FROM excl_shortlinks");
					$sql_redirect = mysqli_query($conn, "SELECT SUM(clicks) AS total_clicks FROM excl_shortlinks");
					$res_user = mysqli_num_rows($sql_user);
					$res_link = mysqli_num_rows($sql_link);
					$res_redirect = mysqli_fetch_assoc($sql_redirect);
				?>
				<section id="milestone" class="" style="min-height: 95vh;">
					<div class="container p-4 pb-0">
						<div class="row mt-4 mb-5">
							<div class="col-md-4 mx-auto px-auto">
								<div class="card m-3 py-3 px-4 border border-primary border-end-0 border-top-0 border-bottom-0 border-3">
									<div class="d-flex justify-content-between">
										<div class="content">
											<div class="title text-dark" style="font-weight: 500;">
												Users Registered
											</div>
											<div class="value fs-2 pt-2 text-dark" style="font-weight: 600;">
												<?php echo $res_user;?>
											</div>
										</div>
										<div class="logo d-flex align-items-center">
											<div class="blob rounded px-2 py-0" style="background-color: #e8ecfc;">
												<i class="uil uil-user-plus fs-2 text-primary"></i>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-4 mx-auto px-auto">
								<div class="card m-3 py-3 px-4 border border-primary border-end-0 border-top-0 border-bottom-0 border-3">
									<div class="d-flex justify-content-between">
										<div class="content">
											<div class="title text-dark" style="font-weight: 500;">
												Links Shortened
											</div>
											<div class="value fs-2 pt-2 text-dark" style="font-weight: 600;">
												<?php echo $res_link;?>
											</div>
										</div>
										<div class="logo d-flex align-items-center">
											<div class="blob rounded px-2 py-0" style="background-color: #e8ecfc;">
												<i class="uil uil-link-add fs-2 text-primary"></i>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-4 mx-auto px-auto">
								<div class="card m-3 py-3 px-4 shadow-sm border border-primary border-end-0 border-top-0 border-bottom-0 border-3">
									<div class="d-flex justify-content-between">
										<div class="content">
											<div class="title text-dark" style="font-weight: 500;">
												Redirects
											</div>
											<div class="value fs-2 pt-2 text-dark" style="font-weight: 600;">
												<?php echo $res_redirect['total_clicks'];?>
											</div>
										</div>
										<div class="logo d-flex align-items-center">
											<div class="blob rounded px-2 py-0" style="background-color: #e8ecfc;">
												<i class="uil uil-external-link-alt fs-2 text-primary"></i>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row mx-3 pt-3 bg-primary" style="background-color: rgba(0,0,0,0.2); border-radius: 8px 8px 0 0; margin-top: 5rem;">
							<div class="col-md-2"></div>
							<div class="col-md-8 text-center p-4">
								<h2 class="fw-bold text-white mb-3">Check out the github repository</h2>
								<h5 class="text-white fw-normal p-3 mx-2">Feel free to have a look around the repository.<br> If you find <span style="font-weight: 600;">ExcL URL shortener</span> useful consider supporting me with a coffee or a star on the repository.</h5>
							</div>
							<div class="col-md-2"></div>
						</div>
						<div class="row mx-3 pt-2 pb-5 bg-primary" style="background-color: rgba(0,0,0,0.2); border-radius: 0 0 8px 8px; margin-bottom: 2rem;">
							<div class="col-md-3"></div>
							<div class="col-md-3 text-center">
								<a href="#" class="btn btn-light my-3 "><i class="uil uil-github" style="font-size: 1.2rem;"></i>&emsp;Github Repository</a>
							</div>
							<div class="col-md-3 text-center">
								<a href="https://buymeacoffee.com/csubhradipta" class="btn btn-light my-3 px-4" style="padding: 0.8rem 0;"><img src="assets/images/bmc.png" width="19.2" height="19.2"/>&emsp;Buy me a coffee</a>
							</div>
						</div>
					</div>
				</section>
			</main>
			<?php require_once('assets/components/footer.php'); ?>
		</div>
  	</body> 
</html>