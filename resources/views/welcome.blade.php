<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>SEGA-HOST — Premium Server Hosting Solutions</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|sora:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
            <script>
                tailwind.config = {
                    theme: {
                        extend: {
                            fontFamily: {
                                sans: ['Space Grotesk', 'ui-sans-serif', 'system-ui'],
                                display: ['Sora', 'ui-sans-serif', 'system-ui'],
                            },
                            colors: {
                                midnight: '#020617',
                            },
                        },
                    },
                    darkMode: 'class',
                };
            </script>
            <style>
                body { font-family: 'Space Grotesk', ui-sans-serif, system-ui; }
            </style>
        @endif
    </head>
    <body class="bg-slate-950 text-slate-200 antialiased">
        <div class="relative min-h-screen overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-950 to-slate-900"></div>
            <div class="pointer-events-none absolute top-16 left-1/2 -translate-x-1/2 h-[32rem] w-[32rem] rounded-full bg-gradient-to-br from-purple-500/40 to-pink-500/30 blur-3xl opacity-70 animate-pulse"></div>
            <div class="pointer-events-none absolute bottom-0 right-10 h-[28rem] w-[28rem] rounded-full bg-gradient-to-br from-emerald-500/30 to-blue-500/30 blur-3xl opacity-70 animate-pulse"></div>

            <!-- Navigation -->
            <nav class="sticky top-0 z-30">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-16 py-4">
                    <div class="flex items-center justify-between rounded-2xl border border-white/10 bg-slate-900/70 backdrop-blur-lg px-6 py-4 shadow-xl">
                        <div class="flex items-center gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-r from-blue-600 to-purple-600 text-xl font-bold tracking-tight">
                                SH
                            </div>
                            <div>
                                <div class="text-lg font-semibold text-white tracking-wider">SEGA-HOST</div>
                                <p class="text-xs text-slate-400">Premium Quantum Hosting Network</p>
                            </div>
                        </div>
                        @if (Route::has('login'))
                            <div class="flex items-center gap-4">
                                <a href="#pricing" class="hidden md:block text-sm text-slate-300 transition duration-200 hover:text-white">Plans</a>
                                <a href="#features" class="hidden md:block text-sm text-slate-300 transition duration-200 hover:text-white">Capabilities</a>
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="rounded-full border border-white/20 px-5 py-2 text-sm font-semibold text-white transition hover:border-white/40 hover:bg-white/10">Dashboard</a>
                                @else
                                    <a href="{{ route('login') }}" class="text-sm font-medium text-slate-200 transition hover:text-white">Log in</a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="rounded-full bg-gradient-to-r from-blue-600 to-purple-600 px-5 py-2 text-sm font-semibold text-white shadow-lg transition hover:-translate-y-1 hover:shadow-2xl">Get Started</a>
                                    @endif
                                @endauth
                            </div>
                        @endif
                    </div>
                </div>
            </nav>

            <!-- Hero -->
            <section class="relative z-20 pt-24 pb-16 sm:py-32">
                <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-16">
                    <div class="grid items-center gap-12 lg:grid-cols-[1.2fr_1fr]">
                        <div>
                            <div class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/5 px-4 py-2 text-xs uppercase tracking-wider text-slate-200">
                                <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                                Hyper Resilient Infrastructure
                            </div>
                            <h1 class="mt-6 text-4xl font-semibold leading-tight text-white md:text-6xl">
                                Hosting forged for the <span class="bg-gradient-to-r from-blue-600 via-purple-500 to-cyan-400 bg-clip-text text-transparent">next internet frontier</span>
                            </h1>
                            <p class="mt-6 text-lg leading-relaxed text-slate-300">
                                Deploy, scale, and safeguard mission-critical workloads with an immersive platform that feels like it was built in the future. Ultra-low latency networks, AI-assisted orchestration, and human engineers on standby 24/7.
                            </p>
                            <div class="mt-10 flex flex-col gap-4 sm:flex-row">
                                <a href="#pricing" class="rounded-full bg-gradient-to-r from-blue-600 to-purple-600 px-8 py-3 text-base font-semibold text-white shadow-xl transition hover:scale-[1.02] hover:shadow-2xl">Explore Plans</a>
                                <a href="#experience" class="rounded-full border border-white/20 px-8 py-3 text-base font-semibold text-slate-100 transition hover:border-white/40 hover:bg-white/10">Immersive Demo</a>
                            </div>
                            <div class="mt-12 grid gap-6 sm:grid-cols-3">
                                <div class="rounded-2xl border border-white/10 bg-white/5 p-6 shadow-lg">
                                    <p class="text-3xl font-semibold text-white">99.99%</p>
                                    <p class="text-sm text-slate-400">active uptime this quarter</p>
                                </div>
                                <div class="rounded-2xl border border-white/10 bg-white/5 p-6 shadow-lg">
                                    <p class="text-3xl font-semibold text-white">12 Tbps</p>
                                    <p class="text-sm text-slate-400">global mitigation shield</p>
                                </div>
                                <div class="rounded-2xl border border-white/10 bg-white/5 p-6 shadow-lg">
                                    <p class="text-3xl font-semibold text-white">18 regions</p>
                                    <p class="text-sm text-slate-400">edge-deployed clusters</p>
                                </div>
                            </div>
                        </div>
                        <div class="relative hidden lg:block">
                            <div class="absolute inset-0 rounded-3xl bg-gradient-to-br from-purple-500/40 via-slate-900 to-blue-500/30 blur-3xl"></div>
                            <div class="relative rounded-3xl border border-white/10 bg-slate-900/80 p-8 shadow-2xl backdrop-blur">
                                <div class="mb-6 text-sm uppercase tracking-wider text-slate-400">Live Telemetry</div>
                                <div class="grid gap-6">
                                    <div class="rounded-2xl border border-white/10 bg-slate-950/60 p-5">
                                        <div class="flex justify-between text-sm text-slate-400"><span>Latency Map</span><span>2.4 ms avg</span></div>
                                        <div class="mt-4 h-24 rounded-lg bg-gradient-to-r from-blue-600/60 via-purple-500/60 to-cyan-400/60"></div>
                                    </div>
                                    <div class="rounded-2xl border border-white/10 bg-slate-950/60 p-5">
                                        <div class="flex justify-between text-sm text-slate-400"><span>CPU Fleet Utilisation</span><span>62%</span></div>
                                        <div class="mt-4 h-2 rounded-full bg-white/10">
                                            <div class="h-full w-3/4 rounded-full bg-gradient-to-r from-emerald-400 to-cyan-400"></div>
                                        </div>
                                        <p class="mt-4 text-xs text-slate-500">Auto-balancing active across all hyperscale regions.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Feature Matrix -->
            <section id="features" class="relative z-20 py-24">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-16">
                    <div class="max-w-4xl text-center md:text-left">
                        <p class="text-sm uppercase tracking-wider text-emerald-300">Sonic-grade capabilities</p>
                        <h2 class="mt-4 text-3xl font-semibold text-white md:text-4xl">
                            A symphony of services engineered for scale, security, and speed.
                        </h2>
                        <p class="mt-6 text-lg leading-relaxed text-slate-300">
                            SEGA-HOST fuses cutting-edge hardware, proprietary orchestration software, and obsessive support to keep your ambitions online around the clock.
                        </p>
                    </div>

                    <div class="mt-16 grid gap-10 md:grid-cols-2 lg:grid-cols-4">
                        <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-xl transition hover:-translate-y-1.5 hover:border-white/20">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-blue-600 to-cyan-400 text-lg font-semibold text-white">AI</div>
                            <h3 class="mt-6 text-xl font-semibold text-white">Cerebral Auto Scaling</h3>
                            <p class="mt-4 text-sm leading-relaxed text-slate-300">Predictive AI anticipates surges, scaling resources in milliseconds before traffic hits.</p>
                        </div>
                        <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-xl transition hover:-translate-y-1.5 hover:border-white/20">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-purple-600 to-pink-500 text-lg font-semibold text-white">XR</div>
                            <h3 class="mt-6 text-xl font-semibold text-white">Immersive Control Center</h3>
                            <p class="mt-4 text-sm leading-relaxed text-slate-300">Command clusters with an augmented reality dashboard designed for intuitive observability.</p>
                        </div>
                        <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-xl transition hover:-translate-y-1.5 hover:border-white/20">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-emerald-500 to-teal-400 text-lg font-semibold text-white">∞</div>
                            <h3 class="mt-6 text-xl font-semibold text-white">Zero-Trust Mesh</h3>
                            <p class="mt-4 text-sm leading-relaxed text-slate-300">Multi-layer encryption, quantum-ready firewalls, and identity-first access at every edge.</p>
                        </div>
                        <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-xl transition hover:-translate-y-1.5 hover:border-white/20">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-amber-500 to-rose-500 text-lg font-semibold text-white">24</div>
                            <h3 class="mt-6 text-xl font-semibold text-white">Sentinel Engineers</h3>
                            <p class="mt-4 text-sm leading-relaxed text-slate-300">Human specialists available every hour of every day, proactively monitoring your universe.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Experience Section -->
            <section id="experience" class="relative z-20 py-24">
                <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-16">
                    <div class="grid items-center gap-12 md:grid-cols-2">
                        <div class="rounded-3xl border border-white/10 bg-white/5 p-8 shadow-2xl">
                            <h3 class="text-2xl font-semibold text-white">Mission Control Timeline</h3>
                            <p class="mt-4 text-sm leading-relaxed text-slate-300">Launch anything in under five minutes with automated blueprints and live-replay rollbacks.</p>
                            <div class="mt-8 space-y-6">
                                <div class="flex gap-4">
                                    <div class="mt-1 flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-blue-600 to-purple-600 text-sm font-semibold">01</div>
                                    <div>
                                        <p class="text-sm uppercase tracking-wider text-slate-400">Provision</p>
                                        <h4 class="text-lg font-semibold text-white">Spin up a cluster blueprint</h4>
                                        <p class="text-sm text-slate-300">Select from curated architectures for apps, games, or enterprise workloads.</p>
                                    </div>
                                </div>
                                <div class="flex gap-4">
                                    <div class="mt-1 flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-emerald-500 to-cyan-400 text-sm font-semibold">02</div>
                                    <div>
                                        <p class="text-sm uppercase tracking-wider text-slate-400">Automate</p>
                                        <h4 class="text-lg font-semibold text-white">Deploy with AI pilots</h4>
                                        <p class="text-sm text-slate-300">Our AI co-pilot validates configs, secures secrets, and executes zero-downtime rollout.</p>
                                    </div>
                                </div>
                                <div class="flex gap-4">
                                    <div class="mt-1 flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-amber-500 to-rose-500 text-sm font-semibold">03</div>
                                    <div>
                                        <p class="text-sm uppercase tracking-wider text-slate-400">Evolve</p>
                                        <h4 class="text-lg font-semibold text-white">Adaptive optimisation</h4>
                                        <p class="text-sm text-slate-300">Continuous tuning of resources, cost, and security posture with self-healing automations.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="rounded-3xl border border-white/10 bg-gradient-to-br from-blue-600/20 via-slate-900/80 to-purple-600/20 p-10 shadow-2xl">
                            <p class="text-sm uppercase tracking-wider text-cyan-300">Trusted by visionary teams</p>
                            <h3 class="mt-4 text-3xl font-semibold text-white">“SEGA-HOST feels like commandeering a starship—seamless control, absurd performance, and a support crew that anticipates our needs.”</h3>
                            <div class="mt-10 flex items-center gap-4">
                                <div class="h-14 w-14 rounded-full bg-white/10"></div>
                                <div>
                                    <p class="text-lg font-semibold text-white">Mira Takahashi</p>
                                    <p class="text-sm text-slate-400">Chief Technology Explorer, NovaCircuit Labs</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Pricing -->
            <section id="pricing" class="relative z-20 py-24">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-16">
                    <div class="mx-auto max-w-4xl text-center">
                        <p class="text-sm uppercase tracking-wider text-purple-300">Choose your launch sequence</p>
                        <h2 class="mt-4 text-3xl font-semibold text-white md:text-4xl">Transparent pricing engineered for ambitious missions of every scale.</h2>
                    </div>

                    <div class="mt-16 grid gap-10 md:grid-cols-3">
                        <div class="rounded-3xl border border-white/10 bg-white/5 p-8 shadow-2xl backdrop-blur transition hover:-translate-y-1.5">
                            <div class="text-sm uppercase tracking-wider text-slate-400">Ignition</div>
                            <h3 class="mt-4 text-2xl font-semibold text-white">Starter Core</h3>
                            <p class="mt-4 text-sm text-slate-300">Perfect for prototypes, boutique sites, and indie games launching their first universe.</p>
                            <div class="mt-6 text-4xl font-semibold text-white">$9<span class="text-lg text-slate-400">/mo</span></div>
                            <ul class="mt-8 space-y-3 text-sm text-slate-200">
                                <li>1 dedicated CPU core</li>
                                <li>2 GB RAM / 40 GB NVMe</li>
                                <li>Global Anycast CDN</li>
                                <li>Advanced DDoS shield</li>
                            </ul>
                            <button class="mt-10 w-full rounded-full border border-white/20 px-6 py-3 text-sm font-semibold text-white transition hover:border-white/40 hover:bg-white/10">Activate</button>
                        </div>
                        <div class="relative rounded-3xl border border-white/30 bg-gradient-to-br from-blue-600/40 via-slate-950/80 to-purple-600/40 p-8 shadow-2xl backdrop-blur">
                            <div class="absolute inset-x-0 -top-5 mx-auto w-fit rounded-full bg-white/20 px-4 py-1 text-xs font-semibold uppercase tracking-wider text-white">Most Requested</div>
                            <div class="text-sm uppercase tracking-wider text-slate-100">Warp</div>
                            <h3 class="mt-4 text-2xl font-semibold text-white">Galactic Grid</h3>
                            <p class="mt-4 text-sm text-slate-100">Run production APIs, multiplayer galaxies, and bursting workloads without compromise.</p>
                            <div class="mt-6 text-4xl font-semibold text-white">$29<span class="text-lg text-slate-200">/mo</span></div>
                            <ul class="mt-8 space-y-3 text-sm text-slate-100">
                                <li>4 dedicated CPU cores</li>
                                <li>8 GB RAM / 160 GB NVMe</li>
                                <li>Edge compute acceleration</li>
                                <li>AI-assisted scaling &amp; support</li>
                            </ul>
                            <button class="mt-10 w-full rounded-full bg-white px-6 py-3 text-sm font-semibold text-slate-900 transition hover:-translate-y-1">Initiate Warp</button>
                        </div>
                        <div class="rounded-3xl border border-white/10 bg-white/5 p-8 shadow-2xl backdrop-blur transition hover:-translate-y-1.5">
                            <div class="text-sm uppercase tracking-wider text-slate-400">Singularity</div>
                            <h3 class="mt-4 text-2xl font-semibold text-white">Quantum Fleet</h3>
                            <p class="mt-4 text-sm text-slate-300">For enterprises orchestrating hyperscale environments with bespoke governance.</p>
                            <div class="mt-6 text-4xl font-semibold text-white">$79<span class="text-lg text-slate-400">/mo</span></div>
                            <ul class="mt-8 space-y-3 text-sm text-slate-200">
                                <li>Dedicated clusters &amp; bare metal</li>
                                <li>32 GB RAM / 1 TB NVMe arrays</li>
                                <li>Private backbone networking</li>
                                <li>Dedicated Sentinel engineering squad</li>
                            </ul>
                            <button class="mt-10 w-full rounded-full border border-white/20 px-6 py-3 text-sm font-semibold text-white transition hover:border-white/40 hover:bg-white/10">Engage Team</button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA -->
            <section class="relative z-20 py-24">
                <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-16">
                    <div class="rounded-3xl border border-white/20 bg-gradient-to-r from-blue-600/30 via-slate-900/80 to-purple-600/30 p-12 text-center shadow-2xl backdrop-blur">
                        <p class="text-sm uppercase tracking-wider text-cyan-200">Ready for launch?</p>
                        <h2 class="mt-4 text-3xl font-semibold text-white">Join trailblazers who trust SEGA-HOST to power their universes.</h2>
                        <p class="mt-6 text-lg text-slate-200">Create an account in seconds, deploy infrastructure in minutes, and scale without friction. Our crew is ready when you are.</p>
                        <div class="mt-10 flex flex-col justify-center gap-4 sm:flex-row">
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="rounded-full bg-white px-8 py-3 text-base font-semibold text-slate-900 shadow-xl transition hover:-translate-y-1">Create Command Center</a>
                            @endif
                            <a href="mailto:hello@sega-host.io" class="rounded-full border border-white/30 px-8 py-3 text-base font-semibold text-white transition hover:border-white/50 hover:bg-white/10">Talk to a Strategist</a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Footer -->
            <footer class="relative z-20 border-t border-white/10 bg-slate-950/90 py-16">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-16">
                    <div class="grid gap-12 md:grid-cols-3">
                        <div>
                            <div class="text-2xl font-semibold text-white">SEGA-HOST</div>
                            <p class="mt-4 text-sm text-slate-400">Architecting the future of hosting with zero-trust infrastructure, human brilliance, and unapologetic speed.</p>
                        </div>
                        <div class="grid gap-8 sm:grid-cols-2 md:grid-cols-2">
                            <div>
                                <h4 class="text-sm font-semibold uppercase tracking-wider text-slate-200">Product</h4>
                                <ul class="mt-4 space-y-3 text-sm text-slate-400">
                                    <li><a href="#" class="transition hover:text-white">VPS Nebula</a></li>
                                    <li><a href="#" class="transition hover:text-white">Dedicated Titans</a></li>
                                    <li><a href="#" class="transition hover:text-white">Cloud Continuum</a></li>
                                    <li><a href="#" class="transition hover:text-white">GameVerse Nodes</a></li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold uppercase tracking-wider text-slate-200">Resources</h4>
                                <ul class="mt-4 space-y-3 text-sm text-slate-400">
                                    <li><a href="#" class="transition hover:text-white">Holo Documentation</a></li>
                                    <li><a href="#" class="transition hover:text-white">Status Matrix</a></li>
                                    <li><a href="#" class="transition hover:text-white">API Reference</a></li>
                                    <li><a href="#" class="transition hover:text-white">Security Trust Center</a></li>
                                </ul>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold uppercase tracking-wider text-slate-200">Stay in orbit</h4>
                            <p class="mt-4 text-sm text-slate-400">Subscribe for launch logs, upgrade briefings, and cosmic inspiration.</p>
                            <form class="mt-6 flex gap-2">
                                <input type="email" placeholder="you@galaxy.dev" class="flex-1 rounded-full border border-white/20 bg-white/5 px-5 py-3 text-sm text-white placeholder:text-slate-500 focus:outline-none">
                                <button type="submit" class="rounded-full bg-gradient-to-r from-blue-600 to-purple-600 px-5 py-3 text-sm font-semibold text-white transition hover:-translate-y-1">Engage</button>
                            </form>
                        </div>
                    </div>
                    <div class="mt-12 border-t border-white/10 pt-6 text-center text-xs text-slate-500">
                        &copy; {{ date('Y') }} SEGA-HOST. All rights reserved.
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
