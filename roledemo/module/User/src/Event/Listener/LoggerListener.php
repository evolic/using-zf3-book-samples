<?php
namespace User\Event\Listener;

use User\Event\AuthEvents;
use User\Event\ImpersonateEvents;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Log\LoggerInterface;
use Zend\Log\Logger;

class LoggerListener
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    private $listeners = [];


    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }


    public function attachEvents(EventManagerInterface $eventManager)
    {
        $authEvents = [
            AuthEvents::EVENT_NAME_LOGIN_ATTEMPT,
            AuthEvents::EVENT_NAME_LOGIN_SUCCESSFUL,
            AuthEvents::EVENT_NAME_LOGIN_FAILED,

            AuthEvents::EVENT_NAME_LOGOUT_ATTEMPT,
            AuthEvents::EVENT_NAME_LOGOUT_SUCCESSFUL,
            AuthEvents::EVENT_NAME_LOGOUT_FAILED,
        ];

        foreach ($authEvents as $eventName) {
            $this->listeners[] = $eventManager->attach(
                $eventName, [$this, 'processEvent']
            );
        }

        $impersonateEvents = [
            ImpersonateEvents::EVENT_NAME_IMPERSONATE,
            ImpersonateEvents::EVENT_NAME_UNIMPERSONATE,
        ];

        foreach ($impersonateEvents as $eventName) {
            $this->listeners[] = $eventManager->attach(
                $eventName, [$this, 'processEvent']
            );
        }
    }

    public function processEvent(EventInterface $event)
    {
        $eventParams = $event->getParams();

        list($data, $message, $logType) = $eventParams;

        $message .= ' ' . json_encode($data);

        $this->logEvent($message, $logType);
    }

    private function logEvent($message, $logType)
    {
        switch ($logType) {
            case Logger::EMERG:
                $this->logger->emerg($message);
                break;
            case Logger::ALERT:
                $this->logger->alert($message);
                break;
            case Logger::CRIT:
                $this->logger->crit($message);
                break;
            case Logger::ERR:
                $this->logger->err($message);
                break;
            case Logger::WARN:
                $this->logger->warn($message);
                break;
            case Logger::NOTICE:
                $this->logger->notice($message);
                break;
            case Logger::INFO:
                $this->logger->info($message);
                break;
            case Logger::DEBUG:
                $this->logger->debug($message);
                break;
        }
    }
}