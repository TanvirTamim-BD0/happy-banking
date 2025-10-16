<div class="d-flex align-items-center justify-content-between">
	<div class="toggle_left_menu">
		<i onclick="controlLeftMenu()" class="fa fa-bars"></i>
	</div>
	<div class="d-flex align-items-center">
		<a class="d-flex align-items-center" href="#">
			<img class="img_sm" src="{{asset('backend')}}/assets/images/logo.png" alt="logo" />
			<span>WB Software</span>
		</a>
		<div class="dropdown ms-2">
			<a class="dropdown-toggle text-white" href="javascript:void(0)" role="button" id="dropdownMenuLink"
				data-bs-toggle="dropdown" aria-expanded="false">
				New
			</a>

			<ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
				<li><a class="dropdown-item" href="{{route('mobile-wallet.index')}}">Mobile Wallet</a></li>
				<li><a class="dropdown-item" href="{{route('banks.index')}}">Bank</a></li>
			</ul>
		</div>
	</div>
	<div class="profile">
		<div id="profile_pic" onclick="toggleClass('profile_content')" class="profile_pic">
			<img class="img_sm" src="https://i.ibb.co/N61Bhkv/avater.jpg" alt="" />
		</div>
		<div id="profile_content" class="profile_content">
			<div class="w-140">
				<div class="d-flex align-items-center border-bottom border-secondary">
					<img src="https://i.ibb.co/N61Bhkv/avater.jpg" alt="">
					<ul>
						<li><a href="#">Jahid</a></li>
						<li><span class="slogan">Web Developer</span></li>
					</ul>
				</div>
				<ul class="pofile_link">
					<li><a href="{{route('user-profile')}}">Edit Profile</a></li>
					<li><a href="{{route('logout')}}">Logout</a></li>
				</ul>
			</div>
		</div>
	</div>
</div>