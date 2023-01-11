<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://pbweb.dk
 * @since      1.0.0
 *
 * @package    Pbweb_Flexya
 * @subpackage Pbweb_Flexya/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
    <p><strong><?php esc_attr_e('Warning',$this->plugin_name); ?>: </strong><?php esc_attr_e('Do not change these values unless you know what you\'re doing.', $this->plugin_name); ?></p>
    <form method="post" name="flexyapress_options" action="options.php">

		<?php
		//Grab all options
		$options = get_option($this->plugin_name);

		// Cleanup
		$base_url = $options['base-url'] ?: "https://demomw.mindworking.eu/api/integrations";
		$auth_url = $options['auth-url'] ?: "https://iam.mindworking.eu";
		$client_id = $options['client-id'] ?: 'mw-service-internal-client-pbweb';
		$client_secret = $options['client-secret'] ?: 'iLQymJ18OSZaUMGlkvpOlD6RB6pKKGID';
		$client_realm = $options['client-realm'] ?: 'demomw';
        $shop_no = $options['shop-no'] ?: '125';

        //$token = $options['token'] ?: '';
		//$org_id = $options['org-id'] ?: '';
		//$office_id = $options['office-id'] ?: '';
		$slug = $options['case-slug'] ?: 'bolig';
        $save_images_locally = $options['save-images-locally'] ?: 0;
		$business_enabled = $options['business-enabled'] ?: 0;
		$base_url_business = ($options['base-url-business']) ?: "https://services.erhvervssystem.flexya.dk/service/v20";
		$token_business = $options['token-business'] ?: '';
		$maps_api_key = $options['maps-api-key'] ?: '';
		$captcha_site_key = $options['captcha-site-key'] ?: '';
		$captcha_secret_key = $options['captcha-secret-key'] ?: '';
		$policy_url = $options['policy-url'] ?: '';
        $primary_color = $options['primary-color'] ?: '#000';
        $no_styling = $options['no-styling'] ?: 0;


		?>

		<?php
		settings_fields($this->plugin_name);
		do_settings_sections($this->plugin_name);
		?>
        <h3>Mindworking</h3>
        <table class="form-table">
            <tbody>
                <tr><!-- Base url -->
                    <th><label for="<?php echo $this->plugin_name; ?>-base-url"><?php esc_attr_e('Base url', $this->plugin_name); ?></label></th>
                    <td><input type="text" class="regular-text code" id="<?php echo $this->plugin_name; ?>-base-url" name="<?php echo $this->plugin_name; ?>[base-url]" placeholder="Base url" value="<?php if(!empty($base_url)) echo $base_url; ?>"/></td>
                </tr>
                <tr><!-- Auth url -->
                    <th><label for="<?php echo $this->plugin_name; ?>-auth-url"><?php esc_attr_e('Auth url', $this->plugin_name); ?></label></th>
                    <td><input type="text" class="regular-text code" id="<?php echo $this->plugin_name; ?>-auth-url" name="<?php echo $this->plugin_name; ?>[auth-url]" placeholder="Auth url" value="<?php if(!empty($auth_url)) echo $auth_url; ?>"/></td>
                </tr>
                <tr><!-- Client Id -->
                    <th><label for="<?php echo $this->plugin_name; ?>-client-realm"><?php esc_attr_e('Client realm', $this->plugin_name); ?></label></th>
                    <td><input type="text" class="regular-text client-realm" id="<?php echo $this->plugin_name; ?>-client-realm" name="<?php echo $this->plugin_name; ?>[client-realm]" placeholder="Client realm" value="<?php if(!empty($client_realm)) echo $client_realm; ?>"/></td>
                </tr>
                <tr><!-- Client Id -->
                    <th><label for="<?php echo $this->plugin_name; ?>-client-id"><?php esc_attr_e('Client ID', $this->plugin_name); ?></label></th>
                    <td><input type="text" class="regular-text client-id" id="<?php echo $this->plugin_name; ?>-client-id" name="<?php echo $this->plugin_name; ?>[client-id]" placeholder="Client ID" value="<?php if(!empty($client_id)) echo $client_id; ?>"/></td>
                </tr>
                <tr><!-- Client secret -->
                    <th><label for="<?php echo $this->plugin_name; ?>-client-secret"><?php esc_attr_e('Client secret', $this->plugin_name); ?></label></th>
                    <td><input type="text" class="regular-text client-secret" id="<?php echo $this->plugin_name; ?>-client-secret" name="<?php echo $this->plugin_name; ?>[client-secret]" placeholder="Client secret" value="<?php if(!empty($client_secret)) echo $client_secret; ?>"/></td>
                </tr>
                <tr><!-- Shop no -->
                    <th><label for="<?php echo $this->plugin_name; ?>-shop-no"><?php esc_attr_e('Shop no', $this->plugin_name); ?></label></th>
                    <td><input type="text" class="regular-text shop-no" id="<?php echo $this->plugin_name; ?>-shop-no" name="<?php echo $this->plugin_name; ?>[shop-no]" placeholder="Shop no" value="<?php if(!empty($shop_no)) echo $shop_no; ?>"/></td>
                </tr>
                <tr><!-- Bolig url slug -->
                    <th><label for="<?php echo $this->plugin_name; ?>-case-slug"><?php esc_attr_e('The slug which will be shown in the url for a case', $this->plugin_name); ?></label></th>
                    <td><input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>-case-slug" name="<?php echo $this->plugin_name; ?>[case-slug]" placeholder="Case slug" value="<?php if(!empty($slug)) echo $slug; ?>"/></td>
                </tr>
                <tr><!-- maps -->
                    <th><label for="<?php echo $this->plugin_name; ?>-maps-api-key"><?php esc_attr_e('Google Maps api key', $this->plugin_name); ?></label></th>
                    <td><input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>-maps-api-key" name="<?php echo $this->plugin_name; ?>[maps-api-key]" placeholder="Google Maps API Key" value="<?php if(!empty($maps_api_key)) echo $maps_api_key; ?>"/></td>
                </tr>
                <tr><!-- captcha -->
                    <th><label for="<?php echo $this->plugin_name; ?>-captcha-site-key"><?php esc_attr_e('Recaptcha site key', $this->plugin_name); ?></label></th>
                    <td><input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>-captcha-site-key" name="<?php echo $this->plugin_name; ?>[captcha-site-key]" placeholder="Recaptcha site key" value="<?php if(!empty($captcha_site_key)) echo $captcha_site_key; ?>"/></td>
                </tr>
                <tr><!-- captcha -->
                    <th><label for="<?php echo $this->plugin_name; ?>-captcha-secret-key"><?php esc_attr_e('Recaptcha secret key', $this->plugin_name); ?></label></th>
                    <td><input type="password" class="regular-text" id="<?php echo $this->plugin_name; ?>-captcha-secret-key" name="<?php echo $this->plugin_name; ?>[captcha-secret-key]" placeholder="Recaptcha secret key" value="<?php if(!empty($captcha_secret_key)) echo $captcha_secret_key; ?>"/></td>
                </tr>
                <tr><!-- policy -->
                    <th><label for="<?php echo $this->plugin_name; ?>-policy-url"><?php esc_attr_e('Url to privacy-policy url', $this->plugin_name); ?></label></th>
                    <td><input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>-policy-url" name="<?php echo $this->plugin_name; ?>[policy-url]" placeholder="Privacy policy url" value="<?php if(!empty($policy_url)) echo $policy_url; ?>"/></td>
                </tr>
                <!--<tr>
                    <th><label for="<?php echo $this->plugin_name; ?>-save-images-locally"><?php esc_attr_e('Save images locally', $this->plugin_name); ?></label></th>
                    <td><input type="checkbox" id="<?php echo $this->plugin_name; ?>-save-images-locally" name="<?php echo $this->plugin_name; ?>[save-images-locally]" <?php if(!empty($save_images_locally)) echo "checked"; ?>/></td>
                </tr>-->
                <!-- Save images locally-->
                <tr valign="top">
                    <th scope="row">Primær farve</th>
                    <td><input type="text" class="color-field"  name="<?php echo $this->plugin_name; ?>[primary-color]" value="<?= $primary_color ?>" />    </td>
                </tr>
                <tr>
                    <th><label for="<?php echo $this->plugin_name; ?>-no-styling"><?php esc_attr_e('No styling', $this->plugin_name); ?></label></th>
                    <td><input type="checkbox" id="<?php echo $this->plugin_name; ?>-no-styling" name="<?php echo $this->plugin_name; ?>[no-styling]" <?php if(!empty($no_styling)) echo "checked"; ?>/></td>
                </tr>
            </tbody>
        </table>
<!--
        <h3>Flexya Erhverv</h3>
        <table class="form-table">
            <tbody>
            <tr>
                <th><label for="<?php echo $this->plugin_name; ?>-business-enabled"><?php esc_attr_e('Enable Flexya Business', $this->plugin_name); ?></label></th>
                <td><input type="checkbox" id="<?php echo $this->plugin_name; ?>-business-enabled" name="<?php echo $this->plugin_name; ?>[business-enabled]" <?php if(!empty($business_enabled)) echo "checked"; ?>/></td>
            </tr>
            <tr class="flexya-business-row">
                <th><label for="<?php echo $this->plugin_name; ?>-base-url-business"><?php esc_attr_e('The business base url for Flexya.', $this->plugin_name); ?></label></th>
                <td><input type="text" class="regular-text code" id="<?php echo $this->plugin_name; ?>-base-url-business" name="<?php echo $this->plugin_name; ?>[base-url-business]" placeholder="Base url business" value="<?php if(!empty($base_url_business)) echo $base_url_business; ?>"/></td>
            </tr>
            <tr class="flexya-business-row">
                <th><label for="<?php echo $this->plugin_name; ?>-token-business"><?php esc_attr_e('The business token.', $this->plugin_name); ?></label></th>
                <td><input type="text" class="regular-text token" id="<?php echo $this->plugin_name; ?>-token-business" name="<?php echo $this->plugin_name; ?>[token-business]" placeholder="Business Token" value="<?php if(!empty($token_business)) echo $token_business; ?>"/></td>
            </tr>
            </tbody>
        </table>
-->
		<?php submit_button(__('Save all changes', $this->plugin_name), 'primary','submit', TRUE); ?>

    </form>

    <a href="<?= get_home_url() ?>?flexya_update=1&key=sd5d2rf16&force&debug" target="_blank">Opdatér sager</a>
    <br><br>
    <a href="<?= get_home_url() ?>/?flexya_update=1&force-images=1&key=sd5d2rf16&force&debug" target="_blank">Opdatér sager og billeder</a>
    <br><br>
    <a href="<?= get_home_url() ?>/?flexya_update=1&clear-images=1&key=sd5d2rf16&force&debug" target="_blank">Opdatér sager og ryd billeder</a>

</div>
<?php wp_enqueue_style( 'wp-color-picker' );  ?>
