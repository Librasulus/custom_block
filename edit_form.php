<?php
 
class block_custom_block_edit_form extends block_edit_form {
 
    protected function specific_definition($mform) {
 
        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));
 
        // A sample string variable with a default value.
        
        $mform->addElement('text', 'config_title', get_string('block_title', 'block_custom_block'));
        $mform->setType('config_title', PARAM_TEXT);        
        $mform->setDefault('config_title', 'Latest Replies');
 
 
        $numberofthreads = array();
        for($i = 1; $i <= 10; $i++) {
            $numberofthreads[$i] = $i;
        }
        $mform->addElement('select', 'config_max_threads', get_string('max_threads', 'block_custom_block'), $numberofthreads);
        $mform->setDefault('config_max_threads', 5);
    }
}