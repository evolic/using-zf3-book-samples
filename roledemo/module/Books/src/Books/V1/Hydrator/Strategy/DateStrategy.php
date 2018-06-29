<?php
namespace Books\V1\Hydrator\Strategy;

use DateTime;
use DateTimeInterface;
use Zend\Hydrator\Strategy\StrategyInterface;

final class DateStrategy implements StrategyInterface
{
    /**
     * @var string
     */
    private $format;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->format   = 'Y-m-d';
    }

    /**
     * {@inheritDoc}
     *
     * Converts to date time string
     *
     * @param mixed|DateTime $value
     *
     * @return mixed|string
     */
    public function extract($value)
    {
        if ($value instanceof DateTimeInterface) {
            return $value->format($this->format);
        }

        return $value;
    }

    /**
     * Converts date time string to DateTime instance for injecting to object
     *
     * {@inheritDoc}
     *
     * @param mixed|string $value
     *
     * @return mixed|DateTime
     */
    public function hydrate($value)
    {
        if ($value === '' || $value === null) {
            return;
        }

        $hydrated = DateTime::createFromFormat($this->format, $value);

        return $hydrated ?: $value;
    }
}
