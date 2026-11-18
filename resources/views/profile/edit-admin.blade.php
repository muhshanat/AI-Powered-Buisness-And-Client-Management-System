
@extends('admin.layouts.app')

@section('content')

<div class="profile-layout">

    {{-- LEFT: Profile Information --}}
    <aside class="profile-sidebar">

        {{-- Profile Overview --}}
        <section class="profile-overview-card">

            <div class="profile-photo-wrapper">
                <img
                    id="profilePreview"
                    src="{{ auth()->user()->profile_photo_path
                        ? asset('storage/' . auth()->user()->profile_photo_path)
                        : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=185f9b&color=fff&size=160' }}"
                    alt="Profile Picture"
                    class="profile-photo-preview"
                >
                <span class="profile-photo-status"></span>
            </div>

            <div class="profile-overview-info">
                <h3>{{ auth()->user()->name }}</h3>
                <p>{{ auth()->user()->email }}</p>

                <span class="profile-role-badge">
                    {{ auth()->user()->role === 'super_admin'
                        ? 'Super Admin'
                        : auth()->user()->role }}
                </span>
            </div>

            <p class="profile-overview-note">
                Keep your account information up to date.
            </p>

        </section>


        {{-- Profile Picture Upload --}}
        <section class="profile-settings-card">

            <div class="profile-card-heading">

                <div class="profile-heading-icon">
                    <svg viewBox="0 0 24 24">
                        <rect x="3" y="3" width="18" height="18" rx="3"/>
                        <circle cx="9" cy="9" r="2"/>
                        <path d="m21 15-5-5L5 21"/>
                    </svg>
                </div>

                <div>
                    <h3>Profile Picture</h3>
                    <p>Upload or update your account photo.</p>
                </div>

            </div>

            <form method="POST"
                  action="{{ route('profile.photo.update') }}"
                  enctype="multipart/form-data"
                  class="profile-photo-form">

                @csrf

                <div class="profile-upload-area">

                    <label for="profilePhoto" class="profile-file-label">
                        Choose a photo
                    </label>

                    <input
                        type="file"
                        name="profile_photo"
                        id="profilePhoto"
                        accept="image/jpeg,image/png,image/webp"
                        required
                    >

                    <span>JPG, PNG or WebP · Maximum 2 MB</span>

                </div>

                @error('profile_photo')
                    <p class="profile-error">{{ $message }}</p>
                @enderror

                <button type="submit" class="profile-primary-btn">
                    Upload Picture
                </button>

            </form>

            @if(auth()->user()->profile_photo_path)

                <form method="POST"
                      action="{{ route('profile.photo.destroy') }}"
                      class="profile-remove-area">

                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="profile-remove-btn"
                            onclick="return confirm('Remove your profile picture?')">
                        Remove current picture
                    </button>

                </form>

            @endif

        </section>

    </aside>


    {{-- RIGHT: Account Settings --}}
    <main class="profile-main-content">


        {{-- Personal Information --}}
        <section class="profile-settings-card">

            <div class="profile-card-heading">

                <div class="profile-heading-icon">
                    <svg viewBox="0 0 24 24">
                        <circle cx="12" cy="8" r="4"/>
                        <path d="M5 21v-2a7 7 0 0 1 14 0v2"/>
                    </svg>
                </div>

                <div>
                    <h3>Personal Information</h3>
                    <p>Update your name and email address.</p>
                </div>

            </div>

            <div class="profile-partial">
                @include('profile.partials.update-profile-information-form')
            </div>

        </section>


        {{-- Change Password --}}
        <section class="profile-settings-card">

            <div class="profile-card-heading">

                <div class="profile-heading-icon">
                    <svg viewBox="0 0 24 24">
                        <rect x="3" y="11" width="18" height="11" rx="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </div>

                <div>
                    <h3>Change Password</h3>
                    <p>Use a strong password to protect your account.</p>
                </div>

            </div>

            <div class="profile-partial">
                @include('profile.partials.update-password-form')
            </div>

        </section>


        {{-- Delete Account --}}
        <section class="profile-settings-card profile-danger-card">

            <div class="profile-card-heading">

                <div class="profile-heading-icon profile-heading-danger">
                    <svg viewBox="0 0 24 24">
                        <path d="M3 6h18M8 6V4h8v2"/>
                        <path d="m19 6-1 14H6L5 6"/>
                        <path d="M10 11v5M14 11v5"/>
                    </svg>
                </div>

                <div>
                    <h3>Delete Account</h3>
                    <p>Permanently delete your account and associated data.</p>
                </div>

            </div>

            <div class="profile-partial">
                @include('profile.partials.delete-user-form')
            </div>

        </section>

    </main>

</div>


{{-- Profile Photo Preview --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const input = document.getElementById('profilePhoto');
    const preview = document.getElementById('profilePreview');

    if (input && preview) {

        input.addEventListener('change', function (event) {

            const file = event.target.files[0];

            if (file) {
                preview.src = URL.createObjectURL(file);
            }

        });

    }

});
</script>

@endsection
