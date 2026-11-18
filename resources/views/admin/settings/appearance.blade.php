
@extends('admin.layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-appearance.css') }}?v=1">
@endpush

@section('title', 'Appearance Settings')
@section('heading', 'Appearance Settings')
@section('page_description', 'Customize your admin panel branding and colors.')

@section('content')

@php
    $defaults = [
        'application_name' => 'Admin Panel',
        'logo_text_color' => '#173B5E',
        'primary_color' => '#1769B2',
        'secondary_color' => '#647F9B',
        'page_background' => '#E5EDF6',
        'nav_background' => '#FFFFFF',
        'nav_text_color' => '#173B5E',
        'button_color' => '#1769B2',
        'heading_color' => '#173B5E',
        'body_text_color' => '#52677F',
        'logo_path' => null,
    ];

    $current = array_merge($defaults, $settings);
@endphp

<div class="appearance-settings-page">

    {{-- Separate Reset Form --}}
    <form action="{{ route('admin.appearance.reset') }}"
          method="POST"
          id="resetAppearanceForm"
          onsubmit="return confirm('Are you sure you want to reset all appearance settings to their defaults?');">
        @csrf
    </form>

    {{-- Appearance Update Form --}}
    <form action="{{ route('admin.appearance.update') }}"
      method="POST"
      id="appearanceForm"
      enctype="multipart/form-data">

        @csrf

        <div class="content-card appearance-card">

            {{-- Application Identity --}}
            <div class="appearance-section-heading">
                <h2>Application Identity</h2>
                <p>Set your application name and logo text color.</p>
            </div>

            <div class="appearance-field">
                <label for="application_name">Application Name</label>

                <input type="text"
                       id="application_name"
                       name="application_name"
                       value="{{ old('application_name', $current['application_name']) }}"
                       maxlength="100"
                       required>

                @error('application_name')
                    <small class="field-error">{{ $message }}</small>
                @enderror
            </div>

            {{-- Logo Upload --}}
<div class="appearance-field logo-upload-field">

    <label for="logo">Application Logo</label>

    <p class="logo-help">
        Upload PNG, JPG, JPEG or WEBP. Maximum size: 2 MB.
    </p>

    <div class="logo-upload-preview">
        <img
            id="logoPreview"
            src="{{ !empty($current['logo_path']) ? asset('storage/' . $current['logo_path']) : '' }}"
            alt="Application Logo Preview"
            style="{{ !empty($current['logo_path']) ? '' : 'display:none;' }}"
        >

        <span id="logoPlaceholder"
              style="{{ !empty($current['logo_path']) ? 'display:none;' : '' }}">
            No logo uploaded
        </span>
    </div>

    <input type="file"
           id="logo"
           name="logo"
           accept="image/png,image/jpeg,image/webp">

    @error('logo')
        <small class="field-error">{{ $message }}</small>
    @enderror

</div>

            {{-- Theme Colors --}}
            <div class="appearance-section-heading">
                <h2>Theme Colors</h2>
                <p>Choose colors for different parts of your admin panel.</p>
            </div>

            <div class="appearance-color-grid">

                @php
                    $colorFields = [
                        'logo_text_color' => 'Logo Text Color',
                        'primary_color' => 'Primary Color',
                        'secondary_color' => 'Secondary Color',
                        'page_background' => 'Page Background',
                        'nav_background' => 'Navigation Background',
                        'nav_text_color' => 'Navigation Text Color',
                        'button_color' => 'Button Color',
                        'heading_color' => 'Heading Color',
                        'body_text_color' => 'Body Text Color',
                    ];
                @endphp

                @foreach ($colorFields as $key => $label)

                    <div class="appearance-color-field">

                        <label for="{{ $key }}">
                            {{ $label }}
                        </label>

                        <div class="color-picker-wrapper">

                            <input type="color"
                                   id="{{ $key }}"
                                   name="{{ $key }}"
                                   value="{{ old($key, $current[$key]) }}"
                                   data-color="{{ $key }}">

                            <span class="color-value">
                                {{ old($key, $current[$key]) }}
                            </span>

                        </div>

                        @error($key)
                            <small class="field-error">{{ $message }}</small>
                        @enderror

                    </div>

                @endforeach

            </div>

            {{-- Live Preview --}}
            <div class="appearance-section-heading">
                <h2>Live Preview</h2>
                <p>Preview your branding before saving.</p>
            </div>

           

<div class="appearance-preview" id="appearancePreview">

    {{-- Preview Navigation --}}
    <div class="preview-navbar">

        <div class="preview-brand">

            <div class="preview-logo" id="previewLogo">
                <span id="previewLogoLetter">A</span>

                <img id="previewLogoImage"
                     src=""
                     alt="Logo Preview"
                     style="display:none;">
            </div>

            <strong id="previewName">
                {{ $current['application_name'] }}
            </strong>

        </div>

        <div class="preview-navigation">
            <span>Dashboard</span>
            <span>Customers</span>
            <span>Settings</span>
        </div>

    </div>

    {{-- Preview Dashboard Content --}}
    <div class="preview-content">

        <h3>Welcome to your Dashboard</h3>

        <p>Your application branding preview.</p>

        <div class="preview-demo-actions">

            <button type="button" class="preview-button">
                Primary Button
            </button>

            <button type="button" class="preview-secondary-button">
                Secondary Button
            </button>

        </div>

        <div class="preview-demo-card">
            <strong>Sample Information Card</strong>
            <p>This is an example of your admin panel content.</p>
        </div>

    </div>

</div>



            {{-- Action Buttons --}}
            <div class="appearance-actions">

                <button type="submit" class="btn">
                    Save Changes
                </button>

                <button type="submit"
                        form="resetAppearanceForm"
                        class="btn btn-secondary">
                    Reset to Defaults
                </button>

            </div>

        </div>

    </form>

</div>

{{-- Live Preview JavaScript --}}
<script>


// Logo live preview
const logoInput = document.getElementById('logo');
const logoPreview = document.getElementById('logoPreview');
const previewLogoImage = document.getElementById('previewLogoImage');
const previewLogoLetter = document.getElementById('previewLogoLetter');

logoInput.addEventListener('change', function () {

    const file = this.files[0];

    if (file) {
        const reader = new FileReader();

        reader.onload = function (event) {

            logoPreview.src = event.target.result;
            logoPreview.style.display = 'block';

            previewLogoImage.src = event.target.result;
            previewLogoImage.style.display = 'block';

            previewLogoLetter.style.display = 'none';
        };

        reader.readAsDataURL(file);
    }
});

document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('appearanceForm');
    const preview = document.getElementById('appearancePreview');

    function getValue(id) {
        return document.getElementById(id).value;
    }

    function updatePreview() {

        // Page background
        preview.style.backgroundColor =
            getValue('page_background');

        // Navigation background
        preview.querySelector('.preview-navbar').style.backgroundColor =
            getValue('nav_background');

        // Application name and logo text
        document.getElementById('previewName').textContent =
            getValue('application_name');

        document.getElementById('previewName').style.color =
            getValue('logo_text_color');

        // Primary color
        preview.querySelector('.preview-logo').style.backgroundColor =
            getValue('primary_color');

        // Navigation text
        preview.querySelectorAll('.preview-navigation span').forEach(item => {
            item.style.color = getValue('nav_text_color');
        });

        // Heading color
        preview.querySelector('.preview-content h3').style.color =
            getValue('heading_color');

        // Body text color
        preview.querySelectorAll('.preview-content p').forEach(item => {
            item.style.color = getValue('body_text_color');
        });

        // Button color
        const primaryButton =
            preview.querySelector('.preview-button');

        primaryButton.style.backgroundColor =
            getValue('button_color');

        primaryButton.style.borderColor =
            getValue('button_color');

        primaryButton.style.color = '#FFFFFF';

        // Secondary color
        const secondaryButton =
            preview.querySelector('.preview-secondary-button');

        secondaryButton.style.backgroundColor =
            getValue('secondary_color');

        secondaryButton.style.borderColor =
            getValue('secondary_color');

        secondaryButton.style.color = '#FFFFFF';
    }

    // Listen for color changes
    form.querySelectorAll('input[type="color"]').forEach(input => {

        input.addEventListener('input', function () {

            this.parentElement.querySelector('.color-value')
                .textContent = this.value.toUpperCase();

            updatePreview();
        });

    });

    // Listen for application name changes
    document.getElementById('application_name')
        .addEventListener('input', updatePreview);

    // Initial preview
    updatePreview();

});

</script>

@endsection
