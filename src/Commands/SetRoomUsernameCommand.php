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
 * Class SetRoomUsernameCommand
 * @package FrankHouweling\CardsSpeak\Commands
 */
final class SetRoomUsernameCommand implements CommandInterface
{
    /**
     * Set the room username
     *
     * @param array $commandData
     * @param ApplicationState $state
     * @param ConnectionInterface $connection
     * @return array
     */
    public function __invoke(array $commandData, ApplicationState $state, ConnectionInterface $connection): array
    {
        $room = $state->getRoomsPool()->findBySlug($commandData['slug'] ?? '');
        if ($room === null){
            return ['error' => 'Unknown room'];
        }
        $room->setUserName(spl_object_hash($connection), $commandData['user_name']);

        foreach ($room->getConnections() as $roomConnection) {
            $roomConnection->send(json_encode([
                'event' => 'room_info_updated',
                'data' => $room,
            ]));
        }
        return [];
    }
}