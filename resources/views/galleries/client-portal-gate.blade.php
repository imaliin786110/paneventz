@extends('layouts.app')

@section('title', 'Client VIP Portal — Download Your Wedding Story | Paneventz')
@section('description', 'Enter your private studio passcode to unlock and download your wedding photographs and 4K cinema films.')

@section('content')
<div style="min-height: 100vh; background: #07090e; color: #fff; display: flex; flex-direction: column; position: relative; overflow: hidden;">

    <!-- AMBIENT BACKGROUND GLOW -->
    <div style="position: absolute; top: 15%; left: 50%; transform: translateX(-50%); width: 600px; height: 350px; background: radial-gradient(circle, rgba(0, 240, 255, 0.08) 0%, rgba(196, 164, 114, 0.05) 50%, transparent 70%); filter: blur(60px); pointer-events: none;"></div>

    <!-- TOP HEADER -->
    <nav style="position: relative; background: rgba(11, 15, 25, 0.95); border-bottom: 1px solid rgba(255,255,255,0.08); padding: 20px 8%; display: flex; justify-content: space-between; align-items: center; backdrop-filter: blur(20px);">
        <div class="logo">
            <a href="/" style="color: #fff; text-decoration: none; font-family: Georgia, serif; font-size: 1.4rem; letter-spacing: 1px;">Paneventz</a>
        </div>
        <div style="font-size: 11px; letter-spacing: 2px; text-transform: uppercase; color: #00f0ff; display: flex; align-items: center; gap: 8px;">
            <span style="display: inline-block; width: 8px; height: 8px; background: #00f0ff; border-radius: 50%; box-shadow: 0 0 10px #00f0ff;"></span>
            PRIVATE CLIENT VAULT
        </div>
    </nav>

    <!-- CENTER ACCESS VAULT CARD -->
    <div style="flex: 1; display: flex; align-items: center; justify-content: center; padding: 60px 20px; z-index: 2;">
        <div style="width: 100%; max-width: 520px; background: rgba(18, 25, 41, 0.92); border: 1px solid rgba(0, 240, 255, 0.25); border-radius: 16px; padding: 50px 40px; text-align: center; box-shadow: 0 25px 60px rgba(0, 0, 0, 0.8), 0 0 35px rgba(0, 240, 255, 0.1); backdrop-filter: blur(25px);">
            
            <!-- VAULT ICON -->
            <div style="width: 72px; height: 72px; margin: 0 auto 24px; border-radius: 50%; background: rgba(0, 240, 255, 0.08); border: 1px solid rgba(0, 240, 255, 0.35); display: flex; align-items: center; justify-content: center; font-size: 30px; box-shadow: 0 0 20px rgba(0, 240, 255, 0.2);">
                🔒
            </div>

            <span style="display: inline-block; font-size: 11px; letter-spacing: 3.5px; text-transform: uppercase; color: #c4a472; font-weight: 600; margin-bottom: 8px;">
                CONFIDENTIAL MEDIA DELIVERY
            </span>

            <h1 style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: clamp(30px, 6vw, 42px); font-weight: 400; color: #fff; margin: 6px 0 14px; letter-spacing: -0.01em;">
                Download Your Story
            </h1>

            <p style="font-size: 14px; color: #94a3b8; line-height: 1.7; margin-bottom: 35px;">
                Enter the private 4-digit passcode provided by Paneventz to unlock your high-resolution wedding photographs and 4K cinema films.
            </p>

            <form id="vaultForm" onsubmit="handlePortalUnlock(event)">
                <div style="margin-bottom: 22px;">
                    <label style="display: block; font-size: 11.5px; letter-spacing: 2px; text-transform: uppercase; color: #cbd5e1; margin-bottom: 10px; font-weight: 500;">
                        Enter Your 4-Digit Passcode:
                    </label>
                    <input 
                        type="password" 
                        id="portalPinInput" 
                        maxlength="10" 
                        placeholder="••••" 
                        style="width: 100%; text-align: center; font-size: 32px; letter-spacing: 16px; background: #080c14; border: 1px solid rgba(0, 240, 255, 0.4); color: #00f0ff; border-radius: 10px; padding: 14px; outline: none; transition: border-color 0.3s, box-shadow 0.3s;"
                        onfocus="this.style.borderColor='#00f0ff'; this.style.boxShadow='0 0 15px rgba(0,240,255,0.3)';"
                        onblur="this.style.borderColor='rgba(0, 240, 255, 0.4)'; this.style.boxShadow='none';"
                        required 
                        autofocus
                    >
                </div>

                <div id="portalErrorMsg" style="display: none; padding: 12px; border-radius: 8px; background: rgba(255, 0, 127, 0.12); border: 1px solid rgba(255, 0, 127, 0.35); color: #ff3399; font-size: 13px; margin-bottom: 18px;"></div>

                <div id="portalSuccessMsg" style="display: none; padding: 12px; border-radius: 8px; background: rgba(0, 255, 157, 0.12); border: 1px solid rgba(0, 255, 157, 0.35); color: #00ff9d; font-size: 13px; margin-bottom: 18px;"></div>

                <button 
                    type="submit" 
                    id="portalSubmitBtn" 
                    style="width: 100%; background: linear-gradient(135deg, #0099cc, #00f0ff); color: #070a12; font-weight: 700; border: none; padding: 16px; border-radius: 10px; font-size: 13px; letter-spacing: 2px; text-transform: uppercase; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; box-shadow: 0 0 25px rgba(0, 240, 255, 0.4);">
                    Unlock & Download Media →
                </button>
            </form>

            <div style="margin-top: 30px; padding-top: 25px; border-top: 1px solid rgba(255,255,255,0.08); font-size: 12.5px; color: #64748b;">
                Lost your access PIN? 
                <a href="https://wa.me/918082024787?text=Hello%20Paneventz!%20I%20need%20assistance%20with%20my%20wedding%20gallery%20passcode." target="_blank" style="color: #c4a472; text-decoration: none; font-weight: 600; margin-left: 4px;">
                    Contact Concierge on WhatsApp →
                </a>
            </div>

        </div>
    </div>

</div>

<script>
    async function handlePortalUnlock(e) {
        e.preventDefault();

        const pinInput = document.getElementById('portalPinInput');
        const btn = document.getElementById('portalSubmitBtn');
        const errorDiv = document.getElementById('portalErrorMsg');
        const successDiv = document.getElementById('portalSuccessMsg');

        const pin = pinInput.value.trim();
        if (!pin) return;

        btn.disabled = true;
        btn.innerText = 'Verifying Passcode...';
        errorDiv.style.display = 'none';
        successDiv.style.display = 'none';

        try {
            const res = await fetch('{{ route("client.portal.unlock") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ pin: pin }),
            });

            const data = await res.json();

            if (res.ok && data.success) {
                successDiv.style.display = 'block';
                successDiv.innerText = `✓ Passcode Accepted! Unlocking ${data.couple}...`;
                btn.innerText = '✓ Redirecting to Your Collection...';
                btn.style.background = '#00ff9d';
                btn.style.color = '#070a12';

                setTimeout(() => {
                    window.location.href = data.redirect_url;
                }, 800);
            } else {
                errorDiv.style.display = 'block';
                errorDiv.innerText = data.message || 'Invalid passcode. Please check with Paneventz.';
                btn.disabled = false;
                btn.innerText = 'Unlock & Download Media →';
                pinInput.focus();
            }
        } catch (err) {
            errorDiv.style.display = 'block';
            errorDiv.innerText = 'Network error. Please try again.';
            btn.disabled = false;
            btn.innerText = 'Unlock & Download Media →';
        }
    }
</script>
@endsection