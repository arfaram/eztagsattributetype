<?php

declare(strict_types=1);

namespace EzSystems\TagsFormTypeBundle\Block\Attribute;

use eZ\Publish\API\Repository\FieldTypeService;
use EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Attribute\FormTypeMapper\AttributeFormTypeMapperInterface;
use EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Definition\BlockAttributeDefinition;
use EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Definition\BlockDefinition;
use EzSystems\TagsFormTypeBundle\Form\Type\AttributeTagsType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class TagsAttributeMapper.
 */
class TagsAttributeMapper implements AttributeFormTypeMapperInterface
{
    /** @var \eZ\Publish\API\Repository\FieldTypeService */
    private $fieldTypeService;

    /**
     * TagsAttributeMapper constructor.
     * @param \eZ\Publish\API\Repository\FieldTypeService $fieldTypeService
     */
    public function __construct(FieldTypeService $fieldTypeService)
    {
        $this->fieldTypeService = $fieldTypeService;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $formBuilder
     * @param \EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Definition\BlockDefinition $blockDefinition
     * @param \EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Definition\BlockAttributeDefinition $blockAttributeDefinition
     * @param array $constraints
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     * @throws \Exception
     */
    public function map(
        FormBuilderInterface $formBuilder,
        BlockDefinition $blockDefinition,
        BlockAttributeDefinition $blockAttributeDefinition,
        array $constraints = []
    ): FormBuilderInterface {
        return $formBuilder->create(
            'value',
            AttributeTagsType::class,
            [
                'constraints' => $constraints,
            ]
        );
    }
}
