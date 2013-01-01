<style type="text/css"> 
#wsl_setup_form .inputgnrc, #wsl_setup_form select {
    font-size: 15px;
    padding: 6px 3px; 
    border: 1px solid #CCCCCC;
    border-radius: 4px 4px 4px 4px;
    color: #444444;
    font-family: arial;
    font-size: 16px;
    margin: 0;
    padding: 3px;
    width: 300px;
} 
#wsl_setup_form .inputsave {
    font-size: 15px;
    padding: 6px 3px;  
    color: #444444;
    font-family: arial;
    font-size: 18px;
    margin: 0;
    padding: 3px;
    width: 400px;
	height: 40px;
} 
#wsl_setup_form ul {
    list-style: none outside none; 
}
#wsl_setup_form .cgfparams ul {
    padding: 0;
	margin: 0;
}
#wsl_setup_form ul li {
    color: #555555;
    font-size: 13px;
    margin-bottom: 10px;
    padding: 0;
}
#wsl_setup_form ul li label {
    color: #000000;
    display: block;
    font-size: 14px;
    font-weight: bold;
	padding-bottom: 5px;
}
#wsl_setup_form .cfg { 
	background: #f5f5f5;
	float: left;
	border-radius: 2px 2px 2px 2px;
	border: 1px solid #AAAAAA;
	margin: 0 0 0 30px;
}
#wsl_setup_form .cgfparams {
   padding: 20px;
   float: left;
}
#wsl_setup_form .cgftip {
   border-left: 1px solid #aaa;
   margin-left: 340px;
   padding: 20px;
   min-height: 202px; 
   width: 770px;
   width: 600px; 
   padding-top: 1px;


   width: 450px; 
} 

/* tobe fixed .. */
#footer {
    display:none; 
}
#wsl_setup_form p {
	font-size: 14px;
}
.wsl_label_notice {
    background-color: #BFBFBF; 
    border-radius: 3px 3px 3px 3px;
    color: #FFFFFF;
    font-size: 9.75px;
    font-weight: bold;
    padding: 1px 3px 2px;
    text-transform: uppercase;
    white-space: nowrap;
}
</style> 

<h2 style="padding-bottom: 10px;">WordPress Social Login 
	<span class="wsl_label_notice">A Forever Beta Plugin</span>
	
<?php
	if( get_option( 'wsl_settings_development_mode_enabled' ) ){
		?>
			<small style="color:red;font-size: 14px;">(Development Mode On)</small>
		<?php
	}
?>
</h2>  
<h2 class="nav-tab-wrapper">
	&nbsp;
	<a class="nav-tab <?php if( $wslp == 1 ) echo "nav-tab-active"; ?>" href="options-general.php?page=wordpress-social-login&wslp=1">Overview</a>
	<a class="nav-tab <?php if( $wslp == 4 ) echo "nav-tab-active"; ?>" href="options-general.php?page=wordpress-social-login&wslp=4">Providers setup</a> 
	<a class="nav-tab <?php if( $wslp == 5 ) echo "nav-tab-active"; ?>" href="options-general.php?page=wordpress-social-login&wslp=5">Customize</a>   
	<a class="nav-tab <?php if( $wslp == 6 ) echo "nav-tab-active"; ?>" href="options-general.php?page=wordpress-social-login&wslp=6">Insights</a>   
	<a class="nav-tab <?php if( $wslp == 3 ) echo "nav-tab-active"; ?>" style="float:right" href="options-general.php?page=wordpress-social-login&wslp=3">Diagnostics</a>  
	<a class="nav-tab <?php if( $wslp == 2 ) echo "nav-tab-active"; ?>" style="float:right" href="options-general.php?page=wordpress-social-login&wslp=2">Help</a> 
</h2>

