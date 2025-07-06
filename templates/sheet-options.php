<div data-form-id="<?php echo $form_id; ?>"
     class="sheet-options-tab content py-2 px-0" data-content="sheet_options">
    <div class="hidden success-message px-4 py-3 body2 rounded-xl flex flex-row gap-3 items-center justify-start text-success-500 bg-success-100 mb-4">
        <i class="afzaliwp-icon afzaliwp-check-circle h2"></i>
        <span>
            <?php esc_html_e( 'Options have been saved successfully!', 'afzaliwp-gs' ); ?>
        </span>
    </div>
    <div class="hidden failed-message px-4 py-3 body2 rounded-xl flex flex-row gap-3 items-center justify-start text-danger-500 bg-danger-100 mb-4">
        <i class="afzaliwp-icon afzaliwp-cross-circle h2"></i>
        <span><?php esc_html_e( 'Something went wrong, please try again.', 'afzaliwp-gs' ); ?></span>
    </div>
    <label class="flex items-center gap-2 mb-2">
		<span class="body3 text-black-500">
			<input type="checkbox"
                   <?php checked( 'true', $form[ 'pause-sending' ] ) ?>
                   class="!text-primary-500 option-field"
                   name="pause-sending">
			<?php _e( 'Temporary pause sending to sheets.', 'afzaliwp-gs' ); ?>
		</span>
    </label>
    <label class="flex items-center gap-2 mb-2">
		<span class="body3 text-black-500">
			<input type="checkbox"
                   <?php checked( 'true', $form[ 'require-payment' ] ) ?>
                   class="!text-primary-500 option-field"
                   name="require-payment">
			<?php _e( 'Require successful payment before sending to Sheets.', 'afzaliwp-gs' ); ?>
		</span>
    </label>

    <button type="button" class="afz-button-primary mt-3 button-14 save-sheet-options">
		<?php esc_html_e( 'Save Options', 'afzaliwp-gs' ); ?>
    </button>
</div>