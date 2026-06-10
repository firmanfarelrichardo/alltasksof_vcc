<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zeta IT Infrastructure | Transformasi Digital Modern</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class', 
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'scroll': 'scroll 35s linear infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                        scroll: {
                            '0%': { transform: 'translateX(0)' },
                            '100%': { transform: 'translateX(-50%)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            overflow-x: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            transition: all 0.3s ease;
        }
        .dark .glass {
            background: rgba(15, 23, 42, 0.85); 
            border-bottom: 1px solid rgba(51, 65, 85, 0.8); 
        }

        .glass-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .dark .glass-card {
            background: #1e293b; 
            border: 1px solid #334155; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);
        }

        .glass-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(37, 99, 235, 0.15), 0 10px 10px -5px rgba(37, 99, 235, 0.04);
            border-color: #bfdbfe; 
        }
        .dark .glass-card:hover {
            box-shadow: 0 20px 25px -5px rgba(6, 182, 212, 0.15), 0 10px 10px -5px rgba(6, 182, 212, 0.04);
            border-color: #06b6d4; 
        }

        .text-gradient {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .dark .text-gradient {
            background: linear-gradient(135deg, #22d3ee 0%, #2dd4bf 100%); 
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        #hero-canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: auto;
            opacity: 0.6;
        }

        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.5, 0, 0, 1);
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f8fafc; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        .dark ::-webkit-scrollbar-track { background: #0f172a; }
        .dark ::-webkit-scrollbar-thumb { background: #334155; }
        .dark ::-webkit-scrollbar-thumb:hover { background: #475569; }
    </style>
    
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="antialiased bg-white text-slate-900 dark:bg-slate-900 dark:text-slate-50 selection:bg-blue-600 dark:selection:bg-cyan-500 selection:text-white transition-colors duration-300">

    <nav class="fixed w-full z-50 transition-all duration-300 glass" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center cursor-pointer">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-900 to-blue-600 dark:from-cyan-500 dark:to-teal-500 flex items-center justify-center mr-3 shadow-md">
                        <i class="ph ph-hexagon-fill text-2xl text-white"></i>
                    </div>
                    <span class="font-extrabold text-2xl tracking-tight text-slate-900 dark:text-white">ZETA<span class="text-blue-600 dark:text-cyan-400">.</span></span>
                </div>
                
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#layanan" class="text-sm font-semibold text-slate-600 hover:text-blue-600 dark:text-slate-300 dark:hover:text-cyan-400 transition-colors">Layanan</a>
                    <a href="#tim" class="text-sm font-semibold text-slate-600 hover:text-blue-600 dark:text-slate-300 dark:hover:text-cyan-400 transition-colors">Tim Kami</a>
                    <a href="#mengapa-kami" class="text-sm font-semibold text-slate-600 hover:text-blue-600 dark:text-slate-300 dark:hover:text-cyan-400 transition-colors">Mengapa Zeta</a>
                    <a href="#kontak" class="px-6 py-2.5 rounded-full bg-slate-900 text-white dark:bg-cyan-500 dark:text-slate-900 font-bold text-sm shadow-md hover:shadow-lg hover:shadow-blue-500/30 dark:hover:shadow-cyan-500/30 transition-all transform hover:-translate-y-0.5">
                        Akses Portal
                    </a>
                    
                    <button id="theme-toggle-desktop" class="w-10 h-10 rounded-full flex items-center justify-center bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 transition-colors focus:outline-none">
                        <i id="theme-icon-desktop" class="ph ph-moon text-xl"></i>
                    </button>
                </div>

                <div class="md:hidden flex items-center space-x-4">
                    <button id="theme-toggle-mobile" class="w-10 h-10 rounded-full flex items-center justify-center bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300 transition-colors focus:outline-none">
                        <i id="theme-icon-mobile" class="ph ph-moon text-xl"></i>
                    </button>
                    <button id="mobile-menu-btn" class="text-slate-900 dark:text-white hover:text-blue-600 dark:hover:text-cyan-400 focus:outline-none">
                        <i class="ph ph-list text-3xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <div id="mobile-menu" class="hidden md:hidden bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 absolute w-full shadow-lg">
            <div class="px-4 pt-2 pb-6 space-y-2">
                <a href="#layanan" class="block px-3 py-2 text-base font-medium text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-cyan-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-md">Layanan</a>
                <a href="#tim" class="block px-3 py-2 text-base font-medium text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-cyan-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-md">Tim Kami</a>
                <a href="#mengapa-kami" class="block px-3 py-2 text-base font-medium text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-cyan-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-md">Mengapa Zeta</a>
                <a href="#kontak" class="block px-3 py-3 mt-4 text-center rounded-lg bg-slate-900 text-white dark:bg-cyan-500 dark:text-slate-900 font-bold shadow-md">Akses Portal</a>
            </div>
        </div>
    </nav>

    <section id="beranda" class="relative min-h-screen flex items-center justify-center pt-20 overflow-hidden">
        <canvas id="hero-canvas"></canvas>
        <div class="absolute inset-0 bg-gradient-to-b from-blue-50/50 via-white/80 to-white dark:from-slate-900/50 dark:via-slate-900/80 dark:to-slate-900 z-10 pointer-events-none transition-colors duration-300"></div>

        <div class="hidden xl:flex absolute top-40 left-10 2xl:left-20 z-20 glass-card px-4 py-3 rounded-2xl items-center gap-3 animate-float shadow-lg" style="animation-duration: 7s;">
            <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                <i class="ph ph-shield-check text-green-600 dark:text-green-400 text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Keamanan</p>
                <p class="text-sm font-extrabold text-slate-900 dark:text-white">Enkripsi Berlapis</p>
            </div>
        </div>

        <div class="hidden xl:flex absolute bottom-40 right-10 2xl:right-20 z-20 glass-card px-4 py-3 rounded-2xl items-center gap-3 animate-float shadow-lg" style="animation-duration: 8s; animation-delay: 1s;">
            <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-cyan-900/30 flex items-center justify-center">
                <i class="ph ph-activity text-blue-600 dark:text-cyan-400 text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Uptime Server</p>
                <p class="text-sm font-extrabold text-slate-900 dark:text-white">99.9% Terjamin</p>
            </div>
        </div>

        <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center mt-10 md:mt-0">
            <div class="inline-flex items-center px-4 py-2 rounded-full border border-blue-200 bg-blue-50 text-blue-600 dark:border-cyan-800/50 dark:bg-slate-800/80 dark:text-cyan-400 text-sm font-semibold mb-8 animate-float shadow-sm backdrop-blur-sm">
                <span class="flex h-2 w-2 rounded-full bg-blue-600 dark:bg-cyan-400 mr-2 animate-pulse"></span>
                Infrastruktur B2B Generasi Baru
            </div>
            
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold tracking-tight mb-6 leading-tight text-slate-900 dark:text-white">
                Membangun Fondasi Digital yang <br class="hidden lg:block" />
                <span class="text-gradient">Tangguh, Aman, & Scalable.</span>
            </h1>
            
            <p class="mt-4 text-lg md:text-xl text-slate-600 dark:text-slate-300 max-w-3xl mx-auto mb-10 leading-relaxed">
                Zeta IT Infrastructure membantu instansi dan bisnis bertransformasi dari sistem tradisional yang rentan menuju ekosistem digital modern yang efisien, terpantau, dan berkeamanan tinggi.
            </p>
            
            <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                <a href="#kontak" class="w-full sm:w-auto px-8 py-4 rounded-full bg-slate-900 text-white dark:bg-cyan-500 dark:text-slate-900 font-bold text-base hover:bg-blue-900 dark:hover:bg-cyan-400 shadow-xl hover:shadow-2xl transition-all flex items-center justify-center group">
                    Masuk ke Platform Portal
                    <i class="ph-bold ph-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                </a>
                <a href="#layanan" class="w-full sm:w-auto px-8 py-4 rounded-full bg-white text-slate-900 border border-slate-200 hover:bg-slate-50 dark:bg-transparent dark:text-white dark:border-slate-700 dark:hover:bg-slate-800 font-bold text-base transition-colors flex items-center justify-center">
                    Pelajari Solusi Kami
                </a>
            </div>
        </div>
        
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex flex-col items-center animate-bounce opacity-70 z-20">
            <span class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Scroll</span>
            <i class="ph ph-arrow-down text-xl text-slate-600 dark:text-slate-300"></i>
        </div>
    </section>

    <section class="py-10 bg-white dark:bg-slate-900/80 border-b border-slate-200 dark:border-slate-800 transition-colors duration-300 overflow-hidden relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6 relative z-20">
            <p class="text-center text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Teknologi & Framework Yang Didukung</p>
        </div>
        
        <div class="relative w-full flex overflow-hidden group">
            <div class="absolute inset-y-0 left-0 w-16 md:w-32 bg-gradient-to-r from-white dark:from-slate-900 to-transparent z-10 pointer-events-none"></div>
            <div class="absolute inset-y-0 right-0 w-16 md:w-32 bg-gradient-to-l from-white dark:from-slate-900 to-transparent z-10 pointer-events-none"></div>
            
            <div class="flex whitespace-nowrap animate-scroll group-hover:[animation-play-state:paused] gap-12 px-6">
                <div class="flex items-center gap-3 text-slate-700 dark:text-slate-300 text-lg font-bold opacity-60 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-300 cursor-pointer"><i class="ph ph-linux-logo text-3xl text-orange-400"></i> Linux Server</div>
                <div class="flex items-center gap-3 text-slate-700 dark:text-slate-300 text-lg font-bold opacity-60 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-300 cursor-pointer"><i class="ph ph-database text-3xl text-blue-500"></i> MySQL DB System</div>
                <div class="flex items-center gap-3 text-slate-700 dark:text-slate-300 text-lg font-bold opacity-60 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-300 cursor-pointer"><i class="ph ph-shield-check text-3xl text-red-500"></i> Next-Gen Firewall</div>
                <div class="flex items-center gap-3 text-slate-700 dark:text-slate-300 text-lg font-bold opacity-60 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-300 cursor-pointer"><i class="ph ph-cloud-network text-3xl text-cyan-500"></i> Cloud Native</div>
                <div class="flex items-center gap-3 text-slate-700 dark:text-slate-300 text-lg font-bold opacity-60 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-300 cursor-pointer"><i class="ph ph-package text-3xl text-blue-400"></i> Node.js API</div>
                
                <div class="flex items-center gap-3 text-slate-700 dark:text-slate-300 text-lg font-bold opacity-60 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-300 cursor-pointer"><i class="ph ph-linux-logo text-3xl text-orange-400"></i> Linux Server</div>
                <div class="flex items-center gap-3 text-slate-700 dark:text-slate-300 text-lg font-bold opacity-60 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-300 cursor-pointer"><i class="ph ph-database text-3xl text-blue-500"></i> MySQL DB System</div>
                <div class="flex items-center gap-3 text-slate-700 dark:text-slate-300 text-lg font-bold opacity-60 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-300 cursor-pointer"><i class="ph ph-shield-check text-3xl text-red-500"></i> Next-Gen Firewall</div>
                <div class="flex items-center gap-3 text-slate-700 dark:text-slate-300 text-lg font-bold opacity-60 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-300 cursor-pointer"><i class="ph ph-cloud-network text-3xl text-cyan-500"></i> Cloud Native</div>
                <div class="flex items-center gap-3 text-slate-700 dark:text-slate-300 text-lg font-bold opacity-60 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-300 cursor-pointer"><i class="ph ph-package text-3xl text-blue-400"></i> Node.js API</div>
            </div>
        </div>
    </section>

    <section class="py-24 relative bg-slate-50 dark:bg-slate-800/50 border-y border-slate-200 dark:border-slate-800 transition-colors duration-300" id="visi-misi">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 reveal">
                <h2 class="text-3xl md:text-4xl font-bold mb-4 text-slate-900 dark:text-white">Mitra Strategis Transformasi Digital Anda</h2>
                <div class="h-1 w-20 bg-blue-600 dark:bg-cyan-500 mx-auto rounded-full"></div>
            </div>

            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="reveal">
                    <div class="glass-card p-10 rounded-3xl relative overflow-hidden h-full flex flex-col justify-center border-t-4 border-t-blue-600 dark:border-t-cyan-500">
                        <div class="absolute -right-10 -top-10 w-40 h-40 bg-blue-100 dark:bg-cyan-900/20 rounded-full blur-3xl"></div>
                        <h3 class="text-2xl font-bold mb-4 text-slate-900 dark:text-white flex items-center relative z-10">
                            <div class="w-12 h-12 rounded-full bg-blue-50 dark:bg-slate-800 text-blue-600 dark:text-cyan-400 flex items-center justify-center mr-4 border border-blue-100 dark:border-slate-700">
                                <i class="ph ph-target text-2xl"></i>
                            </div>
                            Visi Kami
                        </h3>
                        <p class="text-slate-600 dark:text-slate-300 leading-relaxed text-lg relative z-10">
                            Menjadi tulang punggung teknologi terpercaya bagi instansi pemerintahan dan korporasi, mendorong adopsi teknologi mutakhir yang aman dan berkinerja tinggi.
                        </p>
                    </div>
                </div>

                <div class="space-y-6 reveal" style="transition-delay: 200ms;">
                    <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-4 ml-2">Misi Kami</h3>
                    
                    <div class="flex items-start bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex-shrink-0 flex items-center justify-center w-14 h-14 rounded-xl bg-blue-50 dark:bg-cyan-900/30 text-blue-600 dark:text-cyan-400 mr-5">
                            <i class="ph ph-rocket-launch text-3xl"></i>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-slate-900 dark:text-white">Akselerasi Performa</h4>
                            <p class="text-slate-600 dark:text-slate-400 mt-2 text-sm leading-relaxed">Menyediakan arsitektur server dan jaringan yang dirancang untuk kecepatan tanpa kompromi.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex-shrink-0 flex items-center justify-center w-14 h-14 rounded-xl bg-indigo-50 dark:bg-teal-900/30 text-indigo-600 dark:text-teal-400 mr-5">
                            <i class="ph ph-shield-check text-3xl"></i>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-slate-900 dark:text-white">Keamanan Tanpa Kompromi</h4>
                            <p class="text-slate-600 dark:text-slate-400 mt-2 text-sm leading-relaxed">Menerapkan perlindungan berlapis dan audit proaktif terhadap aset digital klien.</p>
                        </div>
                    </div>

                    <div class="flex items-start bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex-shrink-0 flex items-center justify-center w-14 h-14 rounded-xl bg-sky-50 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400 mr-5">
                            <i class="ph ph-chart-line-up text-3xl"></i>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-slate-900 dark:text-white">Eskalasi Bisnis</h4>
                            <p class="text-slate-600 dark:text-slate-400 mt-2 text-sm leading-relaxed">Merancang infrastruktur yang dapat bertumbuh sejalan dengan skala instansi atau bisnis Anda.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 relative bg-white dark:bg-slate-900 transition-colors duration-300" id="layanan">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 reveal">
                <span class="text-blue-600 dark:text-cyan-400 font-bold tracking-wider text-sm uppercase">Core Solutions</span>
                <h2 class="text-3xl md:text-4xl font-extrabold mt-2 mb-4 text-slate-900 dark:text-white">Solusi Infrastruktur End-to-End</h2>
                <p class="text-slate-600 dark:text-slate-400 max-w-2xl mx-auto text-lg">Kami menyediakan ekosistem teknologi lengkap yang dirancang khusus untuk reliabilitas dan keamanan tingkat tinggi.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
                <div class="glass-card p-8 rounded-3xl reveal group">
                    <div class="w-16 h-16 rounded-2xl bg-blue-50 dark:bg-cyan-900/30 flex items-center justify-center mb-6 group-hover:-translate-y-2 transition-transform duration-300 border border-blue-100 dark:border-cyan-800/50">
                        <i class="ph ph-hard-drives text-4xl text-blue-600 dark:text-cyan-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Modernisasi & Virtualisasi Server</h3>
                    <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
                        Optimalkan hardware Anda dengan teknologi virtualisasi (berbasis Ubuntu Server). Tekan biaya pemeliharaan, mudahkan backup, dan maksimalkan kinerja.
                    </p>
                </div>

                <div class="glass-card p-8 rounded-3xl reveal group" style="transition-delay: 100ms;">
                    <div class="w-16 h-16 rounded-2xl bg-indigo-50 dark:bg-teal-900/30 flex items-center justify-center mb-6 group-hover:-translate-y-2 transition-transform duration-300 border border-indigo-100 dark:border-teal-800/50">
                        <i class="ph ph-share-network text-4xl text-indigo-600 dark:text-teal-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Arsitektur Jaringan & Konektivitas</h3>
                    <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
                        Desain topologi canggih dengan tunneling privat dan manajemen DNS internal terpusat. Data antar cabang dijamin rahasia, stabil, dan optimal.
                    </p>
                </div>

                <div class="glass-card p-8 rounded-3xl reveal group" style="transition-delay: 200ms;">
                    <div class="w-16 h-16 rounded-2xl bg-sky-50 dark:bg-sky-900/30 flex items-center justify-center mb-6 group-hover:-translate-y-2 transition-transform duration-300 border border-sky-100 dark:border-sky-800/50">
                        <i class="ph ph-lock-key text-4xl text-sky-600 dark:text-sky-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Audit Keamanan & Pertahanan</h3>
                    <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
                        Evaluasi mendalam kerentanan sistem. Kami menerapkan firewall tingkat lanjut dan traffic rules ketat untuk mencegah intrusi siber.
                    </p>
                </div>

                <div class="glass-card p-8 rounded-3xl reveal group" style="transition-delay: 300ms;">
                    <div class="w-16 h-16 rounded-2xl bg-cyan-50 dark:bg-blue-900/30 flex items-center justify-center mb-6 group-hover:-translate-y-2 transition-transform duration-300 border border-cyan-100 dark:blue-800/50">
                        <i class="ph ph-chart-polar text-4xl text-cyan-600 dark:text-blue-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Pemantauan Infrastruktur Proaktif</h3>
                    <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
                        Pusat komando (dashboard) real-time untuk memantau kesehatan server dan jaringan. Identifikasi bottleneck sebelum menjadi masalah.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 bg-slate-50 dark:bg-slate-800/50 relative border-y border-slate-200 dark:border-slate-800 transition-colors duration-300" id="tim">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20 reveal">
                <span class="text-blue-600 dark:text-cyan-400 font-bold tracking-wider text-sm uppercase">The Dream Team</span>
                <h2 class="text-3xl md:text-4xl font-extrabold mt-2 mb-4 text-slate-900 dark:text-white">Di Balik Infrastruktur Anda</h2>
                <div class="h-1 w-20 bg-blue-600 dark:bg-cyan-500 mx-auto rounded-full mt-6"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 lg:gap-12">
                <div class="group reveal glass-card p-4 rounded-[2rem]">
                    <div class="relative overflow-hidden rounded-[1.5rem] aspect-[4/5] mb-6 shadow-sm bg-slate-200 dark:bg-slate-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/30 to-transparent z-10 opacity-70 group-hover:opacity-60 transition-opacity duration-300"></div>
                        <img src="https://placehold.co/600x800/e2e8f0/1e3a8a?text=Firman+Farel" alt="Firman Farel Richardo" class="object-cover w-full h-full transform group-hover:scale-105 transition-transform duration-700 grayscale group-hover:grayscale-0">
                    </div>
                    <div class="text-center px-2 pb-4">
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white">Firman Farel Richardo</h3>
                        <p class="text-blue-600 dark:text-cyan-400 font-semibold mt-1 mb-3 text-sm">DNS & Network Architecture</p>
                        <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">Memastikan rute lalu lintas data yang paling efisien dan manajemen penamaan domain internal yang stabil.</p>
                    </div>
                </div>

                <div class="group reveal glass-card p-4 rounded-[2rem]" style="transition-delay: 150ms;">
                    <div class="relative overflow-hidden rounded-[1.5rem] aspect-[4/5] mb-6 shadow-sm bg-slate-200 dark:bg-slate-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/30 to-transparent z-10 opacity-70 group-hover:opacity-60 transition-opacity duration-300"></div>
                        <img src="https://placehold.co/600x800/e2e8f0/1e3a8a?text=Muhammad+Farhan" alt="Muhammad Farhan" class="object-cover w-full h-full transform group-hover:scale-105 transition-transform duration-700 grayscale group-hover:grayscale-0">
                    </div>
                    <div class="text-center px-2 pb-4">
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white">Muhammad Farhan</h3>
                        <p class="text-blue-600 dark:text-cyan-400 font-semibold mt-1 mb-3 text-sm">Web Server & Virtualization</p>
                        <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">Ahli dalam mentransformasi server fisik menjadi ekosistem virtual yang high-performance dan scalable.</p>
                    </div>
                </div>

                <div class="group reveal glass-card p-4 rounded-[2rem]" style="transition-delay: 300ms;">
                    <div class="relative overflow-hidden rounded-[1.5rem] aspect-[4/5] mb-6 shadow-sm bg-slate-200 dark:bg-slate-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/30 to-transparent z-10 opacity-70 group-hover:opacity-60 transition-opacity duration-300"></div>
                        <img src="https://placehold.co/600x800/e2e8f0/1e3a8a?text=Arza+Restu+Arjuna" alt="Arza Restu Arjuna" class="object-cover w-full h-full transform group-hover:scale-105 transition-transform duration-700 grayscale group-hover:grayscale-0">
                    </div>
                    <div class="text-center px-2 pb-4">
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white">Arza Restu Arjuna</h3>
                        <p class="text-blue-600 dark:text-cyan-400 font-semibold mt-1 mb-3 text-sm">DB Infrastructure & Security</p>
                        <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">Mengarsiteki penyimpanan data yang aman, terintegrasi, dan memiliki integritas tingkat tinggi.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 bg-white dark:bg-slate-900 transition-colors duration-300" id="mengapa-kami">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 reveal">
                <h2 class="text-3xl md:text-4xl font-extrabold mb-4 text-slate-900 dark:text-white">Keunggulan Zeta IT</h2>
                <p class="text-slate-600 dark:text-slate-400 text-lg max-w-2xl mx-auto">Mengapa instansi dan bisnis terkemuka mempercayakan fondasi teknologinya pada kami?</p>
            </div>

            <div class="flex flex-col gap-8">
                <div class="glass-card p-8 md:p-10 rounded-[2rem] flex flex-col md:flex-row items-center md:items-start gap-8 reveal">
                    <div class="flex-shrink-0 w-24 h-24 rounded-3xl bg-blue-50 dark:bg-cyan-900/30 flex items-center justify-center border border-blue-100 dark:border-cyan-800/50 shadow-sm">
                        <i class="ph ph-cpu text-5xl text-blue-600 dark:text-cyan-400"></i>
                    </div>
                    <div class="text-center md:text-left flex-1 mt-2">
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">Pendekatan Berbasis Data</h3>
                        <p class="text-slate-600 dark:text-slate-400 text-lg leading-relaxed">Keputusan arsitektur didasarkan pada hasil audit yang mendalam dan simulasi performa presisi, bukan sekadar perkiraan. Kami mengukur sebelum membangun.</p>
                    </div>
                </div>

                <div class="glass-card p-8 md:p-10 rounded-[2rem] flex flex-col md:flex-row items-center md:items-start gap-8 reveal" style="transition-delay: 100ms;">
                    <div class="flex-shrink-0 w-24 h-24 rounded-3xl bg-indigo-50 dark:bg-teal-900/30 flex items-center justify-center border border-indigo-100 dark:border-teal-800/50 shadow-sm">
                        <i class="ph ph-shield-star text-5xl text-indigo-600 dark:text-teal-400"></i>
                    </div>
                    <div class="text-center md:text-left flex-1 mt-2">
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">Security by Design</h3>
                        <p class="text-slate-600 dark:text-slate-400 text-lg leading-relaxed">Keamanan adalah prioritas utama sejak kabel pertama ditarik dan server dinyalakan. Infrastruktur dirancang dengan ketahanan bawaan (inherent security) terhadap ancaman modern.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 relative overflow-hidden bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-800 transition-colors duration-300" id="kontak">
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-blue-100/50 dark:bg-cyan-900/20 rounded-full blur-[100px] pointer-events-none transform translate-x-1/3 -translate-y-1/3 transition-colors duration-500"></div>
        <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-sky-100/50 dark:bg-teal-900/20 rounded-full blur-[100px] pointer-events-none transform -translate-x-1/3 translate-y-1/3 transition-colors duration-500"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="bg-white dark:bg-slate-900 p-10 md:p-16 rounded-[2.5rem] text-center shadow-[0_20px_50px_-12px_rgba(0,0,0,0.1)] border border-slate-200 dark:border-slate-700 transition-colors duration-300">
                <div class="w-20 h-20 bg-blue-50 dark:bg-cyan-900/30 rounded-2xl flex items-center justify-center mx-auto mb-8 border border-blue-100 dark:border-cyan-800/50 shadow-inner">
                    <i id="portal-icon" class="ph ph-lock text-4xl text-blue-600 dark:text-cyan-400"></i>
                </div>
                
                <h2 id="portal-title" class="text-4xl md:text-5xl font-extrabold mb-2 text-slate-900 dark:text-white leading-tight">Zeta Cloud Portal</h2>
                <p id="portal-subtitle" class="text-base text-slate-500 dark:text-slate-400 mb-10 max-w-2xl mx-auto">Silakan masuk untuk mengakses manajemen infrastruktur jaringan Anda.</p>
                
                <form id="authForm" class="max-w-md mx-auto space-y-5 text-left">
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 dark:text-slate-300 mb-2">Business Email</label>
                        <input type="email" id="authEmail" required class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3.5 text-slate-900 dark:text-white focus:outline-none focus:border-blue-500 dark:focus:border-cyan-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-cyan-500/20 transition-all shadow-sm" placeholder="email@domain.com">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 dark:text-slate-300 mb-2">Password</label>
                        <input type="password" id="authPassword" required class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3.5 text-slate-900 dark:text-white focus:outline-none focus:border-blue-500 dark:focus:border-cyan-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-cyan-500/20 transition-all shadow-sm" placeholder="••••••••">
                    </div>
                    
                    <div id="authAlert" class="hidden p-4 rounded-xl text-sm font-medium"></div>

                    <button type="submit" id="submitBtn" class="w-full mt-4 bg-slate-900 text-white dark:bg-cyan-500 dark:text-slate-900 font-bold py-4 px-8 rounded-xl hover:bg-blue-900 dark:hover:bg-cyan-400 hover:shadow-lg hover:shadow-blue-500/30 dark:hover:shadow-cyan-500/30 transition-all transform hover:-translate-y-1 flex items-center justify-center">
                        <i class="ph ph-sign-in mr-2 text-xl"></i> <span id="btnText">Sign In ke Portal</span>
                    </button>

                    <div class="text-center mt-6">
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            <span id="switchText">Belum memiliki akses kredensial?</span>
                            <button type="button" id="switchModeBtn" class="text-blue-600 dark:text-cyan-400 font-bold hover:underline focus:outline-none ml-1">Minta Akses (Register)</button>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <footer class="bg-white dark:bg-slate-900 pt-16 pb-8 border-t border-slate-200 dark:border-slate-800 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
                <div class="col-span-1 md:col-span-1">
                    <div class="flex items-center mb-4">
                        <i class="ph ph-hexagon-fill text-3xl text-blue-600 dark:text-cyan-400 mr-2"></i>
                        <span class="font-extrabold text-2xl tracking-tight text-slate-900 dark:text-white">ZETA<span class="text-blue-600 dark:text-cyan-400">.</span></span>
                    </div>
                    <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed mb-6">
                        Menjadi tulang punggung teknologi terpercaya bagi instansi pemerintahan dan korporasi, mendorong adopsi teknologi mutakhir yang aman dan berkinerja tinggi.
                    </p>
                </div>

                <div class="col-span-1 md:col-span-1">
                    <h4 class="text-slate-900 dark:text-white font-bold mb-4 uppercase tracking-wider text-sm">Pintasan</h4>
                    <ul class="space-y-3">
                        <li><a href="#layanan" class="text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-cyan-400 text-sm transition-colors">Solusi Infrastruktur</a></li>
                        <li><a href="#tim" class="text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-cyan-400 text-sm transition-colors">Tim Kami</a></li>
                        <li><a href="#kontak" class="text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-cyan-400 text-sm transition-colors">Portal Akses</a></li>
                    </ul>
                </div>

                <div class="col-span-1 md:col-span-1 bg-slate-50 dark:bg-slate-800/50 p-6 rounded-2xl border border-slate-200 dark:border-slate-700">
                    <h4 class="text-slate-900 dark:text-white font-bold mb-4 uppercase tracking-wider text-sm flex items-center">
                        <i class="ph ph-hard-drives text-blue-600 dark:text-cyan-400 mr-2 text-lg"></i> Data Center & DNS
                    </h4>
                    <ul class="space-y-3 font-mono text-xs">
                        <li class="flex justify-between items-center border-b border-slate-200 dark:border-slate-700 pb-2">
                            <span class="text-slate-500 dark:text-slate-400">Primary Domain</span>
                            <span class="text-slate-900 dark:text-slate-200 font-bold bg-white dark:bg-slate-900 px-2 py-1 rounded">zeta.informatika.site</span>
                        </li>
                        <li class="flex justify-between items-center pt-1">
                            <span class="text-slate-500 dark:text-slate-400">Status Node</span>
                            <span class="inline-flex items-center text-green-600 dark:text-green-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5 animate-pulse"></span> Online
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-200 dark:border-slate-800 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-slate-500 dark:text-slate-400 text-sm text-center md:text-left font-medium">
                    Hak Cipta &copy; 2026 Zeta IT Infrastructure. All rights reserved.
                </p>
                <div class="flex items-center gap-2 text-xs text-slate-400 dark:text-slate-500">
                    <i class="ph ph-shield-check"></i> Secured by Zeta Next-Gen Firewall
                </div>
            </div>
        </div>
    </footer>

    <script>
        const API_URL = "api.php?endpoint=/auth-api/auth.php";

        let isLoginMode = true;

        const authForm = document.getElementById("authForm");
        const switchModeBtn = document.getElementById("switchModeBtn");
        const portalTitle = document.getElementById("portal-title");
        const portalSubtitle = document.getElementById("portal-subtitle");
        const portalIcon = document.getElementById("portal-icon");
        const btnText = document.getElementById("btnText");
        const switchText = document.getElementById("switchText");
        const authAlert = document.getElementById("authAlert");

        // Fungsi switch mode Login / Register
        switchModeBtn.addEventListener("click", () => {
            isLoginMode = !isLoginMode;
            authAlert.classList.add("hidden");
            
            if (isLoginMode) {
                portalTitle.innerText = "Zeta Cloud Portal";
                portalSubtitle.innerText = "Silakan masuk untuk mengakses manajemen infrastruktur jaringan Anda.";
                portalIcon.className = "ph ph-lock text-4xl text-blue-600 dark:text-cyan-400";
                btnText.innerText = "Sign In ke Portal";
                switchText.innerText = "Belum memiliki akses kredensial?";
                switchModeBtn.innerText = "Minta Akses (Register)";
            } else {
                portalTitle.innerText = "Request Node Access";
                portalSubtitle.innerText = "Daftarkan email instansi Anda untuk membuat kredensial database baru.";
                portalIcon.className = "ph ph-user-plus text-4xl text-blue-600 dark:text-cyan-400";
                btnText.innerText = "Register Akun Baru";
                switchText.innerText = "Sudah terdaftar di database?";
                switchModeBtn.innerText = "Sign In Disini";
            }
        });

        // Handler kirim data ke API Node.js
        authForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            
            const email = document.getElementById("authEmail").value;
            const password = document.getElementById("authPassword").value;
            
            // Sembunyikan alert lama
            authAlert.className = "hidden p-4 rounded-xl text-sm font-medium";
        
            try {
                // Tembak LANGSUNG ke auth.php tanpa embel-embel ?endpoint=
                const response = await fetch(API_URL, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    // Kita kirim data email, password, DAN action (login/register) di dalam body
                    body: JSON.stringify({ 
                        action: isLoginMode ? "login" : "register", 
                        email: email, 
                        password: password 
                    })
                });
        
                const data = await response.json();
        
                if (response.ok) {
                    // Jika login/register sukses (Status 200 / 201)
                    authAlert.classList.remove("hidden");
                    authAlert.classList.add("bg-green-100", "text-green-800", "dark:bg-green-900/30", "dark:text-green-400");
                    authAlert.innerText = data.message;
                    
                    if(isLoginMode) {
                        setTimeout(() => {
                            alert("Selamat datang di Portal Utama Zeta Infrastructure!");
                        }, 500);
                    } else {
                        setTimeout(() => {
                            switchModeBtn.click();
                            authForm.reset();
                        }, 1500);
                    }
                } else {
                    // Jika server merespon gagal (Status 400, 401, 500)
                    authAlert.classList.remove("hidden");
                    authAlert.classList.add("bg-red-100", "text-red-800", "dark:bg-red-900/30", "dark:text-red-400");
                    authAlert.innerText = data.message || "Email atau password salah.";
                }
        
            } catch (error) {
                // Blok ini HANYA berjalan jika file auth.php TIDAK ADA atau server mati total
                authAlert.classList.remove("hidden");
                authAlert.classList.add("bg-yellow-100", "text-yellow-800", "dark:bg-yellow-900/30", "dark:text-yellow-400");
                authAlert.innerText = "Gagal memproses request. Pastikan file auth.php berada di folder yang sama!";
                console.error("Fetch Error:", error);
            }
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script>
        const mobileBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        mobileBtn.addEventListener('click', () => { mobileMenu.classList.toggle('hidden'); });

        const revealElements = document.querySelectorAll('.reveal');
        const revealObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if(entry.isIntersecting) {
                    entry.target.classList.add('active');
                    observer.unobserve(entry.target);
                }
            });
        }, { root: null, threshold: 0.1, rootMargin: "0px 0px -50px 0px" });
        revealElements.forEach(el => revealObserver.observe(el));

        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                if (document.documentElement.classList.contains('dark')) {
                    navbar.classList.add('bg-slate-900/95', 'shadow-md');
                } else {
                    navbar.classList.add('bg-white/95', 'shadow-md');
                }
                navbar.classList.remove('glass');
            } else {
                navbar.classList.add('glass');
                navbar.classList.remove('bg-white/95', 'bg-slate-900/95', 'shadow-md');
            }
        });

        const themeToggleDesktop = document.getElementById('theme-toggle-desktop');
        const themeIconDesktop = document.getElementById('theme-icon-desktop');
        const themeToggleMobile = document.getElementById('theme-toggle-mobile');
        const themeIconMobile = document.getElementById('theme-icon-mobile');

        function updateIcons(isDark) {
            if (isDark) {
                themeIconDesktop.classList.replace('ph-moon', 'ph-sun');
                themeIconMobile.classList.replace('ph-moon', 'ph-sun');
            } else {
                themeIconDesktop.classList.replace('ph-sun', 'ph-moon');
                themeIconMobile.classList.replace('ph-sun', 'ph-moon');
            }
        }

        const initialIsDark = document.documentElement.classList.contains('dark');
        updateIcons(initialIsDark);

        function toggleTheme() {
            const htmlClassList = document.documentElement.classList;
            const isDark = htmlClassList.contains('dark');

            if (isDark) {
                htmlClassList.remove('dark');
                localStorage.setItem('theme', 'light');
                updateIcons(false);
                if(window.updateThreeJSTheme) window.updateThreeJSTheme(false);
                window.dispatchEvent(new Event('scroll'));
            } else {
                htmlClassList.add('dark');
                localStorage.setItem('theme', 'dark');
                updateIcons(true);
                if(window.updateThreeJSTheme) window.updateThreeJSTheme(true);
                window.dispatchEvent(new Event('scroll'));
            }
        }

        themeToggleDesktop.addEventListener('click', toggleTheme);
        themeToggleMobile.addEventListener('click', toggleTheme);

        window.onload = function() { initThreeJS(); }

        function initThreeJS() {
            const canvas = document.getElementById('hero-canvas');
            const renderer = new THREE.WebGLRenderer({ canvas: canvas, alpha: true, antialias: true });
            const container = document.getElementById('beranda');
            renderer.setSize(container.clientWidth, container.clientHeight);
            renderer.setPixelRatio(window.devicePixelRatio);

            const scene = new THREE.Scene();
            const camera = new THREE.PerspectiveCamera(75, container.clientWidth / container.clientHeight, 0.1, 1000);
            camera.position.z = 150;

            const particlesGeometry = new THREE.BufferGeometry();
            const particlesCount = 200; 
            const posArray = new Float32Array(particlesCount * 3);
            const velocityArray = [];

            for(let i = 0; i < particlesCount * 3; i++) { posArray[i] = (Math.random() - 0.5) * 400; }
            for(let i=0; i<particlesCount; i++) {
                velocityArray.push({
                    x: (Math.random() - 0.5) * 0.2,
                    y: (Math.random() - 0.5) * 0.2,
                    z: (Math.random() - 0.5) * 0.2
                });
            }

            particlesGeometry.setAttribute('position', new THREE.BufferAttribute(posArray, 3));
            const isCurrentlyDark = document.documentElement.classList.contains('dark');
            
            const particlesMaterial = new THREE.PointsMaterial({
                size: 2,
                color: isCurrentlyDark ? 0x06b6d4 : 0x2563eb,
                transparent: true,
                opacity: 0.6,
                blending: isCurrentlyDark ? THREE.AdditiveBlending : THREE.NormalBlending
            });

            const particlesMesh = new THREE.Points(particlesGeometry, particlesMaterial);
            scene.add(particlesMesh);

            const lineMaterial = new THREE.LineBasicMaterial({
                color: isCurrentlyDark ? 0x0891b2 : 0x93c5fd,
                transparent: true,
                opacity: isCurrentlyDark ? 0.4 : 0.3
            });

            window.updateThreeJSTheme = function(isDark) {
                if (isDark) {
                    particlesMaterial.color.setHex(0x06b6d4);
                    particlesMaterial.blending = THREE.AdditiveBlending;
                    lineMaterial.color.setHex(0x0891b2);
                    lineMaterial.opacity = 0.4;
                } else {
                    particlesMaterial.color.setHex(0x2563eb);
                    particlesMaterial.blending = THREE.NormalBlending;
                    lineMaterial.color.setHex(0x93c5fd);
                    lineMaterial.opacity = 0.3;
                }
                particlesMaterial.needsUpdate = true;
                lineMaterial.needsUpdate = true;
            };

            let mouseX = 0; let mouseY = 0;
            const windowHalfX = window.innerWidth / 2;
            const windowHalfY = window.innerHeight / 2;

            document.addEventListener('mousemove', (event) => {
                mouseX = (event.clientX - windowHalfX);
                mouseY = (event.clientY - windowHalfY);
            });

            function animate() {
                requestAnimationFrame(animate);
                particlesMesh.rotation.y += 0.0005;
                particlesMesh.rotation.x += 0.0002;

                camera.position.x += (mouseX * 0.02 - camera.position.x) * 0.05;
                camera.position.y += (-mouseY * 0.02 - camera.position.y) * 0.05;
                camera.lookAt(scene.position);

                const positions = particlesGeometry.attributes.position.array;
                for(let i=0; i<particlesCount; i++) {
                    positions[i*3] += velocityArray[i].x;
                    positions[i*3+1] += velocityArray[i].y;
                    positions[i*3+2] += velocityArray[i].z;

                    if(positions[i*3] > 200 || positions[i*3] < -200) velocityArray[i].x *= -1;
                    if(positions[i*3+1] > 200 || positions[i*3+1] < -200) velocityArray[i].y *= -1;
                    if(positions[i*3+2] > 200 || positions[i*3+2] < -200) velocityArray[i].z *= -1;
                }
                particlesGeometry.attributes.position.needsUpdate = true;

                const oldLines = scene.children.filter(child => child.type === "LineSegments");
                oldLines.forEach(line => { scene.remove(line); line.geometry.dispose(); });

                const lineGeometry = new THREE.BufferGeometry();
                const linePositions = [];
                
                for (let i = 0; i < particlesCount; i++) {
                    for (let j = i + 1; j < particlesCount; j++) {
                        const dx = positions[i * 3] - positions[j * 3];
                        const dy = positions[i * 3 + 1] - positions[j * 3 + 1];
                        const dz = positions[i * 3 + 2] - positions[j * 3 + 2];
                        const dist = Math.sqrt(dx * dx + dy * dy + dz * dz);

                        if (dist < 40) { 
                            linePositions.push(
                                positions[i * 3], positions[i * 3 + 1], positions[i * 3 + 2],
                                positions[j * 3], positions[j * 3 + 1], positions[j * 3 + 2]
                            );
                        }
                    }
                }

                if(linePositions.length > 0) {
                    lineGeometry.setAttribute('position', new THREE.Float32BufferAttribute(linePositions, 3));
                    const lines = new THREE.LineSegments(lineGeometry, lineMaterial);
                    scene.add(lines);
                }
                renderer.render(scene, camera);
            }
            animate();

            window.addEventListener('resize', () => {
                camera.aspect = container.clientWidth / container.clientHeight;
                camera.updateProjectionMatrix();
                renderer.setSize(container.clientWidth, container.clientHeight);
            });
        }
    </script>
</body>
</html>
