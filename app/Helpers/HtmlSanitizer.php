<?php

namespace App\Helpers;

class HtmlSanitizer
{
    /**
     * Sanitize HTML content while preserving safe tags and Minecraft formatting
     *
     * @param string|null $content
     * @return string
     */
    public static function sanitize(?string $content): string
    {
        if (empty($content)) {
            return '';
        }

        // Allow safe HTML tags commonly used in MOTD and messages
        $allowedTags = [
            'b', 'i', 'u', 'strong', 'em', 'span', 'div', 'p', 'br',
            'font', 'color', 'style' // For Minecraft formatting
        ];

        // Allow safe attributes
        $allowedAttributes = [
            'class', 'style', 'color', 'size', 'face' // For styling and Minecraft formatting
        ];

        // Strip dangerous tags and attributes
        $content = strip_tags($content, '<' . implode('><', $allowedTags) . '>');

        // Remove dangerous attributes like onclick, onload, etc.
        $content = preg_replace('/\s*on\w+\s*=\s*["\'][^"\'>]*["\']/', '', $content);
        $content = preg_replace('/\s*javascript\s*:/', '', $content);
        $content = preg_replace('/\s*vbscript\s*:/', '', $content);
        $content = preg_replace('/\s*data\s*:/', '', $content);

        // Remove script and style tags completely
        $content = preg_replace('/<script[^>]*>.*?<\/script>/is', '', $content);
        $content = preg_replace('/<style[^>]*>.*?<\/style>/is', '', $content);

        return $content;
    }

    /**
     * Sanitize content for display in forms (more restrictive)
     *
     * @param string|null $content
     * @return string
     */
    public static function sanitizeForForm(?string $content): string
    {
        if (empty($content)) {
            return '';
        }

        // For form display, only allow basic formatting
        $allowedTags = ['b', 'i', 'u', 'strong', 'em', 'br'];
        $content = strip_tags($content, '<' . implode('><', $allowedTags) . '>');

        // Remove all attributes for form display
        $content = preg_replace('/\s+[a-zA-Z-]+\s*=\s*["\'][^"\'>]*["\']/', '', $content);

        return $content;
    }

    /**
     * Convert Minecraft color codes to safe HTML
     *
     * @param string|null $content
     * @return string
     */
    public static function minecraftToHtml(?string $content): string
    {
        if (empty($content)) {
            return '';
        }

        // Minecraft color code mapping
        $colorMap = [
            '§0' => '<span style="color: #000000">',
            '§1' => '<span style="color: #0000AA">',
            '§2' => '<span style="color: #00AA00">',
            '§3' => '<span style="color: #00AAAA">',
            '§4' => '<span style="color: #AA0000">',
            '§5' => '<span style="color: #AA00AA">',
            '§6' => '<span style="color: #FFAA00">',
            '§7' => '<span style="color: #AAAAAA">',
            '§8' => '<span style="color: #555555">',
            '§9' => '<span style="color: #5555FF">',
            '§a' => '<span style="color: #55FF55">',
            '§b' => '<span style="color: #55FFFF">',
            '§c' => '<span style="color: #FF5555">',
            '§d' => '<span style="color: #FF55FF">',
            '§e' => '<span style="color: #FFFF55">',
            '§f' => '<span style="color: #FFFFFF">',
            '§l' => '<strong>',
            '§o' => '<em>',
            '§n' => '<u>',
            '§r' => '</span></strong></em></u>'
        ];

        // Replace Minecraft codes with HTML
        foreach ($colorMap as $code => $html) {
            $content = str_replace($code, $html, $content);
        }

        // Also handle & codes
        foreach ($colorMap as $code => $html) {
            $ampCode = str_replace('§', '&', $code);
            $content = str_replace($ampCode, $html, $content);
        }

        return $content;
    }
}