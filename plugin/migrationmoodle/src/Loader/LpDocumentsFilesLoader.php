<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\PluginBundle\MigrationMoodle\Loader;

use Chamilo\PluginBundle\MigrationMoodle\Interfaces\LoaderInterface;

class LpDocumentsFilesLoader implements LoaderInterface
{

    /**
     * Load the data and return the ID inserted.
     *
     * @param array $incomingData
     *
     * @return int
     */
    public function load(array $incomingData)
    {
        $userId = api_get_user_id();
        $courseInfo = api_get_course_info_by_id($incomingData['course']);

        try {
            $filePath = $this->findFilePath($incomingData['contenthash']);
        } catch (\Exception $e) {
            return 0;
        }

        $file = [
            'file' => [
                'name' => $incomingData['filename'],
                'tmp_name' => $filePath,
                'type' => $incomingData['mimetype'],
                'size' => $incomingData['filesize'],
                'error' => 0,
                'from_file' => true,
                'move_file' => true,
            ],
        ];

        $_POST['language'] = $courseInfo['language'];

        $fileData = \DocumentManager::upload_document(
            $file,
            '/',
            $incomingData['filename'],
            '',
            null,
            null,
            true,
            false,
            'file',
            false,
            $userId,
            $courseInfo
        );

        return $fileData['iid'];
    }

    /**
     * @param $contentHash
     *
     * @throws \Exception
     *
     * @return string
     */
    private function findFilePath($contentHash)
    {
        $d1 = substr($contentHash, 0, 2);
        $d2 = substr($contentHash, 2, 2);

        $moodleDataPath = '/var/www/moodle/moodledata';
        $moodleDataPath = rtrim($moodleDataPath, ' /');

        $filePath = "$moodleDataPath/filedir/$d1/$d2/$contentHash";

        if (!file_exists($filePath)) {
            throw new \Exception("File $contentHash not found in $moodleDataPath/filedir");
        }

        return $filePath;
    }
}
