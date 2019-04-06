<?php
/**
 * Template Library Header Template
 */
?>
<label class="jet-template-library-filter-label">
	<input type="radio" value="{{ slug }}" <# if ( '' === slug ) { #> checked<# } #> name="jet-library-filter">
	<span>{{ title }}</span>
</label>