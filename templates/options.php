<?php
use AfzaliWP\GS_Gravity\Includes\Helper;
?>
<div class="afzaliwp-gs-wrapper shadow-sm rounded-lg bg-white p-4">
    <header class="flex items-center justify-between">
        <div class="flex items-center gap-2">
            <h1 class="text-xl font-semibold"><?php _e( 'Google Sheets Settings', 'afzaliwp-gs' ); ?></h1>
            <button class="p-2 text-gray-500 hover:text-gray-700 focus:outline-none"
                    title="<?php _e( 'Options', 'afzaliwp-gs' ); ?>">
                <i class="afzaliwp-icon afzaliwp-more-square"></i>
            </button>
        </div>

        <img width="48" height="28" src="<?php echo Helper::get_assets_images_url('logo.png'); ?>" alt="" class="w-12 h-7">
    </header>
</div>