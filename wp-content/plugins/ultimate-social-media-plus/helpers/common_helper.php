<?php
if (!function_exists('array_column')) {
    /**
     * Returns the values from a single column of the input array, identified by
     * the $columnKey.
     *
     * Optionally, you may provide an $indexKey to index the values in the returned
     * array by the values from the $indexKey column in the input array.
     *
     * @param array $input A multi-dimensional array (record set) from which to pull
     *                     a column of values.
     * @param mixed $columnKey The column of values to return. This value may be the
     *                         integer key of the column you wish to retrieve, or it
     *                         may be the string key name for an associative array.
     * @param mixed $indexKey (Optional.) The column to use as the index/keys for
     *                        the returned array. This value may be the integer key
     *                        of the column, or it may be the string key name.
     * @return array
     */
    function array_column($input = null, $columnKey = null, $indexKey = null)
    {
        // Using func_get_args() in order to check for proper number of
        // parameters and trigger errors exactly as the built-in array_column()
        // does in PHP 5.5.
        $argc = func_num_args();
        $params = func_get_args();

        if ($argc < 2) {
            trigger_error("array_column() expects at least 2 parameters, {$argc} given", E_USER_WARNING);
            return null;
        }

        if (!is_array($params[0])) {
            trigger_error(
                'array_column() expects parameter 1 to be array, ' . gettype($params[0]) . ' given',
                E_USER_WARNING
            );
            return null;
        }

        if (!is_int($params[1])
            && !is_float($params[1])
            && !is_string($params[1])
            && $params[1] !== null
            && !(is_object($params[1]) && method_exists($params[1], '__toString'))
        ) {
            trigger_error('array_column(): The column key should be either a string or an integer', E_USER_WARNING);
            return false;
        }

        if (isset($params[2])
            && !is_int($params[2])
            && !is_float($params[2])
            && !is_string($params[2])
            && !(is_object($params[2]) && method_exists($params[2], '__toString'))
        ) {
            trigger_error('array_column(): The index key should be either a string or an integer', E_USER_WARNING);
            return false;
        }

        $paramsInput = $params[0];
        $paramsColumnKey = ($params[1] !== null) ? (string) $params[1] : null;

        $paramsIndexKey = null;
        if (isset($params[2])) {
            if (is_float($params[2]) || is_int($params[2])) {
                $paramsIndexKey = (int) $params[2];
            } else {
                $paramsIndexKey = (string) $params[2];
            }
        }

        $resultArray = array();

        foreach ($paramsInput as $row) {
            $key = $value = null;
            $keySet = $valueSet = false;

            if ($paramsIndexKey !== null && array_key_exists($paramsIndexKey, $row)) {
                $keySet = true;
                $key = (string) $row[$paramsIndexKey];
            }

            if ($paramsColumnKey === null) {
                $valueSet = true;
                $value = $row;
            } elseif (is_array($row) && array_key_exists($paramsColumnKey, $row)) {
                $valueSet = true;
                $value = $row[$paramsColumnKey];
            }

            if ($valueSet) {
                if ($keySet) {
                    $resultArray[$key] = $value;
                } else {
                    $resultArray[] = $value;
                }
            }

        }

        return $resultArray;
    }
}
if(!function_exists('sfsi_plus_get_displayed_std_desktop_icons')){

    function sfsi_plus_get_displayed_std_desktop_icons($option1=false){

        $option1 =  false !== $option1 && is_array($option1) ? $option1 : maybe_unserialize(get_option('sfsi_plus_section1_options',false));

        $arrDisplay = array();

        if(false !== $option1 && is_array($option1) ){

            foreach ($option1 as $key => $value) {

                if(strpos($key, '_display') !== false){

                    $arrDisplay[$key] = isset($option1[$key]) ? sanitize_text_field($option1[$key]) : '';

                }       
            }
        }
        
        return $arrDisplay;

    }
}

if(!function_exists('sfsi_plus_get_displayed_custom_desktop_icons')){

    function sfsi_plus_get_displayed_custom_desktop_icons($option1=false){
        
        $option1 = false != $option1 && is_array($option1) ? $option1 : maybe_unserialize(get_option('sfsi_plus_section1_options',false));

        $arrDisplay = array();

        if(!empty($option1) && is_array($option1) && isset($option1['sfsi_custom_files']) 
            && !empty($option1['sfsi_custom_files']) ) :
            
            $arrdbDisplay = maybe_unserialize($option1['sfsi_custom_files']);
            
            if(is_array($arrdbDisplay)):

                $arrDisplay = $arrdbDisplay;

            endif;

        endif;

        return $arrDisplay;
    }

}

if(!function_exists('sfsi_plus_get_icon_image')){
    function sfsi_plus_get_icon_image($icon_name,$iconImgName=false){

        $icon = false;

        $option3 = maybe_unserialize(get_option('sfsi_plus_section3_options',false));

        if(isset($option3['sfsi_plus_actvite_theme']) && !empty($option3['sfsi_plus_actvite_theme'])){

            $active_theme = $option3['sfsi_plus_actvite_theme'];

            $icons_baseUrl  = SFSI_PLUS_PLUGURL."images/icons_theme/".$active_theme."/";
            $visit_iconsUrl = SFSI_PLUS_PLUGURL."images/visit_icons/";  

            if(isset($icon_name) && !empty($icon_name)):

                if($active_theme == 'custom_support')
                {
                    switch (strtolower($icon_name)) {

                        case 'facebook':
                            $custom_icon_name = "fb";
                            break;

                        case 'pinterest':
                            $custom_icon_name = "pintrest";
                            break;
                        
                        default:
                            $custom_icon_name = $icon_name;
                            break;
                    }

                    $key = 'plus_'.$custom_icon_name."_skin"; 
                    $skin = get_option($key,false);

                    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http";

                    if($skin)
                    {
                        $skin_url = parse_url($skin);
                        if($skin_url['scheme']==='http' && $scheme==='https'){
                            $icon = str_replace('http','https',$skin);
                        }else if($skin_url['scheme']==='https' && $scheme==='http'){
                            $icon = str_replace('https','http',$skin);
                        }else{
                            $icon = $skin;
                        }
                    }
                    else
                    {
                        $active_theme = 'default';
                        $icons_baseUrl = SFSI_PLUS_PLUGURL."images/icons_theme/default/";

                        $iconImgName = false != $iconImgName ? $iconImgName: $icon_name; 
                        $icon = $icons_baseUrl.$active_theme."_".$iconImgName.".png";
                    }
                }
                else
                {
                    switch (strtolower($icon_name)) {

                        case 'facebook':
                            $custom_icon_name = "fb";
                            break;
                        
                        default:
                            $custom_icon_name = $icon_name;
                            break;
                    }                    

                    $iconImgName = false != $iconImgName ? $iconImgName: $custom_icon_name;
                    $icon = $icons_baseUrl.$active_theme."_".$iconImgName.".png";
                }

            endif;      

        }

        return $icon;
    }
}

if(!function_exists('sfsi_plus_generate_other_icon_effect_admin_html')){

    function sfsi_plus_generate_other_icon_effect_admin_html($iconName,$arrActiveDesktopIcons,$customIconIndex=-1,$customIconImgUrl=null,$customIconSrNo=null){ 

        $iconImgVal         = false;
        $activeIconImgUrl   = false;
     
        $classForRevertLink = 'hide';
        $defaultIconImgUrl  = false;

        $displayIconClass   = "hide";

        $arruploadDir   = wp_upload_dir();

        $sfsi_plus_flat_icon_color = '';
        $sfsi_plus_flat_theme_flag = false;
        $option3 = maybe_unserialize( get_option( 'sfsi_plus_section3_options', false ) );
        $active_theme = ( isset( $option3['sfsi_plus_actvite_theme'] ) && !empty( $option3['sfsi_plus_actvite_theme'] ) ) ? $option3['sfsi_plus_actvite_theme'] : '' ;

        if( isset($iconName) && !empty($iconName)){ 

            if("custom" == $iconName && $customIconIndex >-1){

                if(null !== $customIconImgUrl){

                    $activeIconImgUrl  = $customIconImgUrl;                
                    $defaultIconImgUrl = $customIconImgUrl;

                    // Check if icon is selected under Question 1
                    if(in_array($customIconImgUrl, $arrActiveDesktopIcons)){
                        $displayIconClass = "show";
                    }

                    $iconNameStr = $iconName.$customIconSrNo;

                }

            }

            else{

                $dbKey = "sfsi_plus_".$iconName."_display";

                if(isset($arrActiveDesktopIcons[$dbKey]) && !empty($arrActiveDesktopIcons[$dbKey]) 
                    && "yes" == $arrActiveDesktopIcons[$dbKey]){
                    $displayIconClass = "show";
                }

                $activeIconImgUrl   = sfsi_plus_get_icon_image($iconName); 

                $iconNameStr = $iconName;

                /* Flat icon */
                
                if( $active_theme == 'flat' ) {
                    $sfsi_plus_flat_theme_flag = true;
                    $sfsi_plus_flat_icon_color = sfsi_plus_flat_icon_color( $iconName, $option3 );
                }

            }

            $attrCustomIconSrNo  = null !== $customIconSrNo ? 'data-customiconsrno="'.$customIconSrNo.'"': null;
            $attrCustomIconIndex = -1 != $customIconIndex ? 'data-customiconindex="'.$customIconIndex.'"': null;

            $attrIconName = 'data-iconname="'.$iconName.'"';

            ?>
            <div <?php echo $attrCustomIconIndex;?> <?php echo $attrIconName; ?> class="col-md-3 bottommargin20 <?php echo $displayIconClass.' '.$active_theme; ?>">

                <label <?php echo $attrCustomIconSrNo; ?> class="mouseover_other_icon_label"><?php echo ucfirst($iconNameStr); ?> </label>

                <?php if ( $sfsi_plus_flat_theme_flag ) { ?>
                    <span class="sfsiplus_icon_img_wrapper mouseover_sfsi_plus_<?php echo esc_attr( $iconName ); ?>_bgColor" <?php echo esc_html( $sfsi_plus_flat_icon_color ); ?>>
                        <img data-defaultImg="<?php echo $defaultIconImgUrl; ?>" class="mouseover_other_icon_img" src="<?php echo $activeIconImgUrl; ?>" alt="" />
                    </span>
                <?php } else { ?>
                    <img data-defaultImg="<?php echo $defaultIconImgUrl; ?>" class="mouseover_other_icon_img" src="<?php echo $activeIconImgUrl; ?>" alt="" />
                <?php } ?>

                <input <?php echo $attrCustomIconIndex; ?> <?php echo $attrIconName; ?> type="hidden" value="<?php echo $iconImgVal; ?>" name="mouseover_other_icon_<?php echo $iconName; ?>">

                <a <?php echo $attrCustomIconIndex; ?> <?php echo $attrIconName; ?> id="btn_mouseover_other_icon_<?php echo $iconName; ?>" class="mouseover_other_icon_change_link" href="javascript:void(0)" class="mouseover_other_icon"><?php _e('Change',SFSI_PLUS_DOMAIN); ?></a>

                <a <?php echo $attrCustomIconIndex; ?> <?php echo $attrIconName; ?> class="<?php echo $classForRevertLink; ?> mouseover_other_icon_revert_link" href="javascript:void(0)" class="mouseover_other_icon"><?php _e('Revert',SFSI_PLUS_DOMAIN); ?></a>

            </div>

            <?php 
        
        }

    } 
}
if ( ! function_exists('sfsi_plus_write_log')) {
   function sfsi_plus_write_log ( $log )  {
      if ( is_array( $log ) || is_object( $log ) ) {
         error_log( print_r( $log, true ) );
      } else {
         error_log( $log );
      }
   }
}


function sfsi_plus_flat_icon_color( $iconName, $option3 ) {

    $sfsi_plus_icon_bgColor = $sfsi_plus_icon_bgColor_style = '';
    if ( $iconName ) {

        switch ( $iconName ) {
            case "rss":
                if ( isset( $option3['sfsi_plus_rss_bgColor'] ) && $option3['sfsi_plus_rss_bgColor'] != '' ) {
                    $sfsi_plus_icon_bgColor = $option3['sfsi_plus_rss_bgColor'];
                } else {
                    $sfsi_plus_icon_bgColor = '#FF9845';
                }
            break;

            case "email":
                if ( isset( $option3['sfsi_plus_email_bgColor'] ) && $option3['sfsi_plus_email_bgColor'] != '' ) {
                    $sfsi_plus_icon_bgColor = $option3['sfsi_plus_email_bgColor'];
                } else {
                    $option2 = maybe_unserialize( get_option( 'sfsi_plus_section2_options', false ) );
                    if ($option2['sfsi_plus_rss_icons'] == "sfsi") {
                        $sfsi_plus_icon_bgColor = '#05B04E';
                    } elseif ($option2['sfsi_plus_rss_icons'] == "email") {
                        $sfsi_plus_icon_bgColor = '#343D44';
                    } else {
                        $sfsi_plus_icon_bgColor = '#16CB30';
                    }
                }
            break;

            case "facebook":
                if ( isset( $option3['sfsi_plus_facebook_bgColor'] ) && $option3['sfsi_plus_facebook_bgColor'] != '' ) {
                    $sfsi_plus_icon_bgColor = $option3['sfsi_plus_facebook_bgColor'];
                } else {
                    $sfsi_plus_icon_bgColor = '#336699';
                }
            break;

            case "twitter":
                if ( isset( $option3['sfsi_plus_twitter_bgColor'] ) && $option3['sfsi_plus_twitter_bgColor'] != '' ) {
                    $sfsi_plus_icon_bgColor = $option3['sfsi_plus_twitter_bgColor'];
                } else {
                    $sfsi_plus_icon_bgColor = '#00ACEC';
                }
            break;

            case "youtube":
                if ( isset( $option3['sfsi_plus_youtube_bgColor'] ) && $option3['sfsi_plus_youtube_bgColor'] != '' ) {
                    $sfsi_plus_icon_bgColor = $option3['sfsi_plus_youtube_bgColor'];
                } else {
                    $sfsi_plus_icon_bgColor = 'linear-gradient(141.52deg, #E02F2F 14.26%, #E02F2F 48.98%, #C92A2A 49.12%, #C92A2A 85.18%)';
                }
            break;

            case "linkedin":
                if ( isset( $option3['sfsi_plus_linkedin_bgColor'] ) && $option3['sfsi_plus_linkedin_bgColor'] != '' ) {
                    $sfsi_plus_icon_bgColor = $option3['sfsi_plus_linkedin_bgColor'];
                } else {
                    $sfsi_plus_icon_bgColor = '#0877B5';
                }
            break;

            case "pinterest":
                if ( isset( $option3['sfsi_plus_pinterest_bgColor'] ) && $option3['sfsi_plus_pinterest_bgColor'] != '' ) {
                    $sfsi_plus_icon_bgColor = $option3['sfsi_plus_pinterest_bgColor'];
                } else {
                    $sfsi_plus_icon_bgColor = '#CC3333';
                }
            break;

            case "instagram":
                if ( isset( $option3['sfsi_plus_instagram_bgColor'] ) && $option3['sfsi_plus_instagram_bgColor'] != '' ) {
                    $sfsi_plus_icon_bgColor = $option3['sfsi_plus_instagram_bgColor'];
                } else {
                    $sfsi_plus_icon_bgColor = '#336699';
                }
            break;

            case "houzz":
                if ( isset( $option3['sfsi_plus_houzz_bgColor'] ) && $option3['sfsi_plus_houzz_bgColor'] != '' ) {
                    $sfsi_plus_icon_bgColor = $option3['sfsi_plus_houzz_bgColor'];
                } else {
                    $sfsi_plus_icon_bgColor = '#7BC043';
                }
            break;

            case "ok":
                if ( isset( $option3['sfsi_plus_ok_bgColor'] ) && $option3['sfsi_plus_ok_bgColor'] != '' ) {
                    $sfsi_plus_icon_bgColor = $option3['sfsi_plus_ok_bgColor'];
                } else {
                    $sfsi_plus_icon_bgColor = '#F58220';
                }
            break;

            case "telegram":
                if ( isset( $option3['sfsi_plus_telegram_bgColor'] ) && $option3['sfsi_plus_telegram_bgColor'] != '' ) {
                    $sfsi_plus_icon_bgColor = $option3['sfsi_plus_telegram_bgColor'];
                } else {
                    $sfsi_plus_icon_bgColor = '#33A1D1';
                }
            break;

            case "vk":
                if ( isset( $option3['sfsi_plus_vk_bgColor'] ) && $option3['sfsi_plus_vk_bgColor'] != '' ) {
                    $sfsi_plus_icon_bgColor = $option3['sfsi_plus_vk_bgColor'];
                } else {
                    $sfsi_plus_icon_bgColor = '#4E77A2';
                }
            break;

            case "wechat":
                if ( isset( $option3['sfsi_plus_wechat_bgColor'] ) && $option3['sfsi_plus_wechat_bgColor'] != '' ) {
                    $sfsi_plus_icon_bgColor = $option3['sfsi_plus_wechat_bgColor'];
                } else {
                    $sfsi_plus_icon_bgColor = '#4BAD33';
                }
            break;

            case "whatsapp":
                if ( isset( $option3['sfsi_plus_whatsapp_bgColor'] ) && $option3['sfsi_plus_whatsapp_bgColor'] != '' ) {
                    $sfsi_plus_icon_bgColor = $option3['sfsi_plus_whatsapp_bgColor'];
                } else {
                    $sfsi_plus_icon_bgColor = '#3ED946';
                }
            break;

            case "weibo":
                if ( isset( $option3['sfsi_plus_weibo_bgColor'] ) && $option3['sfsi_plus_weibo_bgColor'] != '' ) {
                    $sfsi_plus_icon_bgColor = $option3['sfsi_plus_weibo_bgColor'];
                } else {
                    $sfsi_plus_icon_bgColor = '#E6162D';
                }
            break;
        }

        if ( $sfsi_plus_icon_bgColor ) {
            $sfsi_plus_icon_bgColor_style = "style=background:" . $sfsi_plus_icon_bgColor . ";";
        }
    }

    return $sfsi_plus_icon_bgColor_style;
}