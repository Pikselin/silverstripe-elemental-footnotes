<?php

namespace Pikselin\Elemental\Footnotes {

use SilverStripe\Core\Manifest\ModuleLoader;
use SilverStripe\Core\Manifest\ModuleResourceLoader;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\HTMLEditor\TinyMCEConfig;
use SilverStripe\Forms\LiteralField;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\Security\Permission;
use SilverStripe\Security\Security;

    class FootNoteDataObject extends DataObject {

        private static $db = [
            'Content' => 'HTMLText',
            'NotesOrder' => 'Int',
        ];
        private static $has_one = [
            'ElementalFootnotes' => ElementalFootnotes::class
        ];
        private static $table_name = 'FootNoteDataObject';
        private static $singular_name = 'Footnote';
        private static $plural_name = 'Footnotes';
        private static $summary_fields = [
            'Content' => 'Content',
            'LinkIDRef' => 'ID'
        ];
        private static $default_sort = 'NotesOrder ASC';
        
//        public function LinkIDSrc() {
//            return 'footnote-item-' . $this->ID;
//        }
        
        public function LinkIDRef() {
            if ((int) $this->ID > 0) {
                return DBField::create_field('Text', $this->ID);
            } else {
                return DBField::create_field('Text', 'ID generate when saved');
            }
        }

        public function LinkID() {
            if ((int) $this->ID > 0) {
                //$anchor = $this->LinkIDRef();
                $dialogImg = ModuleResourceLoader::singleton()->resolveURL('pikselin/silverstripe-elemental-footnotes:client/images/footnote-dialog.png');
                return '<h3>Link ID: <strong style="color: red">' . $this->ID . '</strong><h3><p>This is the element ID for this note. Use this when creating footnote links in content editors. Enter the ID and a title for the link and then click ok in the Footnote link dialog.</p><p><img src="'.$dialogImg.'" alt="example dialog"/></p>';
            } else {
                return '<i>Save this note first in order to generate the ID to link to.</i>';
            }
        }
        
        private function HTMLEditorReduced() {
            $ss_admin = ModuleLoader::inst()->getManifest()->getModule('silverstripe/admin');
            $ss_asset = ModuleLoader::inst()->getManifest()->getModule('silverstripe/asset-admin');
            $ss_cms = ModuleLoader::inst()->getManifest()->getModule('silverstripe/cms');

            $charmap_append = [
                ['256', 'A - macron'],
                ['274', 'E - macron'],
                ['298', 'I - macron'],
                ['332', 'O - macron'],
                ['362', 'U - macron'],
                ['257', 'a - macron'],
                ['275', 'e - macron'],
                ['299', 'i - macron'],
                ['333', 'o - macron'],
                ['363', 'u - macron'],
            ];

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
                'hr' => null
            ]);
            $footnote->setOptions([
                'friendly_name' => 'reduced',
                'skin' => 'silverstripe',
                'browser_spellcheck' => false,
                'importcss_append' => true,
                'importcss_selector_filter' => '.exclude-styles-',
            ]);
            $footnote->enablePlugins([
                'charmap', 'hr'
            ]); 
            
            $footnote->setOption('charmap_append', $charmap_append);
            $footnote->setButtonsForLine(1, 'bold', 'italic', 'removeformat', '|', 'hr', 'bullist', 'numlist', '|', 'sslink', '|', 'charmap', 'ssmedia', '|', 'code');
            $footnote->setButtonsForLine(2, '');
            
            return 'footnote';
            
        }

        public function getCMSFields() {
            $fields = parent::getCMSFields();
            $fields->removeByName('NotesOrder');
            $fields->removeByName('ElementalFootnotesID');
            $fields->removeByName('Content');

            $LinkID = LiteralField::create('LinkID', $this->LinkID());
            
            $Content = HTMLEditorField::create('Content', 'Footnote text', $this->Content, $this->HTMLEditorReduced())->setRows(5);

            $fields->addFieldToTab('Root.Main', $Content);
            $fields->addFieldToTab('Root.Main', $LinkID);

            return $fields;
        }

        public function canView($member = null) {
            return true;
        }

        public function canEdit($member = null) {
            if (!$member) {
                if (!Security::getCurrentUser()) {
                    return false;
                }
                $member = Security::getCurrentUser();
            }
            return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
        }

        public function canDelete($member = null) {
            if (!$member) {
                if (!Security::getCurrentUser()) {
                    return false;
                }
                $member = Security::getCurrentUser();
            }
            return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
        }

        public function canCreate($member = null, $context = []) {
            if (!$member) {
                if (!Security::getCurrentUser()) {
                    return false;
                }
                $member = Security::getCurrentUser();
            }
            return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
        }

    }

}
