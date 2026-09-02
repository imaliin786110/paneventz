<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TermsAndCondition extends Model
{
    protected $table = 'terms_and_conditions';

    protected $fillable = [
        'version',
        'advance_percentage',
        'balance_percentage',
        'balance_due',
        'advance_refundable',
        'cancellation_policy',
        'estimated_delivery_period',
        'delivery_policy',
        'extra_pendrive',
        'extended_coverage_after',
        'late_night_transportation',
        'hotel_coverage',
        'home_coverage',
        'extra_hours',
        'content',
    ];

    protected $casts = [
        'version'            => 'integer',
        'advance_percentage' => 'integer',
        'balance_percentage' => 'integer',
        'advance_refundable' => 'boolean',
    ];

    /**
     * Get the active single Terms & Conditions record.
     * Automatically seeds default terms if none exists.
     */
    public static function current(): self
    {
        $record = static::first();

        if (! $record) {
            $record = static::create(static::defaultAttributes());
        }

        return $record;
    }

    /**
     * Default terms, configuration values, and legal clauses.
     */
    public static function defaultAttributes(): array
    {
        return [
            'version'                   => 1,
            'advance_percentage'        => 50,
            'balance_percentage'        => 50,
            'balance_due'               => 'Event Date',
            'advance_refundable'        => false,
            'cancellation_policy'       => "The 50% advance payment is non-refundable if the client cancels the wedding/event after the booking has been confirmed. The advance payment is used to reserve the date and cover planning and scheduling resources. Any date change is strictly subject to team availability and mutual written agreement.",
            'estimated_delivery_period' => '1–2 months',
            'delivery_policy'           => "Final edited wedding photographs and cinematic films normally take 1 to 2 months following the event date. Delivery timelines may vary depending on the size of the celebration, total volume of media captured, bespoke editing requirements, and timely client selections.",
            'extra_pendrive'            => 'Chargeable',
            'extended_coverage_after'   => '12:30 AM',
            'late_night_transportation' => 'Chargeable',
            'hotel_coverage'            => 'Additional',
            'home_coverage'             => 'Additional',
            'extra_hours'               => 'Chargeable',
            'content'                   => static::defaultContentText(),
        ];
    }

    /**
     * Complete default legal text covering all 8 clauses.
     */
    public static function defaultContentText(): string
    {
        return <<<TEXT
1. BOOKING & PAYMENT
• Advance Payment: A 50% advance payment is required to confirm and secure your booking.
• Balance Payment: The remaining 50% balance payment is due on the event/wedding date.
• Confirmation: The booking and event date reservation are officially confirmed only upon receipt of the advance payment.

2. CANCELLATION & REFUND
• Non-Refundable Advance: The 50% advance payment is strictly non-refundable should the client cancel the wedding or event after booking confirmation.
• Reservation of Date: The advance payment guarantees reservation of the date and covers all pre-production planning and scheduling.
• Date Rescheduling: Any change of date is subject to calendar availability and mutual written agreement.

3. PHOTO & VIDEO DELIVERY
• Delivery Timeline: Final edited photographs and cinematic wedding films are typically delivered within 1 to 2 months after the event date.
• Variation Factors: Delivery schedules may vary based on event scale, total quantity of photos/videos, artistic post-production requirements, and client selection turnaround.

4. PENDRIVE / PHYSICAL STORAGE COPIES
• Package Inclusions: The pendrive/USB drive quantity specified in the agreed package will be provided.
• Additional Units: Any additional pendrives or physical storage media requested by the client are chargeable separately.
• Extra Physical Deliverables: Extra albums, photo prints, or bespoke packaging requests will incur additional fees.

5. EXTENDED & LATE-NIGHT COVERAGE
• Package Hours: Standard coverage will strictly adhere to the agreed schedule and package duration.
• Late-Night Threshold: If event coverage extends beyond 12:30 AM, additional hourly charges will apply.
• Transportation: If regular public transportation is unavailable due to late-night conclusion and the crew requires alternate transport, applicable transportation expenses will be payable by the client.

6. HOTEL & RESIDENCE COVERAGE
• Outside Agreed Venue: Photography and videography at hotels, private residences, or auxiliary locations outside the agreed primary venue schedule may incur additional charges.
• Travel & Accommodation: Any additional travel, parking fees, entry permits, or accommodation expenses outside the contracted scope are payable by the client.

7. ADDITIONAL HOURS & BESPOKE SERVICES
• Overtime Hours: Any shooting hours beyond the contracted package duration are chargeable at standard overtime rates.
• Supplementary Services: Extra crew members (photographers, cinematographers, drone pilots), additional albums, raw footage drives, or specialized equipment not included in the original quotation are chargeable separately.

8. FINAL AGREEMENT & ACCEPTANCE
• Scope of Work: All deliverables and services are provided in accordance with the package and quotation agreed upon at booking.
• Scope Alterations: Any subsequent changes or additions requested after confirmation may result in revised quotations and charges.
• Confirmation: Payment of the booking advance constitutes client acknowledgment, acceptance, and agreement to all terms and conditions outlined herein.
TEXT;
    }
}
