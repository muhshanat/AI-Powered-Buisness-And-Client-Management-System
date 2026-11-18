
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
            background: #f1f5f9;
            color: #243b53;
        }

        .org-layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .org-sidebar {
            width: 250px;
            background: #ffffff;
            border-right: 1px solid #e2e8f0;
            padding: 25px 16px;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            overflow-y: auto;
        }

        .org-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 12px 28px;
            font-size: 21px;
            font-weight: 700;
            color: #183b60;
        }

        .org-brand span {
            color: #168b91;
        }

        .org-nav-label {
            font-size: 10px;
            font-weight: 700;
            color: #94a3b8;
            letter-spacing: 1px;
            padding: 15px 12px 8px;
        }

        .org-nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            margin-bottom: 5px;
            color: #64748b;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            border-radius: 8px;
            transition: 0.2s;
        }

        .org-nav a:hover,
        .org-nav a.active {
            background: #eaf5f5;
            color: #168b91;
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
            height: 75px;
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 30px;
        }

        .org-topbar h2 {
            font-size: 18px;
            color: #183b60;
        }

        .org-user {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 13px;
            font-weight: 600;
        }

        .org-user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #e4eff8;
            color: #17639b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        /* Content */
        .org-content {
            padding: 30px;
        }

        .org-page-heading {
            margin-bottom: 25px;
        }

        .org-page-heading h1 {
            font-size: 24px;
            color: #183b60;
            margin-bottom: 7px;
        }

        .org-page-heading p {
            color: #718096;
            font-size: 13px;
        }

        .org-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 3px 12px rgba(30, 55, 90, 0.04);
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
            color: #dc5555;
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
            .org-nav a span,
            .org-logout span {
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

    {{-- Sidebar --}}
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

            <a href="#">
                <span>♙</span>
                <span>Staff Management</span>
            </a>

            <a href="#">
                <span>▣</span>
                <span>Products</span>
            </a>

            <a href="#">
                <span>◇</span>
                <span>Services</span>
            </a>

            <a href="#">
                <span>🛒</span>
                <span>Orders</span>
            </a>

            <a href="#">
                <span>▦</span>
                <span>Appointments</span>
            </a>

            <div class="org-nav-label">COMMUNICATION</div>

            <a href="#">
                <span>✉</span>
                <span>Conversations</span>
            </a>

            <a href="#">
                <span>✧</span>
                <span>AI Assistant</span>
            </a>

            <div class="org-nav-label">SETTINGS</div>

            <a href="#">
                <span>⚙</span>
                <span>Organization Settings</span>
            </a>

        </nav>

        <div style="margin-top: 25px; border-top: 1px solid #e2e8f0; padding-top: 15px;">

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="org-logout">
                    <span>↪</span>
                    <span>Logout</span>
                </button>

            </form>

        </div>

    </aside>


    {{-- Main Content --}}
    <main class="org-main">

        <header class="org-topbar">

            <h2>@yield('heading', 'Dashboard')</h2>

            <div class="org-user">

                <div class="org-user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

                <div>
                    {{ auth()->user()->name }}
                    <div style="font-size: 11px; color: #718096; font-weight: 400;">
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
