<?php
/**
 * API wrapper for Shopper Approved
 */

namespace ShopperApproved;


class ShopperApprovedAPI {

    const ENDPOINT_TIMEOUT = 120;
    const PUBLIC_ERROR = 'We\'re unable to process your reviews at this time. Contact a site administrator to learn more.';
    const SHOW_LOG = FALSE;


    private $site_id;
    private $rest_token;
    private $comm_response;
    private $comm_response_info;
    private $rest_endpoint_url;
    private $errors = array();
    private $is_dev = FALSE;

    /**
     * ShopperApprovedAPI constructor.
     */
    public function __construct($credentials) {
        if(function_exists('is_dev')) :
            $this->is_dev = (bool) is_dev();
        endif;
        if (is_array($credentials) && count($credentials) > 0) {
            $this->site_id = $credentials['site_id'];
            $this->rest_token = $credentials['token'];
        }
        $this->rest_endpoint_url = "https://www.shopperapproved.com/api/";
    }

    /**
     * @param $args
     * @return array|bool|null
     */
    public function getReviews($args) {
        $args = array_merge(array(
            'from' => '',
            'to' => '',
            'sort' => '',
            'page' => '0',
        ), $args);

        $query = "";
        if (!empty($args['from']) && !empty($args['to'])) {
            $query .= '&from='.urlencode($args['from']).'&to='.urlencode($args['to']);
        }
        if (!empty($args['sort'])) {
            $query .= '&sort='.urlencode($args['sort']);
        }
        if (!empty($args['page'])) {
            $query .= '&page='.urlencode($args['page']);
        }

        if(FALSE === $this->comm('reviews/', 'GET', $query)) :
            return FALSE;
        endif;

        if(!is_array($this->comm_response)) :
            return NULL;
        endif;

        $return = $this->comm_response;

        return $return;
    }

    /**
     * @param null $endpoint_script
     * @param null $endpoint_method
     * @param array $endpoint_args
     * @param null $endpoint_url
     * @param bool $verify_response
     * @return array|bool|mixed
     */
    private function comm($endpoint_script=NULL, $endpoint_method=NULL, $endpoint_args="", $endpoint_url=NULL, $verify_response=TRUE) {
        $this->comm_response = NULL;
        $this->comm_response_info = NULL;

        if(NULL === $endpoint_url) :
            $endpoint_url = $this->rest_endpoint_url.ltrim($endpoint_script, '/');
        endif;

        if(!empty($this->rest_token)) :
            $token_param = '?token='.urlencode($this->rest_token).'&';

            if(strstr($endpoint_url, '?')) :
                $endpoint_url = str_replace('?', $token_param, $endpoint_url);
            else :
                $endpoint_url = $endpoint_url.$token_param;
            endif;
        elseif(strstr($endpoint_url, '?')) :
            $endpoint_url = rtrim($endpoint_url, '&').'&';
        else :
            $endpoint_url = $endpoint_url.'?';
        endif;

        if (!empty($this->site_id)) {
            $endpoint_url .= '&siteid='.urlencode($this->site_id);
        }

        switch($endpoint_method) :
            case 'GET' :
                $endpoint_url_qs = empty($endpoint_args) ? '' : $endpoint_args;

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $endpoint_url.$endpoint_url_qs);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, self::ENDPOINT_TIMEOUT);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                break;
            case 'POST' :
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $endpoint_url);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, self::ENDPOINT_TIMEOUT);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_POST, TRUE);

                if(!empty($endpoint_args)) :
                    $endpoint_args_string = json_encode($endpoint_args);

                    curl_setopt($ch, CURLOPT_POSTFIELDS, $endpoint_args_string);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json',
                        'Content-Length: '.strlen($endpoint_args_string),
                    ));
                endif;
                break;
            case 'PUT' :
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $endpoint_url);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, self::ENDPOINT_TIMEOUT);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT'); # Don't use CURLOPT_PUT (results in a "read timed out")

                if(!empty($endpoint_args)) :
                    $endpoint_args_string = json_encode($endpoint_args);

                    curl_setopt($ch, CURLOPT_POSTFIELDS, $endpoint_args_string);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json',
                        'Content-Length: '.strlen($endpoint_args_string),
                    ));
                endif;
                break;
            default :
                ob_start();
                //var_dump($method);

                $this->errorsSet(array(
                    'Comm error (method)',
                    'Invalid endpoint method ('.ob_get_clean().')',
                ), __LINE__, __METHOD__);

                return FALSE;
                break;
        endswitch;

        $this->comm_response = curl_exec($ch);
        $this->comm_response_info = curl_getinfo($ch);
        curl_close($ch);

        if(FALSE === $this->comm_response) :
            $this->errorsSet(array(
                'Comm error (cURL)',
                'Endpoint: '.$endpoint_url,
                'REST method: '.$endpoint_method,
                'REST args: '.((empty($endpoint_args)) ? 'NULL' : json_encode($endpoint_args)),
                'Error: '.curl_error($ch),
            ), __LINE__, __METHOD__);

            return FALSE;
        endif;

        if(TRUE === $verify_response) :
            if(NULL === ($this->comm_response = json_decode($this->comm_response, TRUE))) :
                $this->errorsSet(array(
                    'Comm error (Failed to decode response)',
                    'Endpoint: '.$endpoint_url,
                    'REST method: '.$endpoint_method,
                    'REST args: '.((empty($endpoint_args)) ? 'NULL' : json_encode($endpoint_args)),
                    'Response: '.$this->comm_response,
                    'Response info: '.json_encode($this->comm_response_info),
                ), __LINE__, __METHOD__);

                return FALSE;
            endif;

            if(!is_array($this->comm_response)) :
                $this->errorsSet(array(
                    'Comm error (Response is not an array)',
                    'Endpoint: '.$endpoint_url,
                    'REST method: '.$endpoint_method,
                    'REST args: '.((empty($endpoint_args)) ? 'NULL' : json_encode($endpoint_args)),
                    'Response: '.$this->comm_response,
                    'Response info: '.json_encode($this->comm_response_info),
                ), __LINE__, __METHOD__);

                return FALSE;
            endif;
        endif;

        if(is_array($this->comm_response) && isset($this->comm_response['error']) || isset($this->comm_response['errorCode'])) :
            $this->errorsSet(array(
                'Comm error (Endpoint error)',
                'Endpoint: '.$endpoint_url,
                'REST method: '.$endpoint_method,
                'REST args: '.((empty($endpoint_args)) ? 'NULL' : json_encode($endpoint_args)),
                'Response: '.json_encode($this->comm_response),
                'Response info: '.json_encode($this->comm_response_info),
            ), __LINE__, __METHOD__);

            return FALSE;
        endif;

        return $this->comm_response;
    }

    public function errorsGetAll() {
        return $this->errors;
    }

    public function errorsGetLast() {
        return empty($this->errors) ? NULL : end($this->errors);
    }

    private function errorsSet($message=NULL, $line=NULL, $method=NULL) {
        if(TRUE !== $this->is_dev) :
            $message = self::PUBLIC_ERROR;
        endif;

        $message = !is_array($message) ? array($message) : $message;
        $prefix = '';
        $suffix = '';

        if(TRUE !== $this->is_dev) :
            $prefix = empty($line) ? '' : '[Err'.$line.']';
        else :
            array_unshift($message, 'Method: '.$method, 'Line: '.$line);
        endif;

        $message = implode('<br>', $message);
        $message = trim($prefix.' '.$message.' '.$suffix);

        $this->errors[] = array('method' => $method, 'line' => $line, 'message' => $message);
        return TRUE;
    }

    private function log($msg) {
        if(FALSE === $this->is_dev || FALSE === self::SHOW_LOG) :
            return;
        endif;

        echo $msg.'<br>';
    }

}