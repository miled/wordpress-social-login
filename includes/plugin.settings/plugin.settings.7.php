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
    <h3 style="margin: 0 0 5px;">Notes</h3>

	<ul style="list-style:circle inside;margin-left:25px;margin-top:10px;">
		<li><strong>WSL</strong> Plugin is introducing a new feature to give you some basic insight into users registration by provider, gender and age. </li>
		<li><strong>WSL</strong> Insights uses <em>Highcharts JavaScript library</em>. In order to use this plug in for commercial website(s), Hicharts licence and pricing policies may apply. Read more at <a href="http://www.highcharts.com/license">http://www.highcharts.com/license</a>.</li>
	</ul>  
</div>
 
 
<script type='text/javascript' src='<?php echo WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL ?>/assets/js/highcharts.js'></script>

<script type="text/javascript">
jQuery(function () { 
    jQuery(document).ready(function() {
	
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'chart_1',
                type: 'spline'
            },
            title: {
                text: 'WSL Users Evolution'
            },
            subtitle: {
                text: 'Year <?php echo date("Y") ?>'
            },
            xAxis: {
                type: 'datetime',
                dateTimeLabelFormats: { 
                    month: '%b',
                    year: '%b'
                }
            },
            yAxis: {
                title: {
                    text: 'nb. users'
                },
                min: 0
            },
            tooltip: {
                formatter: function() {
                    if( this.x ) return '<b>'+ this.series.name +'</b><br/>'+ Highcharts.dateFormat('%b', this.x) +': '+ this.y +' agents';
                    return '<b>'+ this.series.name +'</b><br/>'+ this.y +' agents';
                }
            }, 
            series: 
			[
			{
                name: 'Total users', 
                type: 'areaspline',
				data: 
				[
					<?php
						for( $i = 0; $i<date("m")+1; $i++){ 
							$j=$i+2;
							$date   = date("Y") . "-" . ($j<10?"0$j":$j) . "-01" ;  
							$sql = "SELECT count(*) as counts FROM `{$wpdb->prefix}usermeta` m, `{$wpdb->prefix}users` u where meta_key = 'wsl_user' and m.user_id = u.id and user_registered between '" . date("Y") . "-01-01' and '$date' "; 
							$rs1 = $wpdb->get_results( $sql );   
							?>
								[Date.UTC(<?php echo date("Y"); ?>,  <?php echo $i; ?>, 1 ), <?php echo (int) $rs1[0]->counts; ?>  ],
							<?php 
						}
					?> 
                ]
            }
			,
			{
                name: 'New users',
                type: 'column',
				data: 
				[
					<?php
						for( $i = 0; $i<date("m")+1; $i++){ 
							$j=$i+1;
							$date   = date("Y") . "-" . ($j<10?"0$j":$j) . "-" ; 
							$sql = "SELECT count(*) as counts FROM `{$wpdb->prefix}usermeta` m, `{$wpdb->prefix}users` u where meta_key = 'wsl_user' and m.user_id = u.id and user_registered like '$date%' ";  
							$rs1 = $wpdb->get_results( $sql );
							if( $rs1[0]->counts ){
								?>
									[Date.UTC(<?php echo date("Y"); ?>,  <?php echo $i; ?>, 1 ), <?php echo (int) $rs1[0]->counts; ?>  ],
								<?php 
							}
						}
					?> 
                ]
            } 
			]
        });
	
		chart = new Highcharts.Chart({
			chart: {
				renderTo: 'chart_2' 
			},
			title: {
				text: 'WSL Users by Gender'
			},
			tooltip: {
				formatter: function() { 
					return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(Math.abs(this.percentage), 2) +' %';
				}
			}, 
			series: [{
				type: 'pie',
				name: 'domain',
				data: 
					[
					<?php
						$sql = "SELECT meta_value, count( * ) as items FROM `{$wpdb->prefix}usermeta` where meta_key = 'wsl_user_gender' group by meta_value order by items desc"; 
						$rs1 = $wpdb->get_results( $sql );

						foreach( $rs1 as $item ){
							if( ! $item->meta_value ) $item->meta_value = "Unknown";
						?>
							[ '<?php echo ucfirst( $item->meta_value ); ?>', <?php echo (int) $item->items; ?> ], 
						<?php
						}
					?>  
					]
			}]
		}); 
	
		chart = new Highcharts.Chart({
			chart: {
				renderTo: 'chart_3' 
			},
			title: {
				text: 'WSL Users by Provider'
			},
			tooltip: {
				formatter: function() { 
					return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(Math.abs(this.percentage), 2) +' %';
				}
			}, 
			series: [{
				type: 'pie',
				name: 'domain',
				data: 
					[
					<?php
						$sql = "SELECT meta_value, count( * ) as items FROM `{$wpdb->prefix}usermeta` where meta_key = 'wsl_user' group by meta_value order by items desc"; 
						$rs1 = $wpdb->get_results( $sql );

						foreach( $rs1 as $item ){
							if( ! $item->meta_value ) $item->meta_value = "Unknown";
						?>
							[ '<?php echo ucfirst( $item->meta_value ); ?>', <?php echo (int) $item->items; ?> ], 
						<?php
						}
					?>  
					]
			}]
		}); 
		
		var categories = [ 'Inconnu', '13-17', '18-24', '25-34', '35-44', '45-54', '55+' ];
		chart = new Highcharts.Chart({
			chart: {
				renderTo: 'chart_4',
				type: 'bar'
			},
			title: {
				text: 'WSL users by age and gender'
			},
			subtitle: {
					text: 'Unfinished yet..'
			},
			xAxis: [{
				categories: categories,
				reversed: false
			}, { // mirror axis on right side
				opposite: true,
				reversed: false,
				categories: categories,
				linkedTo: 0
			}],
			yAxis: {
				title: {
					text: null
				},
				labels: {
					formatter: function(){
						return Math.abs(this.value)  ;
					}
				} 
			},

			plotOptions: {
				series: {
					stacking: 'normal'
				}
			},

			tooltip: {
				formatter: function(){
					return '<b>'+ this.series.name +', age '+ this.point.category +'</b><br/>'+
						'Population: '+ Highcharts.numberFormat(Math.abs(this.point.y), 0) + ' users';
				}
			},

			series: [{
				name: 'Hommes', 
				data: 
					[ 
													-57,
															null,
																-12,
																-6,
																-1,
																null,
																null,
								 
					]
			}, {
				name: 'Femmes',
				data: 
					[ 
													10,
															null,
																null,
																3,
																null,
																null,
																null,
								 
					]
			}]
		});

 
	});
});
</script> 


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
							<img src="<?php echo $assets_base_url . strtolower( $item->meta_value ) . '.png' ?>" style="vertical-align: top;" /> <b><?php echo $item->meta_value; ?></b>
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

	<table width="80%">
		<tr>
			<td valign="top"> 
				<hr />
				<div id="chart_1" style="min-width: 470px; height: 400px; margin: 0 auto"></div>
				<hr />
				<div id="chart_2" style="min-width: 470px; height: 400px; margin: 0 auto"></div>
				<hr />
				<div id="chart_3" style="min-width: 470px; height: 400px; margin: 0 auto"></div>
				<!--
				eh ya men3ech
				<hr />
				<div id="chart_4" style="min-width: 470px; height: 400px; margin: 0 auto;"></div>
				-->
			</td>
		</tr> 
	</table>

</center>
