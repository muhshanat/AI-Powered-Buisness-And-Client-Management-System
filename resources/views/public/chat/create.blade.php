
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact {{ $organization->name }} | TanovaFlow</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #eef3f9;
            color: #263445;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 25px;
        }

        .contact-card {
            background: white;
            width: 100%;
            max-width: 550px;
            padding: 35px;
            border-radius: 14px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.07);
        }

        .brand {
            color: #3287d8;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 25px;
        }

        h2 {
            margin-bottom: 10px;
        }

        .description {
            color: #718096;
            margin-bottom: 25px;
            line-height: 1.6;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }

        input, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #d5deea;
            border-radius: 7px;
            font-size: 14px;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        input:focus, textarea:focus {
            outline: none;
            border-color: #3287d8;
        }

        .error {
            color: #dc3545;
            font-size: 13px;
            margin-top: 5px;
        }

        button {
            width: 100%;
            padding: 14px;
            background: #3287d8;
            color: white;
            border: none;
            border-radius: 7px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #246db5;
        }
    </style>
</head>

<body>

<div class="contact-card">

    <div class="brand">TanovaFlow</div>

    <h2>Contact {{ $organization->name }}</h2>

    <p class="description">
        Have a question or need assistance?
        Send a message directly to this organization.
    </p>

    <form action="{{ route('public.contact.store', $organization->id) }}"
          method="POST">

        @csrf

        <div class="form-group">
            <label>Your Name *</label>
            <input type="text" name="customer_name"
                   value="{{ old('customer_name') }}" required>
            @error('customer_name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="customer_email"
                   value="{{ old('customer_email') }}">
            @error('customer_email')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Phone Number *</label>
            <input type="text" name="customer_phone"
                   value="{{ old('customer_phone') }}" required>
            @error('customer_phone')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Subject</label>
            <input type="text" name="subject"
                   value="{{ old('subject') }}"
                   placeholder="What do you need help with?">
        </div>

        <div class="form-group">
            <label>Your Message *</label>
            <textarea name="message" required>{{ old('message') }}</textarea>
            @error('message')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit">Send Message</button>

    </form>

</div>

</body>
</html>
