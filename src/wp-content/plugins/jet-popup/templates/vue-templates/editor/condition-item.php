<Card style="width:100%">
	<div class="jet-popup-conditions-manager__item">
		<div class="jet-popup-conditions-manager__item-control radio-type">
			<RadioGroup v-model="сondition.include" type="button">
				<Radio label="true"><?php echo __( 'Include', 'jet-popup' ); ?></Radio>
				<Radio label="false"><?php echo __( 'Exclude', 'jet-popup' ); ?></Radio>
			</RadioGroup>
		</div>
		<div class="jet-popup-conditions-manager__item-control select-type" v-if="groupVisible">
			<Select
				v-model="сondition.group"
				@on-change="groupChange">
				<Option v-for="group in groupList" :value="group.value" :key="group.value">{{ group.label }}</Option>
			</Select>
		</div>
		<div class="jet-popup-conditions-manager__item-control select-type" v-if="subGroupVisible">
			<Select
				v-model="сondition.subGroup"
				@on-change="subGroupChange">
				<Option v-for="subGroup in subGroupList" :value="subGroup.value" :key="subGroup.value">{{ subGroup.label }}</Option>
			</Select>
		</div>
		<div class="jet-popup-conditions-manager__item-control select-type" v-if="subGroupOptionsVisible">
			<Select
				v-model="сondition.subGroupValue"
				filterable
				clearable
				placeholder="<?php echo __( 'Select', 'jet-popup' ); ?>">
				<Option
					v-for="option in subGroupOptionsList"
					:value="option.value"
					:key="option.value"
				>{{ option.label }}</Option>
			</Select>
		</div>
		<div class="jet-popup-conditions-manager__item-delete">
			<Icon type="ios-trash-outline" @click="deleteCondition" size="20" color="#ed4014" />
		</div>
	</div>
</Card>
