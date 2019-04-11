<?php

define('BMI_ADD_GROUP', 'groups_id');
define('BMI_ASSIGN_GROUP', 'group-assign');
define('BMI_FOLLOW_GROUP', 'group-follow');

function plugin_bettermailimporter_replaceRegexInFile($fileLocation, $regex, $replaceWith) {//ruleaction.class.php
	$mailRuleClassFile = file_get_contents($fileLocation);

	$mailRuleClassFile = preg_replace($regex, $replaceWith, $mailRuleClassFile);

	file_put_contents($fileLocation, $mailRuleClassFile);
}

function plugin_bettermailimporter_install() {
	plugin_bettermailimporter_replaceRegexInFile(GLPI_ROOT . '/inc/rulemailcollector.class.php', //
		'/maxActionsCount[^(]*\([^)]*\).*{[^}]*?}/m', //
		'maxActionsCount() {' . PHP_EOL . '      return 0;' . PHP_EOL . '   }');

	plugin_bettermailimporter_replaceRegexInFile(GLPI_ROOT . '/inc/ruleaction.class.php', //
		'/\'assign\'\s*=>\s*__\(\'Assign\'\)/m', //
		'\'assign\'              => __(\'Assign\'),\'' . BMI_ASSIGN_GROUP . '\'              => __(\'Assign\'),\'' . BMI_FOLLOW_GROUP . '\'              => __(\'From\')');
	return true;
}

function plugin_bettermailimporter_uninstall() {
	plugin_bettermailimporter_replaceRegexInFile(GLPI_ROOT . '/inc/rulemailcollector.class.php', //
		'/maxActionsCount[^(]*\([^)]*\).*{[^}]*?}/m', //
		'maxActionsCount() {' . PHP_EOL . '      return 1;' . PHP_EOL . '   }');

	plugin_bettermailimporter_replaceRegexInFile(GLPI_ROOT . '/inc/ruleaction.class.php', //
		'/\'' . BMI_ASSIGN_GROUP . '\'\s*=>\s*__\(\'Assign\'\),\s*\'' . BMI_FOLLOW_GROUP . '\'\s*=>\s*__\(\'From\'\),/m', //
		'');
	return true;
}

function plugin_bettermailimporter_getRuleActions($context) {
	return [ //
		BMI_ADD_GROUP => [ //
			'name' => 'Group', //
			'force_actions' => [BMI_ASSIGN_GROUP, BMI_FOLLOW_GROUP], //
			'permitseveral' => [BMI_ASSIGN_GROUP, BMI_FOLLOW_GROUP], //
			'type' => 'dropdown', //
			'table' => 'glpi_groups', //
		] //
	];
}

function plugin_bettermailimporter_executeActions($context) {
	global $DB;
	try {
		if (isset($context['action']) //
			&& isset($context['action']->fields) //
			&& isset($context['action']->fields['field']) //
			&& $context['action']->fields['field'] === BMI_ADD_GROUP) {

			$statement = $DB->prepare("INSERT IGNORE INTO glpi_groups_tickets (tickets_id, groups_id, type) VALUES (?, ?, ?)");

			$ticketId = $DB->query('SELECT MAX(id)+1 AS \'0\' FROM glpi_tickets');
			$ticketId = $ticketId->fetch_all();
			$ticketId = '' . $ticketId[0][0];

			$type = '2';
			switch ($context['action']->fields['action_type']) {
				case BMI_ASSIGN_GROUP:
					$type = '2';
					break;
				case BMI_FOLLOW_GROUP:
					$type = '1';
					break;
			}

			$value = $context['action']->fields['value'];
			$statement->bind_param('sss', $ticketId, $value, $type);
			if (!$statement->execute()) {
				Toolbox::logError($context);
			}
			$statement->close();
			return ['bmi-group-' . $type => $context['action']->fields['value']];
		}
	} catch (Throwable $ex) {
		Toolbox::logError($ex);
	}
	return null;
}
