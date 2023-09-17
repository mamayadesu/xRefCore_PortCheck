<?php
declare(ticks = 1);

namespace Program;

use Data\String\BackgroundColors;
use Data\String\ForegroundColors;
use IO\Console;
use Application\Application;

class Main
{
    private string $host;

    public function __construct(array $args)
    {
        $this->SetHost();
        $error_code = 0;
        $error_message = null;
        while (true)
        {
            Console::Write("Port: ");
            $port = intval(Console::ReadLine());

            if ($port == 0)
            {
                $this->SetHost();
                continue;
            }

            if ($port < 0 || $port > 65535)
            {
                Console::WriteLine("Incorrect port '" . $port . "'", ForegroundColors::BLACK, BackgroundColors::RED);
                continue;
            }

            $connection = @fsockopen($this->host, $port, $error_code, $error_message, 5);

            if (is_resource($connection))
            {
                Console::WriteLine("Port '" . $port . "' on host '" . $this->host . "' is OPEN", ForegroundColors::GREEN);
                fclose($connection);
            }

            else
            {
                Console::WriteLine("Port '" . $port . "' on host '" . $this->host . "' is CLOSED.", ForegroundColors::RED);
            }
        }
    }

    public function GetMyPublicIp() : string
    {
        return @file_get_contents("http://ipecho.net/plain");
    }

    private function SetHost() : void
    {
        Console::WriteLine("\n\n * * Leave blank to check port in your network", ForegroundColors::WHITE);
        Console::Write("Host: ");
        $this->host = Console::ReadLine();

        if ($this->host == "")
        {
            $this->host = $this->GetMyPublicIp();
            Console::MoveCursorUp();
            Console::MoveCursorRight(6);
            Console::Write($this->host);
            Console::MoveCursorDown();
            Console::MoveCursorLeft(6 + strlen($this->host));
        }
    }
}