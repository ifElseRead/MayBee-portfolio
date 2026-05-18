<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h2 style="color: #1e293b; font-size: 24px; margin-bottom: 20px;">New Contact Message from {{ $data['name'] }}</h2>

    <div style="background-color: #f8fafc; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
        <p style="margin: 0 0 15px 0;">
            <strong style="color: #64748b;">From:</strong> {{ $data['name'] }}
        </p>
        <p style="margin: 0 0 15px 0;">
            <strong style="color: #64748b;">Email:</strong> <a href="mailto:{{ $data['email'] }}"
                style="color: #facc15; text-decoration: none;">{{ $data['email'] }}</a>
        </p>
    </div>

    <div
        style="background-color: #ffffff; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 20px;">
        <h3 style="color: #1e293b; margin-top: 0; margin-bottom: 10px;">Message:</h3>
        <p style="color: #475569; line-height: 1.6; white-space: pre-wrap;">{{ $data['message'] }}</p>
    </div>

    <div style="text-align: center; padding-top: 20px; border-top: 1px solid #e2e8f0; color: #94a3b8; font-size: 12px;">
        <p>The hive has received this message 🐝</p>
    </div>
</div>
