
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Organization Dashboard') | TanovaFlow</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #e2eaf3;
            color: #153451;
        }

        .org-layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .org-sidebar {
            width: 250px;
            background: #ffffff;
            border-right: 1px solid #d5e0ec;
            padding: 25px 16px;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            overflow-y: auto;
            z-index: 10;
        }

        .org-brand {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 0 12px 28px;
            font-size: 21px;
            font-weight: 700;
            color: #173b60;
        }

        .org-brand span {
            color: #287bd1;
        }

        .org-nav-label {
            font-size: 10px;
            font-weight: 700;
            color: #8498ad;
            letter-spacing: 1px;
            padding: 15px 12px 8px;
        }

        .org-nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            margin-bottom: 5px;
            color: #405b78;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            border-radius: 8px;
            transition: 0.2s;
        }

        .org-nav a:hover,
        .org-nav a.active {
            background: #e5f0fc;
            color: #1769b0;
        }

        .org-nav a.active {
            border-left: 3px solid #3287d8;
        }

        .org-nav-icon {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        /* Main Area */
        .org-main {
            margin-left: 250px;
            width: calc(100% - 250px);
            min-width: 0;
        }

        /* Topbar */
        .org-topbar {
            height: 70px;
            background: #ffffff;
            border-bottom: 1px solid #d5e0ec;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 30px;
        }

        .org-topbar h2 {
            font-size: 18px;
            color: #153b60;
        }

        .org-user {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 13px;
            font-weight: 600;
            color: #173b60;
        }

        .org-user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e1effc;
            color: #2675bd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        /* Content */
        .org-content {
            padding: 30px;
            min-height: calc(100vh - 70px);
        }

        .org-page-heading {
            margin-bottom: 25px;
            background: #f8fbff;
            border: 1px solid #d5e3f1;
            border-left: 4px solid #3287d8;
            border-radius: 13px;
            padding: 30px;
        }

        .org-page-heading h1 {
            font-size: 24px;
            color: #153b60;
            margin-bottom: 9px;
        }

        .org-page-heading p {
            color: #647f9c;
            font-size: 13px;
        }

        .org-card {
            background: #ffffff;
            border: 1px solid #cbdbea;
            border-radius: 13px;
            padding: 25px;
            box-shadow: 0 4px 12px rgba(30, 65, 100, 0.07);
        }

        .org-card h2 {
            color: #153b60;
            font-size: 19px;
        }

        .org-logout {
            width: 100%;
            background: transparent;
            border: none;
            text-align: left;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            color: #d94e55;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border-radius: 8px;
        }

        .org-logout:hover {
            background: #fff0f0;
        }

        @media (max-width: 850px) {
            .org-sidebar {
                width: 210px;
            }

            .org-main {
                margin-left: 210px;
                width: calc(100% - 210px);
            }

            .org-content {
                padding: 20px;
            }
        }

        @media (max-width: 600px) {
            .org-sidebar {
                width: 65px;
                padding: 20px 8px;
            }

            .org-brand {
                font-size: 0;
                justify-content: center;
                padding: 0 0 25px;
            }

            .org-brand span {
                font-size: 20px;
            }

            .org-nav-label,
            .org-nav a span:not(:first-child),
            .org-logout span:not(:first-child) {
                display: none;
            }

            .org-nav a,
            .org-logout {
                justify-content: center;
                padding: 13px 5px;
            }

            .org-main {
                margin-left: 65px;
                width: calc(100% - 65px);
            }

            .org-topbar {
                padding: 0 15px;
            }

            .org-content {
                padding: 15px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

<div class="org-layout">

    <aside class="org-sidebar">

        <div class="org-brand">
            Tanova<span>Flow</span>
        </div>

        <nav class="org-nav">

            <div class="org-nav-label">WORKSPACE</div>

            <a href="{{ route('organization.dashboard') }}"
               class="{{ request()->routeIs('organization.dashboard') ? 'active' : '' }}">
                <span>▦</span>
                <span>Dashboard</span>
            </a>

            <div class="org-nav-label">BUSINESS MANAGEMENT</div>

            <a href="#"><span>♙</span><span>Staff Management</span></a>
            <a href="#"><span>▣</span><span>Products</span></a>
            <a href="#"><span>◇</span><span>Services</span></a>
            <a href="#"><span>🛒</span><span>Orders</span></a>
            <a href="#"><span>▦</span><span>Appointments</span></a>

            <div class="org-nav-label">COMMUNICATION</div>

            <a href="#"><span>✉</span><span>Conversations</span></a>
            <a href="#"><span>✧</span><span>AI Assistant</span></a>

            <div class="org-nav-label">SETTINGS</div>

            <a href="#"><span>⚙</span><span>Organization Settings</span></a>

        </nav>

        <div style="margin-top:25px; border-top:1px solid #dce5ef; padding-top:15px;">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="org-logout">
                    <span>↪</span>
                    <span>Logout</span>
                </button>
            </form>
        </div>

    </aside>

    <main class="org-main">

        <header class="org-topbar">

            <h2>@yield('heading', 'Dashboard')</h2>

            <div class="org-user">

                <div class="org-user-avatar">
                    {{ strtoupper(substr(auth('organization')->user()->name, 0, 1)) }}
                </div>

                <div>
                    {{ auth('organization')->user()->name }}

                    <div style="font-size:11px; color:#71869d; font-weight:400;">
                        Organization Workspace
                    </div>
                </div>

            </div>

        </header>

        <section class="org-content">
            @yield('content')
        </section>

    </main>

</div>

@stack('scripts')

</body>
</html>
