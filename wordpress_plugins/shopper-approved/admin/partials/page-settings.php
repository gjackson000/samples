<?php
/**
 * Created by PhpStorm.
 * User: gordon
 * Date: 11/27/18
 * Time: 4:14 PM
 */

function sa_page_settings()
{

    $site_id = $_POST['sa_settings_site_id'];
    if (isset($site_id) && !empty($site_id)) {
        \ShopperApproved\ShopperApprovedSettings::setSiteID($site_id);
    }
    $site_id = \ShopperApproved\ShopperApprovedSettings::getSiteID();

    $api_token = $_POST['sa_settings_api_token'];
    if (isset($api_token) && !empty($api_token)) {
        \ShopperApproved\ShopperApprovedSettings::setToken($api_token);
    }
    $api_token = \ShopperApproved\ShopperApprovedSettings::getToken();

    ?>
    <div class="sa_admin">
        <h2>Shopper Approved Settings</h2>
        <div>
            Instructions: Obtain your site ID and token for access to the API and enter them below.
        </div>
        <form action="" method="post" name="sa_connect_settings" id="sa_connect_settings_form">
            <div class="sa_form_wrapper">
                <h3>Shopper Approved Settings</h3>
                <fieldset>
                    <label for="sa_settings_site_id">Site ID</label>
                    <input type="text" name="sa_settings_site_id" id="sa_settings_site_id" value="<?php echo esc_attr($site_id); ?>"/>
                    <label for="sa_settings_api_token">API Token</label>
                    <input type="text" name="sa_settings_api_token" id="sa_settings_api_token" value="<?php echo esc_attr($api_token); ?>"/>
                </fieldset>
            </div>
            <div class="sa_form_submit">
                <button type="submit" value="Save" name="sa_save_settings">Save</button>
            </div>
        </form>
    </div>
    <?php
}