I have gone through the code and I found it nearly terrible code, because it is not scalable, hard to maintain, violating SOLID principles,
repository pattern is also not implemented correctly. BookingRepository class is also overloaded. I have tried and implemented as much as I can in the given time.

I have created Interfaces for Booking repository and Base repository, I have break BookingRepo class into further notifications class and created interface for
notification class as well.

I have bound these classes respectively and injected it in BookingController instead of injecting Repository class directly so that my low level code is
independent of high level code.

There are several vulnerabilities in the code, lots of unnecessary ifs and else conditions etc. It was impossible to refactor them all, I have refactored a few of them.

Also, I have implemented Custom notification channel for push notification instead of creating a method, we can utilize Laravel built in methods for that.
however I was unable to implement all the notifications in the given time, so I only implemented single method for push notification, Custom Channels can be
created for Mailer and SMS notification as well, and we can utilize them by adding Notifiable trait in Job's Model.

I have also written tests, You can find it in tests/Unit directory.

Thanks.




