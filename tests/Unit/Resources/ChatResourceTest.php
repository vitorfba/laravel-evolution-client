<?php

// tests/Unit/Resources/ChatResourceTest.php

namespace Vitorfba\LaravelEvolutionClient\Tests\Unit\Resources;

use PHPUnit\Framework\TestCase;
use Vitorfba\LaravelEvolutionClient\Resources\Chat;
use Vitorfba\LaravelEvolutionClient\Services\EvolutionService;

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
    public function it_can_get_all_chats_via_find_chats()
    {
        $service = $this->makeService();
        $service->expects($this->once())
            ->method('post')
            ->with('/chat/findChats/test-instance', [])
            ->willReturn(['status' => 'success']);

        $result = (new Chat($service, 'test-instance'))->all();

        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_find_chat_via_find_chats()
    {
        $service = $this->makeService();
        $service->expects($this->once())
            ->method('post')
            ->with('/chat/findChats/test-instance', [
                'where' => ['remoteJid' => '5511999999999@s.whatsapp.net'],
            ])
            ->willReturn(['status' => 'success']);

        $result = (new Chat($service, 'test-instance'))->find('5511999999999');

        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_get_chat_messages_via_find_messages()
    {
        $service = $this->makeService();
        $service->expects($this->once())
            ->method('post')
            ->with('/chat/findMessages/test-instance', [
                'where' => ['key' => ['remoteJid' => '5511999999999@s.whatsapp.net']],
                'limit' => 20,
            ])
            ->willReturn(['status' => 'success']);

        $result = (new Chat($service, 'test-instance'))->messages('5511999999999', 20);

        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_delete_message_for_everyone()
    {
        $service = $this->makeService();
        $service->expects($this->once())
            ->method('deleteJson')
            ->with('/chat/deleteMessageForEveryone/test-instance', [
                'id' => 'ABC123',
                'remoteJid' => '5511999999999@s.whatsapp.net',
                'fromMe' => true,
            ])
            ->willReturn(['status' => 'success']);

        $result = (new Chat($service, 'test-instance'))
            ->deleteMessageForEveryone('ABC123', '5511999999999@s.whatsapp.net', true);

        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_sends_participant_when_deleting_group_message()
    {
        $service = $this->makeService();
        $service->expects($this->once())
            ->method('deleteJson')
            ->with('/chat/deleteMessageForEveryone/test-instance', [
                'id' => 'ABC123',
                'remoteJid' => '123456789@g.us',
                'fromMe' => false,
                'participant' => '5511999999999@s.whatsapp.net',
            ])
            ->willReturn(['status' => 'success']);

        (new Chat($service, 'test-instance'))
            ->deleteMessageForEveryone('ABC123', '123456789@g.us', false, '5511999999999@s.whatsapp.net');
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
        $service = $this->makeService();
        $service->expects($this->once())
            ->method('post')
            ->with('/chat/findChats/test-instance', ['where' => ['id' => 'foo']])
            ->willReturn(['status' => 'success']);

        $result = (new Chat($service, 'test-instance'))->findChats(['where' => ['id' => 'foo']]);

        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_find_messages()
    {
        $service = $this->makeService();
        $service->expects($this->once())
            ->method('post')
            ->with('/chat/findMessages/test-instance', ['where' => ['id' => 'foo']])
            ->willReturn(['status' => 'success']);

        $result = (new Chat($service, 'test-instance'))->findMessages(['where' => ['id' => 'foo']]);

        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_find_contacts()
    {
        $service = $this->makeService();
        $service->expects($this->once())
            ->method('post')
            ->with('/chat/findContacts/test-instance', ['where' => ['id' => 'foo']])
            ->willReturn(['status' => 'success']);

        $result = (new Chat($service, 'test-instance'))->findContacts(['where' => ['id' => 'foo']]);

        $this->assertEquals('success', $result['status']);
    }

    /** @test */
    public function it_can_verify_whatsapp_numbers()
    {
        $service = $this->makeService();
        $service->expects($this->once())
            ->method('post')
            ->with('/chat/whatsappNumbers/test-instance', ['numbers' => ['5511999999999']])
            ->willReturn(['status' => 'success']);

        $result = (new Chat($service, 'test-instance'))->whatsappNumbers(['5511999999999']);

        $this->assertEquals('success', $result['status']);
    }

    /**
     * @return EvolutionService&\PHPUnit\Framework\MockObject\MockObject
     */
    protected function makeService()
    {
        return $this->getMockBuilder(EvolutionService::class)
            ->disableOriginalConstructor()
            ->getMock();
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
