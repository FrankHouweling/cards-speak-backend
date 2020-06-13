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

namespace FrankHouweling\CardsSpeak\State;

use Ratchet\ConnectionInterface;
use SplObjectStorage;

/**
 * Class ConnectionsPool
 * @package FrankHouweling\CardsSpeak\State
 */
final class ConnectionsPool
{
    /** @var SplObjectStorage  */
    private $connections;

    /** @var ApplicationState */
    private $applicationState;

    /**
     * ConnectionsPool constructor.
     */
    public function __construct(ApplicationState $applicationState)
    {
        $this->connections = new SplObjectStorage();
        $this->applicationState = $applicationState;
    }

    public function add(ConnectionInterface $connection)
    {
        $this->connections->attach($connection);
    }

    /**
     * @param ConnectionInterface $connection
     */
    public function detach(ConnectionInterface $connection)
    {
        $this->connections->detach($connection);
    }
}