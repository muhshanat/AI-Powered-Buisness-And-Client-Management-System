
@extends('admin.layouts.app')

@section('title', '2FA Settings')
@section('heading', 'Two-Factor Authentication')
@section('page_description', 'Add an extra layer of security to your account.')

@section('content')

<div class="admin-card" style="padding:30px; max-width:850px;">



    <div style="padding:25px; background:#f5f9fd; border-radius:12px;">

        <h3 style="margin-bottom:15px;">Authentication Status</h3>

        @if($user->google2fa_enabled)

            {{-- ENABLED STATUS --}}

            <span class="status-badge status-active">
                Enabled
            </span>

            <p style="margin:15px 0; color:#526b83;">
                Your account is protected with Google Authenticator.
                You must enter a verification code when logging in.
            </p>

            {{-- DISABLE FORM --}}

            <div style="margin-top:25px; padding:20px; background:white;
                        border:1px solid #e2e8f0; border-radius:10px;">

                <h3 style="margin-bottom:10px;">
                    Disable Two-Factor Authentication
                </h3>

                <p style="color:#64748b; margin-bottom:15px;">
                    Enter your current Google Authenticator code to disable 2FA.
                </p>

                <form action="{{ route('admin.2fa.disable') }}" method="POST">
                    @csrf

                    <label for="code">Authenticator Code</label>

                    <input
                        type="text"
                        name="code"
                        id="code"
                        maxlength="6"
                        pattern="[0-9]{6}"
                        inputmode="numeric"
                        placeholder="Enter 6-digit code"
                        required
                        style="display:block; width:100%; max-width:300px; margin:10px 0 15px;"
                    >

                    @error('code')
                        <p style="color:red; margin-bottom:12px;">
                            {{ $message }}
                        </p>
                    @enderror

                    <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to disable Google Authenticator?')">
                        Disable 2FA
                    </button>
                </form>

            </div>

        @else

            {{-- DISABLED STATUS --}}

            <span class="status-badge status-inactive">
                Not Enabled
            </span>

            <p style="margin:15px 0; color:#526b83;">
                Google Authenticator is not enabled on your account.
                Enable it to improve your account security.
            </p>

            <a href="{{ route('admin.2fa.setup') }}"
               class="btn btn-primary"
               style="display:inline-block; margin-top:10px;">
                Set Up Google Authenticator
            </a>

        @endif

    </div>

</div>

@endsection
