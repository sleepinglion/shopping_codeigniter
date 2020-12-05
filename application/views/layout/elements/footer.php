<footer>
	<div class="container">
		<div class="row">
		<address class="col-12">
			<a href="https://www.sleepinglion.pe.kr" target="_blank"><?php echo _('Footer Address') ?></a>
			&nbsp;&nbsp; CopyLeft. 2020, SL. All wrongs reserved.
		</address>
		<form class="form-inline">
			<select name="language" class="form-control">
				<option value="english"<?php if ($this->session->userdata('language')=='english'): ?> selected="selected"<?php endif ?>><?php echo _('english') ?></option>
				<option value="korean"<?php if ($this->session->userdata('language')=='korean'): ?> selected="selected"<?php endif ?>><?php echo _('korean') ?></option>
			</select>
			<input type="submit" class="btn" value="<?php echo _('Submit') ?>" />
		</form>
		<script>
		var base_url='<?php echo base_url() ?>';
		</script>
 		</div>
	</div>
</footer>
