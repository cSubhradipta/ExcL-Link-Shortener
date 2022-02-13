<?php
$login = false;
$showError = false;
if($_SERVER["REQUEST_METHOD"] == "POST"){
    include 'assets/components/dbconnect.php';
    $email = $_POST["email"];
    $pswd = $_POST["pswd"];  
     
    $sql = "SELECT * from excl_users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($result);
    if ($num == 1){
        while($row=mysqli_fetch_assoc($result)){
            if (password_verify($pswd, $row['pswd'])){ 
                $login = true;
                session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['email'] = $email;
				$_SESSION['fname'] = $row['firstname'];
                header("location: dashboard.php");
            } 
            else{
                $showError = "Invalid Credentials";
            }
        }
        
    } 
    else{
        $showError = "Invalid Credentials";
    }
}
    
?>
<!doctype html>
<html lang="en">
  	<head>
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
								if($login){
								echo '<div class="col-12 alert alert-danger alert-dismissible fade show" role="alert">
										Login Successful
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
					<div class="container py-4 vh-75">
						<div class="row">
						<div class="col-md-3"></div>
							<div class="col-md-6">
								<div class="card-body p-4 p-lg-5 text-black">
									<form method="POST" action="">
										<h4 class="fw-bold mb-4 pb-4 text-center">Sign into your account</h4>
										<div class="form-outline mb-4">
											<label class="form-label" for="email">Email address</label>
											<input type="email" id="email" name="email" class="form-control form-control-lg" />
										</div>

										<div class="form-outline mb-4">
											<label class="form-label" for="pswd">Password</label>
											<input type="password" id="pswd" name="pswd" class="form-control form-control-lg" />
										</div>

										<div class="pt-2 mb-4 text-center">
											<button class="btn btn-primary btn-block" type="submit">Sign In</button>
										</div>
										<p class="mb-5 pb-lg-2 text-dark form-label" >Don't have an account? <a href="./register.php" class=" text-decoration-none text-primary">Register here</a></p>
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