<?php
$showAlert = false;
$showError = false;
if($_SERVER["REQUEST_METHOD"] == "POST"){
    include 'assets/components/dbconnect.php';
	$firstname = $_POST["fname"];
	$lastname = $_POST["lname"];
    $email = $_POST["email"];
    $pswd = $_POST["pswd"];
    $cpswd = $_POST["cpswd"];
    
    $existSql = "SELECT * FROM excl_users WHERE email = '$email'";
    $result = mysqli_query($conn, $existSql);
    $numExistRows = mysqli_num_rows($result);
    if($numExistRows > 0){
        $showError = "Email Already Exists";
    }
    else{
        if(($pswd == $cpswd)){
            $hash = password_hash($pswd, PASSWORD_DEFAULT);
            $sql = "INSERT INTO excl_users (firstname, lastname, email, pswd, dt) VALUES ('$firstname', '$lastname', '$email', '$hash', current_timestamp())";
            $result = mysqli_query($conn, $sql);
            if ($result){
                $showAlert = true;
            }
        }
        else{
            $showError = "Passwords do not match";
        }
    }
}
    
?>


<!doctype html>
<html lang="en">
  	<head>
	  	<script src="assets/js/loader.js"></script>
  		<?php require_once('assets/components/head.php'); ?>
	</head>
  	<body>
			<header>
				<nav class="navbar navbar-expand-lg navbar-light bg-transparent">
					<div class="container">
						<a class="navbar-brand h1" href="./index.php">ExcL</a>
					</div>
				</nav>
			</header>
			<main>
				<section class="">
					<div class="container">
						<div class="row">
							<div class="col-md-3"></div>
							<div class="col-md-6">
								<div class="d-flex justify-content-around">
								<?php
								if($showAlert){
								echo '<div class="col-12 alert alert-success alert-dismissible fade show" role="alert">
										Account Created Successfully.&ensp;<a href=".\login.php" class="text-decoration-none">Login here</a>
									<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
									</div>';
								}
								if($showError){
								echo
								'<div class="col-12 alert alert-danger alert-dismissible fade show" role="alert">
									'.$showError.'
									<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
								</div>';
								}
								?>
								</div>
							</div>
						</div>
					</div>
				</section>
				<section>
					<div class="container py-4">
						<div class="row">
						<div class="col-md-3"></div>
							<div class="col-md-6">
								<div class="card-body p-4 p-lg-5 text-black">
									<form method="POST" action="">
										<h4 class="fw-bold mb-4 pb-4 text-center">Register your account</h4>
										<div class="row">
											<div class="form-outline mb-4 col">
												<label class="form-label" for="fname">First name</label>
												<input type="text" id="fname" name="fname" class="form-control form-control-lg"  required/>
											</div>

											<div class="form-outline mb-4 col">
												<label class="form-label" for="lname">Last name</label>
												<input type="text" id="lname" name="lname" class="form-control form-control-lg"  required/>
											</div>
										</div>
										
										
										<div class="form-outline mb-4">
											<label class="form-label" for="email">Email address</label>
											<input type="email" id="email" name="email" class="form-control form-control-lg" required />
										</div>

										<div class="form-outline mb-4">
											<label class="form-label" for="pswd">Password</label>
											<input type="password" id="pswd" name="pswd" class="form-control form-control-lg" required />
										</div>

										<div class="form-outline mb-4">
											<label class="form-label" for="cpswd">Confirm password</label>
											<input type="password" id="cpswd" name="cpswd" class="form-control form-control-lg" required/>
										</div>

										<div class="pt-2 mb-4 text-center">
											<button class="btn btn-primary btn-block" type="submit">Register</button>
										</div>
										<p class="mb-5 pb-lg-2 text-dark form-label" >Already have an account? <a href="./login.php" class=" text-decoration-none text-primary">Login here</a></p>
									</form>

								</div>
							</div>		
						</div>
					</div>
				</section>
			</main>
			<?php require_once('assets/components/footer.php'); ?>
  	</body>
</html>