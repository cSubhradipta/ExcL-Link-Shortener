<?php
    include "dbconnect.php";

    $link_id = $_GET['link_id'];
    $previous = "../../dashboard.php#".$link_id;

    if(isset($_GET['id'])){
        $delete_id = mysqli_real_escape_string($conn, $_GET['id']);
        $sql = mysqli_query($conn, "DELETE FROM linx_shortlinks WHERE short_url = '{$delete_id}'");
        if($sql){
            header("Location: $previous");
        }else{
            header("Location: $previous");
        }
    }
    else{
        header("Location: $previous");
    }
?>