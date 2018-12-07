<?php 
/*
Plugin Name: AA插件创建数据库的练习
Description: 启用插件时新建一个数据库
Version: 1.0.0
*/

register_activation_hook(__FILE__, 'qfl_test_wpdb');
function qfl_test_wpdb() {
	global $wpdb;
	if ($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}slider'")!= "{$wpdb->prefix}slider") {
		$sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}slider` (
			`id`int(11) NOT NULL auto_increment COMMENT '编号',
			`name` varchar(30) DEFAULT '' COMMENT '名称',
			`description` varchar(100)  DEFAULT '' COMMENT '描述',    
			PRIMARY KEY (`id`)   			
		) DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;";
		$wpdb->query($sql);
	}
	

	if ($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}slidermeta'") != "{$wpdb->prefix}slidermeta") {
		$sql1 = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}slidermeta` (
			`id` int(11) NOT NULL auto_increment COMMENT '编号',
			`slider_id` int(11) COMMENT '轮播图id',
			`slider_meta_key` varchar(255) NOT NULL COMMENT '轮播图键名',
			`slider_meta_value` longtext NOT Null COMMENT '轮播图键值',
			PRIMARY KEY (`id`)
		)  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;";
		$wpdb->query($sql1);
	}   	
}
add_action('admin_enqueue_scripts', 'qfl_enqueue_style');
function qfl_enqueue_style() {
	wp_enqueue_style('fontawesomecss', plugins_url('css/font-awesome.min.css', __FILE__), false);	
	wp_enqueue_style('swipercss', plugins_url('css/swiper.min.css', __FILE__), false);
	wp_enqueue_style('index', plugins_url('css/index.css', __FILE__), false);
}
add_action('admin_enqueue_scripts', 'qfl_slider_script');
function qfl_slider_script() {
	wp_enqueue_script('qflslider', plugins_url('js/qfl-slider.js', __FILE__), array('jquery'), false, false);
}
add_action('admin_menu', 'qfl_create_slider_menu');
function qfl_create_slider_menu() {
	add_menu_page(
		'轮播图设置首页',
		'轮播图',
		'manage_options',
		'qfl_slider_setting', 
		'qfl_slider_settings_page'
	);
}
function qfl_slider_settings_page() {
	global $wpdb;
	// $curSrc =  'http://'.$_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] ;
	// echo $curSrc;
	// $changenum =  $_GET['count'];
	$sql = "REPLACE INTO `{$wpdb->prefix}slider` VALUES (1, 'homeslider', '首页用的轮播图');";
	$wpdb->query($sql);
	$sliders = $wpdb->get_results("SELECT * FROM `" . $wpdb->prefix ."slider`");
	echo "<pre>";
	print_r($sliders);
	echo "</pre>";
	$slidernum = count($sliders);
	echo $slidernum;
	foreach ($sliders as $slider) {
		echo "<pre>";
		print_r($slider);
		echo "</pre>";
	}
	$sql = "REPLACE INTO `{$wpdb->prefix}slidermeta` VALUES (1, 1, 'imagesrc', '../imagees/1.jpg');";
	$wpdb->query($sql);
	$slider_metas = $wpdb->get_results("SELECT * FROM `" . $wpdb->prefix ."slidermeta`"); 
	
	?>
		<div class="wrap">
			<div style="margin-bottom: 1rem">
				<h1 class="wp-heading-inline">轮播图</h1>
				<a href="<?php echo $curSrc; ?>?page=qfl_slider_setting&count=1" class="page-title-action">添加轮播图</a>
				<a href="<?php echo $curSrc;?>?page=qfl_slider_setting&count=-1" class="page-title-action">删除轮播图</a>
				<hr class="wp-header-end">	
			</div>			
			<?php 
				// if (get_option('qflslidernum')) {
				// 	$slidernum = get_option('qflslidernum');
				// } else {
				// 	$slidernum = 1;
				// }
				
				// $slider = array();
				// if ($slidernum + $changenum > 0) {
				// 	// echo $lidernum - $changenum;
				// 	$slidernum += $changenum;
				// 	update_option('qflslidernum', $slidernum);
				// }				
				// for ($j = 1; $j <= $slidernum; $j++) { 
				$count = 0;
				foreach ($sliders as $slider) {
					$count++;
					?>
					<div class="slider-wrap slider-wrap-<?php echo $count; ?>">
						<div class="head">
							<h3>第 <?php echo $count; ?> 组轮播图(点击右侧设置按钮)</h3>
							<span class="slider-setting">设置<span></span></span>
						</div>
						<div class="content">
							<form action="<?php echo $curSrc;?>/?page=qfl_slider_setting" method="post">
								<h4>第一步：设置轮播图类名和图片数量,点击<em>保存设置</em></h4>
								<?php
									$imagenumkey = 'imagenum' . $j;
									$imagenum = get_option($imagenumkey);
									if (!imagenum) {
										$imagenum = 1;
									}							
									if (!empty($_POST) && check_admin_referer('qfl_slider_nonce')) {
										if ($_POST[$imagenumkey]) {
											update_option($imagenumkey, $_POST[$imagenumkey]);
										}								
										$imagenum = get_option($imagenumkey);
									}
								?>
								<div class="qfl-row">
									<div class="qfl-per2 image-text-width">
										<label for="sliderclassName">轮播图类名：</label>
										<input type="text" id="sliderclassName" name="sliderclassName">
									</div>
									<div class="qfl-per2 image-text-width">
										<label for="sliderremarks">轮播图备注：</label>
										<input type="text" id="sliderremarks" name="sliderremarks">
									</div>
								</div>
								<h4>第二步：设置轮播图图片参数,再点击<em>保存设置</em></h4>

								<?php for ($i = 1; $i <= $imagenum; $i++) { ?>
								<!-- $subcount = 0;
								}	 -->	
								<?php

				            		$slider['imagesrckey' . $i] = 'imagesrc' . $j . $i;
				            		$slider['imagelinkey' . $i] = 'imagelinkey' . $j . $i;
				            		if ($_POST[$slider['imagesrckey' . $i]]) {
					        			update_option($slider['imagesrckey' . $i], $_POST[$slider['imagesrckey' . $i]]);
					        			update_option($slider['imagelinkey' . $i], $_POST[$slider['imagelinkey' . $i]]);		            			
				            		}

									$slider['imagesrc' . $i] = get_option($slider['imagesrckey' . $i]);
									$slider['imagelink' . $i] = get_option($slider['imagelinkey' . $i]);


								?>	
								<div class="qfl-row">
									<div class="qfl-per3 image-text-width imagesrc">
										<span>图片<?php echo $i; ?>：</span>
										<label for="<?php echo $slider['imagesrckey' . $i]; ?>">链接地址：</label>
										<input type="text" id="<?php echo $slider['imagesrckey' . $i]; ?>" name="<?php echo $slider['imagesrckey' . $i]; ?>" value="<?php echo $slider['imagesrc' . $i]; ?>">
									</div>
									<div class="qfl-per3 image-text-width imagelink">
										<label for="<?php echo $slider['imagelinkey' . $i] ?>">跳转链接：</label>
										<input type="text" id="<?php echo $slider['imagelinkey' . $i]; ?>" name="<?php echo $slider['imagelinkey' . $i]; ?>" value="<?php echo $slider['imagelink' . $i]; ?>" >
									</div>
									<div class="qfl-per3 image-text-width imagelink">
										<label for="<?php echo $imagenumkey; ?>">链接alt值：</label>
										<input type="text" id="<?php echo $imagenumkey; ?>" name="<?php echo $imagenumkey; ?>" value="<?php echo $imagenum; ?>">
									</div>
								</div>
								<?php } ?>
								<p>
									<input type="submit" name="submit" value="保存设置">
								</p>
								<?php wp_nonce_field('qfl_slider_nonce');// 输出一个验证信息?>
								<!--<div class="qfl-row">
									<input type="submit" value="保存设置">
								</div> -->

							</form>
						</div>
					</div>

					<?php 
				} 
					?>

						<script type="text/javascript">
							jQuery(document).ready(function ($) {
								$('.slider-wrap').find('.head').click(function () {

									// console.log($(this).parent().find('.content'));
									var content = $(this).parent().find('.content');
									console.log(content.css('display'));
									if (content.css('display') === 'none') {
										content.css('display', 'block');
									} else if (content.css('display') === 'block') {
										content.css('display', 'none');
									}
									// $(this).parent().find('.content').toggle();
								})
							})
						</script>

			<div  style="text-align: right; " >
				<a href="#" style="margin-left: 4px;
    padding: 4px 8px;
    position: relative;
    top: 13px;
    text-decoration: none;
    border: none;
    border: 1px solid #ccc;
    border-radius: 2px;
    background: #f7f7f7;
    text-shadow: none;
    font-weight: 600;
    font-size: 13px;
    line-height: normal;
    color: #0073aa;
    cursor: pointer;
    outline: 0;">添加轮播图</a>
			</div>

			<h4 style="color:#000;">
				<span style="font-weight: bolder;color:red;">设置方法：</span>
				请先输入轮播图数量，保存设置，然后再输入每个图片的地址和跳转链接，再保存设置。
			</h4>
			<?php $ct = 1; ?></div>
		<?php
}
?>