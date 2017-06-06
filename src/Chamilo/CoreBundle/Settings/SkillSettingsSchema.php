<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Settings;

use Sylius\Bundle\SettingsBundle\Schema\SchemaInterface;
use Sylius\Bundle\SettingsBundle\Schema\SettingsBuilderInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class SkillSettingsSchema
 * @package Chamilo\CoreBundle\Settings
 */
class SkillSettingsSchema extends AbstractSettingsSchema
{
    /**
     * {@inheritdoc}
     */
    public function buildSettings(SettingsBuilderInterface $builder)
    {
        $builder
            ->setDefaults(
                array(
                    'allow_skills_tool' => 'true',
                    'allow_hr_skills_management' => 'true',
                )
            );
        $allowedTypes = array(
            'allow_skills_tool' => array('string'),
        );
        $this->setMultipleAllowedTypes($allowedTypes, $builder);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder)
    {
        $builder
            ->add('allow_skills_tool', 'yes_no')
            ->add('allow_hr_skills_management', 'yes_no')
        ;
    }
}
