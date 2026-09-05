<?php

namespace App\Services;

use Symfony\Component\Process\Process;

class ClamAvScanner implements MalwareScanner
{
    public function scan(string $absolutePath): array
    {
        $process = new Process([config('media.clamav.executable', 'clamdscan'), '--fdpass', '--no-summary', $absolutePath]);
        $process->setTimeout((int) config('media.clamav.timeout', 60));

        try {
            $process->run();
        } catch (\Throwable $exception) {
            return ['status' => 'failed', 'message' => 'Scanner unavailable: '.$exception->getMessage()];
        }

        return match ($process->getExitCode()) {
            0 => ['status' => 'clean', 'message' => 'No threat detected.'],
            1 => ['status' => 'infected', 'message' => trim($process->getOutput().$process->getErrorOutput())],
            default => ['status' => 'failed', 'message' => trim($process->getOutput().$process->getErrorOutput()) ?: 'Scanner failed.'],
        };
    }
}
