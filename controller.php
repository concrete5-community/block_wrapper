<?php

namespace Concrete\Package\BlockWrapper;

use A3020\BlockWrapper\Provider\ServiceProvider;
use Concrete\Core\Block\BlockType\BlockType;
use Concrete\Core\Package\Package;

final class Controller extends Package
{
    protected $pkgHandle = 'block_wrapper';
    protected $appVersionRequired = '8.0';
    protected $pkgVersion = '1.0.1';
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

    public function on_start()
    {
        /** @var ServiceProvider $provider */
        $provider = $this->app->make(ServiceProvider::class);
        $provider->register();
    }

    public function install()
    {
        $pkg = parent::install();

        if (!BlockType::getByHandle('block_wrapper')) {
            BlockType::installBlockType('block_wrapper', $pkg);
        }
    }
}
