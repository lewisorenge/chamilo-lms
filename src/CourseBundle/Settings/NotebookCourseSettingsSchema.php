<?php

/* For licensing terms, see /license.txt */

namespace Chamilo\CourseBundle\Settings;

use Chamilo\CoreBundle\Settings\AbstractSettingsSchema;
use Sylius\Bundle\SettingsBundle\Schema\AbstractSettingsBuilder;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class NotebookCourseSettingsSchema.
 */
class NotebookCourseSettingsSchema extends AbstractSettingsSchema
{
    public function buildSettings(AbstractSettingsBuilder $builder)
    {
        /*$builder
            ->setDefaults([
                'enabled' => '',
            ])
        ;
        $allowedTypes = [
            'enabled' => ['string'],
        ];
        $this->setMultipleAllowedTypes($allowedTypes, $builder);*/
    }

    public function buildForm(FormBuilderInterface $builder)
    {
        /*$builder
            ->add('enabled', 'yes_no')
        ;*/
    }
}
