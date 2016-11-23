<div class="page-header" itemscope itemtype="http://schema.org/WebPage">
	<h2 itemprop="mainContentOfPage" itemscope="" itemtype="http://schema.org/WebPageElement"><span itemprop="headline"><?php echo $common_data['title'] ?></span></h2>
	<ol class="breadcrumb">
		<li itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"><a href="/" itemprop="url"><span itemprop="title"><?php echo _('Home') ?></span></a></li>
		<li class="active" itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"><a href="/<?php echo $this->router->fetch_class() ?>" itemprop="url"><span itemprop="title"><?php echo $common_data['title'] ?></span></a></li>
	</ol>
</div>