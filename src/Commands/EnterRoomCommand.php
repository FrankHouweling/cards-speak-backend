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
 * Class EnterRoomCommand
 * @package FrankHouweling\CardsSpeak\Commands
 */
class EnterRoomCommand implements CommandInterface
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
    public function __invoke(array $commandData, ApplicationState $state, ConnectionInterface $connection): array
    {
        $slug = $commandData['slug'];
        $room = $state->getRoomsPool()->findBySlug($slug);
        if ($room === null){
            return [
                'type' => 'response',
                'error' => sprintf('No room found for slug %s', $slug)
            ];
        }
        $room->addConnection($connection);

        foreach ($room->getConnections() as $roomConnection) {
            $roomConnection->send(json_encode([
                'event' => 'room_info_updated',
                'data' => $room,
            ]));
        }

        return [];
    }
}