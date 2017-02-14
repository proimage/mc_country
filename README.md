MC Country
==========

An ExpressionEngine 2 and 3 plugin that uses the IP2Nation module to detect and output a visitor's country (the ISO country code) from their IP. Can optionally display content or redirect to a URL if a visitor is from a specified list of countries. Based on OpenMotive's Country plugin (see credits).


**PLEASE NOTE:**
This plugin requires the "IP to Nation" module

--------------------------------------------------

This plugin has four main features:

1. OUTPUT COUNTRY CODE
----------------------

To output just the two-letter country code, place the following tag in any of your templates:

	{exp:mc_country}

### REQUIRED PARAMETERS

None.

### OPTIONAL PARAMETERS

- default = If a visitor's IP cannot be located, the country code will default to this value.
- debug = Force a specific two-letter country code. Useful when working locally (IPs won't resolve to the correct country on your local network).

2. COUNTRY BASED REDIRECT
-------------------------

To redirect if user is from a specified country, specify a list of countries and a redirect path.

	{exp:mc_country countries="xx|xx|xx|xx" redirect="/path/to/redirect/to"}

### REQUIRED PARAMETERS

- countries = List each two-letter country code separated by |

- redirect = The url to redirect to if user is from one of the specified countries.

### OPTIONAL PARAMETERS

- default = If the IP cannot be located, country code will default to this value. Useful when working locally on your own machine.

- debug = Force a specific two-letter country code. Useful when working locally (IPs won't resolve to the correct country on your local network).


3. COUNTRY SPECIFIC CONTENT
---------------------------

If you need to display content for visitors from specific countries, simply wrap the content in tags and specify the countries as a parameter.

	{exp:mc_country countries="xx|xx|xx"}
	     Content here
	{/exp:mc_country}

### REQUIRED PARAMETERS

- countries = List each two-letter country code separated by |

### OPTIONAL PARAMETERS

- debug = Force a specific two-letter country code. Useful when working locally (IPs won't resolve to the correct country on your local network).


4. RESTRICTED SUBSET COUNTRY DETECTION
--------------------------------------

This feature allows you to restrict which countries visitors are detected from to a specified subset. If a visitor is from one of the allowed countries, it outputs that country. If a visitor is from a country not specified in the subset of allowed countries, it outputs the specified default country. See the examples below for ideas on how to use this feature.

{exp:mc_country:restrict allowed="xx|xx|xx" default="xx"}

### REQUIRED PARAMETERS

- allowed = List each two-letter country code separated by |

- default = If IP cannot be located, country code will default to this value. Note that the default country should typically also be listed among the allowed countries.

### OPTIONAL PARAMETERS

- debug = Force a specific two-letter country code. Useful when working locally (IPs won't resolve to correct country on your local network).

--------------------------------------------------

EXAMPLES
==================================================


- Output two digit country code, defaulting to US:

		{exp:mc_country default="us"}



- Redirect the user if they are from the US, UK, or Canada:

		{exp:mc_country countries="us|gb|ca" redirect="/english"}



- You can also redirect to another site completely:

		{exp:mc_country countries="ca" redirect="http://www.google.ca"}



- Use debug mode to force United States country code:

		{exp:mc_country countries="us|ca|gb" redirect="http://www.google.ca" debug="us"}



- Show certain content to visitors from the US, Canada, and Mexico:

		{exp:mc_country countries="us|ca|mx"}
			<h2>Hello, North America!</h2>
		{/exp:mc_country}



- Figure out which branch of an organization to use for a visitor:

		{exp:mc_country:restrict allowed="us|ca|uk|jp" default="us"}

	OUTPUT:

	If a visitor is detected as being in Canada, the output would be:

	    ca

	If a visitor is detected as being in Germany, which is not in the specified list of allowed countries, the output would default to:

		us

--------------------------------------------------

COUNTRY CODES
==================================================

A list of country codes can be found at:
http://www.iso.org/iso/english_country_names_and_code_elements

--------------------------------------------------

CREDITS
==================================================

This is an EE2 port of the [OpenMotive Country EE1 plugin](http://devot-ee.com/add-ons/country-plugin)

Due to significant changes in the way data is stored in EE2's IP database table, I had to copy and adapt two internal functions from EllisLab's IP2Nation module. No copyright infringement is intended - I simply couldn't figure out how to make use of the database table otherwise.
