<?php

namespace ProPhoto\InstallerPlugin\TestDrive;

use ProPhoto\Core\Service\Design\ActiveDesign as BaseActiveDesign;

class ActiveDesign extends BaseActiveDesign
{
    /**
     * @var string
     */
    protected $id;

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        if (! $this->id) {
            $this->id = $this->settings->get('live_design_id');
        }
        return $this->id;
    }
}
