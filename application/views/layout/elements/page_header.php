<div class="page-header row" itemscope itemtype="http://schema.org/WebPage">
	<div class="col-12">
	<h2 itemprop="mainContentOfPage" itemscope="" itemtype="http://schema.org/WebPageElement"><span itemprop="headline"><?php echo $common_data['title']; ?></span></h2>
	<nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li itemscope="itemscope" class="breadcrumb-item"><?php echo anchor('/', _('Home')); ?></li> 
          <li itemscope="itemscope" class="breadcrumb-item active" aria-current="page">
          <strong>
          <?php echo $common_data['title']; ?>
          </strong>
          </li>
        </ol>
	</nav>
	</div>
</div>