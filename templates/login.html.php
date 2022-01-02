<div id="login" class="auth">
	<div class="header">
		<a href="index.php"><img src="images/collabtime4.png" alt="logo" width="75px" height="80px"></a>
		<h1>Let's Get Started</h1>
	</div>
	<div class="nav">
		<div class="nav-item active">
			<p>Login</p>
		</div>
		<!-- <div class="nav-item">
			<a href="index.php?user/register">Register</a>
		</div> -->
	</div>
	<div class="body">
		<div class="container">
			<form action="" method="post">
				<div class="form-group">
					<label for="email">Email address <span class="alert"><?=$error ?? ''?></span></label>
					<input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Enter email" required>
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
				</div>
				<button type="submit" name="login" class="btn btn-primary">Login</button>
			</form>
		</div>
	</div>
</div>