<?php
/**
 * Contains the shortcodes used by Shopper Approved
 */

function sa_sync_shopper_api($args) {
    $credentials = array(
        'site_id' => ShopperApproved\ShopperApprovedSettings::getSiteID(),
        'token' => ShopperApproved\ShopperApprovedSettings::getToken()
    );
    $shopper = new \ShopperApproved\ShopperApprovedAPI($credentials);
    $page = 0;
    while ($page < 3) {
        $args = array(
            'from' => '2014-01-01',
            'to' => date('Y-m-d'),
            'page' => $page
        );
        $reviews = $shopper->getReviews($args);
        if (is_array($reviews) && count($reviews) > 0) {
            foreach ($reviews as $review) {
                $review_obj = new \ShopperApproved\ShopperApprovedReview();
                $review_obj->init_from_shopper_review($review);
                $review_obj->sync_review_to_post();
            }
        }
        $page++;
    }
}

add_shortcode('sa_sync_reviews', 'sa_sync_shopper_api');

function sa_show_survey($atts) {
    $site_id = \ShopperApproved\ShopperApprovedSettings::getSiteID();
    $token = \ShopperApproved\ShopperApprovedSettings::getToken();
    $name = $_POST['name'];
    $email = $_POST['email'];
    if (!isset($name) || !isset($email) || empty($site_id) || empty($token)) {
        return;
    }
    ?>
    <script type="text/javascript">
        var sa_values = {
            "site":<?php echo $site_id; ?>,
            "token": "<?php echo $token; ?>",
            "name": "<?php echo $name; ?>",
            "email": "<?php echo $email; ?>",
            "country": "United States"
        };

        function saLoadScript(src) {
            var js = window.document.createElement("script");
            js.src = src;
            js.type = "text/javascript";
            document.getElementsByTagName("head")[0].appendChild(js);
        }

        var d = new Date();
        if (d.getTime() - 172800000 > 1477399567000) {
            d.getTime();
            saLoadScript("//www.shopperapproved.com/thankyou/rate/<?php echo $site_id; ?>.js");
        }
        else {
            saLoadScript("//direct.shopperapproved.com/thankyou/rate/<?php echo $site_id; ?>.js?d=" + d.getTime());
        }
    </script>
    <?php
}
add_shortcode('show_shopper_approved_survey', 'sa_show_survey');