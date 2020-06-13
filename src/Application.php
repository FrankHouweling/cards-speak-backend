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

namespace FrankHouweling\CardsSpeak;


use FrankHouweling\CardsSpeak\Commands\CommandInterface;
use FrankHouweling\CardsSpeak\State\ApplicationState;
use Jawira\CaseConverter\Convert;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use ReflectionClass;

final class Application implements MessageComponentInterface
{

    /**
     * @var ApplicationState
     */
    private $applicationState;

    /**
     * Application constructor.
     */
    public function __construct()
    {
        $this->applicationState = new ApplicationState();
    }

    /**
     * Run the application loop :)
     */
    public function run()
    {
        $this->initEvents();
    }

    /**
     * @param ConnectionInterface $conn
     */
    function onOpen(ConnectionInterface $conn)
    {
        $this->applicationState->getConnectionsPool()->add($conn);
    }

    function onClose(ConnectionInterface $conn)
    {
        $room = $this->applicationState
            ->getRoomsPool()
            ->getConnectionRoom($conn);

        if ($room !== null){
            $room->detachConnection($conn);

            foreach ($room->getConnections() as $roomConnection){
                $roomConnection->send(json_encode([
                    'event' => 'room_info_updated',
                    'data' => $room,
                ]));
            }
        }

        $this->applicationState->getConnectionsPool()->detach($conn);
    }

    function onError(ConnectionInterface $conn, \Exception $e)
    {
        return $this->onClose($conn);
    }

    function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true, 50);

        // Invalid JSON received.
        if ($data === null){
            return;
        }

        try{
            $command = $this->getCommand($data['command'] ?? '');
            $data = json_encode($command($data, $this->applicationState, $from));
            $from->send($data);
        } catch(\InvalidArgumentException $e)
        {
            return;
        }
    }

    /**
     * Get command from given command name.
     *
     * @param string $commandName
     * @return CommandInterface
     *
     * @throws \ReflectionException
     * @throws \Jawira\CaseConverter\CaseConverterException
     */
    private function getCommand(string $commandName)
    {
        $commandClassName = (new Convert($commandName))->fromSnake()->toPascal() . 'Command';
        $namespacedClassName = sprintf('FrankHouweling\CardsSpeak\Commands\%s', $commandClassName);
        if (class_exists($namespacedClassName) === false){
            throw new \InvalidArgumentException();
        }
        $reflectionClass = new ReflectionClass($namespacedClassName);
        if ($reflectionClass->implementsInterface(CommandInterface::class) === false){
            throw new \InvalidArgumentException();
        }
        return new $namespacedClassName();
    }
}