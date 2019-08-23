<?php
//PROJECT constants
define("WRITING_PROJECT_TRACKER", "writing_project_tracker");
define("WRITING_PROJECT_TRACKER_ACTIVE", "_active");
define("WRITING_PROJECT_TRACKER_EST_WORD_COUNT", "_estwordcount");
define("WRITING_PROJECT_TRACKER_EST_COMP_DATE", "_estcompleteddate");
define("WRITING_PROJECT_TRACKER_POST_TYPE", 'wtproject');

 //LOG DB CONSTANTS
 define("LOG_META_KEY_VALUE", "wt_log_item");
 define("LOG_META_START_DATE", 'wt_log_start_date');
 define("LOG_META_END_DATE", 'wt_log_end_date');
 define("LOG_META_WORD_COUNT", 'wt_log_word_count');
 define("LOG_META_NOTES", 'wt_log_notes');

 //QUERY CONSTANT STRINGS
 define("GET_LOG_BY_LOGID", "select meta_value, meta_key from wp_postmeta where post_id = %d and meta_key = 'wt_log_start_date' and meta_value = %s");
?>
