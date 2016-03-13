<?php
$comments = array();
// in the inbox, we want to show all the comments and not just the latest comments
if ( isset( $_POST['comment'] ) && sanitize_text_field( $_POST['comment'] ) == 'inbox_comment' ) {
   global $wpdb;
   $post_id = intval( sanitize_text_field( $_POST['post_id'] ));
   $action_history_id = intval( sanitize_text_field( $_POST['actionid'] ));

   // get previous assignment and publish comments from post id
   $action_history_ids = array();
   $results = FCWorkflowBase::get_assignment_comment_from_post( $post_id );
   if ( $results ) {
      foreach ( $results as $result ) {
      	$action_history_ids[] = $result->ID;
         if ( ! empty( $result->comment ) ) {
            $comments[] = json_decode( $result->comment );
         }
      }
   }
   if ( ! empty( $action_history_ids ) ) {
   	$results = self::get_review_status_comment_by_id( $action_history_ids );
   	if ( $results ) {
   		foreach ( $results as $result ) {
   			if ( !empty( $result->comments ) ) {
   				$comments[] = json_decode( $result->comments );
   			}
   		}
   	}
   }   

   // sort the comments via timestamp
   usort( $comments, function( $a, $b) {
      $a = $a[0];
      $b = $b[0];
      if ( isset ( $a->comment_timestamp ) && isset ( $b->comment_timestamp ) ) {
      	return $a->comment_timestamp < $b->comment_timestamp ? 1 : -1; // need to switch 1 and -1
      } else {
      	return 1;
      }
   } );
}

if ( isset( $_POST["page_action"] ) && $_POST["page_action"] == "history" ) {
   $action = FCWorkflowInbox::get_action_history_by_from_id( $_POST["actionid"] );
   if ( $action ) {
      $comments[] = json_decode( $action->comment );
   }
}

if ( isset( $_POST["page_action"] ) && $_POST["page_action"] == "review" ) {
   $action = FCWorkflowInbox::get_review_action_by_id( $_POST["actionid"] );
   if ( $action ) {
      $comments[] = json_decode( $action->comments );
   }
}
?>
<div class="info-setting" id="stepcomment-setting" style="display:none;">
    <div class="dialog-title"><strong><?php echo __( "Comment(s)", "oasisworkflow" ); ?></strong></div>
    <div>
<?php
foreach ( $comments as $object ) {
   foreach ( $object as $comment ) {
   	$comment_timestamp = "";
   	if ( isset ( $comment->comment_timestamp )) {
   		$comment_timestamp = $comment->comment_timestamp;
   	}
      if ( $comment->send_id == "System" ) {
         $lbl = "System";
      } else {
         //$user = get_userdata( $comment->send_id ) ;
         //$lbl = $user->data->user_nicename ;
         $lbl = FCWorkflowInbox::get_user_name( $comment->send_id );
      }
      ?>
              <div class="comment-part">
                  <label><strong><?php echo __( "User", "oasisworkflow" ); ?><?php echo " :" ?></strong> <?php echo $lbl; ?></label>
                  <label id="signoffDate"><strong><?php echo __( "Sign off date", "oasisworkflow" ); ?><?php echo " :" ?></strong>
              <?php echo FCWorkflowBase::format_date_for_display( $comment_timestamp, "-", "datetime" ); ?>
                  </label>
                  <br class="clear">
              </div>
              <p><?php echo nl2br( $comment->comment ); ?></p>
              <div class="comment-split-line"></div>
   <?php }
} ?>
        <div class="changed-data-set">
            <a href="#" id="commentCancel"><?php echo __( "Close", "oasisworkflow" ); ?></a>
        </div>
        <br class="clear">
    </div>
</div>
<script type='text/javascript'>
   jQuery(document).ready(function () {
       jQuery(document).on("click", "#commentCancel, .modalCloseImg", function () {
           jQuery(document).find("#post_com_count_content").html("");
           jQuery(document).find(".post-com-count").show();
           jQuery.modal.close();
       });
   });
</script>