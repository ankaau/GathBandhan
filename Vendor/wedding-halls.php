<div class="main">
<img src="../images/logo4.png">
<h6 style="color:#65aaaa;"><b>&nbsp;&nbsp;&nbsp;&nbsp;Best Services is our only motto!</b></h6>
</div>
<center></center>
<?php include 'controllers/base/head.php' ?>
<div class="container">
                                                      <div class="row clearfix">
                                                          <div class="col-md-12 column">
                                                              <div class="row clearfix">
<?php
    include '_database/database.php';
    session_start();
	$sql = "SELECT * FROM user WHERE user_servicetype = 'wed-hall' order by user_id desc";
    $result = mysql_query($sql) or die(mysql_error());
	 while($rws = mysql_fetch_array($result)){ 
?>
                                                                  <div class="col-md-6 column">
                                                                    <div class="panel-group" id="panel-<?php echo $rws['user_id']; ?>">
                                                                        <div class="panel panel-default">
                                                                            <div id="panel-element-<?php echo $rws['user_id']; ?>" class="panel-collapse collapse in">
                                                                                <div class="panel-body">
                                                                                    <div class="col-md-6 column">
																					<b><?php echo $rws['user_firmname'];?></b>
                                                                                        <img src="userfiles/avatars/<?php echo $rws['user_avatar'];?>" name="aboutme" class="img-responsive">  
																						<label>Mobile No.</label><?php echo $rws['user_mobile'];?>
                                                                                    </div>
                                                                                    <div class="col-md-6 column">
                                                                                        <h4><span><?php echo $rws['user_firstname'];?> <?php echo $rws['user_lastname'];?></span></h4>
																						<label>Description:</label>
																						<p><?php echo $rws['user_description'];?></p>
																						<label>Address:</label>
																						<p><?php echo $rws['user_address'];?></p>
																				   </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                  </div>
 <?php } ?>                                                         
                                                              </div>
                                                          </div>
                                                      </div>
                                                  </div>