<!DOCTYPE html>
  <html lang="{{ str_replace('_','-',app()->getLocale()) }}">
  <head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1" />
      <title>@yield('title',$page) • EMS</title>
      <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
      <style>[x-cloak]{display:none!important}</style>
      @vite(['resources/css/app.css','resources/js/app.js'])
  </head>

  <style>
    input::placeholder {
      color: #000;
      opacity: 0.8;
    }
  </style>

  <body class="bg-gray-50 text-gray-900">
    @if(isset($page) && $page === 'Authentication')
      <div class="min-h-screen flex items-center justify-center p-4 md:p-6">
        @yield('content')
      </div>
    @else
      <div x-data="{ open:false }" class="min-h-screen">

        {{-- Sidebar --}}
        <aside
          class="fixed inset-y-0 left-0 z-40 w-72 border-r border-gray-200 p-4 transition-transform -translate-x-full md:translate-x-0 bg-white"
          :class="{ 'translate-x-0': open }"
        >
          <div class="flex items-center gap-2 mb-8">
            <div class="h-8 w-8 flex items-center justify-center flex-shrink-0">
              <img src="{{ asset('images/logo.png') }}" class="h-full w-full object-contain" alt="">
            </div>
            <h2 
              class="font-bold text-[20px] h-8 flex items-center" 
              style="
                letter-spacing: 8px;
                color: #430000;
                text-shadow: 1px 1px 5px #430000;
              "
            >
              EMS
            </h2>
          </div>

          <nav class="space-y-1 text-sm">
            <a href="{{ route('dashboard') }}" class="no-underline flex items-center justify-between rounded-xl px-3 py-2 font-semibold {{ $page === 'Dashboard' ? 'bg-[#622733] text-[#fff] hover:bg-[#01044e]' : 'text-[#430000] hover:bg-indigo-50' }}">Dashboard</a>
            @if(auth()->user()?->is_organizer)
              <a href="{{ url('/my-events') }}" class="no-underline flex items-center justify-between rounded-xl px-3 py-2 font-semibold {{ $page === 'My Events' ? 'bg-[#622733] text-[#fff] hover:bg-[#01044e]' : 'text-[#430000] hover:bg-indigo-50' }}">My Events</a>
            @endif
            <a href="{{ url('/invitations') }}" class="no-underline flex items-center justify-between rounded-xl px-3 py-2 font-semibold {{ $page === 'Invitations' ? 'bg-[#622733] text-[#fff] hover:bg-[#01044e]' : 'text-[#430000] hover:bg-indigo-50' }}">Invitations</a>
            <a href="{{ url('/discover') }}" class="no-underline flex items-center justify-between rounded-xl px-3 py-2 font-semibold {{ $page === 'Discover' ? 'bg-[#622733] text-[#fff] hover:bg-[#01044e]' : 'text-[#430000] hover:bg-indigo-50' }}">Discover</a>
            <a href="{{ url('/profile') }}" class="no-underline flex items-center justify-between rounded-xl px-3 py-2 font-semibold {{ $page === 'Profile' ? 'bg-[#622733] text-[#fff] hover:bg-[#01044e]' : 'text-[#430000] hover:bg-indigo-50' }}">Profile</a>
            @if(auth()->user()?->is_organizer)
              <div class="pt-4">
                <a href="{{ url('/create/events') }}" class="no-underline inline-flex items-center justify-center gap-2 rounded-xl bg-[#01044e] font-semibold px-4 py-2 text-white hover:bg-[#622733]">
                  <img src="{{ asset('images/add.svg') }}" class="h-4 w-4" alt="Plus Icon" />
                  Create Event
                </a>
              </div>
            @endif
          </nav>

          <div class="absolute bottom-4 left-4 right-4 rounded-xl border border-gray-200 p-3 bg-gray-50">
            <div class="text-[12px] text-[#01044e] font-bold mb-1">Pro Tip</div>
            <div class="text-[12px] text-[#01044e] text-justify">Events with banners and clear agendas get 2–3× more attendees. Ship that poster today.</div>
          </div>
        </aside>

        {{-- Backdrop (mobile only) --}}
        <div
          x-show="open"
          x-transition.opacity
          @click="open=false"
          class="fixed inset-0 z-30 bg-black/40 md:hidden"
        ></div>

        {{-- Main --}}
        <div class="md:ml-72">
          {{-- Topbar --}}
          <header class="sticky top-0 z-20 bg-white border-b border-gray-200">
            <div class="flex items-center justify-end gap-3 px-4 py-3">
              <button @click="open=!open" class="md:hidden rounded-lg p-2 hover:bg-gray-100">
                <img src="{{ asset('images/burger.svg') }}" class="h-5 w-5 opacity-50" alt="Menu" />
              </button>
              @if($page === 'Dashboard' || $page === 'My Events' || $page === 'Invitations' || $page === 'Discover')
                <div class="flex-1 max-w-xl">
                  <form 
                    method="GET" 
                    action="{{ $page === 'Discover' || $page === 'Dashboard' ? url('/discover') : ($page === 'Invitations' ? url('/invitations') : ($page === 'My Events' ? url('/my-events') : '#')) }}"
                    class="w-full"
                  >
                    <div class="relative w-full">
                      <input 
                        class="w-full rounded-xl border-gray-200 bg-gray-200 pl-10 pr-3 py-2 focus:bg-white focus:border-[#01044e] focus:ring-[#01044e] text-[14px]"
                        value="{{ request('q') }}"
                        name="q" 
                        placeholder="Search events, hosts, tags…" 
                      />
                      <img src="{{ asset('images/search.svg') }}" class="absolute left-3 top-1/2 -translate-y-1/2 h-[14px] w-[14px] opacity-80" alt="Search Icon" />
                    </div>
                  </form>
                </div>
              @endif
              <div class="flex items-center gap-3">
                  <a href="{{ url('/invitations') }}" class="relative rounded-lg p-2 hover:bg-gray-100">
                      <span class="absolute -top-1 -right-1 inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-[#01044e] text-white text-xs px-1">{{ auth()->user()->invitations()->where('status', 'pending')->count() }}</span>
                      <img src="{{ asset('images/invitation.svg') }}" class="h-5 w-5" alt="Invitations" />
                  </a>
                  <a href="{{ url('/profile') }}" class="no-underline flex items-center gap-2 rounded-lg px-2 py-1 hover:bg-gray-100">
                      <img src="{{ asset('images/account.png') }}" class="h-8 w-8 rounded-full" style="border: 2px solid gray-300;" alt="avatar">
                      <span class="hidden sm:block text-md font-bold" style="color: #430000">{{ explode(' ', Auth::user()->name)[0] }}</span>
                  </a>
              </div>
            </div>
          </header>

          {{-- Content --}}
          <main class="p-4 md:p-6">
            @yield('content')
          </main>
        </div>
      </div>
    @endif
  </body>
</html>
