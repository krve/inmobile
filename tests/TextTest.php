<?php

namespace Tests;

use Krve\Inmobile\Exceptions\InvalidTextEncodingException;
use Krve\Inmobile\Text;
use PHPUnit\Framework\TestCase;

class TextTest extends TestCase
{
    /** @test */
    public function it_can_set_the_content()
    {
        $text = Text::create('Foobar');

        $this->assertEquals('Foobar', $text->getContent());
    }

    /** @test */
    public function it_can_flash_the_text()
    {
        $text = Text::create('foobar')->flash();

        $this->assertTrue($text->isFlashed());
    }

    /** @test */
    public function it_can_set_the_encoding()
    {
        $text = Text::create('foobar')->encoding(Text::ENCODING_UTF8);

        $this->assertEquals(Text::ENCODING_UTF8, $text->getEncoding());
    }

    /** @test */
    public function it_throws_an_exception_if_the_encoding_is_invalid()
    {
        $this->expectException(InvalidTextEncodingException::class);

        Text::create('foobar')->encoding('bar');
    }
}
