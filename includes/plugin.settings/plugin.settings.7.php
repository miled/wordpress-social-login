<?php
	global $wpdb;
?>

<p style="margin:10px;font-size: 14px;"> 
	<strong>WordPress Social Login</strong> is introducing a new feature to give you some basic insight into users registration by provider, gender and age. 
</p>

<center>
	<table width="80%">
		<tr>
		<td valign="top">
		<?php 
			$sql = "SELECT meta_value, count( * ) as items FROM `{$wpdb->prefix}usermeta` where meta_key = 'wsl_user' group by meta_value order by items desc ";

			$rs = $wpdb->get_results( $sql );  
			
			$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/16x16/';
		?>
		<h3 style="border-bottom:1px solid #ccc"> By provider</h3>
		<table width="60%">
			<?php
				foreach( $rs as $item ){
					if( ! $item->meta_value ) $item->meta_value = "Unknown";
				?>
					<tr>
						<td>
							<img src="<?php echo $assets_base_url . strtolower( $item->meta_value ) . '.png' ?>" style="vertical-align: top;" /> <b><?php echo $item->meta_value; ?></b>
						</td>
						<td>
							<?php echo $item->items; ?>
						</td>
					</tr>
				<?
				}
			?>
		</table>

		</td>
		<td valign="top">

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
				<?
				}
			?>
		</table>

		</td>
		<td valign="top">

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
				<?
				}
			?>
		</td>
		</tr>
		</table>
	</table>
</center>
