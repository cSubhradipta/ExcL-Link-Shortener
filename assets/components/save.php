<?php
    include 'dbconnect.php'; 
	function getTitle($url) {
		$page = file_get_contents($url);
		$title = preg_match('/<title[^>]*>(.*?)<\/title>/ims', $page, $match) ? $match[1] : null;
		return $title;
	}
	$email = $_POST['usermail'];
	date_default_timezone_set("Asia/Kolkata");
    $date = date('Y-m-d H:i:s', time());
	$org_url = mysqli_real_escape_string($conn, $_POST['org_url']);
	if(!empty($org_url) && filter_var($org_url, FILTER_VALIDATE_URL)){
	    $key = substr(md5(microtime()), rand(0, 26), 5);
	    $sql_in = mysqli_query($conn, "SELECT * FROM linx_shortlinks WHERE short_url = '{$key}'");
		if(mysqli_num_rows($sql_in) > 0){
			$err = array("error"=> "Please try again!");
			echo json_encode($err);
		}else{
			$title = getTitle($org_url);
			$sql2_in = mysqli_query($conn, "INSERT INTO linx_shortlinks (user_email, title, full_url, short_url, clicks, link_date) 
											VALUES ('{$email}', '{$title}', '{$org_url}', '{$key}', '0', '{$date}')");
			if($sql2_in){
				$sql3_in = mysqli_query($conn, "SELECT * FROM linx_shortlinks WHERE short_url = '{$key}'");
				if(mysqli_num_rows($sql3_in) > 0){
					$shorten_url = mysqli_fetch_assoc($sql3_in);
					$title_val = $shorten_url['title'];
					$url_val = $shorten_url['full_url'];
					$val = array("title"=> "$title_val" , "url"=> "$url_val" , "short_url"=>$shorten_url['short_url']);
					echo json_encode($val);
				}
			}
		}
	}else{
		$err = array("error"=> "Please provide a valid URL");
		echo json_encode($err);
	}
?>