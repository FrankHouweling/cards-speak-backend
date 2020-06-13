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

/**
 * Class ApplicationState
 * @package State
 */
final class ApplicationState
{
    /**
     * @var RoomsPool
     */
    private $roomsPool;

    /**
     * @var ConnectionsPool
     */
    private $connectionsPool;

    /**
     * ApplicationState constructor.
     */
    public function __construct()
    {
        $this->connectionsPool = new ConnectionsPool($this);
        $this->roomsPool = new RoomsPool();
    }

    /**
     * @return RoomsPool
     */
    public function getRoomsPool(): RoomsPool
    {
        return $this->roomsPool;
    }

    /**
     * @return ConnectionsPool
     */
    public function getConnectionsPool(): ConnectionsPool
    {
        return $this->connectionsPool;
    }
}