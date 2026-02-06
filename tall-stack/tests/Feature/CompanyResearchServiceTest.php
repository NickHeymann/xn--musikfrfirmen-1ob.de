<?php

namespace Tests\Feature;

use App\Services\CompanyResearchService;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CompanyResearchServiceTest extends TestCase
{
    #[Test]
    public function returns_null_when_api_keys_are_empty(): void
    {
        config(['services.tavily.api_key' => '']);
        config(['services.groq.api_key' => '']);

        $service = new CompanyResearchService;
        $result = $service->research('SAP SE');

        $this->assertNull($result);
    }

    #[Test]
    public function returns_null_for_empty_company_name(): void
    {
        config(['services.tavily.api_key' => 'test-key']);
        config(['services.groq.api_key' => 'test-key']);

        $service = new CompanyResearchService;
        $result = $service->research('');

        $this->assertNull($result);
    }

    #[Test]
    public function returns_null_for_whitespace_only_company_name(): void
    {
        config(['services.tavily.api_key' => 'test-key']);
        config(['services.groq.api_key' => 'test-key']);

        $service = new CompanyResearchService;
        $result = $service->research('   ');

        $this->assertNull($result);
    }

    #[Test]
    public function returns_structured_data_on_success(): void
    {
        config(['services.tavily.api_key' => 'test-key']);
        config(['services.groq.api_key' => 'test-key']);
        config(['services.tavily.timeout' => 8]);

        Http::fake([
            'api.tavily.com/*' => Http::response([
                'results' => [
                    ['title' => 'SAP SE - IT Company', 'url' => 'https://sap.com', 'content' => 'SAP is a German software company'],
                ],
            ]),
            'api.groq.com/*' => Http::response([
                'choices' => [
                    ['message' => ['content' => '{"industry": "Software", "employee_count": "107000", "website": "https://sap.com", "location": "Walldorf", "description": "SAP is a global enterprise software company.", "recent_news": [], "past_events": [], "sources": ["https://sap.com"]}']],
                ],
            ]),
        ]);

        $service = new CompanyResearchService;
        $result = $service->research('SAP SE');

        $this->assertNotNull($result);
        $this->assertEquals('Software', $result['industry']);
        $this->assertEquals('107000', $result['employee_count']);
        $this->assertEquals('https://sap.com', $result['website']);
        $this->assertEquals('Walldorf', $result['location']);
    }

    #[Test]
    public function returns_null_when_tavily_fails(): void
    {
        config(['services.tavily.api_key' => 'test-key']);
        config(['services.groq.api_key' => 'test-key']);
        config(['services.tavily.timeout' => 8]);

        Http::fake([
            'api.tavily.com/*' => Http::response([], 500),
        ]);

        $service = new CompanyResearchService;
        $result = $service->research('Unknown GmbH');

        $this->assertNull($result);
    }

    #[Test]
    public function returns_null_when_groq_fails(): void
    {
        config(['services.tavily.api_key' => 'test-key']);
        config(['services.groq.api_key' => 'test-key']);
        config(['services.tavily.timeout' => 8]);

        Http::fake([
            'api.tavily.com/*' => Http::response([
                'results' => [
                    ['title' => 'Test', 'url' => 'https://test.com', 'content' => 'Some content'],
                ],
            ]),
            'api.groq.com/*' => Http::response([], 500),
        ]);

        $service = new CompanyResearchService;
        $result = $service->research('Test GmbH');

        $this->assertNull($result);
    }

    #[Test]
    public function returns_null_on_timeout(): void
    {
        config(['services.tavily.api_key' => 'test-key']);
        config(['services.groq.api_key' => 'test-key']);
        config(['services.tavily.timeout' => 1]);

        Http::fake([
            'api.tavily.com/*' => function () {
                throw new \Illuminate\Http\Client\ConnectionException('Connection timed out');
            },
        ]);

        $service = new CompanyResearchService;
        $result = $service->research('Timeout GmbH');

        $this->assertNull($result);
    }

    #[Test]
    public function handles_groq_response_with_markdown_wrapping(): void
    {
        config(['services.tavily.api_key' => 'test-key']);
        config(['services.groq.api_key' => 'test-key']);
        config(['services.tavily.timeout' => 8]);

        Http::fake([
            'api.tavily.com/*' => Http::response([
                'results' => [
                    ['title' => 'Test', 'url' => 'https://test.com', 'content' => 'Content'],
                ],
            ]),
            'api.groq.com/*' => Http::response([
                'choices' => [
                    ['message' => ['content' => "```json\n{\"industry\": \"IT\", \"employee_count\": null, \"website\": null, \"location\": \"Berlin\", \"description\": null, \"recent_news\": [], \"past_events\": [], \"sources\": []}\n```"]],
                ],
            ]),
        ]);

        $service = new CompanyResearchService;
        $result = $service->research('Berlin IT GmbH');

        $this->assertNotNull($result);
        $this->assertEquals('IT', $result['industry']);
        $this->assertEquals('Berlin', $result['location']);
    }
}
