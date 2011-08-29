<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cli extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');

        if (!defined('STDIN'))
        {
            echo "\nYou are not Administrator\n\n";
            exit();
        }
        else
        {
            echo "\nBravomix command line system\n\n";
        }
    }

    function delete_user($day = 14)
    {
        $now = time();
        $delete_time = time() - $day*86400;
        $user = $this->ion_auth->get_inactive_users_array();

        // delete user account
        echo "Date: " . date("Y-m-d H:i:s") . " Delete user account who registed two weeks ago\n\n";
        foreach($user as $row)
        {
            if($row['created_on'] < $delete_time)
            {
                echo "Delete user " . $row['username'] . " (" . $row['email'] . ") create time: " . date("Y-m-d", $row['created_on']) . "\n";
                $this->ion_auth->delete_user($row['id']);
            }
        }
    }
}

/* End of file cli.php */
/* Location: ./application/controllers/cli.php */