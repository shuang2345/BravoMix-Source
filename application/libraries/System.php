<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class System {

    /**
     * CodeIgniter global
     *
     * @var string
     **/
    protected $ci;
    
    public function __construct()
    {
        $this->ci =& get_instance();
    }

    public function d_print($msg)
    {
        echo '<pre>';
        print_r($msg);
        echo '</pre>';
    }

    public function d_dump($msg)
    {
        echo '<pre>';
        var_dump($msg);
        echo '</pre>';
    }

    /**
    * Generate an activation code
    * @param int $length is the length of the activation code to generate
    * @return string
    */
    function generate_code($length = 11)
    {
        $code = "";
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        srand((double)microtime()*1000000); 
        for ($i=0; $i<$length; $i++)
        {
            $code .= substr ($chars, rand() % strlen($chars), 1); 
        } 
        return $code; 
    }
}

/* End of file System.php */