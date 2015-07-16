<form action="components/update-profile.php" method="post" enctype="multipart/form-data" id="UploadForm">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs">
      <li class="active"><a href="#general" data-toggle="tab">General</a></li>
      <li><a href="#personal" data-toggle="tab">Professional</a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane fade in active" id="general">         
            <div class="col-md-6">
                <div class="form-group float-label-control">                      
                    <label for="">First Name</label>
                    <input type="text" class="form-control" placeholder="<?php echo $rws['user_firstname'];?>" name="user_firstname" value="<?php echo $rws['user_firstname'];?>" required>
                </div>
                <div class="form-group float-label-control">  
                    <label for="">Last Name</label>
                    <input type="text" class="form-control" placeholder="<?php echo $rws['user_lastname'];?>" name="user_lastname" value="<?php echo $rws['user_lastname'];?>" required>
                </div>
                <div class="form-group float-label-control">
                    <label for="">Avatar</label>
                    <input name="ImageFile" type="file" id="uploadFile"/>
                    <div class="col-md-6">
                        <div class="shortpreview">
                            <label for="">Previous Avatar </label>
                            <br> 
                            <img src="userfiles/avatars/<?php echo $rws['user_avatar'];?>" class="img-responsive">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="shortpreview" id="uploadImagePreview">
                            <label for="">Current Uploaded Avatar </label>
                            <br> 
                            <div id="imagePreview"></div>
                        </div>
                    </div>
                </div>
            </div>  
            <div class="col-md-6">
                <label for="">Username</label>
                <div class="form-group float-label-control">       
                        <div class="input-group">
                            <fieldset disabled> 
                                <input type="text" class="form-control" placeholder="<?php echo $rws['user_username'];?>" name="user_username" value="<?php echo $rws['user_username'];?>" id="disabledTextInput" autocomplete="off">
                            </fieldset>  
                        </div>
                    </a>
                </div>
                <div class="form-group float-label-control">
                    <label for="">Password</label>
                    <input type="password" class="form-control" placeholder="<?php echo $rws['user_password'];?>" name="user_password" value="<?php echo $rws['user_password'];?>" required>
                </div>
                <div class="form-group float-label-control">
                    <label for="">Email</label> 
                    <input type="text" class="form-control" placeholder="<?php echo $rws['user_email'];?>" name="user_email" value="<?php echo $rws['user_email'];?>" required>
                </div>  
            </div>
        </div>
        <div class="tab-pane fade" id="personal">
            <div class="col-md-6">
                <div class="form-group float-label-control">
                    <label for="">Description About Your Services</label>
                    <textarea class="form-control" placeholder="<?php echo $rws['user_description'];?>" rows="10" placeholder="<?php echo $rws['user_description'];?>" name="user_description" value="<?php echo $rws['user_description'];?>" required><?php echo $rws['user_description'];?></textarea>
                </div>
                <div class="form-group float-label-control">
                    <label for="">Birthday</label>   
                    <input type="date" class="form-control" placeholder="<?php echo $rws['user_age'];?>" name="user_age" value="<?php echo $rws['user_age'];?>" required>      
                </div>
                <div class="form-group float-label-control">
                    <label for="">Type of Services</label>
					<div class="dropdown">
					<select name="user_servicetype" value="<?php echo $rws['user_servicetype'];?>" placeholder="<?php echo $rws['user_servicetype'];?>" selected>
					<option value="">--Select Service Type--</option>
					<option value="wed-hall" <?php if($rws['user_servicetype']=="wed-hall") echo selected;?>>Wedding/Function Halls</option>
					<option value="pandit" <?php if($rws['user_servicetype']=="pandit") echo selected;?>>Priest</option> 
					<option value="cater" <?php if($rws['user_servicetype']=="cater") echo selected;?>>Caterers</option> 
					<option value="photo" <?php if($rws['user_servicetype']=="photo") echo selected;?>>Photography/Videography</option>
					<option value="florist" <?php if($rws['user_servicetype']=="florist") echo selected;?>>Florists</option>
					<option value="music" <?php if($rws['user_servicetype']=="music") echo selected;?>>Music and Entertainment</option> 
					<option value="wed-card" <?php if($rws['user_servicetype']=="wed-card") echo selected;?>>Wedding Cards</option> 
					<option value="astro" <?php if($rws['user_servicetype']=="astro") echo selected;?>>Astrologers</option> 
					<option value="decor" <?php if($rws['user_servicetype']=="decor") echo selected;?>>Decorators/Tent House</option> 
					<option value="sound" <?php if($rws['user_servicetype']=="sound") echo selected;?>>Sound and Lighting</option> 
					<option value="cars" <?php if($rws['user_servicetype']=="cars") echo selected;?>>Wedding Chariots</option>
					<option value="wed-plan" <?php if($rws['user_servicetype']=="wed-plan") echo selected;?>>Wedding Planners</option> 
					</select>
                     </div>  
                </div>  
                
                
            </div>
            <div class="col-md-6">
			<div class="form-group float-label-control">
                    <label for="">Company/Firm name</label>
                    <input type="text" class="form-control" placeholder="<?php echo $rws['user_firmname'];?>" name="user_firmname" value="<?php echo $rws['user_firmname'];?>" required>    
                </div>
			<label for="">Gender</label>              
                <div class="form-group float-label-control">
                    <div class="radio-inline">
                        <label>
                            <input type="radio" name="user_gender" id="optionsRadios1" value="Male" checked>Male</label>
                    </div>              
                    <div class="radio-inline">
                        <label>
                            <input type="radio" name="user_gender" id="optionsRadios1" value="Female">Female</label>
                    </div>
                </div>
			<div class="form-group float-label-control">
                    <label for="">Country</label>
					<div class="dropdown">
					<select placeholder="<?php echo $rws['user_country'];?>" name="user_country" value="<?php echo $rws['user_country'];?>" selected>
					<option value="" class="dropdown-header">--Select Country--</option>
					<option value="India" <?php if($rws['user_country']=="India") echo selected;?>>India</option>
					<option value="Other" <?php if($rws['user_country']=="Other") echo selected;?>>Other</option> 
					</select>
					</div>
                </div>
				<div class="form-group float-label-control">
                    <label for="">City</label>
					<div class="dropdown">
					<select placeholder="<?php echo $rws['user_city'];?>" name="user_city" value="<?php echo $rws['user_city'];?>" selected>
					<option value="">--Select City--</option>
					<option value="chennai" <?php if($rws['user_city']=="chennai") echo selected;?>>Chennai</option>
					<option value="bengaluru" <?php if($rws['user_city']=="bengaluru") echo selected;?>>Bengaluru</option>
					<option value="hyderabad" <?php if($rws['user_city']=="hyderabad") echo selected;?>>Hyderabad</option>
					</select>
					</div>
                </div>
                <div class="form-group float-label-control">
                    <label for="">Address</label>
                    <input type="text" class="form-control" placeholder="<?php echo $rws['user_address'];?>" name="user_address" value="<?php echo $rws['user_address'];?>" required>    
                </div>
				<div class="form-group float-label-control">
                    <label for="">Mobile No.</label>
                    <input type="INT" size="10" maxlength="10" class="form-control" placeholder="<?php echo $rws['user_mobile'];?>" name="user_mobile" value="<?php echo $rws['user_mobile'];?>" required>    
                </div>
                <label for="">Website</label>
                <div class="form-group float-label-control">
                    <div class="input-group">                  
                        <span class="input-group-addon">http://</span>
                        <input type="text" class="form-control" placeholder="<?php echo $rws['user_website'];?>" name="user_website" value="<?php echo $rws['user_website'];?>">                  
                    </div>
                </div> 
            </div>
        </div>
    </div>     
    <br>
    <div class="submit">
        <center>
            <button class="btn btn-primary ladda-button" data-style="zoom-in" type="submit"  id="SubmitButton" value="Upload" />Save Your Profile</button>
        </center>
    </div>
</form>