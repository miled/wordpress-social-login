<?php
	global $wpdb;
?>

<style> 
.wsl_aside {  
    margin: 6px;  
}  
.wsl_notice {
    line-height: 1;
    padding: 8px;

	background-color: #eaffdc;
	border:1px solid #60cf4e; 
	border-radius: 3px;
	padding: 10px;      
	
	background-color: #FFFFE0;
	border:1px solid #E6DB55; 
} 
</style>

<div class="wsl_notice wsl_aside"> 
	<p>
		<strong>WordPress Social Login</strong> is introducing a new feature to give you some basic insight into users registration by provider, gender and age.
	</p>
</div>
 
<center>
	<table width="80%">
		<tr>
		<td valign="top" width="30%">
		<?php 
			$sql = "SELECT meta_value, count( * ) as items FROM `{$wpdb->prefix}usermeta` where meta_key = 'wsl_user' group by meta_value order by items desc ";

			$rs1 = $wpdb->get_results( $sql );  
			
			$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/16x16/';
		?>
		<h3 style="border-bottom:1px solid #ccc"> By provider</h3>
		<table width="90%">
			<?php
				$total_users = 0;
				foreach( $rs1 as $item ){
					if( ! $item->meta_value ) $item->meta_value = "Unknown";
					
					$total_users += (int) $item->items;
				?>
					<tr>
						<td width="60%">
							<img src="<?php echo $assets_base_url . strtolower( $item->meta_value ) . '.png' ?>" style="vertical-align:top;width:16px;height:16px;" /> <b><?php echo $item->meta_value; ?></b>
						</td>
						<td>
							<?php echo $item->items; ?>
						</td>
					</tr>
				<?php
				}
			?>
				<tr>
					<td>
						&nbsp;
					</td>
					<td style="border-top:1px solid #ccc">
						<b><?php echo $total_users; ?></b> WSL users
					</td>
				</tr>
		</table>

		</td>
		<td valign="top" width="30%">

		<?php 
			$sql = "SELECT meta_value, count( * ) as items FROM `{$wpdb->prefix}usermeta` where meta_key = 'wsl_user_gender' group by meta_value order by items desc "; 

			$rs = $wpdb->get_results( $sql ); 
		?>
		<h3 style="border-bottom:1px solid #ccc">By gender</h3>
		<table width="60%">
			<?php
				foreach( $rs as $item ){
					if( ! $item->meta_value ) $item->meta_value = "Unknown";
				?>
					<tr>
						<td>
							<b><?php echo ucfirst( $item->meta_value ); ?></b>
						</td>
						<td>
							<?php echo $item->items; ?>
						</td>
					</tr>
				<?php
				}
			?>
		</table>

		</td>
		<td valign="top" width="30%">

		<?php 
			$sql = "SELECT meta_value, count( * ) as items FROM `{$wpdb->prefix}usermeta` where meta_key = 'wsl_user_age' group by meta_value order by items desc limit 21"; 

			$rs = $wpdb->get_results( $sql ); 
		?>
		<h3 style="border-bottom:1px solid #ccc">By age</h3>
		<table width="60%">
			<?php
				foreach( $rs as $item ){
					if( ! $item->meta_value ) $item->meta_value = "Unknown";
				?>
					<tr>
						<td>
							<b><?php echo $item->meta_value; ?></b>
						</td>
						<td>
							<?php echo $item->items; ?>
						</td>
					</tr>
				<?php
				}
			?>
		</td>
		</tr>
		</table>
	</table>
 

</center>
