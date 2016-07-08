Worker
======

This project is a example core process work. It's devided into two main components:

* Producer
    * Listing sources from applications.
    * Sending retrieved sources to rabbitmq
    * Sending retrieved sources to rabbitmq

* Consumer
    * Creating mutliple process to listen to rabbit mq.
    * Retrieving sources from rabbit mq for each process.
    * Fetching items for each source.
    * Sending items to the target application.

Installation
------------

Use [Composer](https://getcomposer.org/) to install this bundle:

    composer install

Usage
-----

start producer to fetch sources and send them to rabbitmq

    producer:run

Start consumer to fetch sources from RabbitMQ

    consumer:run

License
-------

This bundle is under the MIT license. See the complete license:

[LICENSE] (./LICENSE)
