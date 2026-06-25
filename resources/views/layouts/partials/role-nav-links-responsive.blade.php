@php
    $user = Auth::user();
    $role = $user->role;
@endphp

@if ($user->isAdmin())
    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
        Panel admin
    </x-responsive-nav-link>
    <x-responsive-nav-link :href="route('admin.usuarios.index')" :active="request()->routeIs('admin.usuarios.*')">
        Usuarios
    </x-responsive-nav-link>
    <x-responsive-nav-link :href="route('admin.pedidos.index')" :active="request()->routeIs('admin.pedidos.*')">
        Pedidos
    </x-responsive-nav-link>
    <x-responsive-nav-link :href="route('admin.inventario.index')" :active="request()->routeIs('admin.inventario.*')">
        Inventario
    </x-responsive-nav-link>
    <x-responsive-nav-link :href="route('admin.reportes.index')" :active="request()->routeIs('admin.reportes.*')">
        Reportes
    </x-responsive-nav-link>
@endif

@if ($role === \App\Enums\UserRole::Cliente)
    <x-responsive-nav-link :href="route('cliente.inicio')" :active="request()->routeIs('cliente.inicio')">
        Inicio
    </x-responsive-nav-link>
    <x-responsive-nav-link :href="route('cliente.pedidos.index')" :active="request()->routeIs('cliente.pedidos.index') || request()->routeIs('cliente.pedidos.show')">
        Mis pedidos
    </x-responsive-nav-link>
    <x-responsive-nav-link :href="route('cliente.reclamos.index')" :active="request()->routeIs('cliente.reclamos.*')">
        Mis reclamos
    </x-responsive-nav-link>
@endif

@if ($role === \App\Enums\UserRole::Vendedor)
    <x-responsive-nav-link :href="route('vendedor.pedidos.index')" :active="request()->routeIs('vendedor.pedidos.index') || request()->routeIs('vendedor.pedidos.show')">
        Gestión de pedidos
    </x-responsive-nav-link>
    <x-responsive-nav-link :href="route('vendedor.pedidos.create')" :active="request()->routeIs('vendedor.pedidos.create')">
        Registrar pedido
    </x-responsive-nav-link>
    <x-responsive-nav-link :href="route('vendedor.clientes.index')" :active="request()->routeIs('vendedor.clientes.*')">
        Clientes
    </x-responsive-nav-link>
@endif

@if ($role === \App\Enums\UserRole::Disenador)
    <x-responsive-nav-link :href="route('disenador.disenos.index')" :active="request()->routeIs('disenador.disenos.*')">
        Diseños
    </x-responsive-nav-link>
@endif

@if ($role === \App\Enums\UserRole::OperarioCorte)
    <x-responsive-nav-link :href="route('operario.corte.index')" :active="request()->routeIs('operario.corte.*')">
        Mis lotes — Corte
    </x-responsive-nav-link>
@endif

@if ($role === \App\Enums\UserRole::OperarioEstampado)
    <x-responsive-nav-link :href="route('operario.estampado.index')" :active="request()->routeIs('operario.estampado.*')">
        Mis lotes — Estampado
    </x-responsive-nav-link>
@endif

@if ($role === \App\Enums\UserRole::OperarioCostura)
    <x-responsive-nav-link :href="route('operario.costura.index')" :active="request()->routeIs('operario.costura.*')">
        Mis lotes — Costura
    </x-responsive-nav-link>
@endif

@if ($role === \App\Enums\UserRole::SupervisorCalidad)
    <x-responsive-nav-link :href="route('supervisor.calidad.index')" :active="request()->routeIs('supervisor.calidad.*')">
        Inspecciones de calidad
    </x-responsive-nav-link>
@endif

@if ($role === \App\Enums\UserRole::Almacenero)
    <x-responsive-nav-link :href="route('almacenero.inventario.index')" :active="request()->routeIs('almacenero.inventario.*')">
        Inventario de tela
    </x-responsive-nav-link>
    <x-responsive-nav-link :href="route('almacenero.insumos.index')" :active="request()->routeIs('almacenero.insumos.*')">
        Insumos
    </x-responsive-nav-link>
@endif
