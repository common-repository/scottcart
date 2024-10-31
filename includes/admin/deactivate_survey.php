<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function scottcart_deactivate_survey() {
?>
	<div class="scottcart-popup-overlay">
		<div class="scottcart-serveypanel">
			<form action="#" method="post" id="scottcart-deactivate-form">
				
				<div class="scottcart-popup-header">
					<h2><?php echo __( 'ScottCart Feedback', 'scottcart' ); ?></h2>
				</div>
				
				<div class="scottcart-popup-body">
					
					<h3><?php echo __( 'What made you deactivate?', 'scottcart' ); ?></h3>
					
					<ul id="scottcart-reason-list">
						
						<li class="scottcart-reason has-input" data-input-type="textfield">
							<label>
							<span>
							<input type="radio" name="scottcart-selected-reason" value="2">
							</span>
							<span><?php echo __( 'I found another plugin / platform.', 'scottcart' ); ?></span>
							</label>
							<div class="scottcart-internal-message"></div>
							<div class="scottcart-reason-input"><textarea class="scottcart_input_field_error" name="better_plugin" placeholder="What's the plugins / platforms name? What feature(s) made you move?"></textarea></div>
						</li>
						
						<li class="scottcart-reason has-input" data-input-type="textfield">
							<label>
							<span>
							<input type="radio" name="scottcart-selected-reason" value="1">
							</span>
							<span><?php echo __( 'The plugin was missing a feature.', 'scottcart' ); ?></span>
							</label>
							<div class="scottcart-internal-message"></div>
							<div class="scottcart-reason-input"><textarea class="scottcart_input_field_error" name="feature" placeholder="What feature(s) was this plugin missing?"></textarea></div>
						</li>
						
						<li class="scottcart-reason" data-input-type="" data-input-placeholder="">
							<label>
							<span>
							<input type="radio" name="scottcart-selected-reason" value="6">
							</span>
							<span><?php echo __( "It's a temporary deactivation.", 'scottcart' ); ?></span>
							</label>
							<div class="scottcart-internal-message"></div>
						</li>
						
						<li class="scottcart-reason has-input" data-input-type="textfield" >
							<label>
							<span>
							<input type="radio" name="scottcart-selected-reason" value="7">
							</span>
							<span><?php echo __( 'Other', 'scottcart' ); ?></span>
							</label>
							<div class="scottcart-internal-message"></div>
							<div class="scottcart-reason-input"><textarea class="scottcart_input_field_error" name="other_reason" placeholder="Please explain. How can we improve?"></textarea></div>
						</li>
						
					</ul>
					
				</div>
					
					<div class="scottcart-popup-footer">
						<span class='scottcart-popup-footer-explain'> Clicking 'Submit & Deactivate' will send your response, email, site URL, and ScottCart verson number to the plugin devleoper so that the plugin can be improved.</span>
						<br /><br />
						<input type="button" class="button button-secondary button-skip loginpress-popup-skip-feedback" value="Skip &amp; Deactivate" >
						<div class="action-btns">
						<span class="scottcart-spinner"><img src="<?php echo admin_url( '/images/spinner.gif' ); ?>" alt=""></span>
						<input type="submit" class="button button-secondary button-deactivate scottcart-popup-allow-deactivate" value="Submit &amp; Deactivate" disabled="disabled">
						<a href="#" class="button button-primary scottcart-popup-button-close"><?php echo __( 'Cancel', 'scottcart' ); ?></a>
					</div>
					
				</div>
				
			</form>
		</div>
	</div>
	<?php
}