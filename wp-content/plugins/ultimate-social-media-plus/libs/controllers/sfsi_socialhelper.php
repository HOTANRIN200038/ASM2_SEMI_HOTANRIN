<?php 
/* social helper class include all function which are used to intract with  */
class sfsi_plus_SocialHelper
{
	private $url,$timeout=10;

	/* get twitter followers */
	function sfsi_get_tweets($username,$tw_settings)
	{
		require_once(SFSI_PLUS_DOCROOT.'/helpers/twitteroauth/twiiterCount.php');
		return sfsi_plus_twitter_followers();		
	}

	/* get linkedIn counts */
	function sfsi_get_linkedin($url)
	{
		$json_string = $this->file_get_contents_curl(
			'https://www.linkedin.com/countserv/count/share?format=json&url='.$url
		);
		$json = json_decode($json_string, true);
		return isset($json['count'])? intval($json['count']):0;
	}

	/* get linkedIn follower */
	function sfsi_getlinkedin_follower($sfsi_plus_ln_company,$APIsettings)
	{      
	   require_once(SFSI_PLUS_DOCROOT.'/helpers/linkedin-api/linkedin-api.php');
	   
	   // $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http";
	   // $url = $scheme.'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

	   $url = sfsi_plus_get_current_url();

	   $linkedin = new Plus_LinkedIn(
			$APIsettings['sfsi_plus_ln_api_key'],
			$APIsettings['sfsi_plus_ln_secret_key'],
			$APIsettings['sfsi_plus_ln_oAuth_user_token'],
			$url
	   );
	   $followers = $linkedin->getCompanyFollowersByName($sfsi_plus_ln_company);
	   if (strpos($followers, '404') === false)
	   {   return  strip_tags($followers); }
	   else
	   {   return  0; }
	}

	/* get facebook likes */
	/*function sfsi_get_fb( $url ) {
		$count 		 = 0; 
		$json_string = $this->file_get_contents_curl('https://graph.facebook.com/?id='.$url, true);
		$json 		 = json_decode($json_string);

		if(isset($json) && isset($json->share) && isset($json->share->share_count)){
			$count  = $json->share->share_count;
		}
		return $count;
	}*/

	function sfsi_get_fb( $url ) {
		$count 		 = 0;
		$appid = '959456867427268';
		$appsecret = '7cc27f382c47fd5cc3a7203e40d70bf1';
		$json_string = $this->file_get_contents_curl( 'https://graph.facebook.com/v12.0/?id='.$url."&fields=engagement&access_token=".$appid.'|'.$appsecret, true );
		$json = json_decode( $json_string );
		if( isset( $json ) && isset( $json->engagement ) ) {
			$count  = $json->engagement->share_count + $json->engagement->reaction_count + $json->engagement->comment_count +  $json->engagement->comment_plugin_count;
		}
		return $count;
	}

	/* get facebook page likes */
	function sfsi_get_fb_pagelike( $url ) {
		$appid 		 = '954871214567352';
   		$appsecret   = 'a780eb3d3687a084d6e5919585cc6a12';
		$json_url 	 = 'https://graph.facebook.com/'.$url.'?fields=fan_count&access_token='.$appid.'|'.$appsecret;
		$json_string = $this->file_get_contents_curl( $json_url, true );
		// return $json_string;
		// var_dump($json_string);die();
		$json 		 = json_decode( $json_string, true );
		return isset( $json['fan_count'] )? $json['fan_count'] : 0;
	}
	
	function sfsi_banner_get_fb( $url ) {
		$count = 0;
		$appid = '290081412196492';
		$appsecret = 'a86ca864f716e974cb3d72294c20b275';
		$json_string = $this->file_get_contents_curl( 'https://graph.facebook.com/?id='.$url."&fields=engagement&access_token=".$appid.'|'.$appsecret, true );
		$json 		 = json_decode( $json_string );
		if( isset( $json ) && isset( $json->engagement ) ) {
			$count = $json->engagement->share_count + $json->engagement->reaction_count + $json->engagement->comment_count +  $json->engagement->comment_plugin_count;
		}
		return $count;
	}

	/* get youtube subscribers  */
	function sfsi_get_youtube( $user ) {
		if( $user == 'follow.it' ) {
			$sfsi_plus_section4_options =  maybe_unserialize(get_option('sfsi_plus_section4_options',false));
			$user = (
				isset($sfsi_plus_section4_options['sfsi_plus_youtube_channelId']) &&
				!empty($sfsi_plus_section4_options['sfsi_plus_youtube_channelId'])
			) ? $sfsi_plus_section4_options['sfsi_plus_youtube_channelId'] : 'UCYQyWnJPrY4XY3Avc7BU9aA';
			
			$xmlData = $this->file_get_contents_curl('https://www.googleapis.com/youtube/v3/channels?part=statistics&id='.$user.'&key=AIzaSyB_XMi9MwNweEYyt7c122CidZxqGZqex6Y');
		} else {
			$xmlData = $this->file_get_contents_curl('https://www.googleapis.com/youtube/v3/channels?part=statistics&forUsername='.$user.'&key=AIzaSyB_XMi9MwNweEYyt7c122CidZxqGZqex6Y');
		}
		
		if( $xmlData ) {   
			$xmlData = json_decode( $xmlData );
			if(
				isset($xmlData->items) &&
				!empty($xmlData->items)
			) {
				$subs = $xmlData->items[0]->statistics->subscriberCount;
				$subs = $this->format_num($subs);
			} else {
				$subs=0;
			}

		} else {
			$subs=0;
		}    
		return $subs;
	}


	/* get pinit counts  */       
	function sfsi_get_pinterest($url)
	{
		$return_data = $this->file_get_contents_curl('https://api.pinterest.com/v1/urls/count.json?callback=receiveCount&url='.$url);
		$json_string = preg_replace('/^receiveCount\((.*)\)$/', "\\1", $return_data);
		$json = json_decode($json_string, true);
		return isset($json['count'])?intval($json['count']):0;
	}

	/* get pinit counts for a user  */
	function get_UsersPins($user_name,$board)
	{   
		$query=$user_name.'/'.$board;
		$url_respon=$this->sfsi_get_http_response_code('https://api.pinterest.com/v3/pidgets/boards/'.$query.'/pins/');
		if($url_respon!=404)
		{    
			$return_data = $this->file_get_contents_curl('https://api.pinterest.com/v3/pidgets/boards/'.$query.'/pins/');
			$json_string = preg_replace('/^receiveCount\((.*)\)$/', "\\1", $return_data);
			$json = json_decode($json_string, true);
		}
		else
		{
			$json['data']['user']['pin_count']=0;
		}    
		return isset($json['data']['user']['pin_count'])? intval($json['data']['user']['pin_count']):0;
	}

	/* send curl request */


	private function file_get_contents_curl( $url, $curl = false ) {

		if ( ! ini_get( 'safe_mode' ) ){
	    	set_time_limit( $this->timeout + 10 );
		}

		if( function_exists( 'curl_init' ) && $curl ) {

			$options = array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_HEADER         => false,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_MAXREDIRS      => 10,
				CURLOPT_ENCODING       => "",
				CURLOPT_USERAGENT      => $_SERVER['HTTP_USER_AGENT'],
				CURLOPT_AUTOREFERER    => true,
				CURLOPT_CONNECTTIMEOUT => $this->timeout,
				CURLOPT_TIMEOUT        => $this->timeout,
			); 

			$ch = curl_init($url);
			curl_setopt_array($ch, $options);

			$content  = curl_exec($ch);
			if(curl_errno($ch)){
				return false;
			} else {
				return $content;
			}
			curl_close($ch);

		} else {

			$args = array(
				'blocking' 	=> true,
				'user-agent'=> $_SERVER['HTTP_USER_AGENT'],
				'timeout' 	=> $this->timeout,
				'header'	=> array("Content-Type"=>"application/x-www-form-urlencoded"),
				'sslverify' => false
			);
			$resp = wp_remote_get( $url, $args );
			if ( is_wp_error( $resp ) ) {
				return null;
			} else {
				return $resp['body'];
			}
		}
	}

	/* convert no. to 2K,3M format   */
	function format_num($num, $precision = 0)
	{
		if ($num >= 1000 && $num < 1000000)
		{
			$n_format = number_format($num/1000,$precision).'k';
		} else if ($num >= 1000000 && $num < 1000000000) {
			$n_format = number_format($num/1000000,$precision).'m';
		} else if ($num >= 1000000000) {
			$n_format=number_format($num/1000000000,$precision).'b';
		} else {
			$n_format = $num;
		}
		return $n_format;
	}
  	
  	/* convert no. to 2K,3M format */
	function format_num_back( $n ) {
		if ( intval( $n ) != $n ) {
			$check = preg_match_all( "/(\d+\.?\d+)\s*(\w)/", $n, $matches );
			if ( $check ) {
				$n = $matches[1][0];
				$suffix = strtolower( $matches[2][0] );
				switch ( $suffix ) {
					case "k":
						$n = $n * 1000;
					break;
					case "m":
						$n = $n * 1000000;
					break;
					case "b":
						$n = $n * 1000000000;
					break;
					case "t":
						$n = $n * 1000000000000;
					break;
				}
			} else {
				$n = intval( $n );
			}
		}
		return $n;
	}

	/* create on page facebook links option */
	public function sfsi_plus_FBlike( $permalink ) {
		
		$fb_like_html = '<div class="fb-like" data-href="'.$permalink.'" data-width="180" data-show-faces="false" data-layout="button" data-action="like"></div>';
		return $fb_like_html;
		exit;
	}

	// /*subscribe like*/
	// function sfsi_plus_Subscribelike($permalink, $show_count)
	// {
	
	// }
	// /*subscribe like*/

	/*twitter like*/
	function sfsi_plus_twitterlike($permalink, $show_count)
	{
		$twitter_text = '';
		return sfsi_twitterShare($permalink,$twitter_text);
	}
	/*twitter like*/

	/* create on page facebook share option */
	public function sfsiFB_Share($permalink)
	{
		// $fb_share_html = '<div class="fb-share-button" data-href="'.$permalink.'" data-layout="button" >';
		// // $fb_share_html .= '<a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u='.$permalink.'"';
		// // $fb_share_html .= '</a>';
		// $fb_share_html .="</div>";
		// return $fb_share_html;

		$fb_share_html = '';
		// $fb_share_html .= '<div class="fb-share-button" data-href="'.$permalink.'" data-layout="button"></div>';
		// return $fb_share_html;
		$sfsi_plus_section5_options = maybe_unserialize(get_option('sfsi_plus_section5_options',false));

		$facebook_icons_lang = $sfsi_plus_section5_options["sfsi_plus_icons_language"];
		// $facebook_icons_lang = $sfsi_plus_section5_options['sfsi_plus_facebook_icons_language'];
		// return $facebook_icons_lang;die();wp_die();
		//    $visit_icon = SFSI_PLUS_DOCROOT.'/images/visit_icons/Visit_us_fb/icon_'.$facebook_icons_lang.'.png';

		$shareurl = "https://www.facebook.com/sharer/sharer.php?u=";
		$shareurl = $shareurl . urlencode(urldecode($permalink));

		$fb_share_html = "<a target='_blank' href='" . $shareurl . "' style='display:inline-block;'> <img class='sfsi_wicon'  data-pin-nopin='true' width='auto' height='auto' alt='fb-share-icon' title='Facebook Share' src='" . SFSI_PLUS_PLUGURL . "images/share_icons/fb_icons/".$facebook_icons_lang.".svg'" . "'  /></a>";
		return $fb_share_html;
	}

	
 
 	/* create on page twitter follow option */ 
 	public function sfsi_twitterFollow($tw_username,$icons_language)
	{
		$twitter_html = "<a ".sfsi_plus_checkNewWindow("https://twitter.com/intent/user?screen_name=".trim($tw_username))." href='https://twitter.com/intent/user?screen_name=".trim($tw_username)."'><img nopin='nopin' width='auto' src='". SFSI_PLUS_PLUGURL . "images/share_icons/Twitter_Follow/".$icons_language."_Follow.svg' class='sfsi_premium_wicon' alt='Follow Me' title='Follow Me' style='opacity: 1;''></a>";
    	// $twitter_html = '<a href="https://twitter.com/'.trim($tw_username).'" class="twitter-follow-button"  data-show-count="false" data-lang="'.$icons_language.'" data-show-screen-name="false">Follow </a>';
    	// $twitter_html = '<a href="https://twitter.com/'.trim($tw_username).'" class="twitter-follow-button"  data-show-count="false" data-lang="'.$icons_language.'" data-show-screen-name="false">Follow </a>';
		return $twitter_html;
	} 
 
 	/* create on page twitter share icon */
 	public function sfsi_twitterShare($permalink,$tweettext,$icons_language)
	{

		$tweet_icon = SFSI_PLUS_PLUGURL . 'images/share_icons/Twitter_Tweet/'.$icons_language.'_Tweet.svg';
		// $tweet_icon = SFSI_PLUS_PLUGURL . 'images/visit_icons/'".$icons_language."'.svg';


		$twitter_html = "<div class='sf_twiter' style='display: inline-block;vertical-align: middle;width: auto;'>
						<a target='_blank' href='https://twitter.com/intent/tweet?text=" . urlencode($tweettext).' '.$permalink. "'style='display:inline-block' >
							<img nopin=nopin width='auto' class='sfsi_plus_wicon' src='" . $tweet_icon . "' alt='Tweet' title='Tweet' >
						</a>
					</div>";
		return $twitter_html;
		// if(empty($tweettext)){
		// 	$tweettext = "&nbsp";
		// }
		// $twitter_html = '<a rel="nofollow" href="https://twitter.com/intent/tweet" data-count="none" class="sr-twitter-button twitter-share-button" data-lang="'.$icons_language.'" data-url="'.$permalink.'" data-text="'.stripslashes($tweettext).'" ></a>';
        //  return $twitter_html;
	}
	
	/* create on page twitter share icon with count */
 	public function sfsi_twitterSharewithcount($permalink,$tweettext, $show_count, $icons_language)
	{
		if($show_count)
		{
			$twitter_html = '<a href="https://twitter.com/intent/tweet" class="sr-twitter-button twitter-share-button" lang="'.$icons_language.'" data-counturl="'.$permalink.'" data-url="'.$permalink.'" data-text="'.stripslashes($tweettext).'" ></a>';
		}
		else
		{
			$twitter_html = '<a href="https://twitter.com/intent/tweet" data-count="none" class="sr-twitter-button twitter-share-button" lang="'.$icons_language.'" data-url="'.$permalink.'" data-text="'.stripslashes($tweettext).'" ></a>';
		}
	   	return $twitter_html;
	}
	
	/* create on page youtube subscribe icon */       
 	public function sfsi_YouTubeSub($yuser)
	{
	 	$option2=  maybe_unserialize(get_option('sfsi_plus_section2_options',false));
		$option4=  maybe_unserialize(get_option('sfsi_plus_section4_options',false));
		if(isset($option2['sfsi_plus_youtubeusernameorid']))
		{
			$sfsi_plus_youtubeusernameorid = $option2['sfsi_plus_youtubeusernameorid'];
			$sfsi_plus_ytube_chnlid = $option2['sfsi_plus_ytube_chnlid'];
		}
		elseif(isset($option4['sfsi_plus_youtubeusernameorid']))
		{
			$sfsi_plus_youtubeusernameorid = $option4['sfsi_plus_youtubeusernameorid'];
			$sfsi_plus_ytube_chnlid = $option4['sfsi_plus_ytube_chnlid'];
		}
		else
		{
			$sfsi_plus_youtubeusernameorid = '';
			$sfsi_plus_ytube_chnlid = '';
		}
		if($sfsi_plus_youtubeusernameorid == 'name')
		{
			$yuser = $option2['sfsi_plus_ytube_user'];
			$youtube_html = '<div class="g-ytsubscribe" data-channel="'.$yuser.'" data-layout="default" data-count="hidden"></div>';
		}
		else
		{
			$yuser = $sfsi_plus_ytube_chnlid;
			$youtube_html = '<div class="g-ytsubscribe" data-channelid="'.$yuser.'" data-layout="default" data-count="hidden"></div>';
		}
		return $youtube_html;
	}  
	
	/* create on page pinit button icon */      
	public function sfsi_PinIt($url='')
	{
		$pinit_html = 'https://www.pinterest.com/pin/create/button/?url=&media=&description';

		$pinit_html = "<a href='#' onclick='sfsi_plus_pinterest_modal_images(event)' style='display:inline-block;'  > <img class='sfsi_wicon'  data-pin-nopin='true' width='auto' height='auto' alt='Pin Share' title='Pin Share' src='" . SFSI_PLUS_PLUGURL . "images/share_icons/en_US_save.svg" . "'  /></a>";
		return $pinit_html;
	}
 	
	/* get instragram followers */
	public function sfsi_get_instagramFollowers( $user_name ) {
		$sfsi_plus_instagram_sf_count = maybe_unserialize( get_option( 'sfsi_plus_instagram_sf_count', false ) );
		$sfsi_current_date = date( "Y-m-d" );

		$sfsi_plus_instagram_sf_count["date_instagram"] = strtotime( $sfsi_current_date );
		$counts = $this->sfsi_plus_get_instagramFollowersCount( $user_name );
		$sfsi_plus_instagram_sf_count["sfsi_instagram_count"] = $counts;
		update_option( 'sfsi_plus_instagram_sf_count', serialize( $sfsi_plus_instagram_sf_count ) );
		
		/*if date is empty (for decrease request count)*/
		/*if( empty( $sfsi_plus_instagram_sf_count["date_instagram"] ) ) {
			$sfsi_plus_instagram_sf_count["date_instagram"] = strtotime( $sfsi_current_date );
			$counts = $this->sfsi_plus_get_instagramFollowersCount( $user_name );
			$sfsi_plus_instagram_sf_count["sfsi_plus_instagram_count"] = $counts;
			update_option( 'sfsi_plus_instagram_sf_count',  serialize( $sfsi_plus_instagram_sf_count ) );
		} else {
			$phpVersion = phpVersion();
			if( $phpVersion >= '5.3' ) {
				$diff = date_diff(
				 	date_create(
						date("Y-m-d", $sfsi_plus_instagram_sf_count["date_instagram"])
					),
					date_create(
						$sfsi_current_date
					)
				);
			}
			
			if( ( isset( $diff ) && $diff->format( "%a" ) > 1 ) ) {
				$sfsi_plus_instagram_sf_count["date_instagram"] = strtotime( $sfsi_current_date );
				$counts = $this->sfsi_plus_get_instagramFollowersCount( $user_name );
				if( $counts > $sfsi_plus_instagram_sf_count["sfsi_plus_instagram_count"] ) {
					$sfsi_plus_instagram_sf_count["sfsi_plus_instagram_count"] = $counts;
				}
				update_option( 'sfsi_plus_instagram_sf_count', serialize( $sfsi_plus_instagram_sf_count ) );
			} else {
				$counts = $sfsi_plus_instagram_sf_count["sfsi_plus_instagram_count"];
			}
		}*/
		return $counts;
	}
	
	/* get instragram followers count*/
 	public function sfsi_plus_get_instagramFollowersCount( $user_name ) {

 		$count = 0;
		if ( $user_name ) {
			$return_data = $this->file_get_contents_curl( 'https://www.instagram.com/' . $user_name . '/channel/?__a=1' );

			$objData = json_decode( $return_data, true );
			if ( isset( $objData ) && isset( $objData['graphql'] ) && isset( $objData['graphql']['user'] ) && isset( $objData['graphql']['user']['edge_followed_by'] ) && isset( $objData['graphql']['user']['edge_followed_by']['count'] ) ) {
				$count = $objData['graphql']['user']['edge_followed_by']['count'];
				$count = $this->format_num_back( $count );
			}
		}

		/* get instagram user id */
		/*$option4 	= maybe_unserialize(get_option('sfsi_plus_section4_options',false));
		$token 		= $option4['sfsi_plus_instagram_token'];

		if(isset($token) && !empty($token)){

			$return_data = $this->get_content_curl('https://api.instagram.com/v1/users/self/?access_token='.$token);
			$objData 	 = json_decode($return_data);

			if(isset($objData) && $objData->data && $objData->data->counts && $objData->data->counts->followed_by){
				$count 	 = $objData->data->counts->followed_by;
			}			
		}*/
		return $this->format_num($count,0);
	}
 	
	/* create linkedIn  follow button */
 	public function sfsi_LinkedInFollow($company_id)
 	{
    	return  $ifollow='<script type="IN/FollowCompany" data-id="'.$company_id.'" data-counter="none"></script>';
 	}
  
  	/* create linkedIn  recommend button */
 	public function sfsi_LinkedInRecommend($company_name,$product_id)
 	{
    	return  $ifollow='<script type="IN/RecommendProduct" data-company="'.$company_name.'" data-product="'.$product_id.'"></script>';
 	}
 
 	/* create linkedIn  share button */
  	public function sfsi_LinkedInShare($url='')
 	{
    	$url=(isset($url))? $url :  home_url();
      	return  $ifollow='<script type="IN/Share" data-url="'.$url.'"></script>';
 	}
 	
	/* get no of subscribers from specificfeeds for current blog */
	public function  SFSI_getFeedSubscriber($feedid)
	{
		$sfsi_plus_instagram_sf_count = maybe_unserialize(get_option('sfsi_plus_instagram_sf_count',false));

		/*if date is empty (for decrease request count)*/
	

		if(empty($sfsi_plus_instagram_sf_count["sfsi_plus_sf_count"]))
		{
			// $sfsi_plus_instagram_sf_count["date_sf"] = strtotime(date("Y-m-d"));
			// $counts = $this->sfsi_plus_getFeedSubscriberCount($feedid);
			// $sfsi_plus_instagram_sf_count["sfsi_plus_sf_count"] = $counts;
			// update_option('sfsi_plus_instagram_sf_count',  serialize($sfsi_plus_instagram_sf_count));
			$counts=0;
		}
		else
		{
			// $phpVersion = phpVersion();
			// if($phpVersion >= '5.3')
			// {
			// 	$diff = date_diff(
			// 	 	date_create(
			// 			date("Y-m-d", $sfsi_plus_instagram_sf_count["date_sf"])
			// 		),
			// 		date_create(
			// 			date("Y-m-d")
			// 	));
			// }
			// var_dump($sfsi_plus_instagram_sf_count,isset($diff),$sfsi_plus_instagram_sf_count["date_sf"],date("Y-m-d", $sfsi_plus_instagram_sf_count["date_sf"]),date('Y-m-d'),$diff , $diff->format("%a"));die();
			// if((isset($diff) && $diff->format("%a") >= 1)||$sfsi_plus_instagram_sf_count["sfsi_plus_sf_count"]=="")
			// {
			// 	$sfsi_plus_instagram_sf_count["date_sf"] = strtotime(date("Y-m-d"));
			// 	$counts = $this->sfsi_plus_getFeedSubscriberCount($feedid);
			// 	$sfsi_plus_instagram_sf_count["sfsi_plus_sf_count"] = $counts;
			// 	update_option('sfsi_plus_instagram_sf_count',  serialize($sfsi_plus_instagram_sf_count));
			// }
			// else
			// {
				$counts = $sfsi_plus_instagram_sf_count["sfsi_plus_sf_count"];
			// }

		}
		
		if(empty($counts) || $counts == "O")
		{
			$counts = 0;
		}
		
		return $counts;
	}
	public function  SFSI_getFeedSubscriberFetch($feedid)
	{
		$sfsi_plus_instagram_sf_count = maybe_unserialize(get_option('sfsi_plus_instagram_sf_count',false));

		/*if date is empty (for decrease request count)*/
	

		// if(empty($sfsi_plus_instagram_sf_count["date_sf"]))
		// {
			$sfsi_plus_instagram_sf_count["date_sf"] = strtotime(date("Y-m-d"));
			$counts = $this->sfsi_plus_getFeedSubscriberCount($feedid);
			$sfsi_plus_instagram_sf_count["sfsi_plus_sf_count"] = $counts;
			
			update_option('sfsi_plus_instagram_sf_count',  serialize($sfsi_plus_instagram_sf_count));
		// }
		// else
		// {
			// $phpVersion = phpVersion();
			// if($phpVersion >= '5.3')
			// {
				// $diff = date_diff(
				 	// date_create(
						// date("Y-m-d", $sfsi_plus_instagram_sf_count["date_sf"])
					// ),
					// date_create(
						// date("Y-m-d")
				// ));
			// }
			// var_dump($sfsi_plus_instagram_sf_count,isset($diff),$sfsi_plus_instagram_sf_count["date_sf"],date("Y-m-d", $sfsi_plus_instagram_sf_count["date_sf"]),date('Y-m-d'),$diff , $diff->format("%a"));die();
			// if((isset($diff) && $diff->format("%a") >= 1)||$sfsi_plus_instagram_sf_count["sfsi_plus_sf_count"]=="")
			// {
			// 	$sfsi_plus_instagram_sf_count["date_sf"] = strtotime(date("Y-m-d"));
			// 	$counts = $this->sfsi_plus_getFeedSubscriberCount($feedid);
			// 	$sfsi_plus_instagram_sf_count["sfsi_plus_sf_count"] = $counts;
			// 	update_option('sfsi_plus_instagram_sf_count',  serialize($sfsi_plus_instagram_sf_count));
			// }
			// else
			// {
				// $counts = $sfsi_plus_instagram_sf_count["sfsi_plus_sf_count"];
			// }

		// }
		
		if(empty($counts) || $counts == "O")
		{
			$counts = 0;
		}
		
		return $counts;
	}
	
	/* get no of subscribers from specificfeeds for current blog count*/
	public function  sfsi_plus_getFeedSubscriberCount($feedid)
	{
		$postto_array = array(
			'feed_id' => $feedid,
			'v' => 'newplugincount'
		);
		$args = array(
		    'body' => $postto_array,
		    'blocking' => true,
		    'user-agent' => 'sf rss request',
		    'header'	=> array("Content-Type"=>"application/x-www-form-urlencoded"),
		    'sslverify' => true
		);
		$resp = wp_remote_post( 'https://api.follow.it/wordpress/wpCountSubscriber', $args );
		
		$httpcode = wp_remote_retrieve_response_code($resp);
		
		if($httpcode == 200){
			
			if(!empty($resp["body"]))
			{
				$resp     = json_decode($resp["body"]);

				$feeddata = stripslashes_deep($resp->subscriber_count);
			}
			else{

				$sfsi_premium_instagram_sf_count = maybe_unserialize(get_option('sfsi_plus_sf_count',false));
				$feeddata = $sfsi_premium_instagram_sf_count["sfsi_sf_count"];
			}
		}
		else{
			$sfsi_premium_instagram_sf_count = maybe_unserialize(get_option('sfsi_plus_sf_count',false));
			$feeddata = $sfsi_premium_instagram_sf_count["sfsi_plus_sf_count"];
		}

		return $this->format_num($feeddata);exit;
	}
    
	/* check response from a url */
    private function sfsi_get_http_response_code($url)
	{
		$headers = @get_headers($url);
		return substr(@$headers[0], 9, 3);
	} 
}
/* end of class */
