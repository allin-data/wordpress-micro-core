<div class="jet-popup-conditions-manager">
	<div class="jet-popup-conditions-manager__container">
		<div class="jet-popup-conditions-manager__blank">
			<div class="jet-popup-conditions-manager__blank-title"><?php echo __( 'Set the Pages where to Display the Popup', 'jet-popup' ); ?></div>
			<div class="jet-popup-conditions-manager__blank-message">
				<span><?php echo __( 'Here you can define the specific pages where you want to show the popup, as well as specify the pages where the popup shouldn’t be displayed, using multiple conditions', 'jet-popup' ); ?></span>
			</div>
		</div>
		<div class="jet-popup-conditions-manager__list">
			<div class="jet-popup-conditions-manager__add-condition">
				<Button type="primary" icon="ios-add-circle-outline" @click="addCondition">
					<span v-if="emptyConditions"><?php echo __( 'Add Condition', 'jet-popup' ); ?></span>
					<span v-else><?php echo __( 'Add Additional Condition', 'jet-popup' ); ?></span>
				</Button>
			</div>
			<div class="jet-popup-conditions-manager__list-inner" v-if="!emptyConditions">
				<transition-group name="conditions-list-anim" tag="div">
					<conditions-item
						v-for="сondition in popupConditions"
						:key="сondition.id"
						:id="сondition.id"
						:rawCondition="сondition"
					></conditions-item>
				</transition-group>
			</div>
		</div>
	</div>
	<div class="jet-popup-conditions-manager__controls">
		<Button type="primary" :loading="saveStatusLoading" @click="saveCondition">
			<span v-if="!saveStatusLoading"><?php echo __( 'Save', 'jet-popup' ); ?></span>
			<span v-else><?php echo __( 'Saving', 'jet-popup' ); ?></span>
		</Button>
	</div>
</div>
