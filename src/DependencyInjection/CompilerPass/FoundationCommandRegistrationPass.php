<?php

namespace MoncareyWS\FoundationBundle\DependencyInjection\CompilerPass;

use MoncareyWS\FoundationBundle\Command\FoundationCommand;
use MoncareyWS\FoundationBundle\Maker\FoundationMakerInterface;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Reference;

class FoundationCommandRegistrationPass implements CompilerPassInterface
{
    const FOUNDATION_MAKER_TAG = 'foundation.maker.command';

    public function process(ContainerBuilder $container)
    {
        foreach ($container->findTaggedServiceIds(self::FOUNDATION_MAKER_TAG) as $id => $tags) {
            $def = $container->getDefinition($id);
            $class = $container->getParameterBag()->resolveValue($def->getClass());
            if (!is_subclass_of($class, FoundationMakerInterface::class)) {
                throw new InvalidArgumentException(sprintf('Service "%s" must implement interface "%s".', $id, FoundationMakerInterface::class));
            }

            $commandDefinition = new ChildDefinition('foundation.maker.auto_command.abstract');
            $commandDefinition->setClass(FoundationCommand::class);
            $commandDefinition->replaceArgument(0, new Reference($id));
            $commandDefinition->addTag('console.command', ['command' => $class::getCommandName()]);

            $container->setDefinition(sprintf('foundation.maker.auto_command.%s', Str::asTwigVariable($class::getCommandName())), $commandDefinition);
        }
    }
}
