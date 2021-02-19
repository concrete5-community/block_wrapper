<?php

namespace A3020\BlockWrapper\Provider;

use A3020\BlockWrapper\BlockCounter;
use A3020\BlockWrapper\Listener\BlockBeforeRender;
use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;

class ServiceProvider implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    public function register()
    {
        // PS. The EventDispatcher is not injected because it doesn't work in 8.0.0

        $this->app['director']->addListener('on_block_before_render', function($event) {
            /** @var BlockBeforeRender $listener */
            $listener = $this->app->make(BlockBeforeRender::class);
            $listener->handle($event);
        });

        $this->app->singleton(BlockCounter::class);
    }
}
