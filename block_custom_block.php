<?php
class block_custom_block extends block_base {
  function init() {
    $this->title = get_string('default_title', 'block_custom_block');
  }
  
  function get_content() {
    global $CFG, $USER;
    
    if ($this->content !== NULL) {
      return $this->content;
    }
    $this->content = new stdClass;
    $this->content->text = '';
    $this->content->footer = '';
    
    if (empty($this->instance)) {
      return $this->content;
    }
    
    if ($this->page->course->newsitems) {   // Create a nice listing of recent postings
      
      require_once($CFG->dirroot.'/mod/forum/lib.php');   // We'll need this
      
      $text = '';
      
      if (!$forum = forum_get_course_forum($this->page->course->id, 'news')) {
        return '';
      }
      
      $modinfo = get_fast_modinfo($this->page->course);
      if (empty($modinfo->instances['forum'][$forum->id])) {
        return '';
      }
      $cm = $modinfo->instances['forum'][$forum->id];
      
      if (!$cm->uservisible) {
        return '';
      }
      
      $context = context_module::instance($cm->id);
      
      /// User must have perms to view discussions in that forum
      if (!has_capability('mod/forum:viewdiscussion', $context)) {
        return '';
      }
      
      /// Get all the recent discussions we're allowed to see
      
      // This block displays the most recent posts in a forum in
      // descending order. The call to default sort order here will use
      // that unless the discussion that post is in has a timestart set
      // in the future.
      // This sort will ignore pinned posts as we want the most recent.
      $sort = forum_get_default_sort_order(true, 'p.modified', 'd', false);
      
      // Getting replies of this course's forum
      $replies = forum_count_discussion_replies($forum->id);
      
      if (! $discussions = forum_get_discussions($cm, $sort, false,
      -1, $this->page->course->newsitems,
      false, -1, 0, FORUM_POSTS_ALL_USER_GROUPS) ) {
        $text .= '('.get_string('nonews', 'forum').')';
        $this->content->text = $text;
        return $this->content;
      }
      
      /// Actually create the listing now
      
      $strftimerecent = get_string('strftimerecent');
      $strmore = get_string('more', 'forum');
      
      /// Accessibility: markup as a list.
      $text .= "\n<ul class='unlist'>\n";
      
      if (!empty($replies)) {
        // Sorting replies of discussions by last post id per discussion
        usort($replies, function($a, $b) { return $a->lastpostid < $b->lastpostid; });
        // grabbing the first n replies
        $replies = array_slice($replies, 0, $this->config->max_threads);
        
        foreach ($replies as $reply){
          // Finding the discussion of the thread
          foreach($discussions as $struct) {
            if ($reply->discussion == $struct->discussion) {
              $discussion = $struct;
              break;
            }
            
          }
          // Pulling last post of the discussion so we can print correct time and user name
          $post=forum_get_post_full($reply->lastpostid);
          
          $discussion->subject = $discussion->name;
          $discussion->subject = format_string($discussion->subject, true, $forum->course);
          $text .= '<li class="post">'.
          '<div class=""><a href="'.$CFG->wwwroot.'/mod/forum/discuss.php?d='.$discussion->discussion.'#p'.$post->id.'">'.$discussion->subject.'</a>  '.
          '<small>'.fullname($post).'  '.userdate($post->modified, $strftimerecent).'</small></div>'.
          "</li>\n";        }
      }
      // if no replies are found, print parent discussions. It could be omitted.
      else {
        // grabbing the first n discussions
        $discussions = array_slice($discussions, 0, $this->config->max_threads);
        
        foreach ($discussions as $discussion) {
          $discussion->subject = $discussion->name;
          $discussion->subject = format_string($discussion->subject, true, $forum->course);
          $text .= '<li class="post">'.
          '<div class=""><a href="'.$CFG->wwwroot.'/mod/forum/discuss.php?d='.$discussion->discussion.'">'.$discussion->subject.'</a>  '.
          '<small>'.fullname($discussion).'  '.userdate($discussion->modified, $strftimerecent).'</small></div>'.
          "</li>\n";
        }
      }   
      $text .= "</ul>\n";
      $this->content->text = $text;
      
      // $this->content->footer = '<a href="'.$CFG->wwwroot.'/mod/forum/view.php?f='.$forum->id.'">'.
      //                           get_string('oldertopics', 'forum').'</a> ...';
    } //endif of news list
    
    return $this->content;
  }
  
  // Pre-assigning title and max number of threads
  public function specialization() {
    if (isset($this->config)) {
        if (empty($this->config->title)) {
            $this->title = get_string('default_title', 'block_custom_block');            
        } else {
            $this->title = $this->config->title;
        }
 
        if (empty($this->config->max_threads)) {
            $this->config->max_threads = 5;
        }    
    }
  }
  // Making the block available only in course view
  public function applicable_formats() {
  return array('course-view' => true);
  }
}
