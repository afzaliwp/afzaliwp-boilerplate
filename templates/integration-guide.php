<?php

use AfzaliWP\GS_Gravity\Includes\Backend\Integration_Guide;

$controller = new Integration_Guide();
$gs_code    = $controller->get_gs_code();
?>
<div data-form-id="<?php echo $form_id; ?>"
     class="integration-guide-tab content py-2 px-0" data-content="integration_guide">
	<div class="flex flex-col md:flex-row items-center justify-between gap-2">
		<div class="flex flex-col">
			<strong class="body1 font-bold">
				<?php _e( 'Google Sheets Script Guide', 'afzaliwp-gs' ); ?>
			</strong>
			<p class="text-black-100 body3">
				<?php _e( 'Copy the code into your google sheets apps script section.', 'afzaliwp-gs' ); ?>
			</p>
		</div>

		<button
			onclick="navigator.clipboard.writeText(document.getElementById('gs-script-code').innerText)"
			class="bg-primary-100 text-black-500 !button-16 !font-bold px-4 py-2 gap-2 rounded-lg flex items-center justify-center hover:bg-primary-200 cursor-pointer transition">
			<?php _e( 'Copy Code', 'afzaliwp-gs' ); ?>
			<i class="afzaliwp-icon afzaliwp-clipboard h5"></i>
		</button>
	</div>

	<div class="p-4 rounded-lg bg-[#151E30] max-w-200">
		<pre id="gs-script-code"
             class="text-white-200 text-wrap overflow-x-hidden overflow-y-scroll max-h-50"
        ><?php echo esc_html( $gs_code ); ?></pre>
	</div>
	<ol class="text-black-300 body2 mt-4">
		<li><?php _e('Create a new Google Sheet', 'afzaliwp-gs'); ?></li>
		<li><?php _e('Open its Script Editor (Tools > Script Editor)', 'afzaliwp-gs'); ?></li>
		<li><?php _e('Paste the code above and deploy it as a Web App', 'afzaliwp-gs'); ?></li>
		<li><?php _e('Copy the Web App URL and paste it into the Webhook URL field to test connection', 'afzaliwp-gs'); ?></li>
	</ol>

	<div class="flex flex-col md:flex-row items-center justify-between gap-2 mt-10">
		<div class="flex flex-col">
			<strong class="body1 font-bold">
				<?php _e( 'Video Guide', 'afzaliwp-gs' ); ?>
			</strong>
			<p class="text-black-100 body3">
				<?php _e( 'If you have any problems setting up the google sheet, watch this video', 'afzaliwp-gs' ); ?>
			</p>
		</div>
	</div>

	<div class="p-4 rounded-lg bg-[#151E30] max-w-200 mt-2">
		<video src=""></video>
	</div>
</div>

