<?php
/**
 * Created by PhpStorm.
 * User: gordon
 * Date: 1/6/19
 * Time: 4:01 PM
 */

namespace ShopperApproved;


class ShopperApprovedReview {

    private $title;
    private $review;
    private $url;
    private $name;
    private $stars;
    private $api_id;
    private $review_date;
    private $status;
    private $last_sync;

    public static function draft_all_reviews() {
        global $wpdb;
        $sql = "update ". $wpdb->prefix . "posts set post_status = 'draft' where post_type = 'sa_review';";
        $wpdb->get_results($sql);
    }

    public function init_from_shopper_review($data) {
        if (!empty($data['displaydate'])) {
            $this->setReviewDate($data['displaydate']);
        }
        if (!empty($data['name'])) {
            $this->setName($data['name']);
        }
        if (!empty($data['Overall'])) {
            $this->setStars($data['Overall']);
        }
        if (!empty($data['public'])) {
            if ($data['public']) {
                $this->setStatus('true');
            }
        }
        if (!empty($data['textcomments'])) {
            $this->setReview($data['textcomments']);
        }
        if (!empty($data['fullurl'])) {
            $this->setURL($data['fullurl']);
            $api_id = $data['fullurl'];
            $api_id = str_replace('http://www.shopperapproved.com/customer-review/elitemover.com/', '', $api_id);
            $this->setApiId($api_id);
        }
    }

    public function init_from_meta($review_meta) {
        if (!is_array($review_meta) || count($review_meta) == 0) {
            return false;
        }
        $this->setApiId($review_meta[ShopperApprovedSettings::SA_OPTIONS_PREFIX.'api_id'][0]);
        $this->setLastSync($review_meta[ShopperApprovedSettings::SA_OPTIONS_PREFIX.'last_sync'][0]);
        $this->setName($review_meta[ShopperApprovedSettings::SA_OPTIONS_PREFIX.'review_name'][0]);
        $this->setStars($review_meta[ShopperApprovedSettings::SA_OPTIONS_PREFIX.'review_rating'][0]);
        $this->setURL($review_meta[ShopperApprovedSettings::SA_OPTIONS_PREFIX.'url'][0]);
    }

    public function sync_review_to_post() {
        if (empty($this->getApiId())) {
            return false;
        }
        $review_date = strtotime($this->getReviewDate());
        $post_id = $this->get_post_id();
        $publish = 'draft';
        if ($this->getStatus() == 'true') {
            $publish = 'publish';
        }
        $post_data = array(
            'comment_status'	=>	'closed',
            'ping_status'		=>	'closed',
            'post_author'		=>	1,
            'post_name'		    =>	sanitize_title("Shopper Approved Review ".$this->getApiId()),
            'post_title'		=>	'Shopper Approved Review '. $this->getApiId(),
            'post_content'      =>  $this->getReview(),
            'post_type'		    =>	'sa_review',
            'post_status'       =>  $publish,
            'post_date'         =>  date('Y-m-d', $review_date)
        );
        if (isset($post_id) && is_numeric($post_id) && $post_id > 0) {
            $post_data['ID'] = $post_id;
            wp_update_post($post_data);
        }
        //create if not found
        else {
            $post_id = wp_insert_post($post_data);
        }
        //set the meta data
        update_post_meta($post_id, ShopperApprovedSettings::SA_OPTIONS_PREFIX.'api_id', $this->getApiId());
        update_post_meta($post_id, ShopperApprovedSettings::SA_OPTIONS_PREFIX.'last_sync', date('Y-m-d'));
        update_post_meta($post_id, ShopperApprovedSettings::SA_OPTIONS_PREFIX.'review_name', $this->getName());
        update_post_meta($post_id, ShopperApprovedSettings::SA_OPTIONS_PREFIX.'review_rating', $this->getStars());
        update_post_meta($post_id, ShopperApprovedSettings::SA_OPTIONS_PREFIX.'url', $this->getURL());

        if (!empty($post_id) && is_numeric($post_id) && $post_id > 0) {
            return true;
        }
        return false;
    }

    public function get_post_id() {
        $post_id = null;
        if (empty($this->api_id)) {
            return false;
        }
        global $wpdb;
        $key = ShopperApprovedSettings::SA_OPTIONS_PREFIX.'api_id';
        $query = $wpdb->prepare("SELECT post_id FROM `".$wpdb->postmeta."` WHERE meta_key=%s AND meta_value=%s", array($key, $this->getApiId()));
        $meta = $wpdb->get_results($query);
        if (is_array($meta) && !empty($meta) && isset($meta[0])) {
            $meta = $meta[0];
        }
        if (is_object($meta)) {
            return $meta->post_id;
        }
        else {
            return false;
        }
    }


    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getReview()
    {
        return $this->review;
    }

    /**
     * @param mixed $review
     */
    public function setReview($review)
    {
        $this->review = $review;
    }

    /**
     * @return mixed
     */
    public function getURL()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setURL($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getStars()
    {
        return $this->stars;
    }

    /**
     * @param mixed $stars
     */
    public function setStars($stars)
    {
        $this->stars = $stars;
    }

    /**
     * @return mixed
     */
    public function getApiId()
    {
        return $this->api_id;
    }

    /**
     * @param mixed $api_id
     */
    public function setApiId($api_id)
    {
        $this->api_id = $api_id;
    }

    /**
     * @return mixed
     */
    public function getReviewDate()
    {
        return $this->review_date;
    }

    /**
     * @param mixed $review_date
     */
    public function setReviewDate($review_date)
    {
        $this->review_date = $review_date;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getLastSync()
    {
        return $this->last_sync;
    }

    /**
     * @param mixed $last_sync
     */
    public function setLastSync($last_sync)
    {
        $this->last_sync = $last_sync;
    }





}