<?php
/* For licensing terms, see /license.txt */

namespace ChamiloLMS\CourseBundle\Tool;

/**
 * Class Survey
 * @package ChamiloLMS\CourseBundle\Tool
 */
class Survey extends BaseTool
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Survey';
    }

    /**
     * {@inheritdoc}
     */
    public function getLink()
    {
        return 'survey/survey_list.php';
    }

    public function getTarget()
    {
        return '_self';
    }

    public function getCategory()
    {
        return 'authoring';
    }
}
