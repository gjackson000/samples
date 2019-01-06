<?php
/**
 * Adds the survey prompt to WooCommerce Checkout
 */


/**
 * @param WC_Order $order
 */
function sa_shopperapproved_survey($order)
{
    $site_id = \ShopperApproved\ShopperApprovedSettings::getSiteID();
    $token = \ShopperApproved\ShopperApprovedSettings::getToken();
    if (!isset($order) || empty($site_id) || empty($token)) {
        return;
    }
    ?>
    <script type="text/javascript">
        var sa_values = {
            "site":<?php echo $site_id; ?>,
            "token": "<?php echo $token; ?>",
            "orderid": "<?php echo $order->get_id(); ?>",
            "name": "<?php echo $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(); ?>",
            "email": "<?php echo $order->get_billing_email(); ?>",
            "country": "United States",
            "state": "<?php echo $order->get_billing_state(); ?>"
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

add_action('woocommerce_order_details_after_order_table', 'sa_shopperapproved_survey');