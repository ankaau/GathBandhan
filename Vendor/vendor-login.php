<?php include 'components/session-check-index.php' ?>
<?php include 'controllers/base/head.php' ?>
<div class="container">
    <div class="row">
      <div class="main">
	      <img src="../images/logo4.png">
          <h3 style="color:#65aeee;">Vendor Log In</h3>
          <form role="form" action="components/login-process.php" method="post" name="login">
              <div class="form-group">
                  <label for="inputUsernameEmail">Username or E-mail</label>
                  <input type="text" class="form-control" id="inputUsernameEmail" name="username" placeholder="Enter Username/Email">
              </div>
              <div class="form-group">
                  <label for="inputPassword">Password</label>
                  <input type="password" class="form-control" id="inputPassword" name="password" placeholder="Enter Password">
              </div>
              <center><button type="submit" class="btn btn btn-primary ladda-button" data-style="zoom-in" value="Sign In" name="login_button">
                  Sign In  
              </button></center>
          </form>
        </div>
    </div>
</div>