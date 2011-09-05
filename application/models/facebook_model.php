<?php
class Facebook_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->config->load('facebook');
        $config = array(
                    'appId'  => $this->config->item("facebook_app_id"),
                    'secret' => $this->config->item("facebook_api_secret"),
                    'fileUpload' => true, // Indicates if the CURL based @ syntax for file uploads is enabled.
        );
        $this->load->library('facebook', $config);
        $user = $this->facebook->getUser();

        // We may or may not have this data based on whether the user is logged in.
        //
        // If we have a $user id here, it means we know the user is logged into
        // Facebook, but we don't know if the access token is valid. An access
        // token is invalid if the user logged out of Facebook.
        $profile = NULL;
        if($user)
        {
            try {
                // Proceed knowing you have a logged in user who's authenticated.
                //$profile = $this->facebook->api('/me');
                $profile = $this->facebook->api('/me');
            } catch (FacebookApiException $e) {
                error_log($e);
                $user = NULL;
            }
        }

        $permission = 'email,publish_stream,user_birthday';
        $fb_data = array(
                        'me' => $profile,
                        'uid' => $user,
                        'loginUrl' => $this->facebook->getLoginUrl(array('scope' => $permission)),
                        'logoutUrl' => $this->facebook->getLogoutUrl(),
        );
        $this->session->set_userdata('fb_data', $fb_data);
    }
}
