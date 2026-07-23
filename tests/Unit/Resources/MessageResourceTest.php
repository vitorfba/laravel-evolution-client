<?php

// tests/Unit/Resources/MessageResourceTest.php

namespace Vitorfba\LaravelEvolutionClient\Tests\Unit\Resources;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Vitorfba\LaravelEvolutionClient\Models\Button;
use Vitorfba\LaravelEvolutionClient\Models\ListRow;
use Vitorfba\LaravelEvolutionClient\Models\ListSection;
use Vitorfba\LaravelEvolutionClient\Resources\Message;
use Vitorfba\LaravelEvolutionClient\Services\EvolutionService;

class MessageResourceTest extends TestCase
{
    /**
     * @var Message
     */
    protected $messageResource;

    /**
     * @var MockHandler
     */
    protected $mockHandler;

    /**
     * @var EvolutionService
     */
    protected $service;

    /** @test */
    public function it_can_send_text_message()
    {
        $result = $this->messageResource->sendText('5511999999999', 'Test message');

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
        $this->assertArrayHasKey('key', $result);
    }

    /** @test */
    public function it_validates_phone_number_when_sending_text()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->messageResource->sendText('', 'Test message');
    }

    /** @test */
    public function it_validates_message_text_when_sending_text()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->messageResource->sendText('5511999999999', '');
    }

    /** @test */
    public function it_formats_phone_number_correctly()
    {
        // Create a subclass that makes the protected method public for testing
        $messageResource = new class($this->service, 'test-instance') extends Message
        {
            public function publicFormatPhoneNumber(string $phoneNumber): string
            {
                return $this->formatPhoneNumber($phoneNumber);
            }
        };

        // Test with regular number
        $this->assertEquals('5511999999999@c.us', $messageResource->publicFormatPhoneNumber('5511999999999'));

        // Test with formatted number
        $this->assertEquals('5511999999999@c.us', $messageResource->publicFormatPhoneNumber('+55 (11) 99999-9999'));
    }

    /** @test */
    public function it_can_send_location_message()
    {
        $result = $this->messageResource->sendLocation(
            '5511999999999',
            -23.5505,
            -46.6333,
            'São Paulo',
            'Paulista Avenue, 1000'
        );

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_send_media_message()
    {
        $result = $this->messageResource->sendMedia(
            '5511999999999',
            'image',
            'image/png',
            'My caption',
            'https://example.com/image.png',
            'image.png'
        );

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_sends_document_through_v2_send_media_endpoint()
    {
        $service = $this->getMockBuilder(EvolutionService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['post'])
            ->getMock();

        $service->expects($this->once())
            ->method('post')
            ->with(
                '/message/sendMedia/test-instance',
                $this->callback(function (array $payload): bool {
                    return ($payload['mediatype'] ?? null) === 'document'
                        && ($payload['mimetype'] ?? null) === 'application/pdf'
                        && ($payload['fileName'] ?? null) === 'report.pdf'
                        && ($payload['media'] ?? null) === 'https://example.com/report.pdf'
                        && ($payload['caption'] ?? null) === 'Doc caption'
                        && str_starts_with((string) ($payload['number'] ?? ''), '5511999999999');
                })
            )
            ->willReturn(['status' => 'success']);

        $resource = new Message($service, 'test-instance');
        $result = $resource->sendDocument(
            '5511999999999',
            'https://example.com/report.pdf',
            'report.pdf',
            'Doc caption'
        );

        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_sends_image_through_v2_send_media_endpoint()
    {
        $service = $this->getMockBuilder(EvolutionService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['post'])
            ->getMock();

        $service->expects($this->once())
            ->method('post')
            ->with(
                '/message/sendMedia/test-instance',
                $this->callback(function (array $payload): bool {
                    return ($payload['mediatype'] ?? null) === 'image'
                        && ($payload['mimetype'] ?? null) === 'image/png'
                        && ($payload['media'] ?? null) === 'https://example.com/photo.png'
                        && ($payload['fileName'] ?? null) === 'photo.png';
                })
            )
            ->willReturn(['status' => 'success']);

        $resource = new Message($service, 'test-instance');
        $result = $resource->sendImage(
            '5511999999999',
            'https://example.com/photo.png',
            'Image caption'
        );

        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_send_contact_message()
    {
        $result = $this->messageResource->sendContact(
            '5511999999999',
            'Contact Name',
            '5511888888888'
        );

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_send_poll_message()
    {
        $result = $this->messageResource->sendPoll(
            '5511999999999',
            'Favorite Color?',
            1,
            ['Red', 'Green', 'Blue', 'Yellow']
        );

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_send_list_message()
    {
        $rows1 = [
            new ListRow('Option 1', 'Description 1', 'opt1'),
            new ListRow('Option 2', 'Description 2', 'opt2'),
        ];

        $sections = [
            new ListSection('Section 1', $rows1),
        ];

        $result = $this->messageResource->sendList(
            '5511999999999',
            'Test List',
            'Choose an option',
            'View Options',
            'Footer text',
            $sections
        );

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_send_buttons_message()
    {
        $buttons = [
            new Button('reply', 'Yes', ['id' => 'btn-yes']),
            new Button('reply', 'No', ['id' => 'btn-no']),
        ];

        $result = $this->messageResource->sendButtons(
            '5511999999999',
            'Confirmation',
            'Do you want to proceed?',
            'Choose an option',
            $buttons
        );

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_send_audio_message()
    {
        $result = $this->messageResource->sendAudio(
            '5511999999999',
            'https://example.com/audio.mp3'
        );

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_send_sticker_message()
    {
        $result = $this->messageResource->sendSticker(
            '5511999999999',
            'https://example.com/sticker.webp'
        );

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_send_template_message()
    {
        $result = $this->messageResource->sendTemplate(
            '5511999999999',
            'hello_world',
            'en_US',
            [
                [
                    'type' => 'body',
                    'parameters' => [
                        [
                            'type' => 'text',
                            'text' => 'John Doe',
                        ],
                    ],
                ],
            ]
        );

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockHandler = new MockHandler([
            new Response(200, [], json_encode([
                'status' => 'success',
                'key' => [
                    'id' => '123456',
                    'remoteJid' => '5511999999999@c.us',
                    'fromMe' => true,
                ],
            ])),
        ]);

        $handlerStack = HandlerStack::create($this->mockHandler);
        $client = new Client(['handler' => $handlerStack]);

        $this->service = $this->getMockBuilder(EvolutionService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->service->method('post')->willReturn([
            'status' => 'success',
            'key' => [
                'id' => '123456',
                'remoteJid' => '5511999999999@c.us',
                'fromMe' => true,
            ],
        ]);

        $this->messageResource = new Message($this->service, 'test-instance');
    }
}
