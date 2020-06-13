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

/**
 * Class RoomsPool
 * @package FrankHouweling\CardsSpeak\State
 */
final class RoomsPool
{
    /** @var array|Room[]  */
    private $rooms;

    /**
     * ConnectionsPool constructor.
     */
    public function __construct()
    {
        $this->rooms = [];
    }

    /**
     * @param Room $room
     * @param string $slug
     */
    public function add(Room $room, string $slug)
    {
        $this->rooms[$slug] = $room;
    }

    /**
     * @param string $slug
     * @return Room|null
     */
    public function findBySlug(string $slug)
    {
        return $this->rooms[$slug] ?? null;
    }

    /**
     * @param ConnectionInterface $connection
     * @return null|Room
     */
    public function getConnectionRoom(ConnectionInterface $connection)
    {
        foreach ($this->rooms as $room){
            if ($room->hasConnection($connection)){
                return $room;
            }
        }
        return null;
    }
}