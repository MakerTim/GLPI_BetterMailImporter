<?php

define("PLUGIN_BETTERMAILIMPORTER_VERSION", "1.0.1");

function plugin_init_bettermailimporter() {
	global $PLUGIN_HOOKS, $CFG_GLPI, $LINK_ID_TABLE, $LANG;

	$PLUGIN_HOOKS['csrf_compliant']['bettermailimporter'] = true;
	$PLUGIN_HOOKS['use_rules']['bettermailimporter'] = ['RuleMailCollector'];
}

function plugin_version_bettermailimporter() {
	global $LANG;

	return [ //
		'name' => __('Better Mail Importer', 'bettermailimporter'), //
		'version' => PLUGIN_BETTERMAILIMPORTER_VERSION, //
		'license' => '<a href="https://github.com/MakerTim/GLPI_Ocean/LICENSE">Apache-2.0</a>', //
		'author' => '<a href="mailto:makertim@outlook.com"> Tim Biesenbeek </b> </a>', //
		'homepage' => 'https://github.com/MakerTim/GLPI_bettermailimporter', //
		'minGlpiVersion' => '9.4' //
	];
}

function plugin_bettermailimporter_check_prerequisites() {
	if (version_compare(GLPI_VERSION, '9.4', '>=')) {
		return true;
	} else {
		return false;
	}
}

function plugin_bettermailimporter_check_config() {
	return true;
}
