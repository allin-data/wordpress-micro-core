<Form :model="settingsData">
	<Card :dis-hover="true">
		<h2 class="jet-popup-settings-page__title"><?php esc_html_e( 'Settings', 'jet-popup' ); ?></h2>
		<Collapse v-model="collapse">
			<Panel name="mailChimpPanel">
				<?php esc_html_e( 'MailChimp', 'jet-popup' ); ?>
				<div slot="content">
					<FormItem>
						<Divider orientation="left">API key</Divider>
						<Input
							v-model="settingsData.apikey"
							placeholder="<?php esc_html_e( 'API key', 'jet-popup' ); ?>"
						>
							<Button
								slot="append"
								type="primary"
								:loading="syncStatusLoading"
								@click="mailchimpSync($event)"
							>
								<?php esc_html_e( 'Sync', 'jet-popup' ); ?>
							</Button>
						</Input>
						<span>Input your MailChimp API key <a href="http://kb.mailchimp.com/integrations/api-integrations/about-api-keys">About API Keys</a></span>
					</FormItem>
					<transition name="simple-fade">
						<FormItem v-if="isMailchimpAccountData">
							<Divider orientation="left">Account Data</Divider>
							<div class="jet-popup-settings-page__account">
								<Avatar
									:src="mailchimpAccountData.avatar_url"
								/>
								<div class="jet-popup-settings-page__account-info">
									<div><b><?php esc_html_e( 'Account ID: ', 'jet-popup' ); ?></b>{{mailchimpAccountData.account_id}}</div>
									<div><b><?php esc_html_e( 'First Name: ', 'jet-popup' ); ?></b>{{mailchimpAccountData.first_name}}</div>
									<div><b><?php esc_html_e( 'Last Name: ', 'jet-popup' ); ?></b>{{mailchimpAccountData.last_name}}</div>
									<div><b><?php esc_html_e( 'Username: ', 'jet-popup' ); ?></b>{{mailchimpAccountData.username}}</div>
								</div>
							</div>
						</FormItem>
					</transition>
					<transition name="simple-fade">
						<FormItem v-if="isMailchimpListsData">
							<Divider orientation="left">MailChimp Lists</Divider>
							<div class="jet-popup-settings-page__lists">
								<mailchimp-list-item
									v-for="(list, key) in mailchimpListsData"
									:key="list.id"
									:list="list"
									:apikey="settingsData.apikey"
								></mailchimp-list-item>
							</div>
						</FormItem>
					</transition>
				</div>
			</Panel>
		</Collapse>
		<FormItem class="jet-popup-settings-page__action">
			<Button
				type="primary"
				:loading="saveStatusLoading"
				@click="openSaveModal"
				style="margin-top: 20px"
			>
				<?php esc_html_e( 'Save', 'jet-popup' ); ?>
			</Button>
			<Modal
				v-model="modalSave"
				title="<?php esc_html_e( 'Save', 'jet-popup' ); ?>"
				@on-ok="saveSettings"
				ok-text="Yes"
				cancel-text="No"
			>
				<p><?php esc_html_e( 'Are you sure you want to save the settings?', 'jet-popup' ); ?></p>
			</Modal>
		</FormItem>
	</Card>
</Form>
