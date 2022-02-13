<?php
    include "dbconnect.php";
    $org_url = mysqli_real_escape_string($conn, $_POST['shorten_url']);
    $shorten_url = str_replace(' ', '', $org_url);
    $hidden_url = mysqli_real_escape_string($conn, $_POST['hidden_url']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);

    if(!empty($shorten_url)){
        if(preg_match("/\//i", $shorten_url)){
            $explodeURL = explode('/', $shorten_url);
            $shortURL = end($explodeURL);
            $err = "";
            if($shortURL != ""){
                $sql = mysqli_query($conn, "SELECT short_url FROM linx_shortlinks WHERE short_url = '{$shortURL}' && short_url != '{$hidden_url}'");
                if(mysqli_num_rows($sql) == 0){
                    $sql2 = mysqli_query($conn, "UPDATE linx_shortlinks SET title = '{$title}', short_url = '{$shortURL}' WHERE short_url = '{$hidden_url}'");
                    if($sql2){
                        echo "success";
                    }else{
                        echo "Error - Failed to update link!";
                    }
                }else{
                    echo "This URL already exists.";
                }
            }else{
                echo "Please enter short url";
            }
        }else{
            echo "Invalid URL - Domain name required";
        }
    }else{
        echo "Please enter short url";
    }
?>