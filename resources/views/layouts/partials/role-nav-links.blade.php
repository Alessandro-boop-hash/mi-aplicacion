@php
    $user = Auth::user();
    $role = $user->role;
@endphp

@if ($user->isAdmin())
    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
        Panel admin
    </x-nav-link>
    <x-nav-link :href="route('admin.usuarios.index')" :active="request()->routeIs('admin.usuarios.*')">
        Usuarios
    </x-nav-link>
    <x-nav-link :href="route('admin.pedidos.index')" :active="request()->routeIs('admin.pedidos.*')">
        Pedidos
    </x-nav-link>
    <x-nav-link :href="route('admin.inventario.index')" :active="request()->routeIs('admin.inventario.*')">
        Inventario
    </x-nav-link>
    <x-nav-link :href="route('admin.reportes.index')" :active="request()->routeIs('admin.reportes.*')">
        Reportes
    </x-nav-link>
@endif

@if ($role === \App\Enums\UserRole::Cliente)
    <x-nav-link :href="route('cliente.inicio')" :active="request()->routeIs('cliente.inicio')">
        Inicio
    </x-nav-link>
    <x-nav-link :href="route('cliente.pedidos.index')" :active="request()->routeIs('cliente.pedidos.index') || request()->routeIs('cliente.pedidos.show')">
        Mis pedidos
    </x-nav-link>
    <x-nav-link :href="route('cliente.reclamos.index')" :active="request()->routeIs('cliente.reclamos.*')">
        Mis reclamos
    </x-nav-link>
@endif

@if ($role === \App\Enums\UserRole::Vendedor)
    <x-nav-link :href="route('vendedor.pedidos.index')" :active="request()->routeIs('vendedor.pedidos.index') || request()->routeIs('vendedor.pedidos.show')">
        Gestión de pedidos
    </x-nav-link>
    <x-nav-link :href="route('vendedor.pedidos.create')" :active="request()->routeIs('vendedor.pedidos.create')">
        Registrar pedido
    </x-nav-link>
    <x-nav-link :href="route('vendedor.clientes.index')" :active="request()->routeIs('vendedor.clientes.*')">
        Clientes
    </x-nav-link>
@endif

@if ($role === \App\Enums\UserRole::Disenador)
    <x-nav-link :href="route('disenador.disenos.index')" :active="request()->routeIs('disenador.disenos.*')">
        Diseños
    </x-nav-link>
@endif

@if ($role === \App\Enums\UserRole::OperarioCorte)
    <x-nav-link :href="route('operario.corte.index')" :active="request()->routeIs('operario.corte.*')">
        Mis lotes — Corte
    </x-nav-link>
@endif

@if ($role === \App\Enums\UserRole::OperarioEstampado)
    <x-nav-link :href="route('operario.estampado.index')" :active="request()->routeIs('operario.estampado.*')">
        Mis lotes — Estampado
    </x-nav-link>
@endif

@if ($role === \App\Enums\UserRole::OperarioCostura)
    <x-nav-link :href="route('operario.costura.index')" :active="request()->routeIs('operario.costura.*')">
        Mis lotes — Costura
    </x-nav-link>
@endif

@if ($role === \App\Enums\UserRole::SupervisorCalidad)
    <x-nav-link :href="route('supervisor.calidad.index')" :active="request()->routeIs('supervisor.calidad.*')">
        Inspecciones de calidad
    </x-nav-link>
@endif

@if ($role === \App\Enums\UserRole::Almacenero)
    <x-nav-link :href="route('almacenero.inventario.index')" :active="request()->routeIs('almacenero.inventario.*')">
        Inventario de tela
    </x-nav-link>
    <x-nav-link :href="route('almacenero.insumos.index')" :active="request()->routeIs('almacenero.insumos.*')">
        Insumos
    </x-nav-link>
@endif
