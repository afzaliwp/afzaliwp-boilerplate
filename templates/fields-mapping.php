<?php
if (
	isset( $form[ 'fields_mapping' ] ) &&
	is_array( $form[ 'fields_mapping' ] ) &&
	! empty( $form[ 'fields_mapping' ] )
) {
	$fields_mapping = $form[ 'fields_mapping' ];
} else {
	$fields_mapping = [];
	foreach ( $form[ 'fields' ] as $field ) {
		$fields_mapping[] = [
			'id'      => $field->id,
			'label'   => $field->label,
			'enabled' => true,
			'order'   => $field->id,
		];
	}
}

usort(
	$fields_mapping,
	function ( $a, $b ) {
		return intval( $a[ 'order' ] ) - intval( $b[ 'order' ] );
	}
);
?>

<div data-form-id="<?php echo $form_id; ?>" class="fields-mapping-tab content py-2 px-0" data-content="fields_mapping">
    <div class="hidden success-message px-4 py-3 body2 rounded-xl flex flex-row gap-3 items-center justify-start text-success-500 bg-success-100 mb-4">
        <i class="afzaliwp-icon afzaliwp-check-circle h2"></i>
        <span>
            <?php esc_html_e( 'Fields have been saved successfully!', 'afzaliwp-gs' ); ?>
        </span>
    </div>
    <div class="hidden failed-message px-4 py-3 body2 rounded-xl flex flex-row gap-3 items-center justify-start text-danger-500 bg-danger-100 mb-4">
        <i class="afzaliwp-icon afzaliwp-cross-circle h2"></i>
        <span><?php esc_html_e( 'Something went wrong, please try again.', 'afzaliwp-gs' ); ?></span>
    </div>
    <div class="rounded-lg border border-white-300">
        <div class="flex flex-row gap-1 p-2 body3 font-bold text-black-500 border-b border-white-300 mb-1">
            <span class="w-2/12"><?php _e( 'Send to Sheet', 'afzaliwp-gs' ); ?></span>
            <span class="w-2/12"><?php _e( 'Order', 'afzaliwp-gs' ); ?></span>
            <span class="w-3/12"><?php _e( 'Column Label', 'afzaliwp-gs' ); ?></span>
            <span class="w-4/12"><?php _e( 'Column Value', 'afzaliwp-gs' ); ?></span>
        </div>

        <div class="flex flex-col gap-1">
			<?php foreach ( $fields_mapping as $index => $field_map ) : ?>
				<?php if ( is_numeric( $field_map[ 'id' ] ) ) :
					$gf_field = false;
					foreach ( $form[ 'fields' ] as $tmp_field ) {
						if ( $tmp_field->id == $field_map[ 'id' ] ) {
							$gf_field = $tmp_field;
							break;
						}
					}
					if ( ! $gf_field ) {
						continue;
					}
					?>
                    <div data-field-id="<?php echo intval( $gf_field->id ); ?>"
                         class="field-row [&.field-clicked]:bg-white-200 [&.field-clicked]:shadow [&.field-clicked]:z-3 group w-full flex flex-row gap-1 px-2 py-2 even:bg-[#fafafa]">
                        <div class="w-2/12 flex items-center justify-start">
                            <button type="button" class="bg-transparent outline-none border-0 opacity-0">
                                <i class="afzaliwp-icon afzaliwp-category text-secondary-500 h4"></i>
                            </button>
                            <input type="checkbox" name="field_enabled[<?php echo intval( $gf_field->id ); ?>]"
                                   class="!text-primary-500" <?php checked( $field_map[ 'enabled' ] ); ?>>
                        </div>
                        <div class="w-2/12 flex items-center justify-start">
                            <span class="order-control w-8/12 p-2 rounded-lg bg-[#fafafa] flex flex-row gap-1 items-center justify-between">
                                <i class="group-first:cursor-not-allowed group-first:opacity-50 cursor-pointer afzaliwp-icon afzaliwp-arrow-up h5"></i>
                                <span class="text-black-400 body3 font-bold"><?php echo intval( $index ) + 1; ?></span>
                                <i class="group-last:cursor-not-allowed group-last:opacity-50 cursor-pointer afzaliwp-icon afzaliwp-arrow-down h5"></i>
                            </span>
                        </div>
                        <div class="w-3/12 flex items-center justify-start">
                            <span class="body3 font-bold text-black-500">
                                <?php echo esc_html( $gf_field->label ); ?>
                                <span class="caption2 text-white-400">
                                    <?php echo $gf_field->isRequired ? __( '(Required)', 'afzaliwp-gs' ) : ''; ?>
                                </span>
                            </span>
                        </div>
                        <div class="w-4/12 flex items-center justify-start">
                            <span class="body3 font-medium text-black-100">
                                <?php esc_html_e( 'From form entry', 'afzaliwp-gs' ); ?>
                            </span>
                        </div>
                    </div>
				<?php else : ?>
                    <!-- Custom field row -->
                    <div data-field-id="<?php echo esc_attr( $field_map[ 'id' ] ); ?>"
                         class="field-row [&.field-clicked]:bg-white-200 [&.field-clicked]:shadow [&.field-clicked]:z-3 group w-full flex flex-row gap-1 px-2 py-2 even:bg-[#fafafa]">
                        <div class="w-2/12 flex items-center justify-start">
                            <button type="button"
                                    class="remove-row p-0 mt-1 outline-none border-0 bg-transparent text-danger-500">
                                <i class="afzaliwp-icon afzaliwp-cross h4"></i>
                            </button>
                            <input type="checkbox" checked
                                   name="field_enabled[<?php echo esc_attr( $field_map[ 'id' ] ); ?>]"
                                   class="!text-primary-500">
                        </div>
                        <div class="w-2/12 flex items-center justify-start">
                            <span class="order-control w-8/12 p-2 rounded-lg bg-[#fafafa] flex flex-row gap-1 items-center justify-between">
                                <i class="group-first:cursor-not-allowed group-first:opacity-50 cursor-pointer afzaliwp-icon afzaliwp-arrow-up h5"></i>
                                <span class="text-black-400 body3 font-bold"><?php echo intval( $index ) + 1; ?></span>
                                <i class="group-last:cursor-not-allowed group-last:opacity-50 cursor-pointer afzaliwp-icon afzaliwp-arrow-down h5"></i>
                            </span>
                        </div>
                        <div class="w-3/12 flex items-center justify-start">
                            <span class="body3 font-bold text-black-500">
                                <input type="text" class="input w-full py-3"
                                       value="<?php echo esc_attr( isset( $field_map[ 'label' ] ) ? $field_map[ 'label' ] : '' ); ?>">
                            </span>
                        </div>
                        <div class="w-3/12 flex items-center justify-start">
                            <span class="body3 font-medium text-black-100">
                                <input type="text" class="input w-full py-3 dynamic-value-input"
                                       value="<?php echo esc_attr( isset( $field_map[ 'value' ] ) ? $field_map[ 'value' ] : '' ); ?>">
                            </span>
                        </div>
                        <div class="w-1/12 flex items-center justify-end">
                            <div class="group relative inline-block">
                                <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')"
                                        class="bg-transparent outline-none border-0 cursor-pointer">
                                    <i class="afzaliwp-icon afzaliwp-category text-secondary-500 h4"></i>
                                </button>

                                <div class="popover-content absolute right-0 hidden transition bg-white-100 shadow-sm text-sm rounded-lg p-2 z-10">
									<?php include AFZALIWP_GS_TPL_DIR . 'dynamic-fields.php'; ?>
                                </div>
                            </div>
                        </div>
                    </div>
				<?php endif; ?>
			<?php endforeach; ?>
        </div>

        <div class="flex flex-row items-start justify-end gap-1 p-2 body2 font-bold text-black-500 border-t border-white-300 mt-3">
            <button type="button" class="afz-button-secondary-accent py-3 button-14">
				<?php _e( 'Add Custom Field', 'afzaliwp-gs' ); ?>
            </button>
        </div>
    </div>

    <button type="button" class="afz-button-primary mt-3 button-14 save-fields-button">
		<?php esc_html_e( 'Save Fields', 'afzaliwp-gs' ); ?>
    </button>
</div>
<script id="custom_field_row" type="text/template">
    <div data-field-id="{{custom_field_id}}"
         class="field-row [&.field-clicked]:bg-white-200 [&.field-clicked]:shadow [&.field-clicked]:z-3 group w-full flex flex-row gap-1 px-2 py-2 even:bg-[#fafafa]">
        <div class="w-2/12 flex items-center justify-start">
            <button class="remove-row p-0 mt-1 outline-none border-0 bg-transparent text-danger-500">
                <i class="afzaliwp-icon afzaliwp-cross h4"></i>
            </button>
            <input type="checkbox" checked name="field_enabled[{{custom_field_id}}]" class="!text-primary-500">
        </div>
        <div class="w-2/12 flex items-center justify-start">
            <span class="order-control w-8/12 p-2 rounded-lg bg-[#fafafa] flex flex-row gap-1 items-center justify-between">
                <i class="group-first:cursor-not-allowed group-first:opacity-50 cursor-pointer afzaliwp-icon afzaliwp-arrow-up h5"></i>
                <span class="text-black-400 body3 font-bold">{{order}}</span>
                <i class="group-last:cursor-not-allowed group-last:opacity-50 cursor-pointer afzaliwp-icon afzaliwp-arrow-down h5"></i>
            </span>
        </div>
        <div class="w-3/12 flex items-center justify-start">
            <span class="body3 font-bold text-black-500">
                <input type="text" class="input w-full py-3" value="{{label}}">
            </span>
        </div>
        <div class="w-3/12 flex items-center justify-start">
            <span class="body3 font-medium text-black-100">
                <input type="text" class="input w-full py-3 dynamic-value-input" value="{{value}}">
            </span>
        </div>
        <div class="w-1/12 flex items-center justify-end">

            <div class="group relative inline-block">
                <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')"
                        class="bg-transparent outline-none border-0 cursor-pointer">
                    <i class="afzaliwp-icon afzaliwp-category text-secondary-500 h4"></i>
                </button>

                <div class="popover-content absolute right-0 hidden transition bg-white-100 shadow-sm text-sm rounded-lg p-2 z-10">
					<?php include AFZALIWP_GS_TPL_DIR . 'dynamic-fields.php'; ?>
                </div>
            </div>

        </div>
    </div>
</script>
