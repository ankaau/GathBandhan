<?php include 'components/authentication.php' ?>   
<?php include 'components/session-check.php' ?>
<?php include 'controllers/base/head.php' ?>
<?php include 'controllers/navigation/first-navigation.php' ?> 
<?php
    if($_POST){
        $query=$_POST['search-form'];
        $sql=mysql_query("select * from user where user_firstname like '%$query%' or user_lastname like '%$query%' order by user_id");
        $number=mysql_num_rows($sql);
    }
?>
                            <div id="content">
                              <div class="row">
                                  <div class="col-md-12">
                                      <div class="panel panel-default" style="margin: 20px 0px;">
                                          <div class="panel-heading">                                 
<?php 
    if($number > 1){ 
?>
        <h3 class="panel-title"><?php echo $number; ?> Results for "<?php echo $query; ?>"</h3>
<?php     
    }
    else{
?>
         <h3 class="panel-title"><?php echo $number; ?> Result for "<?php echo $query; ?>"</h3>                                 
<?php     
    }
?>
    
                                          </div>
                                          <div class="panel-body">
                                              <div class="row">
                                                  <div class="container">
                                                      <div class="row clearfix">
                                                          <div class="col-md-12 column">
                                                              <div class="row clearfix">
<?php
    if($_POST){
        $query=$_POST['search-form'];
        $sql=mysql_query("select * from user where user_firstname like '%$query%' or user_lastname like '%$query%' order by user_id");
        if( mysql_num_rows($sql) > 0) {
            while($rws = mysql_fetch_array($sql)){
?>
                                                                    <div class="panel-group" id="panel-<?php echo $rws['user_id']; ?>">
                                                                        <div class="panel panel-default">
                                                                            <div class="panel-heading">
                                                                                 <a class="panel-title" data-toggle="collapse" data-parent="#panel-<?php echo $rws['user_id']; ?>" href="#panel-element-<?php echo $rws['user_id']; ?>"><?php echo $rws['user_firstname'];?> <?php echo $rws['user_lastname'];?><button type="button" class="close" data-dismiss="panel"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button></a>
                                                                            </div>
                                                                            <div id="panel-element-<?php echo $rws['user_id']; ?>" class="panel-collapse collapse in">
                                                                                <div class="panel-body">
                                                                                    <div class="col-md-6 column">
																						<h2><?php echo $rws['user_firmname'];?></h2>
                                                                                        <img src="userfiles/avatars/<?php echo $rws['user_avatar'];?>" name="aboutme" class="img-responsive">                                  
                                                                                    </div>
                                                                                    <div class="col-md-6 column">
                                                                                        <div class="col-md-12 column">
                                                                                            <h2><span><?php echo $rws['user_firstname'];?> <?php echo $rws['user_lastname'];?></span></h2>
                                                                                            <hr>
                                                                                            <p><?php echo $rws['user_description'];?></p>
                                                                                            <hr>
                                                                                        </div>
                                             
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
<?php 
            } 
        }
        else{
?>
                                                                                <center>
                                                                                    <h1>No Results to show</h1>
                                                                                </center>
<?php      
        }
    }                                                              
?>                                                                
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div>                                        
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>