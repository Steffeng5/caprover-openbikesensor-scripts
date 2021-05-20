<?php

require 'utils.php';

use PHPUnit\Framework\TestCase;

class ApiTest extends TestCase {
  const OBS_UPLOAD_DIR = __DIR__.'/../../tmp/uploads';
  const HOST = 'localhost';
  const PORT = 5000;

  protected static $process;
  protected static $client;

  public static function setUpBeforeClass(): void {
    self::$client = Utils::client(self::HOST, self::PORT);

    self::$process = Utils::startServer(
      self::HOST, self::PORT, self::OBS_UPLOAD_DIR
    );
  }

  public static function tearDownAfterClass(): void {
    self::$process->stop();
  }

  protected function setUp(): void {
    Utils::clearDir(self::OBS_UPLOAD_DIR);
  }

  public function testMissingUserAgent() {
    $response = self::$client->post('/api.php', [
      'headers' => ['User-Agent' => null]
    ]);

    $this->assertEquals(401, $response->getStatusCode());
  }

  public function testInvalidUserAgent() {
    $response = self::$client->post('/api.php', [
      'headers' => ['User-Agent' => 'Invalid Name']
    ]);

    $this->assertEquals(401, $response->getStatusCode());
  }

  public function testSuccessfullUpload() {
    $response = self::$client->post('/api.php', [
      'headers' => ['User-Agent' => 'OBS/SOME_FIRMWARE_VERSION',
                    'Authorization' => 'OBSUserId SOME_API_KEY'],
      'multipart' => [
          [
              'name'     => 'body',
              'contents' => 'SOME_CONTENT',
              'filename' => '2021-04-08T12.25.11-205e.obsdata.csv'
          ]
      ]
    ]);

    $this->assertEquals(201, $response->getStatusCode());
    $this->assertFileExists(
      self::OBS_UPLOAD_DIR.'/SOME_API_KEY/2021-04-08T12.25.11-205e.obsdata.csv'
    );
  }
}

?>
