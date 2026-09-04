<?php

namespace App\Services;

use App\Models\TermsAndCondition;

class TermsService
{
    /**
     * Get the current active Terms & Conditions model.
     */
    public static function getActiveTerms(): TermsAndCondition
    {
        return TermsAndCondition::current();
    }

    /**
     * Create an immutable snapshot array of the current Terms & Conditions
     * for quotation and contract generation.
     */
    public static function getQuotationSnapshot(?TermsAndCondition $terms = null): array
    {
        $terms ??= static::getActiveTerms();

        return [
            'terms_version'             => $terms->version,
            'captured_at'               => now()->toIso8601String(),
            'advance_percentage'        => $terms->advance_percentage,
            'balance_percentage'        => $terms->balance_percentage,
            'balance_due'               => $terms->balance_due,
            'advance_refundable'        => $terms->advance_refundable,
            'cancellation_policy'       => $terms->cancellation_policy,
            'estimated_delivery_period' => $terms->estimated_delivery_period,
            'delivery_policy'           => $terms->delivery_policy,
            'extra_pendrive'            => $terms->extra_pendrive,
            'extended_coverage_after'   => $terms->extended_coverage_after,
            'late_night_transportation' => $terms->late_night_transportation,
            'hotel_coverage'            => $terms->hotel_coverage,
            'home_coverage'             => $terms->home_coverage,
            'extra_hours'               => $terms->extra_hours,
            'content'                   => $terms->content,
        ];
    }

    /**
     * Render the legal terms into formatted HTML suitable for client viewing,
     * modal preview, or inclusion in a quotation / proposal.
     */
    public static function renderHtml(?TermsAndCondition $terms = null): string
    {
        $terms ??= static::getActiveTerms();

        $content = e($terms->content ?? '');

        // Convert bullet points and headers into structured markup
        $lines = explode("\n", $content);
        $html = '<div class="terms-and-conditions-document" style="font-family: inherit; color: inherit; line-height: 1.6;">';

        foreach ($lines as $line) {
            $trimmed = trim($line);

            if (empty($trimmed)) {
                $html .= '<div style="height: 10px;"></div>';
            } elseif (preg_match('/^[0-9]+\.\s+(.+)$/', $trimmed, $matches)) {
                $html .= '<h4 style="font-family: Georgia, serif; font-size: 16px; font-weight: 600; color: #c4a472; margin-top: 18px; margin-bottom: 6px; letter-spacing: 0.5px; text-transform: uppercase;">' . $matches[0] . '</h4>';
            } elseif (str_starts_with($trimmed, '•')) {
                $html .= '<div style="margin-left: 12px; margin-bottom: 6px; font-size: 13px; opacity: 0.9;">' . $trimmed . '</div>';
            } else {
                $html .= '<p style="margin-bottom: 6px; font-size: 13px; opacity: 0.85;">' . $trimmed . '</p>';
            }
        }

        $html .= '</div>';

        return $html;
    }
}
