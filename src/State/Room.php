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
 * Class Room
 * @package FrankHouweling\CardsSpeak\State
 */
final class Room implements \JsonSerializable
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $ownerClientSecret;

    /**
     * @var \SplObjectStorage|ConnectionInterface[]
     */
    private $connections;

    /**
     * Room constructor.
     * @param string $name
     * @param string $clientSecret
     */
    public function __construct(string $name, string $clientSecret)
    {
        $this->name = $name;
        $this->ownerClientSecret = $clientSecret;
        $this->connections = new SplObjectStorage();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getOwnerClientSecret(): string
    {
        return $this->ownerClientSecret;
    }

    /**
     * @param ConnectionInterface $connection
     */
    public function addConnection(ConnectionInterface $connection)
    {
        $this->connections->attach($connection);
    }

    /**
     * @param ConnectionInterface $connection
     */
    public function detachConnection(ConnectionInterface $connection)
    {
        $this->connections->detach($connection);
    }

    /**
     * @param ConnectionInterface $connection
     * @return bool
     */
    public function hasConnection(ConnectionInterface $connection)
    {
        return $this->connections->contains($connection);
    }

    /**
     * @return SplObjectStorage
     */
    public function getConnections()
    {
        return $this->connections;
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'name' => $this->getName(),
            'connectionCount' => $this->getConnections()->count()
        ];
    }
}