<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\PluginBundle\MigrationMoodle\Transformer\Property;

use Chamilo\CoreBundle\Component\Utils\ChamiloApi;

/**
 * Class WrapHtmlAndReplaceFilePaths.
 *
 * @package Chamilo\PluginBundle\MigrationMoodle\Transformer\Property
 */
class WrapHtmlAndReplaceFilePaths extends ReplaceFilePaths
{
    /**
     * @param array $data
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function transform(array $data)
    {
        $content = parent::transform($data);

        $style = api_get_css_asset('bootstrap/dist/css/bootstrap.min.css');
        $style .= api_get_css_asset('fontawesome/css/font-awesome.min.css');
        $style .= api_get_css(ChamiloApi::getEditorDocStylePath());

        $content = "<!DOCTYPE html><head>$style</head><body>$content</body>";

        return $content;
    }
}
