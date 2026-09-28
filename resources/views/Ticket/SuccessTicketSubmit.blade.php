<x-guest-layout>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@600;700&family=IBM+Plex+Sans:wght@400;500;600&display=swap">

    <style>
        .ucua-success-card { width: 100%; max-width: 560px; margin: 40px auto; background: #FFFFFF; border-radius: 26px; box-shadow: 0 40px 90px -24px rgba(1,19,51,0.45), 0 8px 24px rgba(1,19,51,0.14); padding: 48px 44px 40px; text-align: center; font-family: 'IBM Plex Sans', sans-serif; }
        .ucua-success-card * { box-sizing: border-box; }
        .ucua-success-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 30px; }
        .ucua-success-logo-phn { height: 22px; width: auto; }
        .ucua-success-logo-zh { height: 28px; width: auto; }
        .ucua-success-badge { width: 76px; height: 76px; border-radius: 999px; background: #FEE6DE; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; }
        .ucua-success-title { font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 23px; color: #011333; margin: 0 0 18px; }
        .ucua-success-body { font-size: 14.5px; color: #3A3A34; line-height: 1.7; margin-bottom: 28px; }
        .ucua-success-actions { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
        .ucua-btn-primary { display: inline-flex; align-items: center; justify-content: center; gap: 9px; background: #FC471A; color: #FFFFFF; border: none; border-radius: 12px; padding: 15px 28px; font-family: 'IBM Plex Sans', sans-serif; font-weight: 600; font-size: 14.5px; text-decoration: none; box-shadow: 0 10px 24px -8px rgba(252,71,26,0.55); }
        .ucua-btn-primary:hover { color: #FFFFFF; }
        .ucua-btn-ghost { display: inline-flex; align-items: center; gap: 8px; background: #FFFFFF; color: #011333; border: 1.5px solid #E4E0D4; border-radius: 12px; padding: 14px 26px; font-size: 14.5px; font-weight: 600; text-decoration: none; }
        .ucua-btn-ghost:hover { color: #011333; border-color: #FC471A; }
    </style>

    <div class="ucua-success-card">
        <div class="ucua-success-header">
            <img src="{{ asset('/img/phn-logo.png') }}" alt="PHN Logo" class="ucua-success-logo-phn">
            <img src="{{ asset('/img/zero_harm.png') }}" alt="Zero Harm Logo" class="ucua-success-logo-zh">
        </div>

        <div class="ucua-success-badge">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#FC471A" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
        </div>

        <h1 class="ucua-success-title">Observation Submitted</h1>

        <div class="ucua-success-body">
            You have successfully submitted the UCUA Observation.<br>
            Person in Charge will be notified and verify your observation.<br>
            You will receive an email containing the submitted observation's info for your own reference.<br><br>
            <span style="font-weight: 600; color: #011333;">Thank you.</span>
        </div>

        <div class="ucua-success-actions">
            <a href="{{ url('/') }}" class="ucua-btn-ghost">Home</a>
            <a href="{{ route('ShowNewTicketForm') }}" class="ucua-btn-primary">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                New UCUA Observation
            </a>
        </div>
    </div>
</x-guest-layout>
