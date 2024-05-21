	
	<div class="limiter">
		<div class="container-login100" style="background-image: url('<?= staticUrl('themes/mievent/img/slider/0.jpg') ?>');">
			<div class="wrap-login100">
				<form id="form-login" action="<?= base_url('auth/login') ?>" method="POST" class="login100-form validate-form">
					<span class="login100-form-logo">
						<img style="width: 95%;" src="<?= staticUrl('img/sideImage/login2.svg') ?>">
					</span>

					<span class="login100-form-title p-b-34 p-t-27">
						Log in
					</span>

					<div class="wrap-input100 validate-input" data-validate = "Masukkan username atau email">
						<input class="input100" type="text" name="username" placeholder="Username">
						<span class="focus-input100" data-placeholder="&#xf207;"></span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Enter password">
						<input class="input100" type="password" name="password" placeholder="Password">
						<span class="focus-input100" data-placeholder="&#xf191;"> <i class="fa fas-user"></i> </span>
					</div>
					<p id="alert_danger" class="text-white" style="display: none;"></p>
					<div class="container-login100-form-btn">
						<button class="login100-form-btn">
							Login
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>