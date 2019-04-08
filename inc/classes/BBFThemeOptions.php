<?php
class BBFThemeOptions
{
	private $theme_options_key = 'bbf_theme_options';
	private $theme_options_values;
	private $theme_default_values = array(
		'theme_version'=>'0.03',
		'verify_email' => 'yes',
		'nodejs_live_chat' => '0'
	);

	function __construct()
	{
		$this->BBFThemeOptions_constructor();
	}// constructor end here

	function __destruct()
	{
		update_option($this->theme_options_key, serialize($this->theme_options_values));
	}// destructor functoin end here

	private function BBFThemeOptions_constructor()
	{
		$this->theme_options_values = get_option($this->theme_options_key);

		if($this->theme_options_values && !is_array($this->theme_options_values)){
			$this->theme_options_values = unserialize($this->theme_options_values);
			if(count($this->theme_options_values) < 1)
				$this->theme_options_values = $this->theme_default_values;
		}elseif (!$this->theme_options_values) {
			$this->theme_options_values = $this->theme_default_values;
		}
		//$this->theme_options_values['nodejs_live_chat'] = '0';
	}// listingPostMeta_constructor function end here

	public function get_bbf_theme_option($option_value){
		if(isset($this->theme_options_values[$option_value])){
			return $this->theme_options_values[$option_value];
		}else{
			return NULL;
		}
	}

	public function get_option($option_value){
		if(isset($this->theme_options_values[$option_value])){
			return $this->theme_options_values[$option_value];
		}else{
			return NULL;
		}
	}

	public function set_bbf_theme_option($option_key, $option_value){
		$this->theme_options_values[$option_key] = $option_value;
	}

	public function set_option($option_key, $option_value){
		$this->theme_options_values[$option_key] = $option_value;
	}

}// class ListingPostMeta end here
