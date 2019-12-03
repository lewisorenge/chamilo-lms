<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Repository;

use APY\DataGridBundle\Grid\Column\Column;
use APY\DataGridBundle\Grid\Grid;
use Chamilo\CoreBundle\Component\Utils\ResourceSettings;
use Chamilo\CoreBundle\Entity\PersonalFile;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class PersonalFileRepository extends ResourceRepository implements ResourceRepositoryInterface
{
    public function getResourceSettings(): ResourceSettings
    {
        $settings = parent::getResourceSettings();

        $settings
            ->setAllowNodeFolderCreation(true)
            //->setAllowResourceContentCreation(true)
            ->setAllowResourceUploadCreation(true)
            ->setAllowEditResource(false)
        ;

        return $settings;
    }

    public function saveUpload(UploadedFile $file)
    {
        $resource = new PersonalFile();
        $resource->setName($file->getClientOriginalName());

        return $resource;
    }

    public function saveResource(FormInterface $form, $course, $session, $fileType)
    {
        $newResource = $form->getData();
        $newResource
            //->setCourse($course)
            //->setSession($session)
            //->setFiletype($fileType)
            //->setTitle($title) // already added in $form->getData()
        ;

        return $newResource;
    }

    public function getTitleColumn(Grid $grid): Column
    {
        return $grid->getColumn('name');
    }
}
