$loop = new EventLoop();

// Add timer event
$loop->addEvent('timer', function ($event) {
    echo "Timer event: $event" . PHP_EOL;
});

// Add stream listener
$stream = fopen('example.txt', 'r');
$loop->addStreamListener($stream, function ($stream) {
    $data = fread($stream, 1024);
    echo "Read data from stream: $data" . PHP_EOL;
    fclose($stream);
});

// Run the event loop
$loop->run();