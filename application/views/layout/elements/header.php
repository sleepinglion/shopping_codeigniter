<header>
	<div class="container">
		<div class="row">
			<h1><?php echo anchor('/',_('Home Title')) ?></h1>
			<ul id="top_menu">
			<?php if($this->session->userdata('user_id')): ?>
				<li><?php echo anchor('/users/edit',$this->session->userdata('name')) ?> <?php echo _('Welcome') ?></li>
				<li><?php echo anchor('/logout',_('Logout')) ?></li>
			<?php else: ?>
				<li><?php echo anchor('/login',_('Login')) ?></li>
			<?php endif ?>
			</ul>			
			<nav>
				<ul class="nav nav-pills">
					<li <?php echo sl_active_class(array('products','orders')) ?>><?php echo anchor('products', _('Product').'<span class="visible-xs glyphicon glyphicon-chevron-right pull-right"></span>', array('title' => _('Product'))) ?></li>
					<?php if($this->session->userdata('user_id')): ?>
					<li <?php echo sl_active_class(array('users','reports','gene_categories')) ?>><?php echo anchor('users', _('My Page').'<span class="visible-xs glyphicon glyphicon-chevron-right pull-right"></span>', array('title' => _('My Page'))) ?></li>
					<?php else: ?>
					<li <?php echo sl_active_class('users') ?>><?php echo anchor('users/add', _('User Add').'<span class="visible-xs glyphicon glyphicon-chevron-right pull-right"></span>', array('title' => _('User Add'))) ?></li>
					<?php endif ?>
					<li role="presentation" <?php echo sl_active_class(array('notices','questions')) ?>>
						<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><?php echo _('Board') ?> <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li <?php echo sl_active_class('notices') ?>><?php echo anchor('notices', _('Notice').'<span class="visible-xs glyphicon glyphicon-chevron-right pull-right"></span>', array('title' => _('Notice'))) ?></li>
							<li <?php echo sl_active_class('questions') ?>><?php echo anchor('questions', _('Question').'<span class="visible-xs glyphicon glyphicon-chevron-right pull-right"></span>', array('title' => _('Question'))) ?></li>
							<li <?php echo sl_active_class('faqs') ?>><?php echo anchor('faqs', _('Faq').'<span class="visible-xs glyphicon glyphicon-chevron-right pull-right"></span>', array('title' => _('Faq'))) ?></li>							
						</ul>
					</li>
				</ul>
			</nav>
		</div>
	</div>
</header>
