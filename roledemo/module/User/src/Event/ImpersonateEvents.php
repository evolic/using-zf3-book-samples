<?php
namespace User\Event;

/**
 * Class ImpersonateEvents
 * @package User\Event
 */
class ImpersonateEvents
{
    const EVENT_NAME_IMPERSONATE    = 'impersonate.impersonate';
    const EVENT_NAME_UNIMPERSONATE  = 'impersonate.unimpersonate';

    const EVENT_MESSAGE_IMPERSONATE     = 'Successfully impersonated';
    const EVENT_MESSAGE_UNIMPERSONATE   = 'Successfully unimpersonated';
}