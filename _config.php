<?php
//use SilverStripe\Forms\HTMLEditor\HTMLEditorConfig;
use SilverStripe\Forms\HTMLEditor\HTMLEditorConfig;
use SilverStripe\Core\Manifest\ModuleLoader;

$ModuleLoader = ModuleLoader::inst()->getManifest()->getModule('pikselin/silverstripe-elemental-footnotes');

//pikselin/silverstripe-elemental-footnotes:client/js/ElementalFootnotes.js
HtmlEditorConfig::get('cms')->enablePlugins(['footnotelink' => $ModuleLoader->getResource('client/js/TinyMCE/footnote-link.js')]);
HtmlEditorConfig::get('cms')->insertButtonsBefore('sslink','footnotelink');
