<footer>
	<div class="container">
		<div class="row">
		<address><br class="visible-xs" /><?php echo _('Footer Address') ?></address>
	<form action="" class="form-inline">
		<select name="locale" class="form-control">
			<option value="english"<?php if($this->session->userdata('locale')=='english'): ?> selected="selected"<?php endif ?>><?php echo _('english') ?></option>
			<option value="korean"<?php if($this->session->userdata('locale')=='korean'): ?> selected="selected"<?php endif ?>><?php echo _('korean') ?></option>
		</select>
		<input type="submit" class="btn" value="<?php echo _('Submit') ?>" />
	</form>
 	<script>
 		var base_url='<?php echo base_url() ?>';
 	</script>
 		</div>
	</div>
</footer>
