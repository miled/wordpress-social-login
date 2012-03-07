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

			$rs1 = $wpdb->get_results( $sql );  
			
			$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/16x16/';
		?>
		<h3 style="border-bottom:1px solid #ccc"> By provider</h3>
		<table width="60%">
			<?php
				foreach( $rs1 as $item ){
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
				<?php
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
				<?php
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
				<?php
				}
			?>
		</td>
		</tr>
		</table>
	</table>
	
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Providers');
        data.addColumn('number', 'Users'); 
        data.addRows([ 
		<?php 
			$nb_users = 0; 
			foreach( $rs1 as $item ){
				$nb_users += (int) $item->items;
				if( ! $item->meta_value ) $item->meta_value = "Unknown";
			?>
				[ '<?php echo $item->meta_value; ?>', <?php echo $item->items; ?> ], 
			<?php
			}
		?> 
			[ "Total", <?php echo $nb_users; ?> ]
        ]);

        var options = {};

        var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
	<div id="chart_div" style="width: 900px; height: 500px;"></div>

</center>
