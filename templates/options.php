<?php

use AfzaliWP\GS_Gravity\Includes\Helper;

$form_id = esc_attr( $_GET[ 'id' ] );
$form    = \GFAPI::get_form( $form_id );

?>
<div class="afzaliwp-gs-wrapper shadow-sm rounded-lg bg-white p-4">
    <header class="flex items-center justify-between">
        <div class="flex items-center gap-2">
            <h1 class="text-xl font-bold"><?php _e( 'Google Sheets Settings', 'afzaliwp-gs' ); ?></h1>
            <button class="p-2 text-gray-500 hover:text-gray-700 focus:outline-none h2"
                    title="<?php _e( 'Options', 'afzaliwp-gs' ); ?>">
                <i class="afzaliwp-icon afzaliwp-more-square"></i>
            </button>
        </div>

        <img width="48" height="28" src="<?php echo Helper::get_assets_images_url( 'logo.png' ); ?>" alt=""
             class="w-12 h-7">
    </header>

    <main>
        <div class="tabs-wrapper">
            <div class="tabs flex flex-row gap-3 border-b border-white-300 mb-5">
                <button type="button" class="tab-button active" data-tab="connection">
					<?php _e( 'Api Settings', 'afzaliwp-gs' ); ?>
                </button>
                <button type="button" class="tab-button" data-tab="fields_mapping">
					<?php _e( 'Fields Mapping', 'afzaliwp-gs' ); ?>
                </button>
            </div>

            <div class="contents">
				<?php include_once AFZALIWP_GS_TPL_DIR . 'connection-test.php'; ?>
				<?php include_once AFZALIWP_GS_TPL_DIR . 'fields-mapping.php'; ?>
            </div>
        </div>
    </main>
</div>