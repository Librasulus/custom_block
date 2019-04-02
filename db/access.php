<?php
    $capabilities = array(
 
    'block/custom_block:myaddinstance' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
          'guest' => CAP_PREVENT,
          'student' => CAP_PREVENT,
          'teacher' => CAP_ALLOW,
          'editingteacher' => CAP_ALLOW,
          'coursecreator' => CAP_ALLOW,
          'manager' => CAP_ALLOW
        ),
        'clonepermissionsfrom' => 'moodle/my:manageblocks'
    ),
 
    'block/custom_block:addinstance' => array(
        'riskbitmask' => RISK_SPAM | RISK_XSS,
        'captype' => 'write',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes' => array(
          'guest' => CAP_PREVENT,
          'student' => CAP_PREVENT,
          'editingteacher' => CAP_ALLOW,
          'coursecreator' => CAP_ALLOW,
          'manager' => CAP_ALLOW
        ),
        'clonepermissionsfrom' => 'moodle/site:manageblocks'
    ),
    
);