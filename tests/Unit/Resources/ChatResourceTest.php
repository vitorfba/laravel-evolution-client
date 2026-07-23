<?php

// tests/Unit/Resources/ChatResourceTest.php

namespace Vitorfba\LaravelEvolutionClient\Tests\Unit\Resources;

use Vitorfba\LaravelEvolutionClient\Resources\Chat;
use Vitorfba\LaravelEvolutionClient\Services\EvolutionService;
use PHPUnit\Framework\TestCase;

class ChatResourceTest extends TestCase
{
    /**
     * @var Chat
     */
    protected $chatResource;

    /**
     * @var EvolutionService
     */
    protected $service;

    /** @test */
    public function it_can_get_all_chats()
    {
        $result = $this->chatResource->all();

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
        $this->assertArrayHasKey('chats', $result);
    }

    /** @test */
    public function it_can_find_chat()
    {
        $result = $this->chatResource->find('5511999999999');

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_get_chat_messages()
    {
        $result = $this->chatResource->messages('5511999999999', 20);

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
        $this->assertArrayHasKey('messages', $result);
    }

    /** @test */
    public function it_can_mark_chat_as_read()
    {
        $result = $this->chatResource->markAsRead([
            [
                'remoteJid' => '5511999999999@c.us',
                'fromMe' => false,
                'id' => 'ABC123',
            ],
        ]);

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_mark_chat_unread()
    {
        $result = $this->chatResource->markChatUnread('5511999999999@c.us', [
            [
                'remoteJid' => '5511999999999@c.us',
                'fromMe' => false,
                'id' => 'ABC123',
            ],
        ]);

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_archive_chat()
    {
        $result = $this->chatResource->archive('5511999999999@c.us', [
            'remoteJid' => '5511999999999@c.us',
            'fromMe' => false,
            'id' => 'ABC123',
        ]);

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_unarchive_chat()
    {
        $result = $this->chatResource->unarchive('5511999999999@c.us', [
            'remoteJid' => '5511999999999@c.us',
            'fromMe' => false,
            'id' => 'ABC123',
        ]);

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_update_message()
    {
        $result = $this->chatResource->updateMessage('5511999999999', 'Edited text', [
            'remoteJid' => '5511999999999@c.us',
            'fromMe' => true,
            'id' => 'ABC123',
        ]);

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_fetch_profile_picture_url()
    {
        $result = $this->chatResource->fetchProfilePictureUrl('5511999999999');

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_find_status_message()
    {
        $result = $this->chatResource->findStatusMessage(['id' => 'ABC123'], 10);

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_get_base64_from_media_message()
    {
        $result = $this->chatResource->getBase64FromMediaMessage('ABC123', true);

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_update_block_status()
    {
        $result = $this->chatResource->updateBlockStatus('5511999999999', 'block');

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_validates_block_status_value()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->chatResource->updateBlockStatus('5511999999999', 'invalid');
    }

    /** @test */
    public function it_can_start_typing()
    {
        $result = $this->chatResource->startTyping('5511999999999', 2000);

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_stop_typing()
    {
        $result = $this->chatResource->stopTyping('5511999999999');

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_find_chats()
    {
        $result = $this->chatResource->findChats(['where' => ['id' => 'foo']]);

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_find_messages()
    {
        $result = $this->chatResource->findMessages(['where' => ['id' => 'foo']]);

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_find_contacts()
    {
        $result = $this->chatResource->findContacts(['where' => ['id' => 'foo']]);

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_verify_whatsapp_numbers()
    {
        $result = $this->chatResource->whatsappNumbers(['5511999999999']);

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = $this->getMockBuilder(EvolutionService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->service->method('get')->willReturn([
            'status' => 'success',
            'chats' => [
                [
                    'id' => '5511999999999@c.us',
                    'name' => 'Contact Name',
                    'unreadCount' => 0,
                ],
            ],
            'messages' => [
                [
                    'key' => [
                        'remoteJid' => '5511999999999@c.us',
                        'fromMe' => true,
                        'id' => '12345',
                    ],
                    'message' => [
                        'conversation' => 'Hello',
                    ],
                    'timestamp' => 1678901234,
                ],
            ],
        ]);

        $this->service->method('post')->willReturn([
            'status' => 'success',
            'message' => 'Operation successful',
        ]);

        $this->chatResource = new Chat($this->service, 'test-instance');
    }
}
