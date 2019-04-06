<div class="jet-popup-library-page__filters">
	<div class="jet-popup-library-page__filters-category">
		<span><b><?php esc_html_e( 'Categories: ', 'jet-popup' ); ?></b></span>
		<ul>
			<li
				v-for="category in categories"
			>
				<i-switch size="small" @on-change="filterByCategory( $event, category.id )"/>
				<span>{{ category.label }}</span>
			</li>
		</ul>
	</div>
	<div class="jet-popup-library-page__filters-misc">
		<span><b><?php esc_html_e( 'Filter By : ', 'jet-popup' ); ?></b></span>
		<Select size="small" @on-change="filterBy" :value="filterByValue" style="width:100px">
			<Option value="date">Date</Option>
			<Option value="name">Name</Option>
			<Option value="popular">Popular</Option>
		</Select>
	</div>
</div>
