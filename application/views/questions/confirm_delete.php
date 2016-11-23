<section id="section_delete_confirm" class="section_content">
	<?php echo form_open($this->router->fetch_class().'/delete/'.$data['id'],array('id'=>"delete_confirm_form")) ?>		
		<p>
			<?php echo sprintf(_('Are you sure delete article number %d?'),$data['id']) ?>		
		</p>
		<div class="form-group">
			<input type="submit" class="btn btn-primary btn-larghe" value="<?php echo _('Delete') ?>" />
		</div>			
	</form>
</section>
