<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class CompilerController extends Controller
{
    public function index()
    {
        return view('compiler');
    }

    public function compile(Request $request)
    {
        $code = $request->input('code');
        $language = $request->input('language');
        $output = '';

        switch ($language) {
            case 'python':
                $output = $this->executeCode(['python', '-c', $code]);
                break;
            case 'java':
                // Java kodu çalıştırmak için Main.java dosyası oluşturur.
                file_put_contents('Main.java', $code);
                $output = $this->executeCode(['javac', 'Main.java']);
                if ($output === '') {
                    $output = $this->executeCode(['java', 'Main']);
                }
                break;
            case 'cpp':
                // C++ kodu çalıştırmak için main.cpp dosyası oluşturur.
                file_put_contents('main.cpp', $code);
                $output = $this->executeCode(['g++', '-o', 'a.out', 'main.cpp']);
                if ($output === '') {
                    $output = $this->executeCode(['./a.out']);
                }
                break;
            case 'php':
                $output = $this->executeCode(['php', '-r', $code]);
                break;
            case 'javascript':
                $output = $this->executeCode(['node', '-e', $code]);
                break;
            default:
                $output = "Geçersiz dil seçimi!";
        }

        return response()->json(['output' => $output]);
    }

    private function executeCode($command)
    {
        $process = new Process($command);
        $process->run();

        if (!$process->isSuccessful()) {
            return $process->getErrorOutput();
        }

        return $process->getOutput();
    }
}
