<!-- Start Sidebar -->
<aside id="app-menu"
    class="hs-overlay fixed inset-y-0 start-0 z-60 hidden w-sidenav min-w-sidenav bg-primary-900 overflow-y-auto -translate-x-full transform transition-all duration-200 hs-overlay-open:translate-x-0 lg:bottom-0 lg:end-auto lg:z-30 lg:block lg:translate-x-0 rtl:translate-x-full rtl:hs-overlay-open:translate-x-0 rtl:lg:translate-x-0 print:hidden [--body-scroll:true] [--overlay-backdrop:true] lg:[--overlay-backdrop:false]">

    <div class="flex flex-col h-full">
        <!-- Sidenav Logo -->
        <div class="sticky top-0 flex h-topbar items-center justify-between px-6">
            <a href="{{ env('APP_URL') }}">
                <img  src="{{ asset('images/logo-light.png') }}" alt="logo" class="flex" width="150">
            </a>
        </div>

        <div class="p-4 h-[calc(100%-theme('spacing.topbar'))] flex-grow" data-simplebar>
            <!-- Menu -->
            <ul class="admin-menu hs-accordion-group flex w-full flex-col gap-1">
                @if(session()->has('permisos') && !empty(session('permisos')))
                    @foreach(session('permisos') as $menu)
                        <li class="menu-item hs-accordion" id="menu-{{ $menu['modulo'] }}">
                            <a href="javascript:void(0)"
                                class="hs-accordion-toggle group flex items-center gap-x-3.5 rounded-md px-3 py-2 text-sm font-medium text-default-100 transition-all hover:bg-default-100/5 hs-accordion-active:bg-default-100/5 hs-accordion-active:text-default-100">

                                {!! $menu['icono'] ?? "<i class='bx bx-folder-cog'></i>" !!}
                                <span class="menu-text"> {{ $menu['nombre'] ?? ucfirst($menu['modulo']) }} </span>
                                <span class="menu-arrow"></span>
                            </a>

                            <div class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300">
                                <ul class="mt-1 space-y-1">
                                    @foreach($menu['submenus'] ?? [] as $submenu)
                                        <li class="menu-item">
                                            <a class="flex items-center gap-x-3.5 rounded-md px-3 py-1.5 text-sm font-medium text-default-100 transition-all hover:bg-default-100/5"
                                                href="{{ route('second', [$menu['modulo'], $submenu['vista']]) }}">
                                                <i class="menu-dot"></i>

                                                {{ $submenu['nombre'] ?? ucfirst($submenu['vista']) }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </li>
                    @endforeach
                @endif


                <!-- <li class="px-3 py-2 text-sm font-medium text-default-400">Página Principal</li>

                <li class="menu-item">
                    <a class="group flex items-center gap-x-3.5 rounded-md px-3 py-2 text-sm font-medium text-default-100 transition-all hover:bg-default-100/5"
                        href="/">
                        <i class="i-ph-gauge-duotone text-2xl"></i>
                        <span>Dashboard</span>
                    </a>
                </li>     -->



                <!-- <li class="menu-item">
                    <a href="{{ route('second', ['file_control', 'dashboard'])}}"
                        class="group flex items-center gap-x-3.5 rounded-md px-3 py-2 text-sm font-medium text-default-100 transition-all hover:bg-default-100/5">
                        <i class="i-ph-clipboard-text-duotone text-2xl"></i>
                        <span class="menu-text"> Dashboard </span>
                    </a>
                </li>  -->

            </ul>
        </div>

    </div>
</aside>
<!-- End Sidebar -->
