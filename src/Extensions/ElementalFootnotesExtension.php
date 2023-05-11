<?php

namespace DNADesign\Elemental\Extensions;

use DNADesign\Elemental\Models\BaseElement;
use DNADesign\Elemental\Models\ElementalArea;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\ORM\DataExtension;

/**
 * @method ElementalArea ElementalArea()
 * @property int ElementalAreaID
 */
class ElementalFootnotesExtension extends DataExtension //ElementalAreasExtension
{

    /**
     * @see SiteTree::getAnchorsOnPage()
     */
    public function getAnchorsOnPage()
    {
        $parseSuccess = preg_match_all(
            "/\\s+(name|id)\\s*=\\s*([\"'])([^\\2\\s>]*?)\\2|\\s+(name|id)\\s*=\\s*([^\"']+)[\\s +>]/im",
            $this->Content ?? '',
            $matches
        );

        $anchors = [];
        if ($parseSuccess >= 1) {
            $anchors = array_values(array_unique(array_filter(
                array_merge($matches[3], $matches[5])
            )));
        }

        foreach ($this->owner->hasOne() as $key => $class) {
            if ($class !== ElementalArea::class) {
                continue;
            }
            /** @var ElementalArea $area */
            $area = $this->owner->$key();
            if ($area) {
                foreach ($area->Elements() as $element) {
                    echo $element->ClassName;
                    $anchors = array_merge($anchors, $element->getAnchorsInContent());
                }
            }
        }      

        $this->extend('updateAnchorsOnPage', $anchors);

        return $anchors;
    }
}

