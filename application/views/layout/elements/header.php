<header>
	<div id="header-top">
		<div class="container">
			<div class="row">
				<h1><?php echo anchor('/',_('Home Title')) ?></h1>
				<ul id="top-menu">
				<?php if($this->session->userdata('user_id')): ?>
					<li><?php echo anchor('/users/edit',$this->session->userdata('name')) ?> <?php echo _('Welcome') ?></li>
					<li><?php echo anchor('logout', '<i class="material-icons" style="font-size:35px;vertical-align:middle">highlight_off</i>', array('title' => _('Log Out'))); ?></li>
				<?php else: ?>
					<li><?php echo anchor('/login','<i class="material-icons">login</i><span>'._('Login').'</span>', array('title' => _('Login'), 'class' => 'nav-link')) ?></li>					
					<li><?php echo anchor('/users/add','<i class="material-icons">how_to_reg</i><span>'._('User Add').'</span>', array('title' => _('User Add'), 'class' => 'nav-link')) ?></li>
				<?php endif ?>
				</ul>
			</div>
		</div>
	</div>
	<div id="header-main">
		<div class="container">
			<div class="row">
			<nav id="main-menu">
				<ul class="nav nav-pills">
					<li <?php echo sl_active_class(array('products','orders')) ?>>
						<?php echo anchor('products','<i class="material-icons">storage</i><span>'._('Product').'</span>', array('title' => _('Product'), 'class' => 'nav-link')) ?>
					</li>
					<li role="presentation" <?php echo sl_active_class(array('notices','questions','faqs')) ?>>
						<a class="dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="material-icons">dashboard</i><span><?php echo _('Board') ?></span> <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li <?php echo sl_active_class('notices') ?>><?php echo anchor('notices','<i class="material-icons">storage</i><span>'._('Notice').'</span>', array('title' => _('Notice'), 'class' => 'nav-link')) ?></li>
							<li <?php echo sl_active_class('questions') ?>><?php echo anchor('questions','<i class="material-icons">storage</i><span>'._('Question').'</span>', array('title' => _('Question'), 'class' => 'nav-link')) ?></li>
							<li <?php echo sl_active_class('faqs') ?>><?php echo anchor('faqs','<i class="material-icons">storage</i><span>'._('Faq').'</span>', array('title' => _('Faq'), 'class' => 'nav-link')) ?></li>
						</ul>
					</li>
				</ul>
			</nav>
			</div>
		</div>
	</div>
</header>
