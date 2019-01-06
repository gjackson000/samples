<?php
/**
 *  Adds a survey prompt if a Contact Form 7 form has the class 'sa_survey'
 */

/**
 *
 */
function sa_wp_footer() {
    $site_id = \ShopperApproved\ShopperApprovedSettings::getSiteID();
    $token = \ShopperApproved\ShopperApprovedSettings::getToken();
    if (empty($site_id) || empty($token)) {
        return;
    }
    ?>
    <script type="text/javascript">
        function saLoadScript(src) {
            var js = window.document.createElement("script");
            js.src = src; js.type = "text/javascript";
            document .getElementsByTagName("head")[0].appendChild(js);
        }
        var wpcf7Elm = document.querySelector( '.wpcf7' );
        var sa_values;

        wpcf7Elm.addEventListener( 'wpcf7submit', function( event ) {
            var yb_name = jQuery('#qq_name').val();
            var yb_email = jQuery('#qq_email').val();
            var invalid = jQuery('#qq-form').hasClass('invalid');
            if (yb_name !== '' && yb_email !== '' && !invalid) {
                sa_values = {
                    "site":<?php echo $site_id; ?>,
                    "token":"<?php echo $token; ?>",
                    "name":yb_name,
                    "email":yb_email,
                    "country":"United States"};
                var d = new Date();
                if (d.getTime() - 172800000 > 1477399567000) {
                    d.getTime();
                    saLoadScript("//www.shopperapproved.com/thankyou/rate/<?php echo $site_id; ?>.js");
                }
                else {
                    saLoadScript("//direct.shopperapproved.com/thankyou/rate/<?php echo $site_id; ?>.js?d=" + d.getTime());
                }
            }
        }, false );
    </script>
    <?php
}
add_action( 'wp_footer', 'sa_wp_footer' );