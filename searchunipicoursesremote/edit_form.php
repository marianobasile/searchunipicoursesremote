<?php

class block_searchunipicoursesremote_edit_form extends block_edit_form{
	
	protected function specific_definition($mform){

		// Section header title according to language file.
		$mform->addElement('header', 'configheader', get_string('blocksettings', 'block_searchunipicoursesremote'));
		$mform->addElement('text','config_text',get_string('courses_to_show_label','block_searchunipicoursesremote'));
		$mform->hardFreeze('config_text');
		$mform->setDefault('config_text',10);
		$mform->setType('config_text',PARAM_INT);
	}
}