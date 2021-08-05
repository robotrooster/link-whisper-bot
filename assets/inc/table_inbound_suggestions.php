<table class="hidden wp-list-table widefat fixed striped posts tbl_keywords_x js-table wpil-inbound-links best_keywords inbound" id="tbl_keywords">
    <?php   $options = get_user_meta(get_current_user_id(), 'report_options', true); 
            $show_date = (!empty($options['show_date']) && $options['show_date'] == 'on') ? true : false;
            $taxonomies = get_taxonomies(array('public' => true, 'show_ui' => true), 'names', 'or');
            $taxonomies = (!empty($taxonomies)) ? array_keys($taxonomies): array();
            $show_traffic = (isset($options['show_traffic'])) ? ( ($options['show_traffic'] == 'off') ? false : true) : false;
            $gsc_active = Wpil_SearchConsole::is_authenticated();
    ?>
    <?php if (!empty($groups)) : ?>
        <thead>
            <tr class="wpil-suggestion-table-heading">
                <th class="inbound-check-all-col"><input type="checkbox" class="suggestion-select-all"><b style="margin: 0 0 0 5px;">Check All</b></th>
                <th><b>Suggested Phrases</b></th>
                <th><b>Posts To Create Links In</b></th>
                <?php
                if($gsc_active && $show_traffic){
                    echo '<th class="gsc-data-col"><b>' . __('Organic Traffic', 'wpil') . '</b></th>';
                }
                ?>
                <?php
                if($show_date){
                    echo '<th class="date-published-col"><b>' . __('Date Published', 'wpil') . '</b></th>';
                } ?>
            </tr>
        </thead>
        <tbody id="the-list">
            <h3>There are <?php echo count($groups) ?> suggestions for "<em><?php echo $post->title ?></em>"</h3>
        <?php foreach ($groups as $post_id => $group) : $phrase = $group[0]; ?>
            <tr class="wpil-inbound-sentence hidden" data-wpil-sentence-id="<?=esc_attr($post_id)?>" data-wpil-post-published-date="<?php echo strtotime(get_the_date('F j, Y', $post_id)); ?>">
                <td class="inbound-checkbox" data-colname="<?php _e('Check Link', 'wpil'); ?>">
                    <input type="checkbox" name="link_keywords[]" class="chk-keywords" wpil-link-new="" checked="checked">
                </td>
                <td class="sentences" data-colname="<?php _e('Phrase', 'wpil'); ?>">
                    <?php if (count($group) > 1) : ?>
                        <div class="wpil-collapsible-wrapper">
                            <div class="wpil-collapsible wpil-collapsible-static wpil-links-count">
                                <div class="sentence top-level-sentence" data-id="<?=esc_attr($post_id)?>" data-type="<?=esc_attr($phrase->suggestions[0]->post->type)?>">
                                    <div class="wpil_edit_sentence_form">
                                        <textarea class="wpil_content"><?=$phrase->suggestions[0]->sentence_src_with_anchor?></textarea>
                                        <span class="button-primary">Save</span>
                                        <span class="button-secondary">Cancel</span>
                                    </div>
                                    <span class="wpil_sentence_with_anchor" title="<?php _e('Double clicking a word will select it.', 'wpil'); ?>"><?=$phrase->suggestions[0]->sentence_with_anchor?></span>
                                    <span class="wpil_edit_sentence link-form-button">| <a href="javascript:void(0)">Edit Sentence</a></span>
                                    <?=!empty(Wpil_Suggestion::$undeletable)?' ('.esc_attr($phrase->suggestions[0]->anchor_score).')':''?>
                                    <input type="hidden" name="sentence" value="<?=base64_encode($phrase->sentence_src)?>">
                                    <input type="hidden" name="custom_sentence" value="">
                                </div>
                            </div>
                            <div class="wpil-content" style="display: none;">
                                <ul>
                                    <?php foreach ($group as $key_phrase => $phrase) : ?>
                                        <li>
                                            <div class="wpil-inbound-sentence-data-container" data-container-id="<?=$key_phrase?>">
                                                <input type="radio" <?=!$key_phrase?'checked':''?> data-id="<?=$key_phrase?>">
                                                <div class="data">
                                                    <div class="wpil_edit_sentence_form">
                                                        <textarea class="wpil_content"><?=$phrase->suggestions[0]->sentence_src_with_anchor?></textarea>
                                                        <span class="button-primary">Save</span>
                                                        <span class="button-secondary">Cancel</span>
                                                    </div>
                                                    <span class="wpil_sentence_with_anchor" title="<?php _e('Double clicking a word will select it.', 'wpil'); ?>"><?=$phrase->suggestions[0]->sentence_with_anchor?></span>
                                                    <?=!empty(Wpil_Suggestion::$undeletable)?' ('.esc_attr($phrase->suggestions[0]->anchor_score).')':''?>
                                                    <input type="hidden" name="sentence" value="<?=base64_encode($phrase->sentence_src)?>">
                                                    <input type="hidden" name="custom_sentence" value="">
                                                </div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>

                        <?php if (Wpil_Settings::fullHTMLSuggestions()) : ?>
                            <?php foreach ($group as $key_phrase => $phrase) : ?>
                                <div class="raw_html" <?=$key_phrase > 0 ? 'style="display:none"' : '' ?> data-id="<?=$key_phrase?>"><?=htmlspecialchars($phrase->suggestions[0]->sentence_src_with_anchor)?></div>
                            <?php endforeach; ?>
                            <div class="raw_html custom-text" style="display:none" data-id="custom-text"></div>
                        <?php endif; ?>
                    <?php else : ?>
                        <div class="sentence top-level-sentence" data-id="<?=esc_attr($post_id)?>" data-type="<?=esc_attr($phrase->suggestions[0]->post->type)?>">
                            <div class="wpil_edit_sentence_form">
                                <textarea class="wpil_content"><?=$phrase->suggestions[0]->sentence_src_with_anchor?></textarea>
                                <span class="button-primary">Save</span>
                                <span class="button-secondary">Cancel</span>
                            </div>
                            <span class="wpil_sentence_with_anchor" title="<?php _e('Double clicking a word will select it.', 'wpil'); ?>"><?=$phrase->suggestions[0]->sentence_with_anchor?></span>
                            <span class="wpil_edit_sentence link-form-button">| <a href="javascript:void(0)">Edit Sentence</a></span>
                            <?=!empty(Wpil_Suggestion::$undeletable)?' ('.esc_attr($phrase->suggestions[0]->anchor_score).')':''?>
                            <input type="hidden" name="sentence" value="<?=base64_encode($phrase->sentence_src)?>">
                            <input type="hidden" name="custom_sentence" value="">

                            <?php if (Wpil_Settings::fullHTMLSuggestions()) : ?>
                                <div class="raw_html"><?=htmlspecialchars($phrase->suggestions[0]->sentence_src_with_anchor)?></div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </td>
                <td data-colname="<?php _e('Post', 'wpil'); ?>">
                    <div style="opacity:<?=$phrase->suggestions[0]->opacity?>" class="suggestion" data-id="<?=esc_attr($phrase->suggestions[0]->post->id)?>" data-type="<?=esc_attr($phrase->suggestions[0]->post->type)?>">
                        <?php
                            $categories = get_terms(array(
                                'taxonomy' => $taxonomies,
                                'hide_empty' => false,
                                'object_ids' => $phrase->suggestions[0]->post->id,
                            ));

                            if(!is_wp_error($categories) && !empty($categories)){
                                $mapped = array_map(function($obj){ if(isset($obj->name)){return $obj->name;} }, $categories);
                                $categories = implode(', ', $mapped);
                                $found = count($mapped);
                            }else{
                                $categories = false;
                            }
                        ?>
                        <?php echo '<b>' . __('Title: ', 'wpil') . '</b>' . esc_attr($phrase->suggestions[0]->post->getTitle()) . '<br>'; ?>
                        <?php echo '<b>' . __('Type: ', 'wpil') . '</b>' . $phrase->suggestions[0]->post->getType() . '<br>'; ?>
                        <?php echo (!empty($categories)) ? '<b>' . _n(__('Category: ', 'wpil'), __('Categories: ', 'wpil'), $found) . '</b>' . $categories : ''; ?>
                        <?=!empty(Wpil_Suggestion::$undeletable)?' ('.esc_attr($phrase->suggestions[0]->post_score).')':''?>
                        <br>

                            <?php echo '<b style="vertical-align: top;">' . __('Item Link:', 'wpil') . '</b>'?>
                            <a class="post-slug inbound-slug" target="_blank" href="<?=$phrase->suggestions[0]->post->getLinks()->view?>">
                                <?php echo $phrase->suggestions[0]->post->getLinks()->view?>
                            </a>

                        <span class="wpil_add_link_to_ignore link-form-button"><a style="margin-left: 5px 0px;" href="javascript:void(0)">Ignore Link</a></span>
                    </div>
                </td>
                <?php if($gsc_active && $show_traffic){ ?>
                <td data-colname="<?php _e('Organic Traffic', 'wpil'); ?>">
                    <?php
                    $keywords = Wpil_TargetKeyword::get_post_keywords_by_type($phrase->suggestions[0]->post->id, $phrase->suggestions[0]->post->type, 'gsc-keyword', false);
                    $clicks = 0;
                    $position = 0;
                    $impressions = 0;
                    foreach($keywords as $keyword){
                        $clicks += $keyword->clicks;
                        $position += floatval($keyword->position);
                        $impressions += $keyword->impressions;

                    }

                    if($position > 0){
                        $position = round($position/count($keywords), 2);
                    }

                    echo '<ul>
                                <li>' . __('Clicks: ', 'wpil') . $clicks . '</li>
                                <li>' . __('AVG Position: ', 'wpil') . $position . '</li>
                                <li>' . __('Impressions: ', 'wpil') . $impressions . '</li>
                            </ul>';
                    ?>
                </td>
                <?php } ?>
                <?php if($show_date){ ?>
                <td data-colname="<?php _e('Date Published', 'wpil'); ?>">
                    <?=($phrase->suggestions[0]->post->type=='post'?get_the_date('', $phrase->suggestions[0]->post->id):'not set')?>
                </td>
                <?php } ?>
            </tr>
        <?php endforeach; ?>
            <tr class="wpil-no-posts-in-range" style="display:none">
                <td>No suggestions found</td>
            </tr>
        </tbody>
        <script>
            /** Sticky Header **/
            function createSticky(){
                // Makes the thead sticky to the top of the screen when scrolled down far enough
                if(jQuery('.wp-list-table').length){
                    var theadTop = jQuery('.wp-list-table').offset().top;
                    var adminBarHeight = parseInt(document.getElementById('wpadminbar').offsetHeight);
                    var scrollLine = (theadTop - adminBarHeight);
                    var sticky = false;

                    // duplicate the footer and insert in the table head
                    jQuery('.wp-list-table thead tr').clone().addClass('wpil-sticky-header').css({'display': 'none', 'top': adminBarHeight + 'px', 'margin': '0 33px 0 0'}).prepend(jQuery('#inbound-suggestions-dest-post-title').clone()).appendTo('.wp-list-table thead');

                    // resizes the header elements
                    function sizeHeaderElements(){
                        // adjust for any change in the admin bar
                        adminBarHeight = parseInt(document.getElementById('wpadminbar').offsetHeight);
                        jQuery('.wpil-sticky-header').css({'top': adminBarHeight + 'px'});

                        // adjust the size of the header columns
                        var elements = jQuery('.wpil-sticky-header').find('th');
                        jQuery('.wp-list-table thead tr').not('.wpil-sticky-header').find('th').each(function(index, element){
                            var width = getComputedStyle(element).width;

                            jQuery(elements[index]).css({'width': width});
                        });
                    }
                    sizeHeaderElements();

                    function resetScrollLinePositions(){
                        theadTop = jQuery('.wp-list-table').offset().top;
                        adminBarHeight = parseInt(document.getElementById('wpadminbar').offsetHeight);
                        scrollLine = (theadTop - adminBarHeight);
                    }

                    jQuery(window).on('scroll', function(e){
                        var scroll = parseInt(document.documentElement.scrollTop);

                        // if we've passed the scroll line and the head is not sticky
                        if(scroll > scrollLine && !sticky){
                            // sticky the header
                            jQuery('.wpil-sticky-header').css({'display': 'table-row'});
                            sticky = true;
                        }else if(scroll < scrollLine && sticky){
                            // if we're above the scroll line and the header is sticky, unsticky it
                            jQuery('.wpil-sticky-header').css({'display': 'none'});
                            sticky = false;
                        }
                    });

                    var wait;
                    jQuery(window).on('resize', function(){
                        clearTimeout(wait);
                        setTimeout(function(){ 
                            sizeHeaderElements(); 
                            resetScrollLinePositions();
                        }, 150);
                    });

                    setTimeout(function(){ 
                        resetScrollLinePositions();
                    }, 1500);
                }
            }
            createSticky();
            /** /Sticky Header **/
        </script>
    <?php else : ?>
        <tr>
            <td>No suggestions found</td>
        </tr>
    <?php endif; ?>
</table>
<script>
    var inbound_internal_link = '<?=$post->getLinks()->view?>';
</script>
