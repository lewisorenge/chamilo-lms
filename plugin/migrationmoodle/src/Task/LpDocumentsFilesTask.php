<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\PluginBundle\MigrationMoodle\Task;

use Chamilo\PluginBundle\MigrationMoodle\Extractor\BaseExtractor;
use Chamilo\PluginBundle\MigrationMoodle\Loader\LpDocumentsFilesLoader;
use Chamilo\PluginBundle\MigrationMoodle\Transformer\BaseTransformer;
use Chamilo\PluginBundle\MigrationMoodle\Transformer\Property\LoadedCourseLookup;

/**
 * Class LpDocumentsFilesTask.
 *
 * @package Chamilo\PluginBundle\MigrationMoodle\Task
 */
class LpDocumentsFilesTask extends BaseTask
{
    /**
     * @return array
     */
    public function getExtractConfiguration()
    {
        return [
            'class' => BaseExtractor::class,
            'query' => "SELECT
                    f.id,
                    f.contenthash,
                    f.filepath,
                    f.filename,
                    f.filesize,
                    f.mimetype,
                    cm.course
                FROM mdl_files f
                INNER JOIN mdl_context c ON f.contextid = c.id
                INNER JOIN mdl_course_modules cm ON c.instanceid = cm.id
                WHERE f.component = 'mod_lesson' AND f.filearea = 'page_contents' AND c.contextlevel = 70",
        ];
    }

    /**
     * @return array
     */
    public function getTransformConfiguration()
    {
        return [
            'class' => BaseTransformer::class,
            'map' => [
                'contenthash' => 'contenthash',
                'filepath' => 'filepath',
                'filename' => 'filename',
                'filesize' => 'filesize',
                'mimetype' => 'mimetype',
                'course' => [
                    'class' => LoadedCourseLookup::class,
                    'properties' => ['course'],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getLoadConfiguration()
    {
        return [
            'class' => LpDocumentsFilesLoader::class,
        ];
    }
}
