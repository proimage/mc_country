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
	'pi_version'    => '1.0.1',
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

	public function continent()
	{
		$this->EE =& get_instance();

		$default = $this->EE->TMPL->fetch_param('default');
		$redirect_continents = $this->EE->TMPL->fetch_param('continents');
		$redirect_url = $this->EE->TMPL->fetch_param('redirect');
		$debug = $this->EE->TMPL->fetch_param('debug');
		$permitted = $this->EE->TMPL->fetch_param('permitted');
		$tag_data = $this->EE->TMPL->tagdata;

		if ($debug != '')
		{
			$continent = $debug;
			$continent = $this->_continent($continent);
		}
		else
		{
			$ip = $_SERVER['REMOTE_ADDR'];
			$country = $this->_find($ip, $default);
			$continent = $this->_continent($country);
		}

		// If redirect parameter is supplied
		if ($redirect_url != '')
		{

			// if user's continent is in list of continent to redirect
			if (strpos($redirect_continents, $continent) !== FALSE)
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
			// if user's continent is in list
			if (strpos($redirect_continents, $continent) !== FALSE)
			{
				$this->return_data = $tag_data;
			}
		}
		else
		{
			// just return the continent code
			$this->return_data = $continent;
		}
		return $this->return_data;
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
	 * Map country code to continent
	 */
	private function _continent($country)
	{
		// source: http://www.countrycallingcodes.com/iso-country-codes/
		switch(strtoupper($country)){
			case 'DZ':
			case 'AO':
			case 'SH':
			case 'BJ':
			case 'BW':
			case 'BF':
			case 'BI':
			case 'CM':
			case 'CV':
			case 'CF':
			case 'TD':
			case 'KM':
			case 'CG':
			case 'DJ':
			case 'EG':
			case 'GQ':
			case 'ER':
			case 'ET':
			case 'GA':
			case 'GM':
			case 'GH':
			case 'GW':
			case 'GN':
			case 'CI':
			case 'KE':
			case 'LS':
			case 'LR':
			case 'LY':
			case 'MG':
			case 'MW':
			case 'ML':
			case 'MR':
			case 'MU':
			case 'YT':
			case 'MA':
			case 'MZ':
			case 'NA':
			case 'NE':
			case 'NG':
			case 'ST':
			case 'RE':
			case 'RW':
			case 'ST':
			case 'SN':
			case 'SC':
			case 'SL':
			case 'SO':
			case 'ZA':
			case 'SH':
			case 'SD':
			case 'SZ':
			case 'TZ':
			case 'TG':
			case 'TN':
			case 'UG':
			case 'CD':
			case 'ZM':
			case 'TZ':
			case 'ZW':
			case 'SS':
			case 'CD':
				return 'af';
			case 'AQ':
				return 'an';
			case 'AF':
			case 'AM':
			case 'AZ':
			case 'BH':
			case 'BD':
			case 'BT':
			case 'BN':
			case 'KH':
			case 'CN':
			case 'CX':
			case 'CC':
			case 'IO':
			case 'GE':
			case 'HK':
			case 'IN':
			case 'ID':
			case 'IR':
			case 'IQ':
			case 'IL':
			case 'JP':
			case 'JO':
			case 'KZ':
			case 'KP':
			case 'KR':
			case 'KW':
			case 'KG':
			case 'LA':
			case 'LB':
			case 'MO':
			case 'MY':
			case 'MV':
			case 'MN':
			case 'MM':
			case 'NP':
			case 'OM':
			case 'PK':
			case 'PH':
			case 'QA':
			case 'SA':
			case 'SG':
			case 'LK':
			case 'SY':
			case 'TW':
			case 'TJ':
			case 'TH':
			case 'TR':
			case 'TM':
			case 'AE':
			case 'UZ':
			case 'VN':
			case 'YE':
			case 'PS':
				return 'as';
			case 'AS':
			case 'AU':
			case 'NZ':
			case 'CK':
			case 'FJ':
			case 'PF':
			case 'GU':
			case 'KI':
			case 'MP':
			case 'MH':
			case 'FM':
			case 'UM':
			case 'NR':
			case 'NC':
			case 'NZ':
			case 'NU':
			case 'NF':
			case 'PW':
			case 'PG':
			case 'MP':
			case 'SB':
			case 'TK':
			case 'TO':
			case 'TV':
			case 'VU':
			case 'UM':
			case 'WF':
			case 'WS':
			case 'TL':
				return 'oc';
			case 'AL':
			case 'AD':
			case 'AT':
			case 'BY':
			case 'BE':
			case 'BA':
			case 'BG':
			case 'HR':
			case 'CY':
			case 'CZ':
			case 'DK':
			case 'EE':
			case 'FO':
			case 'FI':
			case 'FR':
			case 'DE':
			case 'GI':
			case 'GR':
			case 'HU':
			case 'IS':
			case 'IE':
			case 'IT':
			case 'LV':
			case 'LI':
			case 'LT':
			case 'LU':
			case 'MK':
			case 'MT':
			case 'MD':
			case 'MC':
			case 'NL':
			case 'NO':
			case 'PL':
			case 'PT':
			case 'RO':
			case 'RU':
			case 'SM':
			case 'RS':
			case 'SK':
			case 'SI':
			case 'ES':
			case 'SE':
			case 'CH':
			case 'UA':
			case 'GB':
			case 'VA':
			case 'RS':
			case 'IM':
			case 'RS':
			case 'ME':
				return 'eu';
			case 'AI':
			case 'AG':
			case 'AW':
			case 'BS':
			case 'BB':
			case 'BZ':
			case 'BM':
			case 'VG':
			case 'CA':
			case 'KY':
			case 'CR':
			case 'CU':
			case 'CW':
			case 'DM':
			case 'DO':
			case 'SV':
			case 'GL':
			case 'GD':
			case 'GP':
			case 'GT':
			case 'HT':
			case 'HN':
			case 'JM':
			case 'MQ':
			case 'MX':
			case 'PM':
			case 'MS':
			case 'CW':
			case 'KN':
			case 'NI':
			case 'PA':
			case 'PR':
			case 'KN':
			case 'LC':
			case 'PM':
			case 'VC':
			case 'TT':
			case 'TC':
			case 'VI':
			case 'US':
			case 'SX':
			case 'BQ':
			case 'SA':
			case 'SE':
				return 'na';
			case 'AR':
			case 'BO':
			case 'BR':
			case 'CL':
			case 'CO':
			case 'EC':
			case 'FK':
			case 'GF':
			case 'GY':
			case 'PY':
			case 'PE':
			case 'SR':
			case 'UY':
			case 'VE':
				return 'sa';
			default:
				return '';
		}
	}
	
	/**
	 * Get a country by ip address
	 * Adapted from a function in system/expressionengine/modules/ip_to_nation/models/ip_to_nation_data.php
	 */
	private function _find($ip, $default)
	{
		$BIN = $this->_to_binary($ip);

		// If IP contains 39 or 92, we end up with ASCII quote or backslash
		// Let's be sure to escape!
		$BIN = $this->EE->db->escape_str($BIN);

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
	 * Adapted from a function in system/expressionengine/modules/ip_to_nation/models/ip_to_nation_data.php
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

Uses the IP2Nation module to detect and output a visitor's country (the ISO country code) from their IP. Can optionally display content or redirect to a URL if a visitor is from a specified list of countries. Based on OpenMotive's Country plugin (see credits).


==================================================
*PLEASE NOTE*
This plugin requires the "IP to Nation" module
==================================================


This plugin has four main features:

==================================================
1) OUTPUT COUNTRY CODE
==================================================

To output just the two-letter country code, place the following tag in any of your templates:

{exp:mc_country}

REQUIRED PARAMETERS:

None.

OPTIONAL PARAMETERS:

default = If a visitor's IP cannot be located, the country code will default to this value.

debug = Force a specific two-letter country code. Useful when working locally (IPs won't resolve to the correct country on your local network).

==================================================
2) COUNTRY BASED REDIRECT:
==================================================

To redirect if user is from a specified country, specify a list of countries and a redirect path.

{exp:mc_country countries="xx|xx|xx|xx" redirect="/path/to/redirect/to"}

REQUIRED PARAMETERS:

countries = List each two-letter country code separated by |

redirect = The url to redirect to if user is from one of the specified countries.

OPTIONAL PARAMETERS:

default = If the IP cannot be located, country code will default to this value. Useful when working locally on your own machine.

debug = Force a specific two-letter country code. Useful when working locally (IPs won't resolve to the correct country on your local network).

==================================================
3) COUNTRY SPECIFIC CONTENT:
==================================================

If you need to display content for visitors from specific countries, simply wrap the content in tags and specify the countries as a parameter.

    {exp:mc_country countries="xx|xx|xx"}
         Content here
    {/exp:mc_country}

REQUIRED PARAMETERS:

countries = List each two-letter country code separated by |

OPTIONAL PARAMETERS:

debug = Force a specific two-letter country code. Useful when working locally (IPs won't resolve to the correct country on your local network).

==================================================
4) RESTRICTED SUBSET COUNTRY DETECTION:
==================================================

This feature allows you to restrict which countries visitors are detected from to a specified subset. If a visitor is from one of the allowed countries, it outputs that country. If a visitor is from a country not specified in the subset of allowed countries, it outputs the specified default country. See the examples below for ideas on how to use this feature.

    {exp:mc_country:restrict allowed="xx|xx|xx" default="xx"}

REQUIRED PARAMETERS:

allowed = List each two-letter country code separated by |

default = If IP cannot be located, country code will default to this value. Note that the default country should typically also be listed among the allowed countries.

OPTIONAL PARAMETERS:

debug = Force a specific two-letter country code. Useful when working locally (IPs won't resolve to correct country on your local network).

==================================================
5) OUTPUT CONTINENT CODE
==================================================

To output just the two-letter countinent code, place the following tag in any of your templates:

{exp:mc_country:continent}

REQUIRED PARAMETERS:

None.

OPTIONAL PARAMETERS:

default = If a visitor's IP cannot be located, the continent code will default to this value.

debug = Force a specific two-letter country code. Useful when working locally (IPs won't resolve to the correct country on your local network).

==================================================
6) CONTINENT BASED REDIRECT:
==================================================
Same as for countries (see above):

    {exp:mc_country:continent continents="xx|xx|xx"}
         Content here
    {/exp:mc_country:continent}

REQUIRED PARAMETERS:

continents = List each two-letter continent code separated by |

redirect = The url to redirect to if user is from one of the specified continents.

OPTIONAL PARAMETERS:

default = If the IP cannot be located, continent code will default to this value. Useful when working locally on your own machine.

debug = Force a specific two-letter country code. Useful when working locally (IPs won't resolve to the correct country on your local network).

==================================================
7) CONTINENT SPECIFIC CONTENT:
==================================================
Same as for countries (see above):

    {exp:mc_country:continent continents="xx|xx|xx"}
         Content here
    {/exp:mc_country:continent}

REQUIRED PARAMETERS:

countries = List each two-letter country code separated by |

OPTIONAL PARAMETERS:

debug = Force a specific two-letter country code. Useful when working locally (IPs won't resolve to the correct country on your local network).

==================================================
EXAMPLES
==================================================

Output two digit country code, defaulting to US:

{exp:mc_country default="us"}

--------------------------------------------------

Output two digit continent code, defaulting to EU:

{exp:mc_country:continent default="eu"}

--------------------------------------------------

Redirect the user if they are from the US, UK, or Canada:

{exp:mc_country countries="us|gb|ca" redirect="/english"}

--------------------------------------------------

You can also redirect to another site completely:

{exp:mc_country countries="ca" redirect="http://www.google.ca"}

--------------------------------------------------

Use debug mode to force United States country code:

{exp:mc_country countries="us|ca|gb" redirect="http://www.google.ca" debug="us"}

--------------------------------------------------

Show certain content to visitors from the US, Canada, and Mexico:

    {exp:mc_country countries="us|ca|mx"}
         <h2>Hello, North America!</h2>
    {/exp:mc_country}

--------------------------------------------------

Figure out which branch of an organization to use for a visitor:

    {exp:mc_country:restrict allowed="us|ca|uk|jp" default="us"}

OUTPUT:

If a visitor is detected as being in Canada, the output would be:

    ca

If a visitor is detected as being in Germany, which is not in the specified list of allowed countries, the output would default to:

    us

==================================================
COUNTRY CODES
==================================================

A list of country codes can be found at:
http://www.iso.org/iso/english_country_names_and_code_elements


==================================================
CREDITS
==================================================

This is an EE2 port of the OpenMotive Country EE1 plugin:
http://devot-ee.com/add-ons/country-plugin

Due to significant changes in the way data is stored in EE2's IP database table, I had to copy and adapt two internal functions from EllisLab's IP2Nation module. No copyright infringement is intended - I simply couldn't figure out how to make use of the database table otherwise.


<?php
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
}


/* End of file pi.mc_country.php */
/* Location: /system/expressionengine/third_party/mc_country/pi.mc_country.php */
