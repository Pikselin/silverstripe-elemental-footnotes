<?php

namespace Pikselin\Elemental\Footnotes {

use SilverStripe\Core\Manifest\ModuleResourceLoader;
    use SilverStripe\Forms\FieldList;
    use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\Security\Permission;
use SilverStripe\Security\Security;

    class FootNoteDataObject extends DataObject
    {
        private static array $db = [
            'Content'    => 'HTMLText',
            'NotesOrder' => 'Int',
        ];
        private static array $has_one = [
            'ElementalFootnotes' => ElementalFootnotes::class
        ];
        private static string $table_name = 'FootNoteDataObject';
        private static string $singular_name = 'Footnote';
        private static string $plural_name = 'Footnotes';
        private static array $summary_fields = [
            'Content'   => 'Content',
            'LinkIDRef' => 'ID'
        ];
        private static string $default_sort = 'NotesOrder ASC';

        public function LinkIDRef(): DBField
        {
            if ((int) $this->ID > 0) {
                return DBField::create_field('Text', $this->ID);
            } else {
                return DBField::create_field('Text', 'ID generate when saved');
            }
        }

        public function LinkID(): string
        {
            if ((int) $this->ID > 0) {
                $dialogImg = ModuleResourceLoader::singleton()->resolveURL('pikselin/silverstripe-elemental-footnotes:client/images/footnote-dialog.png');
                return '<h3>Link ID: <strong style="color: red">' . $this->ID . '</strong><h3><p>This is the element ID for this note. Use this when creating footnote links in content editors. Enter the ID and a title for the link and then click ok in the Footnote link dialog.</p><p><strong>Note:</strong> Using any other method of creating the footnote link may result in an error or broken link.</p><p><img src="'.$dialogImg.'" alt="example dialog"/></p>';
            } else {
                return '<i>Save this note first in order to generate the ID to link to.</i>';
            }
        }

        public function getCMSFields(): FieldList
        {
            $fields = parent::getCMSFields();
            $fields->removeByName('NotesOrder');
            $fields->removeByName('ElementalFootnotesID');
            $fields->removeByName('Content');

            $LinkID = LiteralField::create('LinkID', $this->LinkID());

            $Content = HTMLEditorField::create('Content', 'Footnote text', $this->Content, 'footnote')->setRows(5);

            $fields->addFieldToTab('Root.Main', $Content);
            $fields->addFieldToTab('Root.Main', $LinkID);

            return $fields;
        }

        public function canView($member = null): bool
        {
            return true;
        }

        public function canEdit($member = null)
        {
            if (!$member) {
                if (!Security::getCurrentUser()) {
                    return false;
                }
                $member = Security::getCurrentUser();
            }
            return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
        }

        public function canDelete($member = null)
        {
            if (!$member) {
                if (!Security::getCurrentUser()) {
                    return false;
                }
                $member = Security::getCurrentUser();
            }
            return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
        }

        public function canCreate($member = null, $context = [])
        {
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
