/**
* Implement event loop in php .
 */
class EventLoop
{
    private $eventQueue = [];
    private $streamListeners = [];
    
    // Add events  to the event queue.
    public function addEvent($event, $callback)
    {
        $this->eventQueue[] = [
            'event' => $event,
            'callback' => $callback
        ];
    }

    // Add a stream listner to the 
    public function addStreamListener($stream, $callback)
    {
        $this->streamListeners[(int)$stream] = $callback;
    }

    public function run()
    {
        while (!empty($this->eventQueue) || !empty($this->streamListeners)) {
            $event = array_shift($this->eventQueue);

            if ($event) {
                $callback = $event['callback'];
                $callback($event['event']);
            }

            // Check for stream listeners
            $read = $this->streamListeners;
            $write = $except = null;
            $numStreams = stream_select($read, $write, $except, 0);

            if ($numStreams > 0) {
                foreach ($read as $stream) {
                    $callback = $this->streamListeners[(int)$stream];
                    $callback($stream);
                    unset($this->streamListeners[(int)$stream]);
                }
            }
        }
    }
}