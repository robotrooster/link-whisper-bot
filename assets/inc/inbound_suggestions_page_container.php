<?php if (!empty($phrases)){ ?>
    <!-- <h3>Found  <?php echo count($phrases) ?> phrases</h3> -->
    <?php } ?>
<form method="post" action="">
    <!-- <div id="wpil-inbound-suggestions-head-controls">
        <div style="margin-bottom: 15px;">
            <input style="margin-bottom: -5px;" type="checkbox" name="same_category" id="field_same_category" < ?=(isset($same_category) && !empty($same_category)) ? 'checked' : ''?>> <label for="field_same_category"><?php _e('Only Show Link Suggestions in the Same Category as This Post', 'wpil'); ?></label>
            <br>
            <input type="checkbox" name="same_tag" id="field_same_tag" < ?=!empty($same_tag) ? 'checked' : ''?>> <label for="field_same_tag">< ?php _e('Only Show Link Suggestions with the Same Tag as This Post', 'wpil'); ?></label>
            < ?php if (!empty($phrases)){ ?>
                <br />
                <div style="display: inline-block;">
                    <label for="wpil-inbound-daterange" style="font-weight: bold; font-size: 16px !important; margin: 18px 0 8px; display: block; display: inline-block;"><?php _e('Filter Displayed Posts by Published Date', 'wpil'); ?></label><br/>
                    <input id="wpil-inbound-daterange" type="text" name="daterange" class="wpil-date-range-filter" value="< ?php echo '01/01/2000 - ' . date('m/d/Y', strtotime('today')); ?>">
                </div>
                <script>
                    var sentences = jQuery('.wpil-inbound-sentence');
                    jQuery('#wpil-inbound-daterange').on('apply.daterangepicker, hide.daterangepicker', function(ev, picker) {
                        jQuery(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                        var start = picker.startDate.unix();
                        var end = picker.endDate.unix();

                        sentences.each(function(index, element){
                            var elementTime = jQuery(element).data('wpil-post-published-date');
                            if(!start || (start < elementTime && elementTime < end)){
                                jQuery(element).css({'display': 'table-row'});
                            }else{
                                jQuery(element).css({'display': 'none'}).find('input.chk-keywords').prop('checked', false);
                            }
                        });

                        // handle the results of hiding any posts
                        handleHiddenPosts();
                    });

                    jQuery('#wpil-inbound-daterange').on('cancel.daterangepicker', function(ev, picker) {
                        jQuery(this).val('');
                        sentences.each(function(index, element){
                            jQuery(element).css({'display': 'table-row'});
                        });
                    });

                    jQuery('#wpil-inbound-daterange').daterangepicker({
                        autoUpdateInput: false,
                        linkedCalendars: false,
                        locale: {
                            cancelLabel: 'Clear'
                        }
                    });

                    /**
                     * Handles the table display elements when the date range changes
                     **/
                    function handleHiddenPosts(){
                        if(jQuery('.inbound-checkbox:visible').length < 1){
                            // hide the table elements
                            jQuery('.wp-list-table thead, #inbound_suggestions_button, #inbound_suggestions_button_2').css({'display': 'none'});
                            // make sure the "Check All" box is unchecked
                            jQuery('.inbound-check-all-col input').prop('checked', false);
                            // show the "No matches" message
                            jQuery('.wpil-no-posts-in-range').css({'display': 'table-row'});
                        }else{
                            // show the table elements
                            jQuery('.wp-list-table thead').css({'display': 'table-header-group'});
                            jQuery('#inbound_suggestions_button, #inbound_suggestions_button_2').css({'display': 'inline-block'});
                            // hide the "No matches" message
                            jQuery('.wpil-no-posts-in-range').css({'display': 'none'});
                        }
                    }
                </script>

                <div style="display: flex; flex-direction: column; position: absolute; right: 12px; top: 40px;">
                    <label for="suggestion_filter_field" style="font-weight: bold; font-size: 16px !important; margin: 18px 0 8px; display: block; display: inline-block;">Filter Suggestions by Keyword</label>
                    <textarea id="suggestion_filter_field"></textarea>
                </div>

            < ?php } ?>
            < ?php if ($same_category && !empty($categories)) : ?>
                <br>
                <select name="wpil_selected_category">
                    <option value="0">All categories</option>
                    < ?php foreach ($categories as $category) : ?>
                        <option value="< ?=$category->term_id?>" < ?=$category->term_id==$selected_category?'selected':''?>>< ?=$category->name?></option>
                    < ?php endforeach; ?>
                </select>
            < ?php endif; ?>
            < ?php if ($same_tag && !empty($tags)) : ?>
                <br>
                <select name="wpil_selected_tag">
                    <option value="0">All tags</option>
                    < ?php foreach ($tags as $tag) : ?>
                        <option value="< ?=$tag->term_id?>" < ?=$tag->term_id==$selected_tag?'selected':''?>>< ?=$tag->name?></option>
                    < ?php endforeach; ?>
                </select>
            < ?php endif; ?>
            <br>
        </div> -->
        <!--?php if (!empty($phrases)){ ?>
            <button id="inbound_suggestions_button" class="sync_linking_keywords_list button-primary" data-id="< ?=esc_attr($post->id)?>" data-type="<?=esc_attr($post->type)?>" data-page="inbound">Add links</button>
        < ?php } ?>
        < ?php $same_category = !empty(get_user_meta(get_current_user_id(), 'wpil_same_category_selected', true)); ?-->
    <!-- </div> -->
    <!-- <h2>Pfrrrt..</h2> -->
    
        <?php if (!empty($phrases)){ ?>
            <div class="suggestion-box">
            <?php require LWB_CORE_INC . 'table_inbound_suggestions.php'?>
            <button id="inbound_suggestions_button_2" class="sync_linking_keywords_list button-primary" data-id="<?=esc_attr($post->id)?>" data-type="<?=esc_attr($post->type)?>" data-page="inbound">Add links</button>
            </div>
        <?php } ?>

</form>

<style>
    .suggestion-box{
        padding:25px;
        background:white;
        border:1px solid #ccc;
    }
</style>