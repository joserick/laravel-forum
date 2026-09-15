<?php

namespace TeamTeaTime\Forum\Support\Content;

use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

class Sanitizer
{
    private HtmlSanitizer $sanitizer;

    public function __construct(array $config = [])
    {
        $this->sanitizer = new HtmlSanitizer($this->buildConfig($config));
    }

    public function sanitize(string $content): string
    {
        return $this->sanitizer->sanitize($content);
    }

    private function buildConfig(array $config): HtmlSanitizerConfig
    {
        $sanitizerConfig = new HtmlSanitizerConfig;

        foreach ($config['allowed_elements'] ?? [] as $element) {
            $sanitizerConfig = $sanitizerConfig->allowElement($element);
        }

        foreach ($config['allowed_attributes'] ?? [] as $element => $attributes) {
            foreach ($attributes as $attribute) {
                $sanitizerConfig = $sanitizerConfig->allowAttribute($attribute, $element);
            }
        }

        return $sanitizerConfig
            ->allowLinkSchemes($config['allowed_link_schemes'] ?? ['http', 'https', 'mailto'])
            ->allowMediaSchemes($config['allowed_media_schemes'] ?? ['http', 'https']);
    }
}
