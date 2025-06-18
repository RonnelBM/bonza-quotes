<?php
namespace BonzaQuotes\Tests;

use BonzaQuotes\Database\QuoteRepository;
use PHPUnit\Framework\TestCase;

class QuoteRepositoryTest extends TestCase {
    protected static $repo;

    public static function setUpBeforeClass(): void {
        self::$repo = new QuoteRepository('test_quotes');
    }

    public function testInstallMethodExists() {
        $this->assertTrue( method_exists( self::$repo, 'install' ) );
    }

    public function testInsertMethodExists() {
        $this->assertTrue( method_exists( self::$repo, 'insert' ) );
    }
}