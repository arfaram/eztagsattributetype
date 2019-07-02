<?php

declare(strict_types=1);

namespace EzSystems\TagsFormType\Form\Type;

use eZ\Publish\API\Repository\FieldType;
use eZ\Publish\SPI\FieldType\Value;
use Symfony\Component\Form\DataTransformerInterface;

class AttributeTagsTransformer implements DataTransformerInterface
{
    /** @var \eZ\Publish\API\Repository\FieldType */
    private $fieldType;

    /**
     * AttributeTagsTransformer constructor.
     * @param \eZ\Publish\API\Repository\FieldType $fieldType
     */
    public function __construct(FieldType $fieldType)
    {
        $this->fieldType = $fieldType;
    }

    /**
     * @param mixed $value
     * @return array|mixed|null
     *
     * Example:
     * return [
     *       "ids" => "375|#412|#413|#429",
     *      "parent_ids" => "360|#402|#402|#428",
     *      "keywords" => "English|#Eggs|#Entrée|#Fish",
     *      "locales" => "eng-GB|#eng-GB|#eng-GB|#eng-GB",
     *      ];
     */
    public function transform($value)
    {
        if ($value === null) {
            return null;
        }

        $valueArray = json_decode($value, true);

        $value = $this->fieldType->fromHash($valueArray);

        if (!$value instanceof Value) {
            return null;
        }

        $ids = [];
        $parentIds = [];
        $keywords = [];
        $locales = [];

        foreach ($value->tags as $tag) {
            //TODO: get siteaccess language to handle tags translation, $mainKeyword should be the fallback
            $mainKeyword = $tag->getKeyword();

            $ids[] = $tag->id;
            $parentIds[] = $tag->parentTagId;
            $keywords[] = $mainKeyword;
            $locales[] = $tag->mainLanguageCode;
        }

        return [
            'ids' => implode('|#', $ids),
            'parent_ids' => implode('|#', $parentIds),
            'keywords' => implode('|#', $keywords),
            'locales' => implode('|#', $locales),
        ];
    }

    /**
     * @param mixed $value
     * @return false|mixed|string|null
     */
    public function reverseTransform($value)
    {
        if ($value === null || empty(array_filter($value))) {
            return null;
        }

        $ids = explode('|#', $value['ids']);
        $parentIds = explode('|#', $value['parent_ids']);
        $keywords = explode('|#', $value['keywords']);
        $locales = explode('|#', $value['locales']);

        $hash = [];
        for ($i = 0, $count = \count($ids); $i < $count; ++$i) {
            if (!\array_key_exists($i, $parentIds) || !\array_key_exists($i, $keywords) || !\array_key_exists($i, $locales)) {
                break;
            }

            if ($ids[$i] !== '0') {
                $hash[] = ['id' => (int) $ids[$i]];

                continue;
            }

            $hash[] = [
                'parent_id' => (int) $parentIds[$i],
                'keywords' => [$locales[$i] => $keywords[$i]],
                'main_language_code' => $locales[$i],
            ];
        }
        //Todo: We use the same structure like in the FieldType Implementation. Check if another format will not break the TagService.
        $value = $this->fieldType->fromHash($hash);
        $storage = $this->fieldType->toHash($value);

        return json_encode($storage);
    }
}
