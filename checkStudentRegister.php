<?php

$keyword = strval($_REQUEST['reg_no']);

$con = new PDO("mysql:host=localhost;dbname=face_project",'root','');

            
$query2 = "SELECT * FROM `tbl_student` WHERE  register_number = '".$keyword."' ";                    
$sth2 = $con->prepare($query2);
$sth2->execute();

$image_list2=$sth2->fetchAll(); 
if(!empty($image_list2)){

    echo 2;
}else{
    echo 1;
}



?>