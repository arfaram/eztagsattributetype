<?php

declare(strict_types=1);

namespace EzSystems\TagsFormType\Form\Type;

use eZ\Publish\API\Repository\FieldTypeService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class AttributeTagsType.
 */
class AttributeTagsType extends AbstractType
{
    /**
     * @var \eZ\Publish\API\Repository\FieldTypeService */
    private $fieldTypeService;

    /**
     * AttributeTagsType constructor.
     * @param \eZ\Publish\API\Repository\FieldTypeService $fieldTypeService
     */
    public function __construct(FieldTypeService $fieldTypeService)
    {
        $this->fieldTypeService = $fieldTypeService;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ids', HiddenType::class)
            ->add('parent_ids', HiddenType::class)
            ->add('keywords', HiddenType::class)
            ->add('locales', HiddenType::class)
            ->addModelTransformer(
                new AttributeTagsTransformer($this->fieldTypeService->getFieldType('eztags'))
            );
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'ezplatform_tags_blockfield';
    }
}
