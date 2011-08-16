<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */
// ------------------------------------------------------------------------

/**
 * CodeIgniter HTML Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/html_helper.html
 */
// ------------------------------------------------------------------------

/**
 * script_tag
 *
 * Generates an javascript heading tag. First param is the data.
 *
 * @access	public
 * @param	string
 * @return	string
 */
if (!function_exists('script_tag'))
{

    function script_tag($src = NULL)
    {
        if (isset($src) and !empty($src))
        {
            return '<script src="' . base_url($src) . '" type="text/javascript"></script>';
        }

        return "";
    }

}

/* End of file html_helper.php */
/* Location: ./application/helpers/MY_html_helper.php */