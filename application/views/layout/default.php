<?php echo doctype('html5')."\n" ?>
<html lang="ko">
<head>
	<?php echo meta($meta); ?>
	<title><?php echo $title_for_layout; ?></title>
	<link href="<?php echo base_url() ?>images/favicon.ico" type="image/x-icon" rel="shortcut icon"/>	
	<?php echo $style_for_layout ?>
<?php meta_tags(array('general' => true,'og' => true,'twitter'=> true,'robot'=> true),$common_data['meta_title'],$common_data['meta_description']) ?>
	<?php if(isset($data['tags'])): ?>
	<meta content="<?php echo tag_restore($data['tags']) ?>" name="keywords" />
	<?php else: ?>
	<meta content="유전자,유전자검사,암,질병,비만" name="keywords" />
	<?php endif ?>
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta name="author" content="Sleeping-Lion" />
	<!--[if IE]>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<![endif]-->
	<!--[if lt IE 9]>
	<script type="text/javascript" src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <script type="text/javascript" src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
    <![endif]-->
</head>
<body>
	<?php echo $Layout->element('header') ?>
	<div id="mom">
		<div id="main" class="container">					
		<?php if($this -> router -> fetch_class()=='home'): ?>
		<div id="main_main">
			<?php echo $contents_for_layout ?>
		</div>
		<?php else: ?>
		<?php echo $Layout->element('page_header') ?>
		<div class="sub_main">
			<?php echo $contents_for_layout ?>
		</div>
		<?php endif ?>
		</div>
	</div>
	<?php echo $Layout->element('footer') ?>
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog"></div>	
	<?php echo "\n".$script_for_layout."\n" ?>
</body>
</html>
