# A Latest Replies Block
A moodle block that returns the latest replies of the forum discussions at the current course.

## To set up 
1) Clone/fetch repository in "blocks/custom_block" of your Moodle installation
2) Install the block thought the site's administration panel. 
3) Deploy an instance of the block at a course's page. It will be found as "Latest Replies" block.

## How to use

The purpose of the block is to list the latest replies of the current course's announcements to the teacher/manager of the course. If no replies are found, then the parent discussions will be listed using the functionality of the standard news_items block. 

In the returned list each discussion will be printed only once, with the time and user name of the latest reply.

The block's title and max number of replies - threads is configurable through the block's edit options (not global ones).