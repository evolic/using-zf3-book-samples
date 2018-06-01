<?php
namespace User\Event;

/**
 * Class AuthEvents
 *
 * @package User\Event
 */
class AuthEvents
{
    const EVENT_NAME_LOGIN_ATTEMPT      = 'auth.login.attempt';
    const EVENT_NAME_LOGIN_SUCCESSFUL   = 'auth.login.successful';
    const EVENT_NAME_LOGIN_FAILED       = 'auth.login.failed';
    const EVENT_NAME_LOGOUT_ATTEMPT     = 'auth.logout.attempt';
    const EVENT_NAME_LOGOUT_SUCCESSFUL  = 'auth.logout.successful';
    const EVENT_NAME_LOGOUT_FAILED      = 'auth.logout.failed';

    const EVENT_MESSAGE_LOGIN_ATTEMPT       = 'Attempt to login as %s';
    const EVENT_MESSAGE_LOGIN_SUCCESSFUL    = 'Successfully login as %s ';
    const EVENT_MESSAGE_LOGIN_FAILED        = 'Login as %s failed';
    const EVENT_MESSAGE_LOGOUT_ATTEMPT      = 'Attempt to logout as %s';
    const EVENT_MESSAGE_LOGOUT_SUCCESSFUL   = 'Successfully log out as %s';
    const EVENT_MESSAGE_LOGOUT_FAILED       = 'Log out as %s failed';
}