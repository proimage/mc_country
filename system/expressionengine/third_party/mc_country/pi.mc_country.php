<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * mc_country Plugin
 *
 * @category    Plugin
 * @author      Michael Cohen
 * @link        http://www.pro-image.co.il
 */

$plugin_info = array(
	'pi_name'       => 'MC Country',
	'pi_version'    => '0.1',
	'pi_author'     => 'Michael Cohen',
	'pi_author_url' => 'http://www.pro-image.co.il',
	'pi_description'=> "Detect user's country using IP2Nation module",
	'pi_usage'      => Mc_country::usage()
);


class Mc_country {

	public $return_data;

	// --------------------------------------------------------------------

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->EE =& get_instance();

		$default = $this->EE->TMPL->fetch_param('default');
		$redirect_countries = $this->EE->TMPL->fetch_param('countries');
		$redirect_url = $this->EE->TMPL->fetch_param('redirect');
		$debug = $this->EE->TMPL->fetch_param('debug');
		$permitted = $this->EE->TMPL->fetch_param('permitted');
		$tag_data = $this->EE->TMPL->tagdata;

		if ($debug != '')
		{
			$country = $debug;
		}
		else
		{
			$ip = $_SERVER['REMOTE_ADDR'];
			$country = $this->_find($ip, $default);
		}

		// If redirect parameter is supplied
		if ($redirect_url != '')
		{

			// if user's country is in list of countries to redirect
			if (strpos($redirect_countries, $country) !== FALSE)
			{

				$current_url = $_SERVER['REQUEST_URI'];
				$redirect_url = html_entity_decode($redirect_url);

				// make sure we don't enter an infinite loop
				if ($redirect_url != $current_url)
				{
					// redirect to url
					header("Location: ".$redirect_url);
					exit;
				}

			}

		}
		elseif ($tag_data != '')
		{
			// if user's country is in list of countries
			if (strpos($redirect_countries, $country) !== FALSE)
			{
				$this->return_data = $tag_data;
			}
		}
		else
		{
			// just return the country code
			$this->return_data = $country;
		}
	}

	// ----------------------------------------------------------------------

	/**
	 * Return user country if it matches a restricted set, or default otherwise.
	 */
	public function restrict()
	{
		$default = $this->EE->TMPL->fetch_param('default');
		$allowed = explode("|", $this->EE->TMPL->fetch_param('allow'));
		$debug = $this->EE->TMPL->fetch_param('debug');


		// Was a default country specified?
		if ( $default === FALSE )
		{
			$this->EE->TMPL->log_item('MC Country: No "default" parameter provided.');
		}

		if ($debug != '')
		{
			$country = $debug;
		}
		else
		{
			$ip = $_SERVER['REMOTE_ADDR'];
			$country = $this->_find($ip, $default);
		}

		// Check if the user's detected country matches an allowed value
		if (in_array($country, $allowed))
		{
			return $country;
		}
		else
		{
			return $default;
		}
	}

	// ----------------------------------------------------------------------

	/**
	 * Get a country by ip address
	 */
	private function _find($ip, $default)
	{
		$BIN = $this->_to_binary($ip);

		$query = $this->EE->db
			->select('country')
			->where("ip_range_low <= '".$BIN."'", '', FALSE)
			->where("ip_range_high >= '".$BIN."'", '', FALSE)
			->order_by('ip_range_low', 'desc')
			->limit(1, 0)
			->get('ip2nation');

		if ($query->num_rows())
		{
			$country = $query->row('country');
		}
		elseif ($default != '')
		{
			$country = $default;
		}
		else
		{
			$country = 'unknown';
		}

		return $country;
	}

	// ----------------------------------------------------------------------

	/**
	 * Convert an IP address to its IPv6 packed format
	 */
	private function _to_binary($addr)
	{
		// all IPv4 go to IPv6 mapped
		if (strpos($addr, ':') === FALSE && strpos($addr, '.') !== FALSE)
		{
			$addr = '::'.$addr;
		}
		return inet_pton($addr);
	}

	// --------------------------------------------------------------------

	/**
	 * Plugin Usage
	 */
	public static function usage()
	{
		ob_start();
?>
==================================================
MC Country
==================================================

Credits
--------------------------------------------------

This is an EE2 port of the OpenMotive Country EE1 plugin:
http://devot-ee.com/add-ons/country-plugin

Due to significant changes in the way data is stored in EE2's IP database table, I had to copy and adapt two internal functions from EllisLab's IP2Nation module. No copyright infringement is intended - I simply couldn't figure out how to make use of the database table otherwise.


==================================================
*PLEASE NOTE*
This plugin requires the "IP to Nation" module
==================================================


This plugin has three main features:

==================================================
1) OUTPUT COUNTRY CODE
==================================================

To output just the two digit country code, place the following tag in any of your templates:

{exp:mc_country}

PARAMETERS:

default = If ip cannot be located, country code will default to this value.

debug = Force a specific two-digit country code. Useful when working locally (ip's won't resolve to correct country on your local network).

==================================================
2) COUNTRY BASED REDIRECT:
==================================================

To redirect if user is from a specified country, specify a list of countries and a redirect path.

{exp:mc_country countries="xx|xx|xx|xx" redirect="/path/to/redirect/to"}

PARAMETERS:

countries = List each two-digit country code separated by |

redirect = The url to redirect to if user is from one of the specified countries.

default = If ip cannot be located, country code will default to this value. Useful when working locally on your own machine.

debug = Force a specific country code. Useful when working locally (ip's won't resolve to correct country on your local network).

==================================================
3) COUNTRY SPECIFIC CONTENT:
==================================================

If you need to display content for visitors from specific countries, simply wrap the content in tags and specify the countries as a parameter.

    {exp:mc_country countries="xx|xx|xx"}
         Content here
    {/exp:mc_country}

==================================================
MORE EXAMPLES
==================================================

Output two digit country code, defaulting to US:

{exp:mc_country default="us"}

==================================================

Redirect the user if they are from the US, UK, or Canada:

{exp:mc_country countries="us|gb|ca" redirect="/english"}

==================================================

You can also redirect to another site completely:

{exp:mc_country countries="ca" redirect="http://www.google.ca"}

==================================================

Use debug mode to force United States country code:

{exp:mc_country countries="us|ca|gb" redirect="http://www.google.ca" debug="us"}

==================================================

Show certain content to visitors from the US, Canada, and Mexico:

    {exp:mc_country countries="us|ca|mx"}
         <h2>Hello, North America!</h2>
    {/exp:mc_country}

==================================================

COUNTRY CODES

A list of country codes can be found at:
http://www.iso.org/iso/english_country_names_and_code_elements

==================================================
<?php
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
}


/* End of file pi.mc_country.php */
/* Location: /system/expressionengine/third_party/mc_country/pi.mc_country.php */
