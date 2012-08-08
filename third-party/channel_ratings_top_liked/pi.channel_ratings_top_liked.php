<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ExpressionEngine - by EllisLab
 *
 * @package		ExpressionEngine
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2003 - 2011, EllisLab, Inc.
 * @license		http://expressionengine.com/user_guide/license.html
 * @link		http://expressionengine.com
 * @since		Version 2.0
 * @filesource
 */
 
// ------------------------------------------------------------------------

/**
 * Channel Ratings Top Liked Plugin
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Plugin
 * @author		Nuno Albuquerque
 * @link		http://www.nainteractive.com
 */

$plugin_info = array(
	'pi_name'		=> 'Channel Ratings Top Liked',
	'pi_version'	=> '1.0',
	'pi_author'		=> 'Nuno Albuquerque',
	'pi_author_url'	=> 'http://www.nainteractive.com',
	'pi_description'=> 'Displays a list of top liked channel entries based on channel ratings liked count.',
	'pi_usage'		=> Channel_ratings_top_liked::usage()
);


class Channel_ratings_top_liked {

	public $return_data;
    
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->EE =& get_instance();
		
		$channel 			= $this->EE->TMPL->fetch_param('channel', 1);
		$entry_type			= 1; //entry type could be entry(1), reviews, comments, or member.
		$limit 				= $this->EE->TMPL->fetch_param('limit',10);
		
		$query = $this->EE->db->query("select *, COUNT(*) as total_count
									from exp_channel_ratings_likes as likes
									LEFT JOIN exp_channel_titles as entries
									ON likes.entry_id = entries.entry_id
									where likes.like_type = 1
									AND likes.site_id = 1
									AND likes.channel_id = 1
									AND (entries.status = 'open' OR entries.status = 'Under Review')
									GROUP by likes.entry_id
									LIMIT ".$limit);

		// If no results, exit now
		if($query->num_rows() == 0)
		{
			return $this->EE->TMPL->no_results();
		}
		
		$tagdata = $this->EE->TMPL->tagdata;

		$this->return_data = $this->EE->TMPL->parse_variables($tagdata, $query->result_array());

		return;

	}
	
	// ----------------------------------------------------------------
	
	/**
	 * Plugin Usage
	 */
	public static function usage()
	{
		ob_start();
?>

	<table class="table table-striped">
		<thead>
			<tr>
				<th style="width:50px;">ID</th>
				<th>Title</th>
				<th style="width:90px;">Recommendations</th>
			</tr>
		</thead>
		{exp:channel_ratings_top_liked limit="10"}
		<tr>
			 <td>{entry_id}</td>
			 <td><a href="/practices/{entry_id}">{title}</a></td>
			 <td><p><small>{total_count}</td>
		</tr>
		{/exp:channel_ratings_top_liked}
	</table>

<?php
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
}


/* End of file pi.channel_ratinings_top_liked.php */
/* Location: /system/expressionengine/third_party/channel_ratinings_top_liked/pi.channel_ratinings_top_liked.php */