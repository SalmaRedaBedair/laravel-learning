<?php

namespace App\Http\Controllers;

use App\Models\DeviceShutDownError;
use Illuminate\Http\Request;

class ExceptionController extends Controller
{
    private $logger;

    public function __construct($logger)
    {
        $this->logger = $logger;
    }

    public function sendShutDown()
    {
        try {
            return $this->tryToShutDown();
        } catch (DeviceShutDownError $e) {
            $this->logger->log($e);
        }
    }

    private function tryToShutDown()
    {
        $handle = $this->getHandle(1);
        return true;
    }

    private function getHandle($id)
    {
        // ...
        throw new DeviceShutDownError("Invalid handle for: " . $id);
        // ...
    }

    // Other methods omitted for brevity
}
