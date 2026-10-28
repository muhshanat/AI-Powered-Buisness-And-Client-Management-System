
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Customer Dashboard
        </h2>
    </x-slot>

    <div style="background: #f4f7fb; min-height: 80vh; padding: 30px;">
        <div style="max-width: 1100px; margin: auto;">

            <div style="margin-bottom: 25px;">
                <h1 style="font-size: 28px; font-weight: bold; color: #1e293b;">
                    Welcome, {{ Auth::user()->name }}!
                </h1>

                <p style="color: #64748b; margin-top: 8px;">
                    Welcome to your customer dashboard.
                </p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px;">

                <div style="background: white; padding: 25px; border-radius: 12px; border: 1px solid #e2e8f0;">
                    <h3 style="font-size: 18px; font-weight: bold; color: #1e293b;">
                        My Profile
                    </h3>

                    <p style="color: #64748b; margin: 12px 0;">
                        View your account information.
                    </p>

                    <p style="color: #334155;">
                        <strong>Name:</strong> {{ Auth::user()->name }}
                    </p>

                    <p style="color: #334155; margin-top: 5px;">
                        <strong>Email:</strong> {{ Auth::user()->email }}
                    </p>
                </div>

                <div style="background: white; padding: 25px; border-radius: 12px; border: 1px solid #e2e8f0;">
                    <h3 style="font-size: 18px; font-weight: bold; color: #1e293b;">
                        Account Status
                    </h3>

                    <p style="color: #64748b; margin: 12px 0;">
                        Your account is active.
                    </p>

                    <span style="display: inline-block; background: #dcfce7; color: #166534; padding: 6px 12px; border-radius: 20px; font-size: 13px;">
                        Active
                    </span>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
