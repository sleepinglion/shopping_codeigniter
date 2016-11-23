<div class="hidden-xs">
	<form action="<?php echo '/'.$this->router->fetch_class() ?>" class="form-inline sl_search_form" method="get">
    <div class="input-group">
     <div class="input-group-btn">
     		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><?php if($this->input->get('search_type')): ?><?php echo $data['search_type_title'] ?><?php else: ?><?php echo _('label_title') ?><?php endif ?> &nbsp; <span class="caret"></span></button>
     		<ul class="dropdown-menu" role="menu">
     			<li><?php echo anchor($this->router->fetch_class().'?search_type=title',_('label_title')) ?></li>
     			<li><?php echo anchor($this->router->fetch_class().'?search_type=content',_('label_content')) ?></li>
     			<li><?php echo anchor($this->router->fetch_class().'?search_type=titlencontent',_('label_titlencontent')) ?></li>
     		<!--	<li><?php echo anchor('communities?search_type=nickname',_('label_nickname')) ?></li> -->
     		</ul>
			</div>
			<select name="search_type" style="display:none">
		<option value="title" <?php if($this->input->get('search_type')=='title'): ?>selected="selected"<?php endif ?>><?php echo _('label_title') ?></option>
		<option value="content" <?php if($this->input->get('search_type')=='content'): ?>selected="selected"<?php endif ?>><?php echo _('label_content') ?></option>
		<option value="titlencontent" <?php if($this->input->get('search_type')=='titlencontent'): ?>selected="selected"<?php endif ?>>?php echo _('label_titlencontent') ?></option>
	<!--	<option value="nickname" <?php if($this->input->get('search_type')=='nickname'): ?>selected="selected"<?php endif ?>><?php echo _('label_nickname') ?></option> -->
			</select>
  	<input type="search" name="search_word" class="form-control" value="<?php if($this->input->get('search_word')): ?><?php echo $this->input->get('search_word') ?><?php endif ?>" placeholder="<?php echo _('insert search word') ?>" maxlength="60" required="required" />
			<span class="input-group-btn">
  			<input type="submit" class="btn btn-default" value="<?php echo _('Search') ?>" />
  		</span>
  	</div>
	</form>
</div>

<div class="visible-xs-block">
	<form class="form-inline sl_search_form col-xs-12" method="get"  style="margin:20px 0">
		<div class="row">
			<div class="search_type col-xs-4"  style="text-align:right">
			<select name="search_type" class="form-control">
		<option value="title" <?php if($this->input->get('search_type')=='title'): ?>selected="selected"<?php endif ?>><?php echo _('label_title') ?></option>
		<option value="content" <?php if($this->input->get('search_type')=='content'): ?>selected="selected"<?php endif ?>><?php echo _('label_content') ?></option>
		<option value="titlencontent" <?php if($this->input->get('search_type')=='titlencontent'): ?>selected="selected"<?php endif ?>><?php echo _('label_title+content') ?></option>
	<!--	<option value="nickname" <?php if($this->input->get('search_type')=='nickname'): ?>selected="selected"<?php endif ?>><?php echo _('label_nickname') ?></option> -->
			</select>
			</div>
  <div class="input-group col-xs-8">
  	<input type="search" name="search_word" class="form-control" value="<?php if($this->input->get('search_word')): ?><?php echo $this->input->get('search_word') ?><?php endif ?>" placeholder="<?php echo _('insert search word') ?>" maxlength="60" required="required" />
	<span class="input-group-btn">
  	<input type="submit" class="btn btn-default" value="<?php echo _('Search') ?>" />
  </span>
	</div>
	</div>
	</form>
</div>