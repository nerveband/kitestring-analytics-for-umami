<?php
/**
 * The template for displaying the settings page.
 *
 * @since 0.1.0
 * @change 0.2.0 - Added option for ignoring admins.
 *
 * @global array $options Plugin Settings array.
 *
 * @package Kitestring Analytics for Umami
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<form method="post" action="options.php" class="kitestring-umami-form">
	<?php settings_fields( 'kitestring_umami' ); ?>
	<section class="kitestring-umami-setup" aria-labelledby="kitestring-umami-setup-title">
		<h2 id="kitestring-umami-setup-title"><?php esc_html_e( 'Tracking setup', 'kitestring-analytics-for-umami' ); ?></h2>
		<p class="kitestring-umami-intro">
		<?php
		esc_html_e(
			'Copy the script URL and Website ID from Umami, then enable tracking. No API credentials are needed.',
			'kitestring-analytics-for-umami'
		);
		?>
			</p>
	<table class="form-table">
		<tr>
			<th scope="row">
				<?php esc_html_e( 'Enabled', 'kitestring-analytics-for-umami' ); ?>
			</th>
			<td>
				<label for="kitestring_umami_enabled">
					<input type="checkbox" name="kitestring_umami_options[enabled]" id="kitestring_umami_enabled"
						value="1" <?php checked( $options['enabled'] ); ?> />
					<?php esc_html_e( 'Enable tracking on public pages', 'kitestring-analytics-for-umami' ); ?>
				</label>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="kitestring_umami_script_url"><?php esc_html_e( 'Script URL', 'kitestring-analytics-for-umami' ); ?></label>
			</th>
			<td>
				<input class="kitestring-umami-url" type="url" name="kitestring_umami_options[script_url]" id="kitestring_umami_script_url"
					value="<?php echo esc_attr( $options['script_url'] ); ?>"/>
				<p class="description">
					<?php
					echo wp_kses(
						__( 'Self-hosted: <code>https://your-domain.com/script.js</code><br>Cloud: <code>https://cloud.umami.is/script.js</code>', 'kitestring-analytics-for-umami' ),
						array(
							'br'   => array(),
							'code' => array(),
						)
					);
					?>
				</p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="kitestring_umami_website_id"><?php esc_html_e( 'Website ID', 'kitestring-analytics-for-umami' ); ?></label>
			</th>
			<td>
				<input class="kitestring-umami-text"  type="text" name="kitestring_umami_options[website_id]" id="kitestring_umami_website_id"
					value="<?php echo esc_attr( $options['website_id'] ); ?>"/>
				<p class="description"><?php esc_html_e( 'Find this in your Umami website settings, beside the tracking code.', 'kitestring-analytics-for-umami' ); ?></p>
			</td>
		</tr>
	</table>
	</section>

	<details class="kitestring-umami-section" id="advanced-options">
		<summary>
			<span><?php esc_html_e( 'Tracking options', 'kitestring-analytics-for-umami' ); ?></span>
			<span class="kitestring-umami-summary-note"><?php esc_html_e( 'Privacy, events, and collection behavior', 'kitestring-analytics-for-umami' ); ?></span>
		</summary>
		<div class="kitestring-umami-section-body">
			<table class="form-table">
				<tr>
					<th scope="row">
						<label for="kitestring_umami_host_url"><?php esc_html_e( 'Collection host URL', 'kitestring-analytics-for-umami' ); ?></label>
					</th>
					<td>
						<input class="kitestring-umami-url" type="url" name="kitestring_umami_options[host_url]" id="kitestring_umami_host_url"
							value="<?php echo esc_attr( $options['host_url'] ); ?>"/>
						<p class="description">
						<?php
						echo wp_kses(
							__( 'The URL of the Umami instance <b>if it is different from the URL to the script.</b>', 'kitestring-analytics-for-umami' ),
							array(
								'b' => array(),
							)
						);
						?>
						</p>
					</td>
				</tr>

				<tr>
					<th class="row">
						<?php esc_html_e( 'Use Host Url', 'kitestring-analytics-for-umami' ); ?>
					</th>
					<td>
						<label for="kitestring_umami_use_host_url">
							<input type="checkbox" name="kitestring_umami_options[use_host_url]" id="kitestring_umami_use_host_url"
								value="1" <?php checked( $options['use_host_url'] ); ?> />
							<?php
							echo wp_kses(
								__( 'Use Host Url as data target. <a href="https://umami.is/docs/tracker-configuration">More information</a>', 'kitestring-analytics-for-umami' ),
								array(
									'a' => array(
										'href' => array(),
									),
								)
							);
							?>
						</label>
					</td>
				</tr>

				<tr>
					<th class="row">
						<?php esc_html_e( 'Ignore Admins', 'kitestring-analytics-for-umami' ); ?>
					</th>
					<td>
						<label for="kitestring_umami_ignore_admins">
							<input type="checkbox" name="kitestring_umami_options[ignore_admins]" id="kitestring_umami_ignore_admins"
								value="1" <?php checked( $options['ignore_admins'] ); ?> />
							<?php esc_html_e( 'Disable tracking for admin users', 'kitestring-analytics-for-umami' ); ?>
						</label>
					</td>
				</tr>

				<tr>
					<th class="row">
						<?php esc_html_e( 'Auto Tracking', 'kitestring-analytics-for-umami' ); ?>
					</th>
					<td>
						<label for="kitestring_umami_auto_track">
							<input type="checkbox" name="kitestring_umami_options[auto_track]" id="kitestring_umami_auto_track"
								value="1" <?php checked( $options['auto_track'] ); ?> />
							<?php esc_html_e( 'Automatically track pageviews and elements with Umami event attributes', 'kitestring-analytics-for-umami' ); ?>
							<p class="description">
								<?php
								echo wp_kses(
									__(
										'Track manually with <code>umami.track()</code> when disabled. See <a href="https://umami.is/docs/tracker-functions">tracker functions</a>.',
										'kitestring-analytics-for-umami'
									),
									array(
										'code' => array(),
										'a'    => array(
											'href' => array(),
										),
									)
								);
								?>
							</p>
						</label>
					</td>
				</tr>

				<tr>
					<th class="row">
						<?php esc_html_e( 'Track Comment Submit', 'kitestring-analytics-for-umami' ); ?>
					</th>
					<td>
						<label for="kitestring_umami_track_comments">
							<input type="checkbox" name="kitestring_umami_options[track_comments]" id="kitestring_umami_track_comments" value="1" <?php checked( $options['track_comments'] ); ?> />
							<?php
							echo wp_kses(
								__( 'Track Comment submits using <a href="https://umami.is/docs/track-events">events</a>', 'kitestring-analytics-for-umami' ),
								array(
									'a' => array(
										'href' => array(),
									),
								)
							);
							?>
						</label>
					</td>
				</tr>

				<tr>
					<th class="row">
						<?php esc_html_e( 'Do Not Track', 'kitestring-analytics-for-umami' ); ?>
					</th>
					<td>
						<label for="kitestring_umami_do_not_track">
							<input type="checkbox" name="kitestring_umami_options[do_not_track]" id="kitestring_umami_do_not_track"
								value="1" <?php checked( $options['do_not_track'] ); ?> />
							<?php
							echo wp_kses(
								__( 'Respect visitor`s <b>Do Not Track</b> setting (supported by Umami v3)', 'kitestring-analytics-for-umami' ),
								array(
									'b' => array(),
								)
							);
							?>
						</label>
					</td>
				</tr>

				<tr>
					<th class="row">
						<label for="kitestring_umami_tag"><?php esc_html_e( 'Tag', 'kitestring-analytics-for-umami' ); ?></label>
					</th>
					<td>
						<input class="kitestring-umami-text" type="text" name="kitestring_umami_options[tag]" id="kitestring_umami_tag"
							value="<?php echo esc_attr( $options['tag'] ); ?>"/>
						<p class="description">
							<?php
							echo wp_kses(
								__( 'Group events for A/B testing. See <a href="https://umami.is/docs/tracker-configuration">tracker configuration</a>.', 'kitestring-analytics-for-umami' ),
								array(
									'a' => array(
										'href' => array(),
									),
								)
							);
							?>
						</p>
					</td>
				</tr>

				<tr>
					<th class="row">
						<label for="kitestring_umami_domains"><?php esc_html_e( 'Allowed domains', 'kitestring-analytics-for-umami' ); ?></label>
					</th>
					<td>
						<input class="kitestring-umami-text" type="text" name="kitestring_umami_options[domains]" id="kitestring_umami_domains"
							value="<?php echo esc_attr( $options['domains'] ); ?>"/>
						<p class="description">
							<?php esc_html_e( 'Comma-separated list of domains where tracking is active. Leave empty to track on all domains.', 'kitestring-analytics-for-umami' ); ?>
						</p>
					</td>
				</tr>

				<tr>
					<th class="row">
						<?php esc_html_e( 'Exclude Search Params', 'kitestring-analytics-for-umami' ); ?>
					</th>
					<td>
						<label for="kitestring_umami_exclude_search">
							<input type="checkbox" name="kitestring_umami_options[exclude_search]" id="kitestring_umami_exclude_search"
								value="1" <?php checked( $options['exclude_search'] ); ?> />
							<?php esc_html_e( 'Exclude URL query parameters from tracked page URLs', 'kitestring-analytics-for-umami' ); ?>
							<p class="description">
								<?php esc_html_e( 'Removes ?query=string from tracked URLs. Requires Umami v3.', 'kitestring-analytics-for-umami' ); ?>
							</p>
						</label>
					</td>
				</tr>

				<tr>
					<th class="row">
						<?php esc_html_e( 'Exclude Hash', 'kitestring-analytics-for-umami' ); ?>
					</th>
					<td>
						<label for="kitestring_umami_exclude_hash">
							<input type="checkbox" name="kitestring_umami_options[exclude_hash]" id="kitestring_umami_exclude_hash"
								value="1" <?php checked( $options['exclude_hash'] ); ?> />
							<?php esc_html_e( 'Exclude URL hash fragments from tracked page URLs', 'kitestring-analytics-for-umami' ); ?>
							<p class="description">
								<?php esc_html_e( 'Removes #fragment from tracked URLs. Requires Umami v3.', 'kitestring-analytics-for-umami' ); ?>
							</p>
						</label>
					</td>
				</tr>

				<tr>
					<th class="row">
						<label for="kitestring_umami_before_send"><?php esc_html_e( 'Before-send function', 'kitestring-analytics-for-umami' ); ?></label>
					</th>
					<td>
						<input class="kitestring-umami-text" type="text" name="kitestring_umami_options[before_send]" id="kitestring_umami_before_send"
							value="<?php echo esc_attr( $options['before_send'] ); ?>"/>
						<p class="description">
							<?php
							echo wp_kses(
								__( '<code>window[name](type, payload)</code> returns modified data, or a falsy value to cancel. Requires Umami v3.', 'kitestring-analytics-for-umami' ),
								array(
									'code' => array(),
								)
							);
							?>
						</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Automatic Pageviews', 'kitestring-analytics-for-umami' ); ?></th>
					<td><label><input type="checkbox" name="kitestring_umami_options[auto_pageview]" id="kitestring_umami_auto_pageview" value="1" <?php checked( $options['auto_pageview'] ); ?> />
						<?php
						esc_html_e(
							'Track pageviews automatically, including history navigation, while keeping automatic event tracking enabled',
							'kitestring-analytics-for-umami'
						);
						?>
							</label></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Performance Metrics', 'kitestring-analytics-for-umami' ); ?></th>
					<td><label><input type="checkbox" name="kitestring_umami_options[performance]" id="kitestring_umami_performance" value="1" <?php checked( $options['performance'] ); ?> />
						<?php esc_html_e( 'Enable Umami performance and web-vitals collection', 'kitestring-analytics-for-umami' ); ?></label></td>
				</tr>
				<tr>
					<th scope="row"><label for="kitestring_umami_distinct_id"><?php esc_html_e( 'Distinct ID', 'kitestring-analytics-for-umami' ); ?></label></th>
					<td><input class="kitestring-umami-text" type="text" name="kitestring_umami_options[distinct_id]" id="kitestring_umami_distinct_id"
						value="<?php echo esc_attr( $options['distinct_id'] ); ?>" />
						<p class="description">
							<?php esc_html_e( 'A fixed pseudonymous ID groups every visitor together. Never use patient information.', 'kitestring-analytics-for-umami' ); ?>
						</p>
						<p class="description">
							<?php esc_html_e( 'Use kitestring_umami_tracker_attributes or umami.identify() for visitor-specific identities.', 'kitestring-analytics-for-umami' ); ?>
						</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="kitestring_umami_fetch_credentials"><?php esc_html_e( 'Fetch Credentials', 'kitestring-analytics-for-umami' ); ?></label></th>
					<td><select name="kitestring_umami_options[fetch_credentials]" id="kitestring_umami_fetch_credentials">
						<option value="omit" <?php selected( $options['fetch_credentials'], 'omit' ); ?>><?php esc_html_e( 'Omit (recommended)', 'kitestring-analytics-for-umami' ); ?></option>
						<option value="same-origin" <?php selected( $options['fetch_credentials'], 'same-origin' ); ?>><?php esc_html_e( 'Same origin', 'kitestring-analytics-for-umami' ); ?></option>
						<option value="include" <?php selected( $options['fetch_credentials'], 'include' ); ?>><?php esc_html_e( 'Include', 'kitestring-analytics-for-umami' ); ?></option>
					</select>
					<p class="description">
						<?php
							esc_html_e(
								'Controls browser credentials sent with tracker requests. Leave Omit unless your collector requires cookies or HTTP authentication.',
								'kitestring-analytics-for-umami'
							);
							?>
					</p></td>
				</tr>
			</table>
		</div>
	</details>

	<details class="kitestring-umami-section" id="recorder-options">
		<summary>
			<span><?php esc_html_e( 'Heatmaps and session replay', 'kitestring-analytics-for-umami' ); ?></span>
			<span class="kitestring-umami-summary-note"><?php esc_html_e( 'Separate opt-in', 'kitestring-analytics-for-umami' ); ?></span>
		</summary>
		<div class="kitestring-umami-section-body">
			<p class="description">
				<?php esc_html_e( 'Recorder support is off by default. Also enable heatmaps or replay in your Umami website settings.', 'kitestring-analytics-for-umami' ); ?>
			</p>
			<p class="description">
				<?php esc_html_e( 'Umami controls sampling, masking, blocking, and duration. Loading recorder.js alone does not enable server capture.', 'kitestring-analytics-for-umami' ); ?>
			</p>
			<p class="kitestring-umami-warning">
				<?php esc_html_e( 'Replay captures page content and interactions. Heatmaps include full URLs.', 'kitestring-analytics-for-umami' ); ?>
				<br>
				<?php esc_html_e( 'Tracker query/hash exclusions and Before Send do not sanitize recorder payloads.', 'kitestring-analytics-for-umami' ); ?>
				<br>
				<?php esc_html_e( 'Never record pages with patient information or other sensitive data. Obtain appropriate consent first.', 'kitestring-analytics-for-umami' ); ?>
			</p>
			<table class="form-table">
				<tr><th scope="row"><?php esc_html_e( 'Load Recorder', 'kitestring-analytics-for-umami' ); ?></th>
					<td><label><input type="checkbox" name="kitestring_umami_options[recorder_enabled]" id="kitestring_umami_recorder_enabled"
						value="1" <?php checked( $options['recorder_enabled'] ); ?> />
						<?php esc_html_e( 'I understand the privacy implications and want to load the Umami heatmap/session replay recorder', 'kitestring-analytics-for-umami' ); ?></label></td></tr>
				<tr><th scope="row"><label for="kitestring_umami_recorder_url"><?php esc_html_e( 'Recorder URL', 'kitestring-analytics-for-umami' ); ?></label></th>
					<td><input class="kitestring-umami-url" type="url" name="kitestring_umami_options[recorder_url]" id="kitestring_umami_recorder_url"
						value="<?php echo esc_attr( $options['recorder_url'] ); ?>" />
						<p class="description">
							<?php
							esc_html_e(
								'Blank uses recorder.js beside the tracker. Cloud: https://cloud.umami.is/recorder.js. Enter a URL for another host.',
								'kitestring-analytics-for-umami'
							);
							?>
						</p></td></tr>
			</table>
		</div>
	</details>

	<details class="kitestring-umami-section" id="api-options">
		<summary>
			<span><?php esc_html_e( 'Dashboard connection', 'kitestring-analytics-for-umami' ); ?></span>
			<span class="kitestring-umami-summary-note"><?php esc_html_e( 'Optional API credentials', 'kitestring-analytics-for-umami' ); ?></span>
		</summary>
		<div class="kitestring-umami-section-body">
			<p class="description">
				<?php
				echo wp_kses(
					__( 'Dashboard API: Cloud key or self-hosted username/password. See <a href="https://umami.is/docs/api">API docs</a>.', 'kitestring-analytics-for-umami' ),
					array(
						'a' => array(
							'href' => array(),
						),
					)
				);
				?>
			</p>
			<table class="form-table">
				<tr>
					<th class="row">
						<label for="kitestring_umami_api_key"><?php esc_html_e( 'Cloud API key', 'kitestring-analytics-for-umami' ); ?></label>
					</th>
					<td>
						<input class="kitestring-umami-text" type="password" name="kitestring_umami_options[api_key]" id="kitestring_umami_api_key"
							value="<?php echo esc_attr( $options['api_key'] ); ?>" autocomplete="off"/>
						<p class="description">
							<?php
							echo wp_kses(
								__(
									'For Umami Cloud: create an API key at Settings &gt; API Keys in your <a href="https://cloud.umami.is">Umami Cloud</a> dashboard.',
									'kitestring-analytics-for-umami'
								),
								array(
									'a' => array(
										'href' => array(),
									),
								)
							);
							?>
						</p>
					</td>
				</tr>

				<tr>
					<th class="row">
						<label for="kitestring_umami_api_username"><?php esc_html_e( 'Self-hosted username', 'kitestring-analytics-for-umami' ); ?></label>
					</th>
					<td>
						<input class="kitestring-umami-text" type="text" name="kitestring_umami_options[api_username]" id="kitestring_umami_api_username"
							value="<?php echo esc_attr( $options['api_username'] ); ?>" autocomplete="off"/>
						<p class="description">
							<?php esc_html_e( 'For self-hosted Umami: your Umami admin username.', 'kitestring-analytics-for-umami' ); ?>
						</p>
					</td>
				</tr>

				<tr>
					<th class="row">
						<label for="kitestring_umami_api_password"><?php esc_html_e( 'Self-hosted password', 'kitestring-analytics-for-umami' ); ?></label>
					</th>
					<td>
						<input class="kitestring-umami-text" type="password" name="kitestring_umami_options[api_password]" id="kitestring_umami_api_password"
							value="<?php echo esc_attr( $options['api_password'] ); ?>" autocomplete="off"/>
						<p class="description">
							<?php esc_html_e( 'For self-hosted Umami: your Umami admin password.', 'kitestring-analytics-for-umami' ); ?>
						</p>
					</td>
				</tr>
			</table>
		</div>
	</details>

	<div class="kitestring-umami-save">
		<?php submit_button( __( 'Save settings', 'kitestring-analytics-for-umami' ) ); ?>
		<p><?php esc_html_e( 'After saving, purge your page cache and check a signed-out visit. Configuration alone does not confirm collection.', 'kitestring-analytics-for-umami' ); ?></p>
	</div>
</form>
