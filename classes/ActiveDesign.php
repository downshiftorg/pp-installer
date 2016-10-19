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
        // backwards compat for pre ProPhoto 6.14.0
        if (null === $this->settings->get('live_design_id')) {
            return parent::getId();
        }

        if (! $this->id) {
            $this->id = $this->settings->get('live_design_id');
        }
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function isLiveDesign()
    {
        return false;
    }
}
