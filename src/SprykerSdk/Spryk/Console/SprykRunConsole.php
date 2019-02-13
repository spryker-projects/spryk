<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Console;

use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Resolver\OptionsContainer;
use SprykerSdk\Spryk\Style\SprykStyle;
use SprykerSdk\Spryk\Style\SprykStyleInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SprykRunConsole extends AbstractSprykConsole
{
    public const ARGUMENT_SPRYK = 'spryk';
    public const ARGUMENT_SPRYK_SHORT = 's';

    public const OPTION_INCLUDE_OPTIONALS = 'include-optional';
    public const OPTION_INCLUDE_OPTIONALS_SHORT = 'i';

    /**
     * @var array|null
     */
    protected static $argumentsList;

    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    protected $input;

    /**
     * @var \SprykerSdk\Spryk\Style\SprykStyleInterface
     */
    protected $output;

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('spryk:run')
            ->setDescription('Runs a Spryk build process.')
            ->setHelp($this->getHelpText())
            ->addArgument(static::ARGUMENT_SPRYK, InputArgument::REQUIRED, 'Name of the Spryk which should be build.')
            ->addOption(static::OPTION_INCLUDE_OPTIONALS, static::OPTION_INCLUDE_OPTIONALS_SHORT, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Name(s) of the Spryks which are marked as optional but should be build.');

        foreach ($this->getSprykArguments() as $argumentDefinition) {
            $this->addOption(
                $argumentDefinition['name'],
                null,
                $argumentDefinition['mode'],
                $argumentDefinition['description']
            );
        }
    }

    /**
     * @return array
     */
    protected function getSprykArguments(): array
    {
        if (static::$argumentsList === null) {
            static::$argumentsList = $this->getFacade()->getArgumentList();
        }

        return array_filter(static::$argumentsList, function (array $argumentDefinition) {
            return strpos($argumentDefinition['name'], '.') === false;
        });
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->input = $input;
        $this->output = $this->createOutput($input, $output);

        OptionsContainer::setOptions($input->getOptions());

        $sprykName = $this->getSprykName($input);
        $this->getFacade()->executeSpryk(
            $sprykName,
            (array)OptionsContainer::getOption(static::OPTION_INCLUDE_OPTIONALS),
            $this->output
        );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return \SprykerSdk\Spryk\Style\SprykStyleInterface
     */
    protected function createOutput(InputInterface $input, OutputInterface $output): SprykStyleInterface
    {
        return new SprykStyle(
            $input,
            $output
        );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return string
     */
    protected function getSprykName(InputInterface $input): string
    {
        return current((array)$input->getArgument(static::ARGUMENT_SPRYK));
    }

    /**
     * @return string
     */
    protected function getHelpText(): string
    {
        return 'Use `console spryk:dump <info>{SPRYK NAME}</info>` to get the options of a specific Spryk.';
    }
}
