<?php
    ini_set("display_errors",1);
    session_start();
    $temp=$_SESSION['user_username'];
    if(isset($_POST)){
        require '../_database/database.php';
        $Destination = '../userfiles/background-images';
        if(!isset($_FILES['BackgroundImageFile']) || !is_uploaded_file($_FILES['BackgroundImageFile']['tmp_name'])){
            $BackgroundNewImageName= 'default-background.jpg';
            move_uploaded_file($_FILES['BackgroundImageFile']['tmp_name'], "$Destination/$BackgroundNewImageName");
        }
        else{
            $RandomNum = rand(0, 9999999999);
            $ImageName = str_replace(' ','-',strtolower($_FILES['BackgroundImageFile']['name']));
            $ImageType = $_FILES['BackgroundImageFile']['type'];
            $ImageExt = substr($ImageName, strrpos($ImageName, '.'));
            $ImageExt = str_replace('.','',$ImageExt);
            $ImageName      = preg_replace("/\.[^.\s]{3,4}$/", "", $ImageName);
            $BackgroundNewImageName = $ImageName.'-'.$RandomNum.'.'.$ImageExt;
            move_uploaded_file($_FILES['BackgroundImageFile']['tmp_name'], "$Destination/$BackgroundNewImageName");
        }
        $sql1="UPDATE user SET user_backgroundpicture='$BackgroundNewImageName' WHERE user_username = '$temp'";
        $sql2="INSERT INTO user (user_backgroundpicture) VALUES ('$BackgroundNewImageName') WHERE user_username = '$temp'";
        $result = mysql_query("SELECT * FROM user WHERE user_username = '$temp'");
        if( mysql_num_rows($result) > 0) {
            if(!empty($_FILES['BackgroundImageFile']['name'])){
                mysqli_query($database,$sql1)or die(mysqli_error($database));
                header("location:../edit-profile.php?user_username=$temp");
            }
        } 
        else {
            mysql_query($sql2)or die(mysql_error());
            header("location:../edit-profile.php?user_username=$temp");
        }
        $Destination = '../userfiles/avatars';
        if(!isset($_FILES['ImageFile']) || !is_uploaded_file($_FILES['ImageFile']['tmp_name'])){
            $NewImageName= 'default.png';
            move_uploaded_file($_FILES['ImageFile']['tmp_name'], "$Destination/$NewImageName");
        }
        else{
            $RandomNum   = rand(0, 9999999999);
            $ImageName = str_replace(' ','-',strtolower($_FILES['ImageFile']['name']));
            $ImageType = $_FILES['ImageFile']['type'];
            $ImageExt = substr($ImageName, strrpos($ImageName, '.'));
            $ImageExt = str_replace('.','',$ImageExt);
            $ImageName = preg_replace("/\.[^.\s]{3,4}$/", "", $ImageName);
            $NewImageName = $ImageName.'-'.$RandomNum.'.'.$ImageExt;
            move_uploaded_file($_FILES['ImageFile']['tmp_name'], "$Destination/$NewImageName");
        }
        $sql5="UPDATE user SET user_avatar='$NewImageName' WHERE user_username = '$temp'";
        $sql6="INSERT INTO user (user_avatar) VALUES ('$NewImageName') WHERE user_username = '$temp'";
        $result = mysql_query("SELECT * FROM user WHERE user_username = '$temp'");
        if( mysql_num_rows($result) > 0) {
            if(!empty($_FILES['ImageFile']['name'])){
                mysql_query($sql5)or die(mysqli_error($database));
                header("location:../edit-profile.php?user_username=$temp");
            }
        } 
        else {
            mysql_query($sql6)or die(mysql_error());
            header("location:../edit-profile.php?user_username=$temp");
        }  
        $user_firstname=$_REQUEST['user_firstname'];
        $user_lastname=$_REQUEST['user_lastname'];
        $user_email=$_REQUEST['user_email'];
        $user_password=$_REQUEST['user_password'];
        $user_servicetype=$_REQUEST['user_servicetype'];
        $user_address=$_REQUEST['user_address'];
        $user_description=$_REQUEST['user_description'];     
        $user_age=$_REQUEST['user_age'];
        $user_gender=$_REQUEST['user_gender'];
        $user_country=$_REQUEST['user_country'];
        $user_website=$_REQUEST['user_website'];
		$user_city=$_REQUEST['user_city'];
		$user_mobile=$_REQUEST['user_mobile'];
		$user_firmname=$_REQUEST['user_firmname'];
        $sql3="UPDATE user SET user_firstname='$user_firstname',user_lastname='$user_lastname',user_servicetype='$user_servicetype',user_address='$user_address',user_email='$user_email',user_password='$user_password',user_description='$user_description',user_age='$user_age',user_gender='$user_gender',user_country='$user_country',user_website='$user_website',user_mobile='$user_mobile',user_city='$user_city',user_firmname='$user_firmname' WHERE user_username = '$temp'";
            mysql_query($sql3)or die(mysql_error());
            header("location:../home.php?user_username=$temp&request=profile-update&status=success");
    }    
?>