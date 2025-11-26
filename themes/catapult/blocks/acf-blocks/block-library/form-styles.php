<?php
/**
 * The form styles section for the block library.
 *
 * @package Catapult
 * @since   3.0.0
 */

?>

<section class="block-library__forms">
	<div class="block-library__foundations-header">
		<div class="container">
			<h2><?php esc_html_e( 'Form Styles', 'catapult' ); ?></h2>
		</div>
	</div>

	<div class="block-library__forms-section">
		<div class="block-library__forms-grid container">
			<h3 class="block-library__foundations-heading-small"><?php esc_html_e( 'Input Field', 'catapult' ); ?></h3>

			<div>
				<h4 class="block-library__foundations-overline"><?php esc_html_e( 'Default', 'catapult' ); ?></h4>
				<label for="block-library-input-default"><?php esc_html_e( 'Label', 'catapult' ); ?><span class="required">*</span></label>
				<input id="block-library-input-default" type="text" placeholder="<?php esc_html_e( 'Label', 'catapult' ); ?>">
			</div>

			<div>
				<h4 class="block-library__foundations-overline"><?php esc_html_e( 'Focused', 'catapult' ); ?></h4>
				<label for="block-library-input-focused"><?php esc_html_e( 'Label', 'catapult' ); ?><span class="required">*</span></label>
				<input id="block-library-input-focused" type="text" placeholder="<?php esc_html_e( 'Label', 'catapult' ); ?>" value=" " class="focus">
			</div>

			<div>
				<h4 class="block-library__foundations-overline"><?php esc_html_e( 'Activated', 'catapult' ); ?></h4>
				<label for="block-library-input-activated"><?php esc_html_e( 'Label', 'catapult' ); ?><span class="required">*</span></label>
				<input id="block-library-input-activated" type="text" placeholder="<?php esc_html_e( 'Label', 'catapult' ); ?>" value="<?php esc_html_e( 'Text Input', 'catapult' ); ?>">
			</div>
			
			<div>
				<h4 class="block-library__foundations-overline"><?php esc_html_e( 'Error', 'catapult' ); ?></h4>
				<label for="block-library-input-error"><?php esc_html_e( 'Label', 'catapult' ); ?><span class="required">*</span></label>
				<input id="block-library-input-error" type="text" placeholder="<?php esc_html_e( 'Label', 'catapult' ); ?>" value="<?php esc_html_e( 'efjlw fvjq', 'catapult' ); ?>" class="error">
			</div>

			<div>
				<h4 class="block-library__foundations-overline"><?php esc_html_e( 'Autofill', 'catapult' ); ?></h4>
				<label for="block-library-input-autofill"><?php esc_html_e( 'Label', 'catapult' ); ?><span class="required">*</span></label>
				<input id="block-library-input-autofill" type="text" placeholder="<?php esc_html_e( 'Label', 'catapult' ); ?>" value="<?php esc_html_e( 'Autofill Text', 'catapult' ); ?>" class="autofill">
			</div>

			<div>
				<h4 class="block-library__foundations-overline"><?php esc_html_e( 'Password', 'catapult' ); ?></h4>
				<label for="block-library-input-password"><?php esc_html_e( 'Label', 'catapult' ); ?><span class="required">*</span></label>
				<input id="block-library-input-password" type="password" placeholder="<?php esc_html_e( 'Label', 'catapult' ); ?>" value="<?php esc_html_e( '12345', 'catapult' ); ?>" >
			</div>
		</div>
	</div>

	<div class="block-library__forms-section">
		<div class="block-library__forms-grid container">
			<h3 class="block-library__foundations-heading-small"><?php esc_html_e( 'Message Field', 'catapult' ); ?></h3>

			<div>
				<h4 class="block-library__foundations-overline"><?php esc_html_e( 'Default', 'catapult' ); ?></h4>
				<label for="block-library-message-default"><?php esc_html_e( 'Label', 'catapult' ); ?><span class="required">*</span></label>
				<textarea id="block-library-message-default" placeholder="<?php esc_html_e( 'Label', 'catapult' ); ?>"></textarea>
			</div>

			<div>
				<h4 class="block-library__foundations-overline"><?php esc_html_e( 'Focused', 'catapult' ); ?></h4>
				<label for="block-library-message-focused"><?php esc_html_e( 'Label', 'catapult' ); ?><span class="required">*</span></label>
				<textarea id="block-library-message-focused" placeholder="<?php esc_html_e( 'Label', 'catapult' ); ?>" class="focus"> </textarea>
			</div>

			<div>
				<h4 class="block-library__foundations-overline"><?php esc_html_e( 'Activated', 'catapult' ); ?></h4>
				<label for="block-library-message-activated"><?php esc_html_e( 'Label', 'catapult' ); ?><span class="required">*</span></label>
				<textarea id="block-library-message-activated" placeholder="<?php esc_html_e( 'Label', 'catapult' ); ?>"><?php esc_html_e( 'Message text goes here', 'catapult' ); ?></textarea>
			</div>
		</div>
	</div>

	<div class="block-library__forms-section">
		<div class="block-library__forms-grid container">
			<h3 class="block-library__foundations-heading-small"><?php esc_html_e( 'Search Field Universal', 'catapult' ); ?></h3>

			<div>
				<h4 class="block-library__foundations-overline"><?php esc_html_e( 'Default', 'catapult' ); ?></h4>
				<input id="block-library-search-default" type="search" placeholder="<?php esc_html_e( 'Search [Content Type]', 'catapult' ); ?>">
			</div>

			<div>
				<h4 class="block-library__foundations-overline"><?php esc_html_e( 'Focused', 'catapult' ); ?></h4>
				<input id="block-library-search-focused" type="search" placeholder="<?php esc_html_e( 'Label', 'catapult' ); ?>" value="<?php esc_html_e( 'Key', 'catapult' ); ?>" class="focus">
			</div>

			<div>
				<h4 class="block-library__foundations-overline"><?php esc_html_e( 'Activated', 'catapult' ); ?></h4>
				<input id="block-library-search-activated" type="search" placeholder="<?php esc_html_e( 'Label', 'catapult' ); ?>" value="<?php esc_html_e( 'Keyword One', 'catapult' ); ?>">
			</div>
		</div>
	</div>

	<div class="block-library__forms-section">
		<div class="block-library__forms-grid container">
			<h3 class="block-library__foundations-heading-small"><?php esc_html_e( 'Dropdown', 'catapult' ); ?></h3>

			<div>
				<h4 class="block-library__foundations-overline"><?php esc_html_e( 'Default', 'catapult' ); ?></h4>
				<label for="block-library-dropdown-default"><?php esc_html_e( 'Label', 'catapult' ); ?></label>
				<select id="block-library-dropdown-default">
					<option value="" disabled selected><?php esc_html_e( 'Select', 'catapult' ); ?></option>
					<option value="1"><?php esc_html_e( 'Option 1', 'catapult' ); ?></option>
					<option value="2"><?php esc_html_e( 'Option 2', 'catapult' ); ?></option>
					<option value="3"><?php esc_html_e( 'Option 3', 'catapult' ); ?></option>
				</select>
			</div>

			<div>
				<h4 class="block-library__foundations-overline"><?php esc_html_e( 'Activated', 'catapult' ); ?></h4>
				<label for="block-library-dropdown-activated"><?php esc_html_e( 'Label', 'catapult' ); ?></label>
				<select id="block-library-dropdown-activated" class="focus">
					<option value="" disabled selected><?php esc_html_e( 'Select', 'catapult' ); ?></option>
					<option value="1"><?php esc_html_e( 'Option 1', 'catapult' ); ?></option>
					<option value="2"><?php esc_html_e( 'Option 2', 'catapult' ); ?></option>
					<option value="3"><?php esc_html_e( 'Option 3', 'catapult' ); ?></option>
				</select>
			</div>
		</div>
	</div>

	<div class="block-library__forms-section">
		<div class="block-library__forms-grid container">
			<h3 class="block-library__foundations-heading-small"><?php esc_html_e( 'Number Input', 'catapult' ); ?></h3>

			<div>
				<label for="block-library-number-default"><?php esc_html_e( 'Label', 'catapult' ); ?></label>
				<input id="block-library-number-default" type="number" value="0" step="1" min="0" max="100">
			</div>
		</div>
	</div>

	<div class="block-library__forms-section">
		<div class="block-library__forms-grid container">
			<h3 class="block-library__foundations-heading-small"><?php esc_html_e( 'File Upload', 'catapult' ); ?></h3>

			<div>
				<input id="block-library-file-default" type="file" aria-label="<?php esc_html_e( 'File upload', 'catapult' ); ?>">
			</div>
		</div>
	</div>

	<div class="block-library__forms-section">
		<div class="block-library__forms-grid container">
			<h3 class="block-library__foundations-heading-small"><?php esc_html_e( 'Form Selection', 'catapult' ); ?></h3>

			<?php for ( $i = 1; $i <= 2; $i++ ) : ?>
				<?php
				$div_attributes = '';

				if ( 2 === $i ) {
					$div_attributes = ' class="bg-dark"';
				}
				?>

				<div <?php echo wp_kses_post( $div_attributes ); ?>>
					<input id="block-library-radio-default-<?php echo esc_attr( $i ); ?>" type="radio" value="<?php esc_html_e( 'Radio Option', 'catapult' ); ?>">
					<label for="block-library-radio-default-<?php echo esc_attr( $i ); ?>"><?php esc_html_e( 'Radio Option', 'catapult' ); ?></label>
				</div>

				<div <?php echo wp_kses_post( $div_attributes ); ?>>
					<input id="block-library-radio-hover-<?php echo esc_attr( $i ); ?>" type="radio" value="<?php esc_html_e( 'Radio Option', 'catapult' ); ?>" class="hover">
					<label for="block-library-radio-hover-<?php echo esc_attr( $i ); ?>"><?php esc_html_e( 'Radio Option', 'catapult' ); ?></label>
				</div>

				<div <?php echo wp_kses_post( $div_attributes ); ?>>
					<input id="block-library-radio-checked-<?php echo esc_attr( $i ); ?>" type="radio" value="<?php esc_html_e( 'Radio Option', 'catapult' ); ?>" checked>
					<label for="block-library-radio-checked-<?php echo esc_attr( $i ); ?>"><?php esc_html_e( 'Radio Option', 'catapult' ); ?></label>
				</div>

				<div></div>
			<?php endfor; ?>

			<?php for ( $i = 1; $i <= 2; $i++ ) : ?>
				<?php
				$div_attributes = '';

				if ( 2 === $i ) {
					$div_attributes = ' class="bg-dark"';
				}
				?>

				<div <?php echo wp_kses_post( $div_attributes ); ?>>
					<input id="block-library-checkbox-default-<?php echo esc_attr( $i ); ?>" type="checkbox" value="<?php esc_html_e( 'Checkbox Option', 'catapult' ); ?>">
					<label for="block-library-checkbox-default-<?php echo esc_attr( $i ); ?>"><?php esc_html_e( 'Checkbox Option', 'catapult' ); ?></label>
				</div>

				<div <?php echo wp_kses_post( $div_attributes ); ?>>
					<input id="block-library-checkbox-hover-<?php echo esc_attr( $i ); ?>" type="checkbox" value="<?php esc_html_e( 'Checkbox Option', 'catapult' ); ?>" class="hover">
					<label for="block-library-checkbox-hover-<?php echo esc_attr( $i ); ?>"><?php esc_html_e( 'Checkbox Option', 'catapult' ); ?></label>
				</div>

				<div <?php echo wp_kses_post( $div_attributes ); ?>>
					<input id="block-library-checkbox-checked-<?php echo esc_attr( $i ); ?>" type="checkbox" value="<?php esc_html_e( 'Checkbox Option', 'catapult' ); ?>" checked>
					<label for="block-library-checkbox-checked-<?php echo esc_attr( $i ); ?>"><?php esc_html_e( 'Checkbox Option', 'catapult' ); ?></label>
				</div>

				<div></div>
			<?php endfor; ?>
		</div>
	</div>
</section>
