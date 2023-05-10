<?php

//use SilverStripe\Forms\HTMLEditor\HTMLEditorConfig;

use SilverStripe\Forms\HTMLEditor\HTMLEditorConfig;
use SilverStripe\Core\Manifest\ModuleLoader;
use SilverStripe\Forms\HTMLEditor\TinyMCEConfig;

$ss_admin = ModuleLoader::inst()->getManifest()->getModule('silverstripe/admin');
$ss_asset = ModuleLoader::inst()->getManifest()->getModule('silverstripe/asset-admin');
$ss_cms = ModuleLoader::inst()->getManifest()->getModule('silverstripe/cms');
$footnotes = ModuleLoader::inst()->getManifest()->getModule('pikselin/silverstripe-elemental-footnotes');

HtmlEditorConfig::get('cms')->enablePlugins(['footnotelink' => $footnotes->getResource('client/js/TinyMCE/footnote-link.js')]);
HtmlEditorConfig::get('cms')->insertButtonsBefore('sslink', 'footnotelink');


$footnote = TinyMCEConfig::get('footnote');

$footnote->enablePlugins([
    'sslink' => $ss_admin->getResource('client/dist/js/TinyMCE_sslink.js'),
    'sslinkexternal' => $ss_admin->getResource('client/dist/js/TinyMCE_sslink-external.js'),
    'sslinkemail' => $ss_admin->getResource('client/dist/js/TinyMCE_sslink-email.js'),
    'sslinkinternal' => $ss_cms->getResource('client/dist/js/TinyMCE_sslink-internal.js'),
    'sslinkanchor' => $ss_cms->getResource('client/dist/js/TinyMCE_sslink-anchor.js'),
    'ssmedia' => $ss_asset->getResource('client/dist/js/TinyMCE_ssmedia.js'),
    'ssembed' => $ss_asset->getResource('client/dist/js/TinyMCE_ssembed.js'),
    'sslinkfile' => $ss_asset->getResource('client/dist/js/TinyMCE_sslink-file.js'),
    'footnotelink' => $footnotes->getResource('client/js/TinyMCE/footnote-link.js'),
    'hr' => null
]);
$footnote->setOptions([
    'friendly_name' => 'footnote',
    'skin' => 'silverstripe',
    'browser_spellcheck' => false,
    'importcss_append' => true,
    'importcss_selector_filter' => '.exclude-styles-',
]);
$footnote->enablePlugins([
    'charmap', 'hr'
]);

$footnote->setButtonsForLine(1, 'bold', 'italic', 'removeformat', '|', 'hr', 'bullist', 'numlist', '|', 'sslink', '|', 'ssmedia', 'footnotelink', '|', 'code');
$footnote->setButtonsForLine(2, '');
