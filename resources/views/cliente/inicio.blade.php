<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Inicio — Catálogo de prendas</h2>
    </x-slot>

    <div class="py-12" x-data="shoppingCart()" x-init="init()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            {{-- Hero Section --}}
            <div class="relative overflow-hidden rounded-2xl p-8 sm:p-12 text-white shadow-xl"
                  style="background: radial-gradient(circle at 80% 20%, rgba(249, 115, 22, 0.18), transparent 50%), linear-gradient(135deg, #1c1917 0%, #0c0a09 100%); border: 1px solid rgba(255,255,255,0.05);">
                <div class="relative z-10 max-w-2xl space-y-4">
                    <span class="inline-flex rounded-full bg-orange-500/10 px-3 py-1 text-xs font-bold uppercase tracking-wider text-orange-400 border border-orange-500/20 animate-pulse">
                        Colección Deportiva 2026
                    </span>
                    <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl text-white">
                        MARTE
                    </h1>
                    <p class="text-lg text-stone-300">
                        Prendas deportivas de alta resistencia y rendimiento. Diseños premium adaptados a las exigencias de tu marca o equipo corporativo.
                    </p>
                    <div class="pt-4 flex flex-col sm:flex-row gap-3">
                        <button @click="isCartOpen = true" class="inline-flex items-center justify-center rounded-lg bg-orange-500 px-5 py-3 text-sm font-bold text-white shadow-md hover:bg-orange-600 transition duration-150">
                            Ver Carrito de Compras
                        </button>
                        <a href="#catalogo" class="inline-flex items-center justify-center rounded-lg border border-stone-700 bg-stone-900/50 px-5 py-3 text-sm font-bold text-stone-300 hover:bg-stone-800 transition duration-150">
                            Explorar Catálogo
                        </a>
                    </div>
                </div>
                {{-- Decorative Mars circles --}}
                <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-orange-600/10 blur-3xl"></div>
                <div class="absolute -right-32 -bottom-32 h-96 w-96 rounded-full bg-orange-500/10 blur-3xl"></div>
            </div>

            {{-- Catalog Section --}}
            <div id="catalogo" class="space-y-6">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">Muestras de Prendas y Accesorios</h3>
                    <p class="text-sm text-gray-500">Configura la talla y cantidad de cada prenda para añadirlos a tu carrito de producción.</p>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    {{-- Prenda 1: Polo Fit --}}
                    <div class="group overflow-hidden rounded-xl bg-white border border-stone-200 shadow-sm transition hover:shadow-md flex flex-col justify-between">
                        <div class="aspect-square w-full overflow-hidden bg-gray-150 relative">
                            <img src="/images/prendas/polo.png" alt="Polo Fit" 
                                 class="h-full w-full object-cover object-center group-hover:scale-105 transition duration-300">
                        </div>
                        <div class="p-5 space-y-3 flex-1 flex flex-col justify-between">
                            <div class="space-y-1">
                                <div class="flex justify-between items-center">
                                    <h4 class="font-bold text-gray-900 text-lg">Polo Deportivo Fit</h4>
                                    <span class="text-base font-bold text-stone-900">S/ 35.00</span>
                                </div>
                                <p class="text-xs font-semibold text-orange-600 uppercase tracking-wider">Línea Performance</p>
                                <p class="text-sm text-gray-600 leading-relaxed">
                                    Polo fitted con tecnología Dry-Fit para control de humedad y costuras planas anti-roce.
                                </p>
                            </div>
                            <button @click="openAddModal('Polo Deportivo Fit', 35.00)" 
                                    class="w-full mt-4 bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs uppercase py-2.5 px-4 rounded-lg shadow-sm transition duration-150">
                                + Añadir al carrito
                            </button>
                        </div>
                    </div>

                    {{-- Prenda 2: Casaca --}}
                    <div class="group overflow-hidden rounded-xl bg-white border border-stone-200 shadow-sm transition hover:shadow-md flex flex-col justify-between">
                        <div class="aspect-square w-full overflow-hidden bg-gray-150 relative">
                            <img src="/images/prendas/casaca.png" alt="Casaca Cortaviento" 
                                 class="h-full w-full object-cover object-center group-hover:scale-105 transition duration-300">
                        </div>
                        <div class="p-5 space-y-3 flex-1 flex flex-col justify-between">
                            <div class="space-y-1">
                                <div class="flex justify-between items-center">
                                    <h4 class="font-bold text-gray-900 text-lg">Casaca Cortaviento</h4>
                                    <span class="text-base font-bold text-stone-900">S/ 75.00</span>
                                </div>
                                <p class="text-xs font-semibold text-orange-600 uppercase tracking-wider">Línea Outdoor</p>
                                <p class="text-sm text-gray-600 leading-relaxed">
                                    Cortaviento ultra liviano con acabado impermeable y capucha regulable para entrenamientos externos.
                                </p>
                            </div>
                            <button @click="openAddModal('Casaca Cortaviento', 75.00)" 
                                    class="w-full mt-4 bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs uppercase py-2.5 px-4 rounded-lg shadow-sm transition duration-150">
                                + Añadir al carrito
                            </button>
                        </div>
                    </div>

                    {{-- Prenda 3: Licra --}}
                    <div class="group overflow-hidden rounded-xl bg-white border border-stone-200 shadow-sm transition hover:shadow-md flex flex-col justify-between">
                        <div class="aspect-square w-full overflow-hidden bg-gray-150 relative">
                            <img src="/images/prendas/licra.png" alt="Licra Pro" 
                                 class="h-full w-full object-cover object-center group-hover:scale-105 transition duration-300">
                        </div>
                        <div class="p-5 space-y-3 flex-1 flex flex-col justify-between">
                            <div class="space-y-1">
                                <div class="flex justify-between items-center">
                                    <h4 class="font-bold text-gray-900 text-lg">Licra Pro Performance</h4>
                                    <span class="text-base font-bold text-stone-900">S/ 55.00</span>
                                </div>
                                <p class="text-xs font-semibold text-orange-600 uppercase tracking-wider">Línea Pro-Fit</p>
                                <p class="text-sm text-gray-600 leading-relaxed">
                                    Calzas de compresión de alta elasticidad y soporte muscular, diseñadas para alto impacto.
                                </p>
                            </div>
                            <button @click="openAddModal('Licra Pro Performance', 55.00)" 
                                    class="w-full mt-4 bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs uppercase py-2.5 px-4 rounded-lg shadow-sm transition duration-150">
                                + Añadir al carrito
                            </button>
                        </div>
                    </div>

                    {{-- Prenda 4: Short --}}
                    <div class="group overflow-hidden rounded-xl bg-white border border-stone-200 shadow-sm transition hover:shadow-md flex flex-col justify-between">
                        <div class="aspect-square w-full overflow-hidden bg-gray-150 relative">
                            <img src="/images/prendas/short.png" alt="Short Runner" 
                                 class="h-full w-full object-cover object-center group-hover:scale-105 transition duration-300">
                        </div>
                        <div class="p-5 space-y-3 flex-1 flex flex-col justify-between">
                            <div class="space-y-1">
                                <div class="flex justify-between items-center">
                                    <h4 class="font-bold text-gray-900 text-lg">Short Runner Premium</h4>
                                    <span class="text-base font-bold text-stone-900">S/ 30.00</span>
                                </div>
                                <p class="text-xs font-semibold text-orange-600 uppercase tracking-wider">Línea Running</p>
                                <p class="text-sm text-gray-600 leading-relaxed">
                                    Shorts de carrera ligeros con forro transpirable interior y perforaciones láser para ventilación.
                                </p>
                            </div>
                            <button @click="openAddModal('Short Runner Premium', 30.00)" 
                                    class="w-full mt-4 bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs uppercase py-2.5 px-4 rounded-lg shadow-sm transition duration-150">
                                + Añadir al carrito
                            </button>
                        </div>
                    </div>

                    {{-- Accesorio 5: Gorra --}}
                    <div class="group overflow-hidden rounded-xl bg-white border border-stone-200 shadow-sm transition hover:shadow-md flex flex-col justify-between">
                        <div class="aspect-square w-full overflow-hidden bg-gray-150 relative">
                            <img src="/images/prendas/gorra.png" alt="Gorra Deportiva" 
                                 class="h-full w-full object-cover object-center group-hover:scale-105 transition duration-300">
                        </div>
                        <div class="p-5 space-y-3 flex-1 flex flex-col justify-between">
                            <div class="space-y-1">
                                <div class="flex justify-between items-center">
                                    <h4 class="font-bold text-gray-900 text-lg">Gorra Deportiva Marte</h4>
                                    <span class="text-base font-bold text-stone-900">S/ 25.00</span>
                                </div>
                                <p class="text-xs font-semibold text-orange-600 uppercase tracking-wider">Línea Accesorios</p>
                                <p class="text-sm text-gray-600 leading-relaxed">
                                    Gorra ultraligera con tela respirable, banda absorbente interna y cierre regulable ergonómico.
                                </p>
                            </div>
                            <button @click="openAddModal('Gorra Deportiva Marte', 25.00)" 
                                    class="w-full mt-4 bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs uppercase py-2.5 px-4 rounded-lg shadow-sm transition duration-150">
                                + Añadir al carrito
                            </button>
                        </div>
                    </div>

                    {{-- Accesorio 6: Mochila --}}
                    <div class="group overflow-hidden rounded-xl bg-white border border-stone-200 shadow-sm transition hover:shadow-md flex flex-col justify-between">
                        <div class="aspect-square w-full overflow-hidden bg-gray-150 relative">
                            <img src="/images/prendas/mochila.png" alt="Mochila Deportiva" 
                                 class="h-full w-full object-cover object-center group-hover:scale-105 transition duration-300">
                        </div>
                        <div class="p-5 space-y-3 flex-1 flex flex-col justify-between">
                            <div class="space-y-1">
                                <div class="flex justify-between items-center">
                                    <h4 class="font-bold text-gray-900 text-lg">Mochila Deportiva</h4>
                                    <span class="text-base font-bold text-stone-900">S/ 90.00</span>
                                </div>
                                <p class="text-xs font-semibold text-orange-600 uppercase tracking-wider">Línea Accesorios</p>
                                <p class="text-sm text-gray-600 leading-relaxed">
                                    Mochila resistente al agua con compartimento separado para zapatillas y múltiples bolsillos de organización.
                                </p>
                            </div>
                            <button @click="openAddModal('Mochila Deportiva', 90.00)" 
                                    class="w-full mt-4 bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs uppercase py-2.5 px-4 rounded-lg shadow-sm transition duration-150">
                                + Añadir al carrito
                            </button>
                        </div>
                    </div>

                    {{-- Prenda 7: Termica --}}
                    <div class="group overflow-hidden rounded-xl bg-white border border-stone-200 shadow-sm transition hover:shadow-md flex flex-col justify-between">
                        <div class="aspect-square w-full overflow-hidden bg-gray-150 relative">
                            <img src="/images/prendas/termica.png" alt="Camiseta Térmica Pro" 
                                 class="h-full w-full object-cover object-center group-hover:scale-105 transition duration-300">
                        </div>
                        <div class="p-5 space-y-3 flex-1 flex flex-col justify-between">
                            <div class="space-y-1">
                                <div class="flex justify-between items-center">
                                    <h4 class="font-bold text-gray-900 text-lg">Camiseta Térmica Pro</h4>
                                    <span class="text-base font-bold text-stone-900">S/ 45.00</span>
                                </div>
                                <p class="text-xs font-semibold text-orange-600 uppercase tracking-wider">Línea Térmica</p>
                                <p class="text-sm text-gray-600 leading-relaxed">
                                    Polera de manga larga con aislamiento térmico y ajuste de compresión para conservar el calor durante entrenamientos en climas fríos.
                                </p>
                            </div>
                            <button @click="openAddModal('Camiseta Térmica Pro', 45.00)" 
                                    class="w-full mt-4 bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs uppercase py-2.5 px-4 rounded-lg shadow-sm transition duration-150">
                                + Añadir al carrito
                            </button>
                        </div>
                    </div>

                    {{-- Prenda 8: Jogger --}}
                    <div class="group overflow-hidden rounded-xl bg-white border border-stone-200 shadow-sm transition hover:shadow-md flex flex-col justify-between">
                        <div class="aspect-square w-full overflow-hidden bg-gray-150 relative">
                            <img src="/images/prendas/jogger.png" alt="Jogger Active Elite" 
                                 class="h-full w-full object-cover object-center group-hover:scale-105 transition duration-300">
                        </div>
                        <div class="p-5 space-y-3 flex-1 flex flex-col justify-between">
                            <div class="space-y-1">
                                <div class="flex justify-between items-center">
                                    <h4 class="font-bold text-gray-900 text-lg">Jogger Active Elite</h4>
                                    <span class="text-base font-bold text-stone-900">S/ 60.00</span>
                                </div>
                                <p class="text-xs font-semibold text-orange-600 uppercase tracking-wider">Línea Performance</p>
                                <p class="text-sm text-gray-600 leading-relaxed">
                                    Pantalón jogger slim-fit con bolsillos impermeables con cremallera, puños elásticos y tejido elástico multidireccional ultra suave.
                                </p>
                            </div>
                            <button @click="openAddModal('Jogger Active Elite', 60.00)" 
                                    class="w-full mt-4 bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs uppercase py-2.5 px-4 rounded-lg shadow-sm transition duration-150">
                                + Añadir al carrito
                            </button>
                        </div>
                    </div>

                    {{-- Prenda 9: Medias --}}
                    <div class="group overflow-hidden rounded-xl bg-white border border-stone-200 shadow-sm transition hover:shadow-md flex flex-col justify-between">
                        <div class="aspect-square w-full overflow-hidden bg-gray-150 relative">
                            <img src="/images/prendas/medias.png" alt="Medias Crew Técnicas" 
                                 class="h-full w-full object-cover object-center group-hover:scale-105 transition duration-300">
                        </div>
                        <div class="p-5 space-y-3 flex-1 flex flex-col justify-between">
                            <div class="space-y-1">
                                <div class="flex justify-between items-center">
                                    <h4 class="font-bold text-gray-900 text-lg">Medias Crew (Pack x3)</h4>
                                    <span class="text-base font-bold text-stone-900">S/ 18.00</span>
                                </div>
                                <p class="text-xs font-semibold text-orange-600 uppercase tracking-wider">Línea Accesorios</p>
                                <p class="text-sm text-gray-600 leading-relaxed">
                                    Medias de alta amortiguación en talón y puntera con soporte de arco elástico y canales de ventilación de alto rendimiento.
                                </p>
                            </div>
                            <button @click="openAddModal('Medias Crew (Pack x3)', 18.00)" 
                                    class="w-full mt-4 bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs uppercase py-2.5 px-4 rounded-lg shadow-sm transition duration-150">
                                + Añadir al carrito
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Floating Cart Button --}}
        <button @click="isCartOpen = true" 
                class="fixed bottom-6 right-6 z-40 flex h-14 w-14 items-center justify-center rounded-full bg-orange-500 text-white shadow-lg hover:bg-orange-600 hover:scale-105 active:scale-95 transition duration-150">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <template x-if="totalItems > 0">
                <span class="absolute -top-1 -right-1 flex h-6 w-6 items-center justify-center rounded-full bg-stone-950 text-xs font-extrabold text-white" 
                      x-text="totalItems"></span>
            </template>
        </button>

        {{-- Slide-over Cart Sidebar --}}
        <div x-show="isCartOpen" 
             class="fixed inset-0 z-50 overflow-hidden" 
             style="display: none;" 
             role="dialog" aria-modal="true">
            <div class="absolute inset-0 overflow-hidden">
                <div x-show="isCartOpen" 
                     x-transition.opacity 
                     class="absolute inset-0 bg-stone-950/40 bg-opacity-75 transition-opacity" 
                     @click="isCartOpen = false"></div>

                <div class="fixed inset-y-0 right-0 flex max-w-full pl-10">
                    <div x-show="isCartOpen" 
                         x-transition:enter="transform transition ease-in-out duration-300"
                         x-transition:enter-start="translate-x-full"
                         x-transition:enter-end="translate-x-0"
                         x-transition:leave="transform transition ease-in-out duration-300"
                         x-transition:leave-start="translate-x-0"
                         x-transition:leave-end="translate-x-full"
                         class="w-screen max-w-md bg-white shadow-2xl flex flex-col justify-between">
                        
                        <div class="flex-1 overflow-y-auto py-6 px-4 sm:px-6">
                            <div class="flex items-start justify-between border-b border-stone-100 pb-4">
                                <h2 class="text-lg font-bold text-gray-900">Carrito de Producción</h2>
                                <button type="button" @click="isCartOpen = false" class="text-gray-400 hover:text-gray-500">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <div class="mt-6">
                                <template x-if="cart.length === 0">
                                    <div class="text-center py-12 space-y-3">
                                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                        <p class="text-sm text-gray-500">Tu carrito está vacío.</p>
                                        <button @click="isCartOpen = false" class="text-sm text-orange-500 font-bold hover:text-orange-600">Explorar prendas</button>
                                    </div>
                                </template>

                                <template x-if="cart.length > 0">
                                    <ul role="list" class="-my-6 divide-y divide-stone-100">
                                        <template x-for="(item, index) in cart" :key="index">
                                            <li class="flex py-6">
                                                <div class="flex-1 space-y-1">
                                                    <div class="flex justify-between text-sm font-semibold text-gray-900">
                                                        <h5 x-text="item.modelo"></h5>
                                                        <p class="ml-4" x-text="'S/ ' + (item.precio_unitario * item.cantidad).toFixed(2)"></p>
                                                    </div>
                                                    <p class="text-xs text-orange-600 font-medium">Talla: <span x-text="item.talla"></span></p>
                                                    <div class="flex items-center justify-between text-xs text-gray-500 pt-1">
                                                        <p x-text="item.cantidad + ' unidades x S/ ' + item.precio_unitario.toFixed(2)"></p>
                                                        <button type="button" @click="removeFromCart(index)" class="font-medium text-red-600 hover:text-red-500">
                                                            Quitar
                                                        </button>
                                                    </div>
                                                </div>
                                            </li>
                                        </template>
                                    </ul>
                                </template>
                            </div>
                        </div>

                        <template x-if="cart.length > 0">
                            <div class="border-t border-stone-100 py-6 px-4 sm:px-6 space-y-4 bg-gray-50">
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Total unidades:</span>
                                    <span class="font-semibold text-gray-900" x-text="totalItems + ' uds.'"></span>
                                </div>
                                <div class="flex justify-between text-base font-bold text-gray-900">
                                    <span>Total estimado:</span>
                                    <span x-text="'S/ ' + totalPrice.toFixed(2)"></span>
                                </div>
                                <p class="text-xs text-gray-500">
                                    Al continuar, estos productos se pre-cargarán en el formulario para registrar tu pedido final.
                                </p>
                                <div class="grid grid-cols-2 gap-3 pt-2">
                                    <button @click="clearCart()" 
                                            class="w-full text-center py-3 border border-stone-300 rounded-lg text-sm font-bold text-stone-700 hover:bg-stone-100 transition">
                                        Vaciar
                                    </button>
                                    <button @click="checkout()" 
                                            class="w-full text-center py-3 bg-orange-500 rounded-lg text-sm font-bold text-white shadow-md hover:bg-orange-600 transition">
                                        Crear Pedido
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        {{-- Add to Cart Configuration Modal --}}
        <div x-show="selectedProduct" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;" 
             role="dialog" aria-modal="true">
            <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="selectedProduct" 
                     x-transition.opacity 
                     class="fixed inset-0 bg-stone-950/45 transition-opacity" 
                     @click="selectedProduct = null"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="selectedProduct" 
                     x-transition.scale 
                     class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                    
                    <div class="bg-white px-6 pt-6 pb-4">
                        <div class="flex justify-between items-start border-b border-stone-100 pb-3">
                            <h3 class="text-lg font-bold text-gray-900" x-text="'Configurar ' + (selectedProduct ? selectedProduct.name : '')"></h3>
                            <button @click="selectedProduct = null" class="text-gray-400 hover:text-gray-500">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="mt-4 space-y-4">
                            {{-- Talla Selection --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Seleccionar Talla</label>
                                <div class="grid grid-cols-4 gap-2">
                                    <template x-for="t in ['S', 'M', 'L', 'XL']">
                                        <button @click="selectedTalla = t" 
                                                type="button"
                                                class="py-2 text-center rounded-lg border text-sm font-bold transition duration-150"
                                                :class="selectedTalla === t ? 'border-orange-500 bg-orange-50 text-orange-600' : 'border-stone-200 text-stone-700 hover:bg-stone-50'"
                                                x-text="t"></button>
                                    </template>
                                </div>
                            </div>

                            {{-- Cantidad Input --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Cantidad de prendas</label>
                                <div class="flex items-center gap-3">
                                    <button type="button" @click="selectedCantidad = Math.max(1, selectedCantidad - 1)" 
                                            class="flex h-10 w-10 items-center justify-center rounded-lg border border-stone-300 text-stone-600 hover:bg-stone-50 active:scale-95 transition">
                                        -
                                    </button>
                                    <input type="number" 
                                           x-model.number="selectedCantidad" 
                                           min="1" 
                                           class="w-20 text-center rounded-lg border-stone-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm font-bold">
                                    <button type="button" @click="selectedCantidad = selectedCantidad + 1" 
                                            class="flex h-10 w-10 items-center justify-center rounded-lg border border-stone-300 text-stone-600 hover:bg-stone-50 active:scale-95 transition">
                                        +
                                    </button>
                                </div>
                                <p class="mt-2 text-xs text-stone-500">
                                    Nota: Para pedidos grupales de confección, el mínimo del pedido general debe acumular al menos 12 unidades.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 flex sm:flex-row-reverse gap-2 rounded-b-xl border-t border-stone-100">
                        <button type="button" @click="addToCart()" 
                                class="w-full sm:w-auto inline-flex justify-center rounded-lg bg-orange-500 hover:bg-orange-600 px-4 py-2 text-sm font-bold text-white shadow-sm transition">
                            Añadir al carrito
                        </button>
                        <button type="button" @click="selectedProduct = null" 
                                class="w-full sm:w-auto inline-flex justify-center rounded-lg border border-stone-300 bg-white hover:bg-stone-50 px-4 py-2 text-sm font-bold text-stone-700 transition">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Frontend Cart Logic Script --}}
    <script>
        function shoppingCart() {
            return {
                cart: [],
                isCartOpen: false,
                selectedProduct: null,
                selectedTalla: 'M',
                selectedCantidad: 12,

                init() {
                    const savedCart = localStorage.getItem('marte_cart');
                    if (savedCart) {
                        try {
                            this.cart = JSON.parse(savedCart);
                        } catch (e) {
                            console.error('Error parsing cart:', e);
                        }
                    }
                },

                saveCart() {
                    localStorage.setItem('marte_cart', JSON.stringify(this.cart));
                },

                openAddModal(productName, price) {
                    this.selectedProduct = { name: productName, price: price };
                    this.selectedTalla = 'M';
                    this.selectedCantidad = 12; // Mínimo inicial recomendado
                },

                addToCart() {
                    if (!this.selectedProduct) return;

                    // Validar cantidad
                    const qty = parseInt(this.selectedCantidad) || 1;

                    // Buscar si ya existe la misma prenda y misma talla en el carrito
                    const existingItem = this.cart.find(
                        item => item.modelo === this.selectedProduct.name && item.talla === this.selectedTalla
                    );

                    if (existingItem) {
                        existingItem.cantidad += qty;
                    } else {
                        this.cart.push({
                            modelo: this.selectedProduct.name,
                            talla: this.selectedTalla,
                            cantidad: qty,
                            precio_unitario: this.selectedProduct.price
                        });
                    }

                    this.saveCart();
                    this.selectedProduct = null;
                    this.isCartOpen = true; // Abre el carrito para dar feedback visual
                },

                removeFromCart(index) {
                    this.cart.splice(index, 1);
                    this.saveCart();
                },

                clearCart() {
                    this.cart = [];
                    this.saveCart();
                },

                get totalItems() {
                    return this.cart.reduce((sum, item) => sum + item.cantidad, 0);
                },

                get totalPrice() {
                    return this.cart.reduce((sum, item) => sum + (item.cantidad * item.precio_unitario), 0);
                },

                checkout() {
                    this.saveCart();
                    window.location.href = "{{ route('cliente.pedidos.create') }}";
                }
            };
        }
    </script>
</x-app-layout>
