<ul class="dynamic-fields flex flex-col gap-1 max-h-50 w-50 overflow-y-scroll p-1"
    onclick="
    var li = event.target.closest('li');
    if (! li) return;
    var val = li.dataset.value;
    var row = this.closest('.field-row');
    row.querySelector('.dynamic-value-input').value = val;
    this.closest('.popover-content').classList.add('hidden');
  "
>
	<li class="last-of-type:border-0 border-b border-white-300 pb-2 cursor-pointer"
	    data-value="{current_date}">
		<?php echo esc_html__( 'Current Date', 'afzaliwp-gs' ); ?>
	</li>
	<li class="last-of-type:border-0 border-b border-white-300 pb-2 cursor-pointer"
	    data-value="{current_time}">
		<?php echo esc_html__( 'Current Time', 'afzaliwp-gs' ); ?>
	</li>
	<li class="last-of-type:border-0 border-b border-white-300 pb-2 cursor-pointer"
	    data-value="{created_by}">
		<?php echo esc_html__( 'Created By (User ID)', 'afzaliwp-gs' ); ?>
	</li>
	<li class="last-of-type:border-0 border-b border-white-300 pb-2 cursor-pointer"
	    data-value="{entry_id}">
		<?php echo esc_html__( 'Entry ID', 'afzaliwp-gs' ); ?>
	</li>
	<li class="last-of-type:border-0 border-b border-white-300 pb-2 cursor-pointer"
	    data-value="{date_created}">
		<?php echo esc_html__( 'Date Created', 'afzaliwp-gs' ); ?>
	</li>
	<li class="last-of-type:border-0 border-b border-white-300 pb-2 cursor-pointer"
	    data-value="{date_updated}">
		<?php echo esc_html__( 'Date Updated', 'afzaliwp-gs' ); ?>
	</li>
	<li class="last-of-type:border-0 border-b border-white-300 pb-2 cursor-pointer"
	    data-value="{source_url}">
		<?php echo esc_html__( 'Source URL', 'afzaliwp-gs' ); ?>
	</li>
	<li class="last-of-type:border-0 border-b border-white-300 pb-2 cursor-pointer"
	    data-value="{transaction_id}">
		<?php echo esc_html__( 'Transaction ID', 'afzaliwp-gs' ); ?>
	</li>
	<li class="last-of-type:border-0 border-b border-white-300 pb-2 cursor-pointer"
	    data-value="{payment_amount}">
		<?php echo esc_html__( 'Payment Amount', 'afzaliwp-gs' ); ?>
	</li>
	<li class="last-of-type:border-0 border-b border-white-300 pb-2 cursor-pointer"
	    data-value="{payment_date}">
		<?php echo esc_html__( 'Payment Date', 'afzaliwp-gs' ); ?>
	</li>
	<li class="last-of-type:border-0 border-b border-white-300 pb-2 cursor-pointer"
	    data-value="{payment_status}">
		<?php echo esc_html__( 'Payment Status', 'afzaliwp-gs' ); ?>
	</li>
	<li class="last-of-type:border-0 border-b border-white-300 pb-2 cursor-pointer"
	    data-value="{post_id}">
		<?php echo esc_html__( 'Post ID', 'afzaliwp-gs' ); ?>
	</li>
	<li class="last-of-type:border-0 border-b border-white-300 pb-2 cursor-pointer"
	    data-value="{user_agent}">
		<?php echo esc_html__( 'User Agent', 'afzaliwp-gs' ); ?>
	</li>
	<li class="last-of-type:border-0 border-b border-white-300 pb-2 cursor-pointer"
	    data-value="{ip}">
		<?php echo esc_html__( 'User IP', 'afzaliwp-gs' ); ?>
	</li>
	<li class="last-of-type:border-0 border-b border-white-300 pb-2 cursor-pointer"
	    data-value="{payment_date_only}">
		<?php echo esc_html__( 'Payment Date (Only Date)', 'afzaliwp-gs' ); ?>
	</li>
	<li class="last-of-type:border-0 border-b border-white-300 pb-2 cursor-pointer"
	    data-value="{payment_time_only}">
		<?php echo esc_html__( 'Payment Time (Only Time)', 'afzaliwp-gs' ); ?>
	</li>
	<li class="last-of-type:border-0 border-b border-white-300 pb-2 cursor-pointer"
	    data-value="{date_created_only}">
		<?php echo esc_html__( 'Date Created (Only Date)', 'afzaliwp-gs' ); ?>
	</li>
	<li class="last-of-type:border-0 border-b border-white-300 pb-2 cursor-pointer"
	    data-value="{time_created_only}">
		<?php echo esc_html__( 'Time Created (Only Time)', 'afzaliwp-gs' ); ?>
	</li>
</ul>