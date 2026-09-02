<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Wedding Inquiry</title>
</head>
<body style="margin: 0; padding: 0; background-color: #080a0f; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #d1d5db; -webkit-font-smoothing: antialiased;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #080a0f; padding: 40px 15px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 600px; background-color: #0e1320; border: 1px solid rgba(196, 164, 114, 0.3); border-radius: 12px; overflow: hidden; box-shadow: 0 15px 40px rgba(0,0,0,0.7);">
                    
                    <!-- HEADER -->
                    <tr>
                        <td align="center" style="padding: 35px 30px 25px; background: linear-gradient(180deg, #131b2e 0%, #0e1320 100%); border-bottom: 1px solid rgba(255,255,255,0.06);">
                            <span style="font-size: 11px; letter-spacing: 4px; text-transform: uppercase; color: #c4a472; font-weight: 700; display: block; margin-bottom: 8px;">
                                PANEVENTZ STUDIO
                            </span>
                            <h1 style="margin: 0; font-family: Georgia, 'Times New Roman', serif; font-size: 28px; font-weight: normal; color: #ffffff; letter-spacing: 0.5px;">
                                New Wedding Inquiry
                            </h1>
                            <p style="margin: 8px 0 0; font-size: 13px; color: #94a3b8;">
                                Received on {{ now()->format('F d, Y \a\t h:i A') }}
                            </p>
                        </td>
                    </tr>

                    <!-- CONTENT -->
                    <tr>
                        <td style="padding: 35px 35px 25px;">
                            
                            <!-- QUICK ACTION WHATSAPP / CALL BAR -->
                            @php
                                $cleanPhone = preg_replace('/[^0-9]/', '', $enquiry->phone ?? '');
                                if (strlen($cleanPhone) === 10) {
                                    $cleanPhone = '91' . $cleanPhone;
                                }
                                $waText = rawurlencode("Hello " . ($enquiry->name ?? '') . "! Thank you for reaching out to Paneventz regarding your wedding photography.");
                            @endphp

                            <div style="background: rgba(196, 164, 114, 0.08); border: 1px solid rgba(196, 164, 114, 0.25); border-radius: 8px; padding: 18px; margin-bottom: 30px; text-align: center;">
                                <span style="font-size: 11px; letter-spacing: 1.5px; text-transform: uppercase; color: #c4a472; font-weight: bold; display: block; margin-bottom: 12px;">
                                    ⚡ Quick Client Reach-Out
                                </span>
                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center">
                                    <tr>
                                        <td style="padding-right: 10px;">
                                            <a href="https://wa.me/{{ $cleanPhone }}?text={{ $waText }}" target="_blank" style="display: inline-block; background-color: #25D366; color: #ffffff; text-decoration: none; padding: 10px 18px; border-radius: 6px; font-size: 12px; font-weight: bold; letter-spacing: 0.5px;">
                                                💬 Chat on WhatsApp
                                            </a>
                                        </td>
                                        <td>
                                            <a href="tel:{{ $enquiry->phone }}" style="display: inline-block; background-color: #1f293d; border: 1px solid rgba(255,255,255,0.2); color: #ffffff; text-decoration: none; padding: 10px 18px; border-radius: 6px; font-size: 12px; font-weight: bold; letter-spacing: 0.5px;">
                                                📞 Call {{ $enquiry->phone }}
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <!-- LEAD DETAILS TABLE -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="border-collapse: collapse;">
                                <tr>
                                    <td style="padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.06); width: 35%; color: #94a3b8; font-size: 13px; text-transform: uppercase; letter-spacing: 1px;">
                                        Client Name(s)
                                    </td>
                                    <td style="padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.06); color: #ffffff; font-size: 15px; font-weight: bold;">
                                        {{ $enquiry->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.06); color: #94a3b8; font-size: 13px; text-transform: uppercase; letter-spacing: 1px;">
                                        Phone / WhatsApp
                                    </td>
                                    <td style="padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.06); color: #c4a472; font-size: 15px; font-weight: bold;">
                                        <a href="tel:{{ $enquiry->phone }}" style="color: #c4a472; text-decoration: none;">
                                            {{ $enquiry->phone }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.06); color: #94a3b8; font-size: 13px; text-transform: uppercase; letter-spacing: 1px;">
                                        Email Address
                                    </td>
                                    <td style="padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.06); color: #ffffff; font-size: 14px;">
                                        @if($enquiry->email)
                                            <a href="mailto:{{ $enquiry->email }}" style="color: #38bdf8; text-decoration: none;">
                                                {{ $enquiry->email }}
                                            </a>
                                        @else
                                            <span style="color: #64748b;">Not provided</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.06); color: #94a3b8; font-size: 13px; text-transform: uppercase; letter-spacing: 1px;">
                                        Wedding Date
                                    </td>
                                    <td style="padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.06); color: #ffffff; font-size: 14px;">
                                        {{ $enquiry->wedding_date ? \Carbon\Carbon::parse($enquiry->wedding_date)->format('l, F d, Y') : 'Date to be decided' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.06); color: #94a3b8; font-size: 13px; text-transform: uppercase; letter-spacing: 1px;">
                                        Venue / City
                                    </td>
                                    <td style="padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.06); color: #ffffff; font-size: 14px;">
                                        {{ $enquiry->wedding_location ?: 'Not specified' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.06); color: #94a3b8; font-size: 13px; text-transform: uppercase; letter-spacing: 1px;">
                                        Requested Service
                                    </td>
                                    <td style="padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.06); color: #ffffff; font-size: 14px;">
                                        {{ $enquiry->service ?: 'Signature Wedding Photography & Cinema' }}
                                    </td>
                                </tr>
                            </table>

                            <!-- CLIENT MESSAGE -->
                            @if(!empty($enquiry->message))
                                <div style="margin-top: 25px;">
                                    <span style="font-size: 12px; letter-spacing: 1px; text-transform: uppercase; color: #94a3b8; display: block; margin-bottom: 8px;">
                                        Client Vision & Message:
                                    </span>
                                    <div style="background: rgba(0, 0, 0, 0.4); border-left: 3px solid #c4a472; padding: 15px 18px; border-radius: 0 6px 6px 0; font-size: 14px; line-height: 1.6; color: #e2e8f0;">
                                        {!! nl2br(e($enquiry->message)) !!}
                                    </div>
                                </div>
                            @endif

                            <!-- ACTION BUTTON -->
                            <div style="margin-top: 35px; text-align: center;">
                                <a href="{{ url('/admin/enquiries') }}" target="_blank" style="display: inline-block; background-color: #c4a472; color: #080a0f; text-decoration: none; padding: 13px 28px; border-radius: 6px; font-size: 13px; font-weight: bold; letter-spacing: 1.5px; text-transform: uppercase;">
                                    View in Admin Panel →
                                </a>
                            </div>

                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td align="center" style="padding: 20px 30px; background-color: #090c14; border-top: 1px solid rgba(255,255,255,0.06); font-size: 11px; color: #64748b; letter-spacing: 1px; text-transform: uppercase;">
                            Paneventz Luxury Wedding Photography · Automated Lead Notification
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
