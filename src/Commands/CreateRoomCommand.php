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
use FrankHouweling\CardsSpeak\State\Room;
use Ratchet\ConnectionInterface;
use Webmozart\Assert\Assert;

/**
 * Class CreateRoomCommand
 * @package FrankHouweling\CardsSpeak\Commands
 */
class CreateRoomCommand implements CommandInterface
{

    /**
     * Create a new Room
     *
     * @param array $commandData
     * @param ApplicationState $state
     * @param ConnectionInterface $connection
     *
     * @return array
     */
    public function __invoke(array $commandData, ApplicationState $state, ConnectionInterface $connection): array
    {
        Assert::notEmpty($commandData['name']);
        Assert::notEmpty($commandData['client_secret']);
        Assert::notEmpty($commandData['slug']);

        $room = $state->getRoomsPool()->findBySlug($commandData['slug']);
        if ($room !== null){
            return ['error' => 'Slug is already in use'];
        }

        $room = new Room(
            $commandData['name'],
            $commandData['client_secret']
        );

        $state->getRoomsPool()->add($room, $commandData['slug']);

        return [];
    }
}