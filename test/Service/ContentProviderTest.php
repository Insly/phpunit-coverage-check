<?php

declare(strict_types=1);

namespace PhpunitCoverageCheck\Service;

use PHPUnit\Framework\TestCase;
use phpmock\mockery\PHPMockery;
use Mockery;

final class ContentProviderTest extends TestCase
{
    private $mockFileGetContents;
    private $mockStreamGetContents;
    private $mockFgets;

    protected function setUp(): void
    {
        $this->mockFileGetContents = $this->mockFileGetContents();
        $this->mockStreamGetContents = $this->mockStreamGetContents();
        $this->mockFgets = $this->mockFgets();
    }

    protected function tearDown(): void
    {
        // Close all mocks to avoid re-enabling errors
        Mockery::close();
    }

    /**
     * @test
     */
    public function get_content_from_file_when_file_given()
    {
        $contentProvider = new ContentProvider();

        $this->assertEquals(
            'file_get_contents() was run',
            $contentProvider->getContent('path/to/file')
        );
    }

    /**
     * @test
     */
    public function get_content_from_stream_when_file_not_given()
    {
        $contentProvider = new ContentProvider();

        $this->assertEquals(
            'stream_get_contents() was run',
            $contentProvider->getContent()
        );
    }

    /**
     * @test
     */
    public function output_string_when_verbose()
    {
        $contentProvider = new ContentProvider();

        $this->expectOutputString('test');

        $contentProvider->getContent(null, true);
    }

    /**
     * Mocks file_get_contents function in the ContentProvider namespace.
     */
    private function mockFileGetContents()
    {
        return PHPMockery::mock('PhpunitCoverageCheck\Service', 'file_get_contents')
            ->andReturn('file_get_contents() was run');
    }

    /**
     * Mocks stream_get_contents function in the ContentProvider namespace.
     */
    private function mockStreamGetContents()
    {
        return PHPMockery::mock('PhpunitCoverageCheck\Service', 'stream_get_contents')
            ->andReturn('stream_get_contents() was run');
    }

    /**
     * Mocks fgets function in the ContentProvider namespace to simulate line-by-line output.
     */
    private function mockFgets()
    {
        return PHPMockery::mock('PhpunitCoverageCheck\Service', 'fgets')
            ->andReturnUsing(function () {
                static $line = 0;
                $lines = ["test", false];
                return $lines[$line++];
            });
    }
}