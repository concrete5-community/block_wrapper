<?php

namespace Concrete\Package\BlockWrapper;

use Concrete\Core\Block\BlockType\BlockType;
use Concrete\Core\Package\Package;

final class Controller extends Package
{
    protected $pkgHandle = 'block_wrapper';
    protected $appVersionRequired = '8.0';
    protected $pkgVersion = '0.9.4';
    protected $pkgAutoloaderRegistries = [
        'src/BlockWrapper' => '\A3020\BlockWrapper',
    ];

    public function getPackageName()
    {
        return t('Block Wrapper');
    }

    public function getPackageDescription()
    {
        return t('Wrap one or more blocks in a container element.');
    }

    public function install()
    {
        $pkg = parent::install();

        if (!BlockType::getByHandle('block_wrapper')) {
            BlockType::installBlockType('block_wrapper', $pkg);
        }
    }
}
