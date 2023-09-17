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
            Console::Write("Порт: ");
            $port = intval(Console::ReadLine());

            if ($port == 0)
            {
                $this->SetHost();
                continue;
            }

            if ($port < 0 || $port > 65535)
            {
                Console::WriteLine("Порт '" . $port . "' некорректный", ForegroundColors::BLACK, BackgroundColors::RED);
                continue;
            }

            $connection = @fsockopen($this->host, $port, $error_code, $error_message, 5);

            if (is_resource($connection))
            {
                Console::WriteLine("Порт '" . $port . "' на хосте '" . $this->host . "' ОТКРЫТ.", ForegroundColors::GREEN);
                fclose($connection);
            }

            else
            {
                Console::WriteLine("Порт '" . $port . "' на хосте '" . $this->host . "' ЗАКРЫТ.", ForegroundColors::RED);
            }
        }
    }

    public function GetMyPublicIp() : string
    {
        return @file_get_contents("http://ipecho.net/plain");
    }

    private function SetHost() : void
    {
        Console::WriteLine("\n\n * * Оставьте пустым, если хотите проверить порт в своей сети", ForegroundColors::WHITE);
        Console::Write("Хост: ");
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