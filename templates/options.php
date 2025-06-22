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
            <div class="tabs">
                <button type="button" class="border-0 shadow-0 bg-transparent" data-tab="connection">
					<?php _e( 'Api Settings', 'afzaliwp-gs' ); ?>
                </button>
            </div>

            <div class="contents">
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
                        <span>Success message</span>
                    </div>
                    <div class="hidden failed-message px-4 py-3 body2 rounded-xl flex flex-row gap-3 items-center justify-start text-danger-500 bg-danger-100">
                        <i class="afzaliwp-icon afzaliwp-cross-circle h2"></i>
                        <span>Error message</span>
                    </div>

                </div>
            </div>
        </div>
    </main>
</div>