<?php

namespace core\flysystem;

use League\Flysystem\Adapter\Local;

class LocalFilesystem extends \creocoder\flysystem\LocalFilesystem
{
    /**
     * @return Local
     */
    protected function prepareAdapter()
    {
        return new Local($this->path, LOCK_EX, Local::DISALLOW_LINKS, [
            'file' => [
                'public' => 0775,
                'private' => 0600,
            ],
            'dir' => [
                'public' => 0777,
                'private' => 0700,
            ]
        ]);
    }
}