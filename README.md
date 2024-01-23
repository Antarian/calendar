# Calendar
This is a project to demonstrate DDD architecture approach to create code with no PHP framework.

In the [project](project) directory we have a `Scope/Calendar` namespace which has a common structure:
`Command -> Command Handler -> Repository -> Entity (Model) -> Value Object(s)`

There is also a simple App implementing domain commands created in [app](app) directory.

### Run the project
You need to have installed PHP on the system or load project in Docker.
```php
php public/index.php
```

### The Problem
Adding conflicting calendar events.

The calendar can contain thousands of events. It is not a good idea to load them all within Calendar entity and for writing the new event it is not even needed. 

#### Solution 1
Used here, is the service interface [AvailabilityService](project/Scopes/Calendar/Service/AvailabilityService.php) to check for availability/conflicts in calendar. This interface is then implemented by App level service [AvailabilityVerifier](project/Scopes/Calendar/Service/AvailabilityService.php).

This solution is preferred if we have conditions from other parts of the domain. E.g. there is a work-shift system, and only some calendars should not allow placing events outside the working hours.

#### Solution 2 (not used here)
Is to create [CalendarRepository](project/Scopes/Calendar/Repository/CalendarRepository.php) method
```php
getWithEventsForDays(DateTimeImmutable $startDateTime, DateTimeImmutable $endDateTime): Calendar
```
to load Calendar with events only for required days. Check for conflicts will then move inside to [CalendarEventCollection](project/Scopes/Calendar/ValueObject/CalendarEventCollection.php) class.

This solution may be preferred in this case, as validation is related to owned calendar and domain scope.

### Additional info for using DDD
In PHP, the DDD structure is best to use for writing operations. We can add `Query -> QueryHandler -> Read Repository -> Read Entity (Model)`. But reading operations are prone to change often and many times they need to be optimized for speed. They are also often hard to contain in one Domain Scope.

Using DDD Queries with almost any PHP framework is also bypassing what frameworks and ORMs are able to offer.

### Extending
Command and Command Handler are ready for adding the queue system, as they are the same style as in [Symfony Messenger](https://symfony.com/doc/current/messenger.html). Which I think is the best PHP library to handle event queues. Validation of the commands is currently in the Controller, but this can be easily moved into message/command bus validation middleware [https://symfony.com/doc/current/messenger.html#middleware](https://symfony.com/doc/current/messenger.html#middleware).

If we want to manage multiple calendars at once, belonging to the same person or multiple persons, the solution would be to add CalendarGroup aggregate. This aggregate can then still use solution 1 or 2 for the validation.

If there are domain-specific rules to validate, this should be done in Entity(Model). 

Implementing ES depends on requirements.
- best for current example, entity will create events and react to those in own protected methods. This will ensure replay-able events fully handling the correct entity state. Events will be sent to the queue after successful `$repository->store(entity)`
- if event queue is acting as a storage, e.g. DB is a service accepting specific events, repository->store may be ignored or used as event queue processor

### Tests
```php
php ./vendor/bin/phpunit tests/
php ./vendor/bin/phpunit project/Scopes/Calendar/Tests
```

### License
[MIT License](LICENSE.md)