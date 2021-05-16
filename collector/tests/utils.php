<?php

use Symfony\Component\Process\Process;
use GuzzleHttp\Client;

class Utils {
  public static function client(string $host, string $port) {
    return new Client([
      'base_uri' => 'http://'.$host.':'.$port,
      'http_errors' => false,
      'headers' => ['User-Agent' => 'OBS/SOME_FIRMWARE_VERSION',
                    'Authorization' => 'OBSUserId SOME_API_KEY']
    ]);
  }

  public static function clearDir(string $dir) {
    $files = new RecursiveIteratorIterator(
      new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
      RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ( $files as $file ) {
        $file->isDir() ?  rmdir($file) : unlink($file);
    }
  }

  public static function startServer(
    string $host, string $port, string $upload_dir)
  {
    $process = new Process(
      [
        '/usr/bin/php',
        '-S', $host.':'.$port,
        '-t', realpath(__DIR__.'/../lib')
      ],
      null,
      [ 'OBS_UPLOAD_DIR' => $upload_dir ]
    );

    $process->disableOutput();
    $process->start();
    sleep(1);

    return $process;
  }
}

?>
