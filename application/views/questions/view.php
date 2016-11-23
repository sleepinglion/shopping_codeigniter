<section id="sl_question_view">
  <article id="sl_question_content" class="slboard_content">	  	
    <div class="sl_content_header box_header">
      <h3 itemprop="name"><?php echo $data['content']['title'] ?></h3>
    </div>
    <div class="sl_content_main">
     		<p class="sl_content_info"><span  itemprop="author" class="none"><?php echo $data['content']['nickname'] ?></span>&nbsp;&nbsp;&nbsp; <?php echo _('label_created_at') ?> : <span itemprop="dateCreated"><?php echo $data['content']['created_at'] ?></span><span class="none" itemprop="dateModified"><?php $data['content']['updated_at'] ?></span></p>    	
      <div class="sl_content_text" itemprop="text"><?php echo nl2br($data['content']['content']) ?></div>
    </div>
  </article>
	<?php include __DIR__.DIRECTORY_SEPARATOR.'comments/index.php' ?>  
	<?php include __DIR__.DIRECTORY_SEPARATOR.'bottom_menu.php' ?>
	<?php if($this->session->userdata('user_id')): ?>
	<?php include __DIR__.DIRECTORY_SEPARATOR.'comments/add.php' ?>
	<?php endif ?>	
	<!-- <?php /*include __DIR__.DIRECTORY_SEPARATOR.'bottom_menu.php' */ ?> -->   
</section>