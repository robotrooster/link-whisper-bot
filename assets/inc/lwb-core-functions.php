<?php 
/*
*
*	***** Link Whisper Bot *****
*
*	Core Functions
*	
*/
// If this file is called directly, abort. //
if ( ! defined( 'WPINC' ) ) {die;} // end if
/*
*
* Custom Front End Ajax Scripts / Loads In WP Footer
*
*/
global $lwbinfo;
function lwb_frontend_ajax_form_scripts(){
?>
<script type="text/javascript">
jQuery(document).ready(function($){
    "use strict";
    // add basic front-end ajax page scripts here
    $('#lwb_custom_plugin_form').submit(function(event){
        event.preventDefault();
        // Vars
        var myInputFieldValue = $('#myInputField').val();
        // Ajaxify the Form
        var data = {
            'action': 'lwb_custom_plugin_frontend_ajax',
            'myInputFieldValue':   myInputFieldValue,
        };
        
        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        var ajaxurl = "<?php echo admin_url('admin-ajax.php');?>";
        $.post(ajaxurl, data, function(response) {
                console.log(response);
                if(response.Status == true   console.log(response.message);
                    $('#lwb_custom_plugin_form_wrap').html(response);

                }
                else
                {
                    console.log(response.message);
                    $('#lwb_custom_plugin_form_wrap').html(response);
                }
        });
    });
}(jQuery));    
</script>
<?php }
add_action('wp_footer','lwb_frontend_ajax_form_scripts');


add_action( 'admin_menu', 'lwb_menu' );  

function lwb_menu(){  
    
    $lwbinfo = ["fart"];
    
    $page_title = 'Link Whisper Bot';   
    $menu_title = 'LW Bot';   
    $capability = 'manage_options';   
    $menu_slug  = 'lwb';   
    $function   = 'lwb_info_page';   
    $icon_url   = 'dashicons-admin-generic';   
    $position   = 4;    
    
    add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position ); 
} 


if( !function_exists("lwb_info_page") ) { 
    function lwb_info_page(){ 
        ?>   <h1>Info</h1> 

        <?php 
                $orphans = str_replace(" AND p.ID IN (", "", Wpil_Query::reportPostIds(true, $hide_noindex));
                $lwbinfo = explode(",",substr($orphans, 0, -1));

                // $keywords = Wpil_TargetKeyword::get_keywords_by_post_ids($lwbinfo, "post");
                // $keyword_sources = Wpil_TargetKeyword::get_active_keyword_sources();

                // $lwbinfo = ["pfrt","brrt"]; 
                // print_r($lwbinfo);
                // print_r($keywords);

                getInternalLinks($lwbinfo[1], time()); 
                // getInternalLinks($lwbinfo[2], time()); 
                // getInternalLinks($lwbinfo[3], time()); 

                // print_r($keyword_sources);
            } 
        
        } 


function getInternalLinks($post_id, $key){
            $phrases = [];
            $memory_break_point = Wpil_Report::get_mem_break_point();
            $ignore_posts = Wpil_Settings::getIgnorePosts();
            $batch_size = Wpil_Settings::getProcessingBatchSize();

            $post = new Wpil_Model_Post($post_id);
            
            // if the keywords list only contains newline semicolons
            if(isset($_POST['keywords']) && empty(trim(str_replace(';', '', $_POST['keywords'])))){
                // remove the "keywords" index
                unset($_POST['keywords']);
                unset($_REQUEST['keywords']);
            }

            $completed_processing_count = (isset($_POST['completed_processing_count']) && !empty($_POST['completed_processing_count'])) ? (int) $_POST['completed_processing_count'] : 0;

            $words = $post->getTitle() . ' ' . Wpil_TargetKeyword::get_active_keyword_string($post->id, $post->type);
            $keywords = array(implode(' ', Wpil_Word::cleanIgnoreWords(explode(' ', Wpil_Word::strtolower($words)))));

            $suggested_post_ids = get_transient('wpil_inbound_suggested_post_ids_' . $key);
            // get all the suggested posts for linking TO this post
            if(empty($suggested_post_ids)){
                $search_keywords = (is_array($keywords)) ? $keywords[0] : $keywords;
                $suggested_posts = Wpil_Suggestion::getInboundSuggestedPosts($search_keywords, Wpil_Post::getLinkedPostIDs($post));
                $suggested_post_ids = array();
                foreach($suggested_posts as $suggested_post){
                    $suggested_post_ids[] = $suggested_post->ID;
                }
                set_transient('wpil_inbound_suggested_post_ids_' . $key, $suggested_post_ids, MINUTE_IN_SECONDS * 10);
            }else{
                // if there are stored ids, re-save the transient to refresh the count down
                set_transient('wpil_inbound_suggested_post_ids_' . $key, $suggested_post_ids, MINUTE_IN_SECONDS * 10);
            }

            $last_post = (isset($_POST['last_post'])) ? (int) $_POST['last_post'] : 0;

            if(isset(array_flip($suggested_post_ids)[$last_post])){
                $post_ids_to_process = array_slice($suggested_post_ids, (array_search($last_post, $suggested_post_ids) + 1), $batch_size);
            }else{
                $post_ids_to_process = array_slice($suggested_post_ids, 0, $batch_size);
            }

            $process_count = 0;
            $current_post = $last_post;
            foreach ($keywords as $keyword) {
                $temp_phrases = [];
                foreach($post_ids_to_process as $post_id) {
                    if (Wpil_Base::overTimeLimit(15, 60) || ('disabled' !== $memory_break_point && memory_get_usage() > $memory_break_point) ){
                        break;
                    }

                    $links_post = new Wpil_Model_Post($post_id);
                    $current_post = $post_id;

                    // if the post isn't being ignored
                    if(!in_array( ($links_post->type . '_' . $post_id), $ignore_posts)){
                        // if the user has set a max link count for posts
                        if(!empty($max_links_per_post)){
                            // skip any posts that are at the limit
                            preg_match_all('`<a[^>]*?href=(\"|\')([^\"\']*?)(\"|\')[^>]*?>([\s\w\W]*?)<\/a>|<!-- wp:core-embed\/wordpress {"url":"([^"]*?)"[^}]*?"} -->|(?:>|&nbsp;|\s)((?:(?:http|ftp|https)\:\/\/)(?:[\w_-]+(?:(?:\.[\w_-]+)+))(?:[\w.,@?^=%&:/~+#-]*[\w@?^=%&/~+#-]))(?:<|&nbsp;|\s)`i', $links_post->getContent(), $matches);
                            if(isset($matches[0]) && count($matches[0]) >= $max_links_per_post){
                                $process_count++;
                                continue;
                            }
                        }

                        //get suggestions for post
                        if (!empty($_REQUEST['keywords'])) {
                            $suggestions = Wpil_Suggestion::getPostSuggestions($links_post, $post, false, $keyword, null, $key);
                        } else {
                            $suggestions = Wpil_Suggestion::getPostSuggestions($links_post, $post, false, null, null, $key);
                        }

                        //skip if no suggestions
                        if (!empty($suggestions)) {
                            $temp_phrases = array_merge($temp_phrases, $suggestions);
                        }
                    }

                    $process_count++;
                }

                if (count($temp_phrases)) {
                    Wpil_Phrase::TitleKeywordsCheck($temp_phrases, $keyword);
                    $phrases = array_merge($phrases, $temp_phrases);
                }
            }

            // get the suggestions transient
            $stored_phrases = get_transient('wpil_post_suggestions_' . $key);

            // if there are suggestions stored
            if(!empty($stored_phrases)){
                // decompress the suggestions so we can add more to the list
                $stored_phrases = Wpil_Suggestion::decompress($stored_phrases);
            }else{
                $stored_phrases = array();
            }

            // if there are phrases to save
            if($phrases){
                if(empty($stored_phrases)){
                    $stored_phrases = $phrases;
                }else{
                    // add the suggestions
                    $stored_phrases = array_merge($stored_phrases, $phrases);
                }
            }

            // compress the suggestions to save space
            $stored_phrases = Wpil_Suggestion::compress($stored_phrases);

            // save the suggestion data
            set_transient('wpil_post_suggestions_' . $key, $stored_phrases, MINUTE_IN_SECONDS * 15);

            $processing_status = array(
                    'status' => 'no_suggestions',
                    'keywords' => $keywords,
                    'last_post' => $current_post,
                    'post_count' => count($suggested_post_ids),
                    'id_count_to_process' => count($post_ids_to_process),
                    'completed' => empty(count($post_ids_to_process)), // has the processing run completed? If it has, then there won't be any posts to process
                    'completed_processing_count' => ($completed_processing_count += $process_count),
                    'batch_size' => $batch_size,
                    'posts_processed' => $process_count,
            );

            if(!empty($phrases)){
                $processing_status['status'] = 'has_suggestions';
            }

            //wp_send_json($processing_status);
            echo "<h2>Processing ".$post_id."</h2>";
            print_r($processing_status);

            $phrases = get_transient('wpil_post_suggestions_' . $key);
            // decompress the suggestions
            $phrases = Wpil_Suggestion::decompress($phrases);
            //add links to phrases
            Wpil_Phrase::InboundSort($phrases);
            $phrases = Wpil_Suggestion::addAnchors($phrases);
            $groups = Wpil_Suggestion::getInboundGroups($phrases);
            $selected_category = !empty($_POST['selected_category']) ? (int)$_POST['selected_category'] : 0;
            if ($same_category) {
                $categories = wp_get_post_categories($post_id, ['fields' => 'all_with_object_id']);
                if (empty($categories) || count($categories) < 2) {
                    $categories = [];
                }
            }

            $same_tag = !empty($_POST['same_tag']);
            $selected_tag = !empty($_POST['selected_tag']) ? (int)$_POST['selected_tag'] : 0;
            if ($same_tag) {
                $tags = wp_get_post_tags($post_id, ['fields' => 'all_with_object_id']);
                if (empty($tags) || count($tags) < 2) {
                    $tags = [];
                }
            }
            // include WP_INTERNAL_LINKING_PLUGIN_DIR . '/templates/inbound_suggestions_page_container.php';
            include LWB_CORE_INC . 'inbound_suggestions_page_container.php';
            Wpil_Suggestion::clearSuggestionProcessingCache($key, $post->id);

}



