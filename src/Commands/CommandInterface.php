<?php
declare(strict_types=1);

/**
 * This file is part of Cards Speak.
 *
 * (c) Frank Houweling
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FrankHouweling\CardsSpeak\Commands;

use FrankHouweling\CardsSpeak\State\ApplicationState;
use Ratchet\ConnectionInterface;

/**
 * Interface CommandInterface
 * @package FrankHouweling\CardsSpeak\Commands
 */
interface CommandInterface
{
    /**
     * Invoke a new call to the command.
     *
     * @param array $commandData
     * @param ApplicationState $state
     *
     * @param ConnectionInterface $connection
     * @return array
     */
    public function __invoke(
        array $commandData,
        ApplicationState $state,
        ConnectionInterface $connection
    ): array;
}