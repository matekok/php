<?php
class template{
    public $ini;
    public function __construct($ini) {
        $this->ini = $ini;  
        
        $this->ini->css[] = "css/bootstrap.css";
        $this->ini->css[] = "css/component.css";
        $this->ini->css[] = "css/style_grid.css";
        $this->ini->css[] = "css/style.css";
                //font-awesome-icons
 $this->ini->css[] = "css/font-awesome.css"; 
//font-awesome-icons
        $this->ini->css[] = "//fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800";
        $this->ini->JS->addJs('');
    }
    function getJs(){
//        $this->ini->JS->plugins[] = 'js/jquery.min.js';
//        $this->ini->JS->plugins[] = 'js/bootstrap.min.js';
//        $this->ini->JS->plugins[] = 'js/SmoothScroll.min.js';
//        $this->ini->JS->plugins[] = 'js/top.js';
//        $this->ini->JS->plugins[] = 'js/modernizr.min.js';
//        $this->ini->JS->plugins[] = 'js/index.js';
//        
//        $this->ini->JS->plugins[] = 'js/aos.js';
//        $this->ini->JS->plugins[] = 'js/aos2.js';
    }
    public function header() {
        $html = '<div class="wthree_agile_admin_info">
            <!-- /w3_agileits_top_nav-->
		  <!-- /nav-->
		  <div class="w3_agileits_top_nav">
			<ul id="gn-menu" class="gn-menu-main">

				<!-- //nav_agile_w3l -->
                <li class="second logo admin"><h1><a href="#"><i class="fa fa-graduation-cap" aria-hidden="true"></i>MattFrame </a></h1></li>
					
				<li class="second w3l_search admin_login">
				 
						<form action="#" method="post">
							<input type="search" name="search" placeholder="Search here..." required="">
							<button class="btn btn-default" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
						</form>
					
				</li>
				<li class="second full-screen">
					<section class="full-top">
						<button id="toggle"><i class="fa fa-arrows-alt" aria-hidden="true"></i></button>	
					</section>
				</li>

			</ul>
			<!-- //nav -->
			
		</div>
		<div class="clearfix"></div>
		<!-- //w3_agileits_top_nav-->';
        return $html;
    }
    public function footer() {
        $html = '<!--copy rights start here-->
<div class="copyrights">
	 <p>© 2017 MattFrame. All Rights Reserved | Design by  <a href="http://matekok.com/" target="_blank">matekok</a> </p>
</div>	
<!--copy rights end here--></div>';
        return $html;
    }
    public function LoginInner() {
        $html = '		<!-- /inner_content-->
				<div class="inner_content">
				    <!-- /inner_content_w3_agile_info-->
					<div class="inner_content_w3_agile_info">
					

							<div class="registration admin_agile">
								
												<div class="signin-form profile admin">
													<h2>Admin Login</h2>
													<div class="login-form">
														<form action="main-page.html" method="post">
															<input type="text" name="name" placeholder="Username" required="true">
															<input type="password" name="password" placeholder="Password" required="true">
															<div class="tp">
																<input type="submit" value="LOGIN">
															</div>
															
														</form>
													</div>
													<div class="login-social-grids admin_w3">
														<ul>
															<li><a href="#"><i class="fa fa-facebook"></i></a></li>
															<li><a href="#"><i class="fa fa-twitter"></i></a></li>
															<li><a href="#"><i class="fa fa-rss"></i></a></li>
														</ul>
													</div>
												
													 <h6><a href="main-page.html">Back To Home</a><h6>

													 
												</div>

					

				    </div>
					<!-- //inner_content_w3_agile_info-->
				</div>
		<!-- //inner_content-->';
        return $html;
    }
}
?>