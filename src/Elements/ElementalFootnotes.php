<?php

namespace Pikselin\Elemental\Footnotes {

    use DNADesign\Elemental\Models\BaseElement;
    use SilverStripe\Forms\CheckboxField;
    use SilverStripe\Forms\FieldList;
    use SilverStripe\Forms\GridField\GridField;
    use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;
    use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
    use SilverStripe\Forms\GridField\GridFieldFilterHeader;
    use SilverStripe\ORM\DataObject;
    use SilverStripe\ORM\FieldType\DBHTMLText;
    use SilverStripe\View\Requirements;
    use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
    use function _t;

    class ElementalFootnotes extends BaseElement {

        private static $singular_name = 'Footnote';
        private static $plural_name = 'Footnotes';
        private static $icon = 'font-icon-book-open';
        private static $table_name = 'ElementalFootnotes';
        private static $db = [
            'Enumerate' => 'Boolean'
        ];
        private static $has_many = [
            'Notes' => FootNoteDataObject::class
        ];
        private static $defaults = [
            'Title' => 'References'
        ];
        private static $cascade_deletes = [
            'Notes',
        ];
        private static $inline_editable = false;

        /**
         *
         * @param type $record
         * @param type $isSingleton
         * @param type $model
         *
         * Add the remote Javascript here, based on chart type
         */
        function __construct($record = null, $isSingleton = false, $model = null) {
            parent::__construct($record, $isSingleton, $model);

            Requirements::javascript('pikselin/silverstripe-elemental-footnotes:client/js/ElementalFootnotes.js', ['defer' => true]);
        }

        public function onBeforeWrite() {
            parent::onBeforeWrite();
        }

        public function getAnchorsInContent(): array {
            $anchors = [$this->getAnchor()];
            $anchorRegex = "/\\s+(name|id)\\s*=\\s*([\"'])([^\\2\\s>]*?)\\2|\\s+(name|id)\\s*=\\s*([^\"']+)[\\s +>]/im";
            $allFields = DataObject::getSchema()->fieldSpecs($this);
            foreach ($allFields as $field => $fieldSpec) {
                $fieldObj = $this->owner->dbObject($field);
                if ($fieldObj instanceof DBHTMLText) {
                    $parseSuccess = preg_match_all($anchorRegex, $fieldObj->getValue() ?? '', $matches);
                    if ($parseSuccess >= 1) {
                        $fieldAnchors = array_values(array_filter(
                                        array_merge($matches[3], $matches[5])
                        ));
                        $anchors = array_merge($anchors, $fieldAnchors);
                    }
                }
            }
            if ($this->Notes()) {
                foreach ($this->Notes() as $k => $v) {
                    $anchors[] = $v->LinkIDSrc();
                }
            }
            $anchors = array_unique($anchors);
            $this->extend('updateAnchorsInContent', $anchors);
            return $anchors;
        }

        public function getCMSFields() {

            $this->beforeUpdateCMSFields(function (FieldList $fields) {
                
                $Enumerate = CheckboxField::create('Enumerate', 'Enumerate')->setDescription('Add a numeric index to each footnote in this block.');

                $GridConf = GridFieldConfig_RecordEditor::create(10);
                $GridConf->addComponent(new GridFieldOrderableRows('NotesOrder'));

                $remove = [];
                $remove[] = GridFieldAddExistingAutocompleter::class;
                $remove[] = GridFieldFilterHeader::class;
                if (count($remove) > 0) {
                    $GridConf->removeComponentsByType($remove);
                }

                $Notes = new GridField('Notes', 'Notes', $this->Notes(), $GridConf);
                $Notes->setDescription('Add one or more series configs and order them manually.');

                $fields->addFieldToTab('Root.Main', $Enumerate);
                $fields->addFieldToTab('Root.Main', $Notes);
            });
            return parent::getCMSFields();
        }

        /**
         *
         * @return type
         */
        public function getType() {
            return _t(__CLASS__ . '.BlockType', 'Footnotes');
        }

        /**
         *
         * @return string
         */
        public function getSummary() {
            return '';
        }

        /**
         * @return array
         */
        protected function provideBlockSchema() {
            $blockSchema = parent::provideBlockSchema();

            $content = [];

            $content[] = 'Number of notes: ' . count($this->Notes());

            if ($this->Enumerate) {
                $content[] = 'Enumerated: yes';
            } else {
                $content[] = 'Enumerated: no';
            }

            $blockSchema['content'] = implode(', ', $content);
            return $blockSchema;
        }
    }
}