<transition-group name="preset-list" tag="div" class="jet-popup-library-page__list" mode="in-out">
	<preset-item
		v-for="preset in presets"
		:key="preset.id"
		:presetId="preset.id"
		:title="preset.title"
		:category="preset.category"
		:categoryNames="preset.categoryNames"
		:thumbUrl="preset.thumb"
		:install="preset.install"
		:required="preset.required"
		:excerpt="preset.excerpt"
		:details="preset.details"
		:permalink="preset.permalink"
		>
	</preset-item>
</transition-group>
