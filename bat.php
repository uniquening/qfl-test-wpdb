	<style>
		/*.wrap {
			max-width: 1000px;
			width: 100%;
			margin: 0 auto;
			background: yellow;
		}*/
		.slider-wrap {
			background: #fff;
			border: 1px solid #ddd;
			position: relative;
		}
		.slider-wrap .head {
			background: #ddd;
			position: relative;
		}
		.slider-wrap .head span {
			position: absolute;
		}
		.slider-wrap .head:hover {
			background: #bbb;
		}
		.slider-wrap .head h3 {
			padding: 1rem;
			margin: 0;
		}
		.slider-wrap .head .slider-setting {
			position: absolute;
		    right: 2rem;
		    top: 1rem;
		}
		.slider-wrap .head .slider-setting span {
			font-size: 24px;
		    top: 0.4rem;
		    display: inline-block;
		    border-color: black transparent transparent transparent;
		    border-width: 8px 6.6667px;
		    border-style: solid;
		    right: -1.2rem;
		}
		.slider-wrap .head .slider-setting.active span {
			border-color: transparent transparent black transparent;
    		top: 0;
		}				
		.slider-wrap .content {
			padding: 0 1rem 1rem;
			display: none;
		}
		.slider-wrap .content h4 {
			font-size: 1rem;
			margin: 1rem 0;
		}
		.qfl-row {
			overflow: hidden;
			position: relative;
		}
		.qfl-row .imageindex {
			position: absolute;
			left: 1rem;
		}
		.qfl-per3 {
			width: 33.33333%;
			float: left;
		}
		.qfl-per2 {
			width: 50%;
			float: left;
		}
		.image-text-width input[type='text'] {
			width: 17rem;
		}
		.imagesrc {
			margin-bottom: 0.5rem;
		}
		.imagesrc span {
			margin-right: 3rem;
		}
		.imagelink label {
			margin-left: 3rem;
		}
		.slider-wrap .content {
			position: relative;
		}
		.slider-wrap .content .btn-slider {
			position: absolute;
			right: 1rem;
			right: 16px;
			bottom: 1rem;
			bottom: 16px;

		}
	</style>


function qfl_slider1_settings_page() {
	global $wpdb;
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
	if (!empty($_POST) && check_admin_referer('qfl_slider1_nonce')) {
		$wpdb->update("{$wpdb->prefix}slider", array("name" => $_POST['name'], "description" => $_POST['description'] ));
	}

	?>
		<div class="wrap" style="position: relative;">
			<div style="margin-bottom: 1rem">
				<h1 class="wp-heading-inline">轮播图</h1>
				<a href="<?php echo $curSrc; ?>?page=qfl_slider_setting" class="page-title-action">添加轮播图</a>
				<hr class="wp-header-end">	
			</div>	
			<?php 
			foreach ($sliders as $slider) {
				if (!empty($_POST) && check_admin_referer('qfl_slider1_nonce')) {
					$wpdb->update("{$wpdb->prefix}slider", array("name" => $_POST['name'], "description" => $_POST['description'] ), array('id' => $slider['id']));
				}
			}
			if (!empty($_POST) && check_admin_referer('qfl_slider1_nonce')) {
				$wpdb->update("{$wpdb->prefix}slider", array("name" => $_POST['name'], "description" => $_POST['description'] ));
			}

			?>		
			<div class="slider-wrap slider-wrap<?php echo $j; ?>">
				<div class="head">
					<h3>第 <?php echo $j; ?> 组轮播图(点击右侧设置按钮)</h3>
					<span class="slider-setting">设置<span></span></span>
				</div>
				<div class="content">
					<form action="<?php echo $curSrc;?>/?page=qfl_slider_setting" method="post">
						<h4>第一步：设置轮播图类名和图片数量,点击<em>保存设置</em></h4>

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
					
						<p>
							<input type="submit" name="submit" value="保存设置">
						</p>
						<?php wp_nonce_field('qfl_slider_nonce');// 输出一个验证信息?>
						<!--<div class="qfl-row">
							<input type="submit" value="保存设置">
						</div> -->

					</form>
					<div class="btn-slider">
						<a href="<?php echo $curSrc;?>?page=qfl_slider_setting" class="page-title-action">删除轮播图</a>
					</div>
				</div>

				
			</div>
		<div style="text-align: left; margin-left: 1rem;margin-top: 1rem;">
			<a href="<?php echo $curSrc; ?>?page=qfl_slider_setting" class="page-title-action" style="">添加轮播图</a>			
		</div>
		
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
<?php } ?>