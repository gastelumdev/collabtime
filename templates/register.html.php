<!-- REGISTER.HTLM.PHP -->
<div id="login" class="auth">
	<div class="header">
		<a href="index.php"><img src="images/logo.svg" alt="logo" width="50px" height="50px"></a>
		<h1>Let's Get Started</h1>
	</div>
	<div class="nav">
		<div class="nav-item">
			<a href="index.php?login">Login</a>
		</div>
		<div class="nav-item active">
			<p>Register</p>
		</div>
	</div>
	<div class="body">
		<div class="container">
			<form action="index.php?user/register" method="post" enctype="multipart/form-data">
				<div class="form-group">
					<label for="firstname">Firstname <span class="alert"><?=$errors['firstname'] ?? ''?></span></label>
					<input type="text" class="form-control form-control-sm" id="firstname" name="user[firstname]" placeholder="Enter first name">
				</div>
				<div class="form-group">
					<label for="lastname">Lastname <span class="alert"><?=$errors['lastname'] ?? ''?></span></label>
					<input type="text" class="form-control form-control-sm" id="lastname" name="user[lastname]" placeholder="Enter last name">
				</div>
				<div class="form-group">
					<label for="email">Email address <span class="alert"><?=$errors['email'] ?? ''?></span></label>
					<input type="email" class="form-control form-control-sm" id="email" name="user[email]" placeholder="Enter email">
				</div>
				<div class="form-group">
					<label for="password">Password <span class="alert"><?=$errors['password'] ?? ''?></span></label>
					<input type="password" class="form-control form-control-sm" id="password" name="user[password]" placeholder="Password">
				</div>
				<div class="custom-file">
					<input type="file" class="custom-file-input" name="userImage" id="customFile" accept=".jpg">
					<label id="customFileLabel" class="custom-file-label" for="customFile">Profile Image</label>
					<span class="alert"><?=$errors['userImage'] ?? ''?></span>
				</div>
				<button type="submit" class="btn btn-primary">Sign Up</button>
			</form>
		</div>
	</div>
</div>