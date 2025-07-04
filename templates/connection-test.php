<div class="connection-tab content py-2 px-0" data-content="connection">
	<form action="" method="post" class="mb-5">
		<input type="hidden" name="form-id" value="<?php echo $form_id; ?>">
		<div class="flex flex-col md:flex-row gap-2">
			<input type="text"
			       value="<?php echo esc_url( $form[ 'gsheets_webhook' ] ) ?? ''; ?>"
			       name="gs-scripts-url" class="flex-1 input"
			       placeholder="<?php _e( 'Google Sheets Script Url', 'afzaliwp-gs' ); ?>">
			<button class="afz-button-primary">
				<?php _e( 'Test Connection', 'afzaliwp-gs' ); ?>
			</button>
		</div>
	</form>

	<div class="hidden success-message px-4 py-3 body2 rounded-xl flex flex-row gap-3 items-center justify-start text-success-500 bg-success-100">
		<i class="afzaliwp-icon afzaliwp-check-circle h2"></i>
		<span></span>
	</div>
	<div class="hidden failed-message px-4 py-3 body2 rounded-xl flex flex-row gap-3 items-center justify-start text-danger-500 bg-danger-100">
		<i class="afzaliwp-icon afzaliwp-cross-circle h2"></i>
		<span></span>
	</div>

</div>